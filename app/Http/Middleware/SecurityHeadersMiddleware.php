<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers untuk melindungi aplikasi dari berbagai serangan
        $this->setSecurityHeaders($response, $request);

        return $response;
    }

    /**
     * Set security headers pada response
     */
    protected function setSecurityHeaders(Response $response, Request $request): void
    {
        // X-Content-Type-Options: Mencegah browser melakukan MIME-sniffing
        // Melindungi dari serangan yang memanfaatkan kesalahan deteksi tipe konten
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options: Mencegah aplikasi di-embed dalam iframe
        // Melindungi dari serangan Clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-XSS-Protection: Mengaktifkan filter XSS di browser lama
        // (Browser modern sudah punya proteksi built-in, tapi tetap berguna untuk kompatibilitas)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: Mengontrol informasi referrer yang dikirim
        // 'strict-origin-when-cross-origin' = kirim referrer hanya untuk same-origin atau HTTPS
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy: Mengontrol fitur browser yang bisa digunakan
        // Menonaktifkan fitur yang tidak diperlukan untuk mengurangi attack surface
        $response->headers->set('Permissions-Policy', implode(', ', [
            'geolocation=()',
            'microphone=()',
            'camera=()',
            'payment=()',
            'usb=()',
            'magnetometer=()',
            'gyroscope=()',
            'accelerometer=()',
        ]));

        // Content-Security-Policy: Mengontrol sumber daya yang bisa dimuat
        // Sangat penting untuk mencegah XSS dan injection attacks
        $this->setContentSecurityPolicy($response, $request);

        // Strict-Transport-Security (HSTS): Hanya kirim jika HTTPS
        // Memaksa browser menggunakan HTTPS untuk koneksi berikutnya
        if ($request->secure() || $request->header('X-Forwarded-Proto') === 'https') {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Cache-Control untuk halaman admin (tidak boleh di-cache)
        if ($request->is('admin/*')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
        }
    }

    /**
     * Set Content Security Policy header
     */
    protected function setContentSecurityPolicy(Response $response, Request $request): void
    {
        // CSP untuk internal PLN - mengizinkan CDN yang digunakan aplikasi
        $isLocal = app()->environment('local') || $request->getHost() === '127.0.0.1' || $request->getHost() === 'localhost';
        
        $cspDirectives = [
            "default-src 'self'",
            // Script sources: izinkan CDN yang digunakan (Bootstrap, Leaflet, ExcelJS)
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com",
            // Style sources: izinkan CDN yang digunakan (Bootstrap, Leaflet, Font Awesome, Google Fonts)
            "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https://fonts.googleapis.com",
            "img-src 'self' data: https: blob: https://api.qrserver.com",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            // Connect sources: allow self dan localhost untuk development
            $isLocal ? "connect-src 'self' http://127.0.0.1:* http://localhost:* ws://127.0.0.1:* ws://localhost:*" : "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];
        
        // Hanya tambahkan upgrade-insecure-requests jika bukan local
        if (!$isLocal) {
            $cspDirectives[] = "upgrade-insecure-requests";
        }

        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));
    }
}

