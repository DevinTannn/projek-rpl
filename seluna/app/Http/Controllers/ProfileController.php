<?php

namespace App\Http\Controllers;

use App\Models\FundraiserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['follows.campaign', 'campaigns', 'donations' => function($q) {
            $q->with('campaign')->latest();
        }]);
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'description' => 'nullable|string|max:500',
            'cropped_image' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('username', 'email', 'date_of_birth', 'gender', 'description');

        if ($request->filled('cropped_image')) {
            $base64Image = $request->input('cropped_image');
            $image_parts = explode(";base64,", $base64Image);
            if (count($image_parts) > 1) {
                $image_base64 = base64_decode($image_parts[1]);
                $filename = 'profiles/' . uniqid() . '.jpg';
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                Storage::disk('public')->put($filename, $image_base64);
                $data['profile_photo'] = $filename;
            }
        } elseif ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function upgrade(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'fundraiser') {
            return redirect()->back()->with('error', 'You are already a fundraiser.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'organization_name' => 'required|string|max:255',
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'statement' => 'required|accepted',
        ]);

        $path = $request->file('ktp_photo')->store('verifications', 'public');

        FundraiserVerification::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'nik' => $request->nik,
            'organization_name' => $request->organization_name,
            'ktp_photo' => $path,
            'status' => 'approved', // Auto-approve for demo as requested (upgrade directly)
        ]);

        $user->update(['role' => 'fundraiser']);

        return redirect()->route('profile.show')->with('success', 'Congratulations! You are now a Fundraiser.');
    }
}
