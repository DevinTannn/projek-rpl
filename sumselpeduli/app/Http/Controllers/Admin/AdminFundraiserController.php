<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundraiserVerification;
use Illuminate\Http\Request;

class AdminFundraiserController extends Controller
{
    public function index()
    {
        $verifications = FundraiserVerification::with('user')->latest()->paginate(15);
        return view('admin.fundraisers.index', compact('verifications'));
    }

    public function verify($id)
    {
        $verification = FundraiserVerification::findOrFail($id);
        $verification->update(['status' => 'approved']);
        
        // Optionally update user role if not already fundraiser
        if ($verification->user->role !== 'fundraiser') {
            $verification->user->update(['role' => 'fundraiser']);
        }

        return redirect()->back()->with('success', 'Fundraiser berhasil diverifikasi.');
    }

    public function reject($id)
    {
        $verification = FundraiserVerification::findOrFail($id);
        $verification->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Verifikasi fundraiser ditolak.');
    }
}
