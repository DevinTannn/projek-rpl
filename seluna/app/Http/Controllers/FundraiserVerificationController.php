<?php

namespace App\Http\Controllers;

use App\Models\FundraiserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FundraiserVerificationController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $verification = $user->verification;

        return view('profile.verification', compact('user', 'verification'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Prevent re-submission if already verified
        if ($user->isVerified()) {
            return redirect()->back()->with('error', 'Akun Anda sudah terverifikasi.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'organization_name' => 'required|string|max:255',
            'ktp_photo' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'agreement' => 'accepted'
        ]);

        try {
            $path = $request->file('ktp_photo')->store('verifications', 'public');

            FundraiserVerification::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $request->full_name,
                    'nik' => $request->nik,
                    'organization_name' => $request->organization_name,
                    'ktp_photo' => $path,
                    'status' => 'pending'
                ]
            );

            return redirect()->route('profile.verification')->with('success', 'Dokumen berhasil dikirim. Mohon tunggu proses verifikasi admin.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan dokumen: ' . $e->getMessage());
        }
    }

    public function updateOrganization(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'organization_name' => 'required|string|max:255'
        ]);

        if ($user->verification) {
            $user->verification->update(['organization_name' => $request->organization_name]);
        }

        return redirect()->back()->with('success', 'Nama organisasi berhasil diperbarui.');
    }
}
