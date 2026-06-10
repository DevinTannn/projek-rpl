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

    private function applySorting($query, Request $request, $type = 'campaign')
    {
        $sort = $request->get('sort', 'newest');
        
        switch ($sort) {
            case 'newest': $query->latest(); break;
            case 'oldest': $query->oldest(); break;
            case 'az': 
                $field = ($type === 'campaign') ? 'title' : (($type === 'account') ? 'id' : 'id'); 
                // For account, maybe search by user name later, for now ID
                $query->orderBy($field, 'asc'); 
                break;
            case 'za': 
                $field = ($type === 'campaign') ? 'title' : (($type === 'account') ? 'id' : 'id');
                $query->orderBy($field, 'desc'); 
                break;
            case 'amount_hi': 
                $field = ($type === 'campaign') ? 'goal_amount' : 'amount';
                $query->orderBy($field, 'desc'); 
                break;
            case 'amount_lo': 
                $field = ($type === 'campaign') ? 'goal_amount' : 'amount';
                $query->orderBy($field, 'asc'); 
                break;
            case 'collected_hi': 
                if ($type === 'campaign') $query->orderBy('collected_amount', 'desc');
                break;
            case 'popular': 
                if ($type === 'campaign') $query->withCount('follows')->orderBy('follows_count', 'desc');
                break;
            case 'completion': 
                if ($type === 'campaign') $query->orderByRaw('(collected_amount / goal_amount) DESC');
                break;
        }

        // Always prioritize pending at the top if sorting by newest/default
        if ($sort === 'newest') {
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END");
        }

        return $query;
    }

    public function campaignIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Campaign::query();
        $query = $this->applySorting($query, $request, 'campaign');

        $campaigns = ($perPage == 'all') ? $query->get() : $query->paginate($perPage);
        return view('admin.verifikasi.kampanye', compact('campaigns'));
    }

    public function verifyCampaign(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        $status = $request->status; // 'active' or 'rejected'
        
        $campaign->update(['status' => $status]);

        if ($status == 'active') {
            $this->mailService->sendCampaignVerified($campaign->load('user'));
        }

        return back()->with('success', "Kampanye berhasil " . ($status == 'active' ? 'disetujui' : 'ditolak'));
    }

    public function accountIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = FundraiserVerification::with('user');
        $query = $this->applySorting($query, $request, 'account');

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
            $this->mailService->sendAccountVerified($verification->load('user'));
        }

        return back()->with('success', "Verifikasi akun berhasil " . ($status == 'approved' ? 'disetujui' : 'ditolak'));
    }

    public function donationIndex(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Donation::with(['user', 'campaign']);
        $query = $this->applySorting($query, $request, 'donation');

        $donations = ($perPage == 'all') ? $query->get() : $query->paginate($perPage);
        return view('admin.verifikasi.donasi', compact('donations'));
    }

    public function verifyDonation(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        $status = $request->status; // 'paid' or 'rejected'
        
        $donation->update(['status' => $status]);
        
        if ($status == 'paid') {
            $donation->campaign->increment('collected_amount', $donation->amount);
            $this->mailService->sendDonationVerified($donation->load(['user', 'campaign']));
        }

        return back()->with('success', "Donasi berhasil " . ($status == 'paid' ? 'diverifikasi' : 'ditolak'));
    }
}
