<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class PasswordHistoryRule implements ValidationRule
{
    protected $adminId;
    protected $historyCount;

    public function __construct($adminId = null, $historyCount = 5)
    {
        $this->adminId = $adminId;
        $this->historyCount = $historyCount;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->adminId) {
            return; // Skip validasi kalau gak ada admin ID
        }

        $admin = Admin::find($this->adminId);
        if (!$admin) {
            return; // Skip validasi kalau admin gak ditemukan
        }

        // Ambil riwayat password dari metadata
        $passwordHistory = $admin->password_history ?? [];
        
        // Cek terhadap N password terakhir
        $recentPasswords = array_slice($passwordHistory, -$this->historyCount);
        
        foreach ($recentPasswords as $oldPassword) {
            if (Hash::check($value, $oldPassword)) {
                $fail("Password tidak boleh sama dengan {$this->historyCount} password terakhir.");
                return;
            }
        }
    }
}