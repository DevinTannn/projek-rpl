<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Step 1: Show email form
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    // Step 2: Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email ini tidak terdaftar di sistem kami.',
        ]);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing OTP for this email
        DB::table('password_reset_otps')->where('email', $request->email)->delete();

        // Insert new OTP
        DB::table('password_reset_otps')->insert([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Get the user's username for the email
        $user = User::where('email', $request->email)->first();

        // Send OTP email
        Mail::to($request->email)->send(new OtpMail($otp, $user->username));

        // Store email in session to use on verify page
        session(['otp_email' => $request->email]);

        return redirect()->route('password.verify-otp')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // Step 3: Show OTP verification form
    public function showOtpForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp', ['email' => session('otp_email')]);
    }

    // Step 4: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        // Mark OTP as verified — store a token in session
        session(['otp_verified' => true, 'otp_email' => $email]);

        return redirect()->route('password.reset-form');
    }

    // Step 5: Show new password form
    public function showResetForm()
    {
        if (!session('otp_verified') || !session('otp_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // Step 6: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('otp_email');

        if (!session('otp_verified') || !$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Cleanup OTP record and session
        DB::table('password_reset_otps')->where('email', $email)->delete();
        session()->forget(['otp_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan masuk dengan password baru Anda.');
    }
}
