<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\PasswordSecurityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset form
     */
    public function showResetForm()
    {
        if (!session('otp_verified') || !session('otp_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Please verify your OTP first.');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset the user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Please confirm your password.'
        ]);

        $password = PasswordSecurityHelper::sanitizeInput($request->password);
        
        // Additional password security validation
        $passwordErrors = PasswordSecurityHelper::validatePasswordStrength($password);
        if (!empty($passwordErrors)) {
            throw ValidationException::withMessages([
                'password' => $passwordErrors
            ]);
        }
        
        // Check for common passwords
        if (PasswordSecurityHelper::isCommonPassword($password)) {
            throw ValidationException::withMessages([
                'password' => 'This password is too common. Please choose a more secure password.'
            ]);
        }

        $email = session('otp_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Session expired. Please start the process again.');
        }

        // Rate limiting: max 3 password reset attempts per 15 minutes per IP
        $key = 'password-reset:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'password' => "Too many attempts. Please try again in {$seconds} seconds."
            ]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'User not found.');
        }

        // Check if new password is different from current password
        if (Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'New password must be different from your current password.'
            ]);
        }

        // Update the password
        $user->password = Hash::make($password);
        $user->save();

        // Clear all session data
        session()->forget(['otp_email', 'otp_verified']);

        // Increment rate limiter
        RateLimiter::hit($key, 900); // 15 minutes

        return redirect()->route('login')
            ->with('success', 'Password has been reset successfully. You can now login with your new password.');
    }
}
