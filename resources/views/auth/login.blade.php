<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#21618C">

    <title>Login Admin - PLN Icon Plus</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS - Theme Dominan Putih dan Biru PLN -->
    <style>
        :root {
            --pln-blue: #21618C;
            --pln-blue-dark: #1A4D73;
            --pln-blue-light: #2E86AB;
            --pln-blue-lighter: #E8F4F8;
            --pln-blue-bg: #F5FAFC;
            --white: #FFFFFF;
            --gray-light: #F8F9FA;
            --gray-border: #D1D5DB;
            --text-dark: #1A1A1A;
            --text-gray: #6C757D;
            --success-color: #28A745;
            --danger-color: #DC3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--pln-blue-bg) 0%, #ffffff 100%);
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 1rem;
        }

        .login-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(33, 97, 140, 0.12);
            border: 1px solid rgba(33, 97, 140, 0.15);
            overflow: hidden;
            max-width: 380px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .login-header {
            background: var(--white);
            color: var(--pln-blue);
            padding: 1.5rem 1.5rem 1.25rem;
            text-align: center;
            position: relative;
            border-bottom: 2px solid var(--pln-blue);
        }

        .login-header .logo {
            position: relative;
            z-index: 2;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-header h2 {
            color: var(--pln-blue);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0.5rem 0 0.375rem 0;
            position: relative;
        }

        .login-header p {
            margin: 0 0 0 0;
            color: var(--text-gray);
            position: relative;
            font-size: 0.8125rem;
            font-weight: 400;
        }

        .login-body {
            padding: 1.5rem;
            background: var(--white);
            position: relative;
            min-height: auto;
        }


        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: var(--pln-blue);
            margin-right: 0.5rem;
            font-size: 0.875rem;
            width: 16px;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid rgba(33, 97, 140, 0.2);
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background: var(--white);
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--pln-blue);
            box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
            outline: none;
            background: var(--white);
        }

        .form-control::placeholder {
            color: #ADB5BD;
            font-size: 0.875rem;
        }

        .btn-login {
            background: var(--pln-blue);
            color: var(--white) !important;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.9375rem;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
            cursor: pointer;
            margin-top: 0;
            text-transform: none;
            letter-spacing: normal;
        }

        .btn-login:hover {
            background: var(--pln-blue-dark);
            box-shadow: 0 3px 6px rgba(33, 97, 140, 0.3);
            color: var(--white) !important;
        }

        .btn-login:active {
            transform: translateY(1px);
            box-shadow: 0 1px 2px rgba(33, 97, 140, 0.2);
        }

        .btn-login:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.2);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-login.loading {
            position: relative;
            color: transparent !important;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login i {
            margin-right: 8px;
            transition: opacity 0.3s ease;
        }

        .btn-login.loading i {
            opacity: 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--gray-border);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: var(--pln-blue);
            border-color: var(--pln-blue);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.15);
        }

        .form-check-label {
            color: var(--text-gray);
            font-size: 14px;
            cursor: pointer;
            margin-left: 8px;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 0.875rem 1rem;
            font-size: 0.8125rem;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #FFF5F5;
            color: #C53030;
            border-left: 4px solid var(--danger-color);
        }

        .alert-success {
            background-color: #F0FFF4;
            color: #22543D;
            border-left: 4px solid var(--success-color);
        }

        .input-group-text {
            background-color: var(--pln-blue);
            border: 2px solid var(--pln-blue);
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: var(--white);
            padding: 0.75rem 0.875rem;
            font-size: 0.875rem;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        /* Password toggle button */
        .password-toggle {
            background-color: var(--pln-blue);
            border: 2px solid var(--pln-blue);
            border-left: none;
            border-radius: 0 8px 8px 0;
            color: var(--white);
            padding: 0.75rem 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
        }

        .password-toggle:hover {
            background-color: var(--pln-blue-dark);
            border-color: var(--pln-blue-dark);
            color: var(--white);
        }

        .password-toggle:active {
            opacity: 0.8;
        }

        .input-group.password-group .form-control {
            border-right: none;
            border-radius: 0;
        }

        .input-group.password-group .password-toggle {
            border-left: 2px solid var(--pln-blue);
        }

        .input-group.password-group:focus-within .password-toggle {
            border-color: var(--pln-blue-dark);
            background-color: var(--pln-blue-dark);
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--pln-blue-dark);
            background-color: var(--pln-blue-dark);
        }

        .input-group:focus-within .form-control {
            border-left: 2px solid var(--pln-blue);
        }

        .logo {
            margin-bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pln-logo {
            height: 48px;
            width: auto;
            max-width: 130px;
            object-fit: contain;
            background: transparent;
            border-radius: 0;
            padding: 0;
            backdrop-filter: none;
            box-shadow: none;
            border: none;
            filter: none;
        }

        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1rem 0 1.25rem 0;
        }

        .forgot-password {
            color: var(--pln-blue);
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .forgot-password:hover {
            color: var(--pln-blue-dark);
            text-decoration: underline;
        }

        /* Keep alert area height constant so form size doesn't change */
        .alert-fixed { 
            min-height: 40px; 
            margin-bottom: 1rem;
        }
        .alert-fixed:empty { 
            display: block; 
            min-height: 40px; 
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.75rem;
            margin-top: 0.375rem;
        }

        /* Desain Responsive */
        @media (max-width: 768px) {
            body {
                padding: 0.75rem;
            }

            .login-container {
                max-width: 100%;
                border-radius: 12px;
            }
            
            .login-header {
                padding: 1.25rem 1.25rem 1rem;
            }
            
            .login-body {
                padding: 1.25rem;
            }
            
            .pln-logo {
                height: 44px;
                max-width: 120px;
            }
            
            .login-header h2 {
                font-size: 1.125rem;
            }
            
            .login-header p {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }

            .login-container {
                max-width: 100%;
                border-radius: 12px;
            }
            
            .login-header {
                padding: 1.25rem 1rem 1rem;
            }
            
            .login-body {
                padding: 1.25rem 1rem;
            }
            
            .pln-logo {
                height: 40px;
                max-width: 110px;
            }

            .login-header h2 {
                font-size: 1.0625rem;
            }
            
            .login-header p {
                font-size: 0.75rem;
            }
            
            .form-control {
                font-size: 16px; /* Prevent zoom on iOS */
                padding: 0.75rem 0.875rem;
            }
            
            .input-group-text {
                padding: 0.75rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            .password-toggle {
                padding: 0.75rem 0.75rem;
                min-width: 40px;
            }
            
            .btn-login {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .form-label {
                font-size: 0.8125rem;
                margin-bottom: 0.5rem;
            }
            
            .remember-me {
                margin: 0.875rem 0 1rem 0;
            }
            
            .forgot-password {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="padding: 0;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-container">
                    <!-- Header -->
                    <div class="login-header">
                        <div class="logo">
                            <img src="{{ asset('images/logo/pln-logo.png') }}" alt="PLN Logo" class="pln-logo">
                        </div>
                        <h2>PLN Icon Plus</h2>
                        <p>Management Asset & Property</p>
                    </div>

                    <!-- Body -->
                    <div class="login-body">
                        <div class="alert-fixed">
                            @if ($errors->any())
                                <div class="alert alert-danger mb-2">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success mb-2">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger mb-2">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user"></i>Username
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           id="username" 
                                           name="username" 
                                           value="{{ old('username') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="username"
                                           placeholder="Masukkan username">
                                </div>
                                @error('username')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>Password
                                </label>
                                <div class="input-group password-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required 
                                           autocomplete="current-password"
                                           placeholder="Masukkan password">
                                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="remember-me">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        Ingat saya
                                    </label>
                                </div>
                                <a href="#" class="forgot-password">
                                    Lupa password?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-login" id="loginButton">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Masuk ke Admin Panel</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alert dengan fade effect
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });

            // Password Toggle Functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            if (togglePassword && passwordInput && togglePasswordIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle icon
                    if (type === 'password') {
                        togglePasswordIcon.classList.remove('fa-eye-slash');
                        togglePasswordIcon.classList.add('fa-eye');
                    } else {
                        togglePasswordIcon.classList.remove('fa-eye');
                        togglePasswordIcon.classList.add('fa-eye-slash');
                    }
                });
            }

            // Form validation and loading state
            const form = document.querySelector('form');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');

            if (form) {
                // Prevent double submission
                let isSubmitting = false;

                form.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }

                    let isValid = true;

                    // Validate username
                    if (!username.value.trim()) {
                        e.preventDefault();
                        username.focus();
                        username.style.borderColor = 'var(--danger-color)';
                        isValid = false;
                    } else {
                        username.style.borderColor = '';
                    }

                    // Validate password
                    if (!password.value.trim()) {
                        e.preventDefault();
                        if (isValid) password.focus();
                        password.style.borderColor = 'var(--danger-color)';
                        isValid = false;
                    } else {
                        password.style.borderColor = '';
                    }

                    // If valid, show loading state
                    if (isValid && !e.defaultPrevented) {
                        isSubmitting = true;
                        loginButton.disabled = true;
                        loginButton.classList.add('loading');
                    }

                    return isValid;
                });

                // Remove error styling on input
                [username, password].forEach(function(input) {
                    if (input) {
                        input.addEventListener('input', function() {
                            this.style.borderColor = '';
                            // Remove error class if exists
                            this.classList.remove('is-invalid');
                        });
                    }
                });

                // Enter key to submit (natural form behavior, but ensure it works)
                [username, password].forEach(function(input) {
                    if (input) {
                        input.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter' && !isSubmitting) {
                                form.requestSubmit();
                            }
                        });
                    }
                });
            }

            // Add focus animation to inputs
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(function(input) {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Auto-focus username if empty (already has autofocus attribute, but ensure it works)
            if (username && !username.value) {
                setTimeout(function() {
                    username.focus();
                }, 100);
            }
        });
    </script>
</body>
</html>