<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function showForm(Request $request): View|RedirectResponse
    {
        $email = $request->query('email');

        if (! $email) {
            return redirect()->route('register');
        }

        $user = User::where('email', $email)->first();

        if (! $user || $user->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $request->email)
            ->where('email_verification_code', $request->otp)
            ->where('email_verification_code_expires_at', '>', now())
            ->first();

        if (! $user) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        $user->markEmailAsVerified();
        $user->forceFill([
            'email_verification_code' => null,
            'email_verification_code_expires_at' => null,
        ])->save();

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function resend(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (! $user || $user->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->forceFill([
            'email_verification_code' => $otp,
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->name));

        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}
