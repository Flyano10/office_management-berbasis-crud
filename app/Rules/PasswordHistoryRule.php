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
            return; // Skip validation if no admin ID provided
        }

        $admin = Admin::find($this->adminId);
        if (!$admin) {
            return; // Skip validation if admin not found
        }

        // Get password history from metadata
        $passwordHistory = $admin->password_history ?? [];
        
        // Check against last N passwords
        $recentPasswords = array_slice($passwordHistory, -$this->historyCount);
        
        foreach ($recentPasswords as $oldPassword) {
            if (Hash::check($value, $oldPassword)) {
                $fail("Password tidak boleh sama dengan {$this->historyCount} password terakhir.");
                return;
            }
        }
    }
}