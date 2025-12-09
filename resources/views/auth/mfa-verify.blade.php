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
            --pln-blue: #21618C;
            --pln-blue-dark: #1A4D73;
            --pln-blue-light: #2E86AB;
            --pln-blue-lighter: #E8F4F8;
            --pln-blue-bg: #F5FAFC;
            --text-dark: #1A1A1A;
            --text-gray: #6C757D;
        }

        body {
            background: linear-gradient(135deg, var(--pln-blue-bg) 0%, #ffffff 100%);
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .mfa-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(33, 97, 140, 0.12);
            border: 1px solid rgba(33, 97, 140, 0.15);
            overflow: hidden;
            max-width: 380px;
            width: 100%;
            padding: 0;
        }

        .mfa-header {
            background: white;
            padding: 1.5rem 1.5rem 1.25rem;
            text-align: center;
            border-bottom: 2px solid var(--pln-blue);
        }

        .mfa-icon {
            width: 56px;
            height: 56px;
            background: var(--pln-blue-lighter);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 2px solid var(--pln-blue);
        }

        .mfa-icon i {
            font-size: 1.75rem;
            color: var(--pln-blue);
        }

        .mfa-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pln-blue);
            margin-bottom: 0.375rem;
        }

        .mfa-subtitle {
            color: var(--text-gray);
            font-size: 0.8125rem;
            margin: 0;
            line-height: 1.4;
        }

        .mfa-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
        }

        .form-control {
            border: 2px solid rgba(33, 97, 140, 0.2);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--pln-blue);
            box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
            outline: none;
        }

        .btn-primary {
            background: var(--pln-blue);
            border: 1px solid var(--pln-blue);
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.9375rem;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
        }

        .btn-primary:hover {
            background: var(--pln-blue-dark);
            border-color: var(--pln-blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 1.25rem;
            border: none;
            padding: 0.875rem 1rem;
            font-size: 0.8125rem;
        }

        .alert-info {
            background: var(--pln-blue-lighter);
            color: var(--pln-blue-dark);
            border-left: 3px solid var(--pln-blue);
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid #dc3545;
        }

        .alert ul {
            margin: 0.5rem 0 0 1rem;
            padding-left: 0;
        }

        .back-link {
            text-align: center;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(33, 97, 140, 0.1);
        }

        .back-link a {
            color: var(--pln-blue);
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            transition: all 0.2s ease;
        }

        .back-link a:hover {
            color: var(--pln-blue-dark);
            text-decoration: none;
        }

        .mfa-code-input {
            font-size: 1.375rem;
            text-align: center;
            letter-spacing: 0.5rem;
            font-weight: 700;
            padding: 0.875rem 1rem;
            border: 2px solid rgba(33, 97, 140, 0.2);
            border-radius: 10px;
            color: var(--pln-blue);
        }

        .mfa-code-input:focus {
            border-color: var(--pln-blue);
            box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
            color: var(--pln-blue);
        }

        .form-text {
            color: var(--text-gray);
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.375rem;
            line-height: 1.4;
        }

        .form-text i {
            color: var(--pln-blue);
            margin-top: 0.125rem;
            flex-shrink: 0;
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

        <div class="mfa-body">
            @if (session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
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
                    <small class="form-text">
                        <i class="fas fa-info-circle"></i>
                        <span>Gunakan kode dari aplikasi authenticator atau backup code</span>
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i>
                    Verifikasi
                </button>
            </form>

            <div class="back-link">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-focus dan format input
        const mfaCodeInput = document.getElementById('mfa_code');
        
        mfaCodeInput.addEventListener('input', function(e) {
            // Hanya angka
            this.value = this.value.replace(/[^0-9]/g, '');
            // Maksimal 6 digit
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });

        // Auto-submit jika sudah 6 digit
        mfaCodeInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                // Optional: auto-submit setelah delay kecil
                // setTimeout(() => {
                //     this.form.submit();
                // }, 500);
            }
        });
    </script>
</body>
</html>
