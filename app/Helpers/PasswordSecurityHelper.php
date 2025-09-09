<?php

namespace App\Helpers;

class PasswordSecurityHelper
{
    /**
     * Check if password meets security requirements
     */
    public static function validatePasswordStrength(string $password): array
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password must contain at least one lowercase letter';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password must contain at least one special character';
        }
        
        // Check for common weak patterns
        if (preg_match('/^(.)\1+$/', $password)) {
            $errors[] = 'Password cannot be all the same character';
        }
        
        if (preg_match('/123456|abcdef|qwerty/i', $password)) {
            $errors[] = 'Password contains common sequences';
        }
        
        return $errors;
    }
    
    /**
     * Check if password is in common passwords list
     */
    public static function isCommonPassword(string $password): bool
    {
        $commonPasswords = [
            'password', '123456', '123456789', 'qwerty', 'abc123',
            'password123', 'admin', 'letmein', 'welcome', 'monkey',
            '1234567890', 'password1', 'qwerty123', 'dragon', 'master',
            'hello', 'freedom', 'whatever', 'qazwsx', 'trustno1'
        ];
        
        return in_array(strtolower($password), $commonPasswords);
    }
    
    /**
     * Generate secure OTP
     */
    public static function generateSecureOtp(): string
    {
        // Use cryptographically secure random number generator
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Validate OTP format
     */
    public static function validateOtpFormat(string $otp): bool
    {
        return preg_match('/^[0-9]{6}$/', $otp) === 1;
    }
    
    /**
     * Check if email is valid and not disposable
     */
    public static function isValidEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Check for disposable email domains (basic list)
        $disposableDomains = [
            '10minutemail.com', 'tempmail.org', 'guerrillamail.com',
            'mailinator.com', 'yopmail.com', 'temp-mail.org'
        ];
        
        $domain = substr(strrchr($email, "@"), 1);
        return !in_array($domain, $disposableDomains);
    }
    
    /**
     * Sanitize input to prevent XSS
     */
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
