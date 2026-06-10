<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Auto-seed admin user if they don't exist in the database yet
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'password') {
            User::firstOrCreate(
                ['username' => 'admin'],
                [
                    'name' => 'Administrator',
                    'email' => 'admin@sumselpeduli.org',
                    'password' => Hash::make('password'),
                    'role' => 'admin'
                ]
            );
        }

        // Attempt to log in using username or email
        $fieldType = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            
            // Log out if not admin
            Auth::logout();
            return back()->withErrors([
                'username' => 'Akses ditolak. Anda bukan Administrator.',
            ]);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
