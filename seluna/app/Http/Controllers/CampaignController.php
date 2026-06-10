<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignMedia;
use App\Models\CampaignMilestone;
use App\Models\Follow;
use App\Models\CampaignView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function myIndex()
    {
        $campaigns = Campaign::where('user_id', Auth::id())->latest()->get();
        return view('campaigns.my-index', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isVerified()) {
            return redirect()->route('profile.verification')->with('error', 'Anda harus melengkapi verifikasi identitas (KTP) sebelum dapat membuat campaign.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1000',
            'tag' => 'nullable|string|max:50',
            'milestones' => 'required|array|size:4',
            'milestones.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $user = Auth::user();
            // Promoted to fundraiser if they create a campaign
            if ($user->role !== 'fundraiser' && $user->role !== 'admin') {
                $user->update(['role' => 'fundraiser']);
            }

            $campaign = Campaign::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'goal_amount' => $request->goal_amount,
                'tag' => $request->tag,
                'status' => 'pending', // Requires admin verification
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

        return redirect()->route('campaigns.index')->with('success', 'Kampanye berhasil dibuat! Silakan tunggu verifikasi dari admin.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $campaigns = Campaign::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('tag', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->paginate(12);

        return view('campaigns.search', compact('campaigns', 'query'));
    }

    public function apiSearch(Request $request)
    {
        $query = $request->input('query');
        if (empty($query)) return response()->json([]);

        $campaigns = Campaign::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('tag', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->take(5)
            ->get();

        return response()->json($campaigns);
    }

    public function show($id)
    {
        $campaign = Campaign::with(['milestones', 'media', 'updates'])->findOrFail($id);
        
        // Restrict access to non-active campaigns
        if ($campaign->status !== 'active') {
            if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::id() !== $campaign->user_id)) {
                return redirect()->route('home')->with('error', 'Kampanye ini belum aktif atau sedang dalam verifikasi admin.');
            }
        }

        // Track view if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Promote 'user' to 'donatur' if entering someone else's campaign
            if ($user->role === 'user' && $user->id !== $campaign->user_id) {
                $user->update(['role' => 'donatur']);
            }

            CampaignView::updateOrCreate(
                ['user_id' => Auth::id(), 'campaign_id' => $campaign->id],
                ['last_viewed_at' => now()]
            );
        }

        return view('campaigns.show', compact('campaign'));
    }

    public function syncStatus()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false]);

        $latestDonation = \App\Models\Donation::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'success', 'settlement'])
            ->latest()
            ->first();

        $verificationStatus = \App\Models\FundraiserVerification::where('user_id', $user->id)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'donation' => $latestDonation ? [
                'id' => $latestDonation->id,
                'status' => $latestDonation->status,
                'amount' => $latestDonation->amount
            ] : null,
            'verification' => $verificationStatus ? [
                'status' => $verificationStatus->status
            ] : null,
            'role' => $user->role
        ]);
    }

    public function toggleFollow($id)
    {
        $campaign = Campaign::findOrFail($id);
        $user = Auth::user();

        $follow = Follow::where('user_id', $user->id)->where('campaign_id', $campaign->id)->first();

        if ($follow) {
            $follow->delete();
            $status = 'unfollowed';
        } else {
            Follow::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id
            ]);
            $status = 'followed';
        }

        return response()->json(['success' => true, 'status' => $status]);
    }

    public function followedIndex()
    {
        $campaigns = Campaign::whereHas('follows', function($q) {
            $q->where('user_id', Auth::id());
        })->latest()->paginate(12);

        return view('campaigns.followed', compact('campaigns'));
    }

    public function lastAccessedIndex()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $campaigns = Campaign::whereHas('views', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->join('campaign_views', 'campaigns.id', '=', 'campaign_views.campaign_id')
            ->where('campaign_views.user_id', $user->id)
            ->where('campaigns.status', 'active')
            ->orderBy('campaign_views.last_viewed_at', 'desc')
            ->select('campaigns.*', 'campaign_views.last_viewed_at as last_viewed_at')
            ->take(12)
            ->get();

        return view('campaigns.last-accessed', compact('campaigns'));
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1000',
            'tag' => 'nullable|string|max:50',
            'milestones' => 'required|array|size:4',
            'milestones.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $campaign) {
            $campaign->update([
                'title' => $request->title,
                'description' => $request->description,
                'goal_amount' => $request->goal_amount,
                'tag' => $request->tag,
            ]);

            foreach ([25, 50, 75, 100] as $percentage) {
                $campaign->milestones()->where('percentage', $percentage)->update([
                    'amount' => ($percentage / 100) * $request->goal_amount,
                    'badge_label' => $request->milestones[$percentage],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Campaign updated successfully!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'Campaign deleted successfully!');
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

    public function uploadMedia(Request $request, $id)
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'media'   => 'required|array|min:1|max:10',
            'media.*' => 'required|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi,webm,pdf,docx,xlsx|max:102400',
        ]);

        $files = $request->file('media');

        if (!$files || !is_array($files)) {
            return redirect()->back()->with('error', 'Tidak ada file yang dipilih.');
        }

        $newMedia = [];
        foreach ($files as $file) {
            if (!$file || !$file->isValid()) continue;

            $mime = $file->getMimeType();
            $type = 'image';
            if (str_starts_with($mime, 'video')) {
                $type = 'video';
            } elseif (in_array($mime, [
                'application/pdf', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ])) {
                $type = 'document';
            }
            $path = $file->store("campaigns/{$campaign->id}/media", 'public');

            $m = CampaignMedia::create([
                'campaign_id'   => $campaign->id,
                'file_path'     => $path,
                'file_type'     => $type,
                'original_name' => $file->getClientOriginalName(),
                'sort_order'    => $campaign->media()->count(),
            ]);
            $newMedia[] = [
                'id' => $m->id,
                'url' => $m->url,
                'type' => $m->file_type
            ];
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'media' => $newMedia]);
        }

        return redirect()->back()->with('success', 'Media berhasil diupload!');
    }

    public function deleteMedia($campaignId, $mediaId)
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($campaignId);
        $media = CampaignMedia::where('campaign_id', $campaign->id)->findOrFail($mediaId);

        Storage::delete($media->file_path);
        $media->delete();

        return response()->json(['success' => true]);
    }

    public function addUpdate(Request $request, $id)
    {
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'media' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi,webm,pdf,docx,xlsx|max:20480',
        ]);

        $path = null;
        $type = null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mime = $file->getMimeType();
            $type = 'image';
            if (str_starts_with($mime, 'video')) {
                $type = 'video';
            } elseif (in_array($mime, [
                'application/pdf', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ])) {
                $type = 'document';
            }
            $path = $file->store("campaigns/{$campaign->id}/updates", 'public');
        }

        $campaign->updates()->create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'media_path' => $path,
            'media_type' => $type,
        ]);

        return redirect()->back()->with('success', 'Update berhasil ditambahkan!');
    }
}
