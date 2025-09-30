<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check minimum length
        if (strlen($value) < 8) {
            $fail('Password harus minimal 8 karakter.');
            return;
        }

        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf besar.');
            return;
        }

        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf kecil.');
            return;
        }

        // Check for number
        if (!preg_match('/[0-9]/', $value)) {
            $fail('Password harus mengandung minimal 1 angka.');
            return;
        }

        // Check for special character
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('Password harus mengandung minimal 1 karakter khusus.');
            return;
        }

        // Check for common weak passwords
        $weakPasswords = [
            'password', '123456', 'admin', 'qwerty', 'abc123',
            'password123', 'admin123', '12345678', 'qwerty123'
        ];

        if (in_array(strtolower($value), $weakPasswords)) {
            $fail('Password terlalu umum, gunakan password yang lebih kuat.');
            return;
        }
    }
}