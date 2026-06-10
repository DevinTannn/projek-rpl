<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignReportController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        // Ensure only owner can upload
        if (auth()->id() !== $campaign->user_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'files.*' => 'required|mimes:pdf,docx,xlsx,zip|max:51200', // 50MB
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '_' . uniqid() . '.' . $extension;
                
                $path = $file->storeAs('campaigns/' . $campaign->id . '/reports', $filename, 'public');

                CampaignReport::create([
                    'campaign_id' => $campaign->id,
                    'file_path' => $path,
                    'original_name' => $originalName,
                    'file_type' => $extension,
                    'file_size' => $file->getSize(),
                    'status' => 'pending'
                ]);
            }
        }

        return back()->with('success', 'Laporan berhasil diupload dan menunggu verifikasi admin.');
    }

    public function destroy(CampaignReport $report)
    {
        // Ensure only owner can delete
        if (auth()->id() !== $report->campaign->user_id) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Delete from storage
        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
