<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\PasswordSecurityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to user's email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'The provided email address is not registered in our system.'
        ]);

        $email = PasswordSecurityHelper::sanitizeInput($request->email);
        
        // Additional email validation
        if (!PasswordSecurityHelper::isValidEmail($email)) {
            throw ValidationException::withMessages([
                'email' => 'Please provide a valid email address.'
            ]);
        }

        $user = User::where('email', $email)->first();

        // Rate limiting: max 3 attempts per 15 minutes per IP
        $key = 'forgot-password:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many attempts. Please try again in {$seconds} seconds."
            ]);
        }

        // Rate limiting: max 1 OTP per email per 2 minutes
        $emailKey = 'otp-email:' . $email;
        if (Cache::has($emailKey)) {
            throw ValidationException::withMessages([
                'email' => 'An OTP has already been sent. Please wait 2 minutes before requesting another.'
            ]);
        }

        // Generate secure 6-digit OTP
        $otp = PasswordSecurityHelper::generateSecureOtp();
        
        // Hash the OTP for storage
        $hashedOtp = Hash::make($otp);
        
        // Store hashed OTP in cache for 2 minutes
        Cache::put($emailKey, $hashedOtp, 120); // 2 minutes
        
        // Store email in session for verification step
        session(['otp_email' => $email]);
        
        // Increment rate limiter
        RateLimiter::hit($key, 900); // 15 minutes

        // Send OTP via email
        try {
            Mail::send('emails.password-reset-otp', [
                'otp' => $otp,
                'user' => $user
            ], function ($message) use ($email, $user) {
                $message->to($email, $user->name)
                        ->subject('Password Reset OTP - ' . config('app.name'));
            });

            return redirect()->route('password.verify-otp')
                ->with('success', 'OTP has been sent to your email address.');
                
        } catch (\Exception $e) {
            // Remove cached OTP if email fails
            Cache::forget($emailKey);
            
            throw ValidationException::withMessages([
                'email' => 'Failed to send OTP. Please try again later.'
            ]);
        }
    }
}
