<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'description' => 'nullable|string|max:500',
        ]);

        $user->update($request->only('date_of_birth', 'gender', 'description'));

        return redirect()->route('home')->with('success', 'Profile updated successfully!');
    }
}
