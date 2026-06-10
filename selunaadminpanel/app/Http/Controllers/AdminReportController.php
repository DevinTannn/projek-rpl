<?php

namespace App\Http\Controllers;

use App\Models\CampaignReport;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = CampaignReport::with('campaign');
        
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'newest': $query->latest(); break;
            case 'oldest': $query->oldest(); break;
            case 'az': $query->orderBy('original_name', 'asc'); break;
            case 'za': $query->orderBy('original_name', 'desc'); break;
            default: $query->latest(); break;
        }

        $reports = $query->get();
        return view('admin.verifikasi.laporan', compact('reports'));
    }

    public function verify(CampaignReport $report)
    {
        $report->update(['status' => 'verified']);
        return back()->with('success', 'Laporan berhasil diverifikasi.');
    }

    public function reject(Request $request, CampaignReport $report)
    {
        $report->update([
            'status' => 'rejected',
            'admin_note' => $request->note
        ]);
        return back()->with('success', 'Laporan ditolak.');
    }
}
