<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundraiserVerification;
use App\Services\MailService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function campaignIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Campaign::orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest();

        $campaigns = ($perPage == 'all') ? $query->get() : $query->paginate($perPage);
        return view('admin.verifikasi.kampanye', compact('campaigns'));
    }

    public function verifyCampaign(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        $status = $request->status; // 'active' or 'rejected'
        
        $campaign->update(['status' => $status]);

        if ($status == 'active') {
            // Send Email Notification via PHPMailer
            $this->mailService->sendCampaignVerified($campaign->load('user'));
        }

        return back()->with('success', "Kampanye berhasil " . ($status == 'active' ? 'disetujui' : 'ditolak'));
    }

    public function accountIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = FundraiserVerification::with('user')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest();

        $verifications = ($perPage == 'all') ? $query->get() : $query->paginate($perPage);
        return view('admin.verifikasi.akun', compact('verifications'));
    }

    public function verifyAccount(Request $request, $id)
    {
        $verification = FundraiserVerification::findOrFail($id);
        $status = $request->status; // 'approved' or 'rejected'
        
        $verification->update(['status' => $status]);

        if ($status == 'approved') {
            $verification->user->update(['role' => 'fundraiser']);
            
            // Send Email Notification via PHPMailer
            $this->mailService->sendAccountVerified($verification->load('user'));
        }

        return back()->with('success', "Verifikasi akun berhasil " . ($status == 'approved' ? 'disetujui' : 'ditolak'));
    }

    public function donationIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        // Show all donations, prioritize pending at the top
        $query = Donation::with(['user', 'campaign'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest();

        $donations = ($perPage == 'all') ? $query->get() : $query->paginate($perPage);
        return view('admin.verifikasi.donasi', compact('donations'));
    }

    public function verifyDonation(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        $status = $request->status; // 'paid' or 'rejected'
        
        $donation->update(['status' => $status]);
        
        if ($status == 'paid') {
            // Update collected amount in campaign
            $donation->campaign->increment('collected_amount', $donation->amount);
            
            // Send Email Notification via PHPMailer
            $this->mailService->sendDonationVerified($donation->load(['user', 'campaign']));
        }

        return back()->with('success', "Donasi berhasil " . ($status == 'paid' ? 'diverifikasi' : 'ditolak'));
    }
}
