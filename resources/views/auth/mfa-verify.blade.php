<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi MFA - PLN Icon Plus</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #6C757D;
            --success-color: #28A745;
            --danger-color: #DC3545;
            --warning-color: #FFC107;
            --info-color: #17A2B8;
            --light-color: #F8F9FA;
            --dark-color: #343A40;
            --white: #FFFFFF;
            --blue-light: #E3F2FD;
            --blue-lighter: #F0F8FF;
        }

        body {
            background: #f5f7fb;
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mfa-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            padding: 2rem;
        }

        .mfa-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .mfa-icon {
            width: 80px;
            height: 80px;
            background: var(--blue-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .mfa-icon i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .mfa-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .mfa-subtitle {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 6px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #357ABD;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        .alert {
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .mfa-code-input {
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="mfa-container">
        <div class="mfa-header">
            <div class="mfa-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="mfa-title">Verifikasi MFA</h1>
            <p class="mfa-subtitle">Masukkan kode 6 digit dari aplikasi authenticator Anda</p>
        </div>

        @if (session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('mfa.verify.post') }}">
            @csrf

            <div class="form-group">
                <label for="mfa_code" class="form-label">Kode Autentikasi</label>
                <input 
                    type="text" 
                    id="mfa_code" 
                    name="mfa_code" 
                    class="form-control mfa-code-input" 
                    placeholder="000000" 
                    maxlength="6" 
                    pattern="[0-9]{6}" 
                    required 
                    autofocus
                    autocomplete="one-time-code"
                >
                <small class="form-text text-muted mt-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Gunakan kode dari aplikasi authenticator (Google Authenticator, Authy, dll) atau backup code
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check me-2"></i>
                Verifikasi
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Login
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-focus dan format input
        document.getElementById('mfa_code').addEventListener('input', function(e) {
            // Hanya angka
            this.value = this.value.replace(/[^0-9]/g, '');
            // Maksimal 6 digit
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
    </script>
</body>
</html>

