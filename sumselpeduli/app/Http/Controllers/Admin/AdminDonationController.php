<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['user', 'campaign'])->latest()->paginate(15);
        return view('admin.donations.index', compact('donations'));
    }

    public function verify($id)
    {
        $donation = Donation::findOrFail($id);
        
        if ($donation->status !== 'success') {
            $donation->update([
                'status' => 'success',
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ]);

            // Increment campaign collected_amount by net_amount
            $donation->campaign->increment('collected_amount', $donation->net_amount);
        }

        return redirect()->back()->with('success', 'Donasi berhasil diverifikasi dan progress campaign diperbarui.');
    }

    public function reject($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->update([
            'status' => 'failed',
            'verified_by' => Auth::id(),
            'verified_at' => now()
        ]);

        return redirect()->back()->with('success', 'Donasi berhasil ditolak.');
    }
}
