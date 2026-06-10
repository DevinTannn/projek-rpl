<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDonations = Donation::whereIn('status', ['paid', 'approved'])->sum('amount');
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $totalFundraisers = User::where('role', 'fundraiser')->count();
        $pendingVerifications = Campaign::where('status', 'pending')->count();

        // Get latest 5 donations for quick view
        $recentDonations = Donation::with(['user', 'campaign'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalDonations', 
            'activeCampaigns', 
            'totalFundraisers', 
            'pendingVerifications',
            'recentDonations'
        ));
    }

    public function sync()
    {
        $pendingCampaigns = \App\Models\Campaign::where('status', 'pending')->count();
        $pendingDonations = \App\Models\Donation::where('payment_method', 'Manual')->where('status', 'pending')->count();
        $pendingAccounts = \App\Models\FundraiserVerification::where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'summary' => [
                'campaigns' => $pendingCampaigns,
                'donations' => $pendingDonations,
                'accounts' => $pendingAccounts,
                'total' => $pendingCampaigns + $pendingDonations + $pendingAccounts
            ]
        ]);
    }
}
