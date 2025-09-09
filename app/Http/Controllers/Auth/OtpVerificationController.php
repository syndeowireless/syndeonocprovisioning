<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\PasswordSecurityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form
     */
    public function showOtpForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Please request an OTP first.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify the OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/'
        ], [
            'otp.size' => 'OTP must be exactly 6 digits.',
            'otp.regex' => 'OTP must contain only numbers.'
        ]);

        $otp = PasswordSecurityHelper::sanitizeInput($request->otp);
        
        // Additional OTP validation
        if (!PasswordSecurityHelper::validateOtpFormat($otp)) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP format.'
            ]);
        }

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Session expired. Please request a new OTP.');
        }

        // Rate limiting: max 5 attempts per 15 minutes per IP
        $key = 'otp-verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'otp' => "Too many failed attempts. Please try again in {$seconds} seconds."
            ]);
        }

        $emailKey = 'otp-email:' . $email;
        $hashedOtp = Cache::get($emailKey);

        if (!$hashedOtp) {
            return redirect()->route('password.request')
                ->with('error', 'OTP has expired. Please request a new one.');
        }

        // Verify the OTP
        if (!Hash::check($otp, $hashedOtp)) {
            RateLimiter::hit($key, 900); // 15 minutes
            
            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please check and try again.'
            ]);
        }

        // OTP is valid - clear it from cache and set verification flag
        Cache::forget($emailKey);
        session(['otp_verified' => true]);
        
        // Clear rate limiter on successful verification
        RateLimiter::clear($key);

        return redirect()->route('password.reset-form')
            ->with('success', 'OTP verified successfully. You can now reset your password.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Please request an OTP first.');
        }

        // Rate limiting: max 3 resends per 15 minutes per IP
        $key = 'otp-resend:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'otp' => "Too many resend attempts. Please try again in {$seconds} seconds."
            ]);
        }

        // Clear any existing OTP for this email
        $emailKey = 'otp-email:' . $email;
        Cache::forget($emailKey);

        // Generate new secure OTP
        $otp = PasswordSecurityHelper::generateSecureOtp();
        $hashedOtp = Hash::make($otp);
        
        // Store new OTP
        Cache::put($emailKey, $hashedOtp, 120); // 2 minutes
        
        // Increment rate limiter
        RateLimiter::hit($key, 900); // 15 minutes

        // Send new OTP
        $user = User::where('email', $email)->first();
        try {
            Mail::send('emails.password-reset-otp', [
                'otp' => $otp,
                'user' => $user
            ], function ($message) use ($email, $user) {
                $message->to($email, $user->name)
                        ->subject('New Password Reset OTP - ' . config('app.name'));
            });

            return back()->with('success', 'New OTP has been sent to your email address.');
            
        } catch (\Exception $e) {
            Cache::forget($emailKey);
            
            throw ValidationException::withMessages([
                'otp' => 'Failed to send OTP. Please try again later.'
            ]);
        }
    }
}
