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
        // Cek panjang minimum
        if (strlen($value) < 8) {
            $fail('Password harus minimal 8 karakter.');
            return;
        }

        // Cek huruf besar
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf besar.');
            return;
        }

        // Cek huruf kecil
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf kecil.');
            return;
        }

        // Cek angka
        if (!preg_match('/[0-9]/', $value)) {
            $fail('Password harus mengandung minimal 1 angka.');
            return;
        }

        // Cek karakter khusus
        if (!preg_match('/[^A-Za-z0-9]/', $value)) {
            $fail('Password harus mengandung minimal 1 karakter khusus.');
            return;
        }

        // Cek password lemah yang umum
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