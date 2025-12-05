<?php

namespace App\Services;

use PragmaRX\Google2FA\Google2FA;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MFAService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate secret key untuk MFA
     */
    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR Code URL untuk setup MFA
     */
    public function getQRCodeUrl(Admin $admin, ?string $secret = null): string
    {
        $companyName = config('app.name', 'PLN Icon Plus');
        $companyEmail = $admin->email;
        $secretKey = $secret ?? $admin->mfa_secret;
        
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secretKey
        );
    }

    /**
     * Generate QR Code sebagai inline image menggunakan QR Server API
     */
    public function getQRCodeInline(Admin $admin, ?string $secret = null): string
    {
        $companyName = config('app.name', 'PLN Icon Plus');
        $companyEmail = $admin->email;
        $secretKey = $secret ?? $admin->mfa_secret;
        
        // Generate otpauth URL
        $otpauthUrl = $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secretKey
        );
        
        // Encode URL untuk QR Server API
        $encodedUrl = urlencode($otpauthUrl);
        
        // Return sebagai img tag dengan QR Server API
        return '<img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $encodedUrl . '" alt="QR Code" class="img-fluid" style="max-width: 250px; height: auto;">';
    }

    /**
     * Generate backup codes (10 codes)
     */
    public function generateBackupCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4))); // 8 karakter hex
        }
        return $codes;
    }

    /**
     * Verify OTP code
     * @param Admin $admin Admin model (bisa dengan mfa_enabled = false untuk setup)
     * @param string $code OTP code dari authenticator
     * @param bool $skipEnabledCheck Skip check mfa_enabled (untuk setup)
     */
    public function verifyOTP(Admin $admin, string $code, bool $skipEnabledCheck = false): bool
    {
        if (!$admin->mfa_secret) {
            return false;
        }
        
        // Skip enabled check jika sedang setup
        if (!$skipEnabledCheck && !$admin->mfa_enabled) {
            return false;
        }

        // Cek apakah code adalah backup code (hanya jika MFA sudah enabled)
        if ($admin->mfa_enabled && $this->verifyBackupCode($admin, $code)) {
            return true;
        }

        // Clean code (remove spaces, non-numeric)
        $cleanCode = preg_replace('/[^0-9]/', '', $code);
        
        if (strlen($cleanCode) !== 6) {
            Log::warning('MFA verification failed: invalid code length', [
                'admin_id' => $admin->id ?? null,
                'code_length' => strlen($cleanCode),
                'original_code' => $code,
            ]);
            return false;
        }

        // Verify TOTP code (allow 2 time windows = 60 detik sebelum/sesudah untuk toleransi clock skew)
        $valid = $this->google2fa->verifyKey(
            $admin->mfa_secret,
            $cleanCode,
            2 // 2 time windows = 60 detik sebelum/sesudah untuk toleransi lebih besar
        );

        if ($valid) {
            Log::info('MFA OTP verified successfully', [
                'admin_id' => $admin->id ?? null,
                'username' => $admin->username ?? null,
                'skip_enabled_check' => $skipEnabledCheck,
            ]);
        } else {
            Log::warning('MFA OTP verification failed', [
                'admin_id' => $admin->id ?? null,
                'username' => $admin->username ?? null,
                'code' => $cleanCode,
                'secret_length' => strlen($admin->mfa_secret),
                'skip_enabled_check' => $skipEnabledCheck,
            ]);
        }

        return $valid;
    }

    /**
     * Verify backup code
     */
    public function verifyBackupCode(Admin $admin, string $code): bool
    {
        if (!$admin->mfa_backup_codes) {
            return false;
        }

        $backupCodes = $admin->mfa_backup_codes;
        $codeIndex = array_search($code, $backupCodes);

        if ($codeIndex !== false) {
            // Remove used backup code
            unset($backupCodes[$codeIndex]);
            $admin->mfa_backup_codes = array_values($backupCodes); // Re-index array
            $admin->save();

            Log::info('MFA backup code used', [
                'admin_id' => $admin->id,
                'username' => $admin->username,
                'remaining_codes' => count($admin->mfa_backup_codes),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Enable MFA untuk admin
     */
    public function enableMFA(Admin $admin, string $secret, array $backupCodes): void
    {
        $admin->mfa_secret = $secret;
        $admin->mfa_enabled = true;
        $admin->mfa_backup_codes = $backupCodes;
        $admin->save();

        Log::info('MFA enabled for admin', [
            'admin_id' => $admin->id,
            'username' => $admin->username,
        ]);
    }

    /**
     * Disable MFA untuk admin
     */
    public function disableMFA(Admin $admin): void
    {
        $admin->mfa_secret = null;
        $admin->mfa_enabled = false;
        $admin->mfa_backup_codes = null;
        $admin->save();

        Log::info('MFA disabled for admin', [
            'admin_id' => $admin->id,
            'username' => $admin->username,
        ]);
    }

    /**
     * Regenerate backup codes
     */
    public function regenerateBackupCodes(Admin $admin): array
    {
        $backupCodes = $this->generateBackupCodes();
        $admin->mfa_backup_codes = $backupCodes;
        $admin->save();

        Log::info('MFA backup codes regenerated', [
            'admin_id' => $admin->id,
            'username' => $admin->username,
        ]);

        return $backupCodes;
    }
}

