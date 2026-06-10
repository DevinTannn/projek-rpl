<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        
        // Akses Terakhir = Campaign yang terakhir kali dimasuki oleh donatur
        $lastAccessedCampaigns = collect();
        if ($user) {
            $lastAccessedCampaigns = Campaign::whereHas('views', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->join('campaign_views', 'campaigns.id', '=', 'campaign_views.campaign_id')
            ->where('campaign_views.user_id', $user->id)
            ->where('campaigns.status', 'active')
            ->orderBy('campaign_views.last_viewed_at', 'desc')
            ->select('campaigns.*')
            ->take(3)
            ->get();
        }
        
        // Populer Sekarang = Berdasarkan jumlah dana terkumpul terbanyak (Hanya yang Aktif)
        $popularCampaigns = Campaign::where('status', 'active')->orderBy('collected_amount', 'desc')->take(3)->get();
        
        // Eksplorasi = Kampanye terbaru yang di-upload (Hanya yang Aktif)
        $explorationCampaigns = Campaign::where('status', 'active')->latest()->take(6)->get();

        if (auth()->check()) {
            return view('home', [
                'lastUpdatedCampaigns' => $lastAccessedCampaigns,
                'popularCampaigns' => $popularCampaigns,
                'explorationCampaigns' => $explorationCampaigns
            ]);
        } else {
            return view('landing', [
                'popularCampaigns' => $popularCampaigns,
                'explorationCampaigns' => $explorationCampaigns
            ]);
        }
    }
}
