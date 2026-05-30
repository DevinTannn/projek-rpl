<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignMilestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function myIndex()
    {
        $campaigns = Campaign::where('user_id', Auth::id())->latest()->get();
        return view('campaigns.my-index', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1000',
            'milestones' => 'required|array|size:4',
            'milestones.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $campaign = Campaign::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'goal_amount' => $request->goal_amount,
                'status' => 'active',
            ]);

            $percentages = [25, 50, 75, 100];
            foreach ($percentages as $percentage) {
                CampaignMilestone::create([
                    'campaign_id' => $campaign->id,
                    'percentage' => $percentage,
                    'amount' => ($percentage / 100) * $request->goal_amount,
                    'badge_label' => $request->milestones[$percentage],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Campaign created successfully!');
    }

    public function show($id)
    {
        $campaign = Campaign::with('milestones')->where('user_id', Auth::id())->findOrFail($id);
        return view('campaigns.show', compact('campaign'));
    }

    public function updateTag(Request $request, $id)
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'tag' => 'nullable|string|max:50'
        ]);

        $campaign->update(['tag' => $request->tag]);

        return response()->json(['success' => true, 'tag' => $campaign->tag]);
    }
}
