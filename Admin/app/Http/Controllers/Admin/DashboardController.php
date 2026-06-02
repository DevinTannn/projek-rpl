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
        $totalDonations = Donation::where('status', 'paid')->sum('amount');
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
}
