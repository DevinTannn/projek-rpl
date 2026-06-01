<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Barryvdh\DomPDF\Facade\Pdf;

class DonationController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Bypass SSL if NOT in production (fixes local SSL error)
        if (!config('services.midtrans.is_production')) {
            Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            // The SDK has a bug where it expects HTTPHEADER to exist if any curlOptions are set
            Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
        }
    }

    public function store(Request $request, $campaignId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|in:Midtrans,Manual',
            'proof' => 'required_if:payment_method,Manual|image|max:5120'
        ]);

        $campaign = Campaign::findOrFail($campaignId);
        $user = Auth::user();

        $amount = (float)$request->amount;
        $fee = floor($amount * 0.05); // 5% fee
        $netAmount = $amount - $fee;

        return DB::transaction(function () use ($request, $campaign, $user, $amount, $fee, $netAmount) {
            $orderId = 'DON-' . time() . '-' . $user->id;
            
            $data = [
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'fee_amount' => $fee,
                'net_amount' => $netAmount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'midtrans_status' => 'pending'
            ];

            if ($request->payment_method === 'Manual') {
                $data['proof_path'] = $request->file('proof')->store('proofs', 'public');
                $donation = Donation::create($data);

                return redirect()->route('profile.archived')->with('success', 'Konfirmasi donasi manual berhasil dikirim. Tunggu verifikasi admin.');
            }

            $donation = Donation::create($data);

            // Create Midtrans Transaction
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$amount,
                ],
                'customer_details' => [
                    'first_name' => $user->username,
                    'email' => $user->email,
                ],
                'callbacks' => [
                    'finish' => route('campaigns.show', $campaign->id)
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;
                
                $donation->update([
                    'snap_token' => $snapToken,
                    'payment_url' => $paymentUrl
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapToken,
                        'payment_url' => $paymentUrl,
                        'message' => 'Token pembayaran berhasil dibuat.'
                    ]);
                }
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
                }
                return redirect()->back()->with('error', $e->getMessage());
            }

            return redirect()->back()->with('success', 'Silahkan selesaikan pembayaran Anda.');
        });
    }

    public function notification(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config('services.midtrans.server_key'));

        if ($notification->signature_key != $validSignatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $donation = Donation::where('order_id', $orderId)->first();

        if ($donation) {
            $donation->update(['midtrans_status' => $transactionStatus]);
            
            // Note: status 'success' and 'failed' are controlled by Admin in this flow,
            // but we can update midtrans_status here.
        }

        return response()->json(['message' => 'Notification processed']);
    }

    public function archive()
    {
        $donations = Donation::with('campaign')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('l, d M Y');
            });

        return view('profile.archived', compact('donations'));
    }
}
