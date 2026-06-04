<?php

namespace App\Http\Controllers;

use App\Models\CampaignReport;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index()
    {
        $reports = CampaignReport::with('campaign')->latest()->get();
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
