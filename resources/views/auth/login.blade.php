<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin - PLN Icon Plus</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS - Theme Dominan Putih dan Biru Muda -->
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

        .login-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            max-width: 300px;
            width: 100%;
        }

        .login-header {
            background: #f8fafc;
            color: #1e293b;
            padding: 12px 12px 10px 12px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            min-height: 140px; /* keep header height steady despite bigger logo */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .login-header h2 { display: none; }

        .login-header p {
            margin: 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            font-size: 0.75rem;
        }

        .login-body {
            padding: 16px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: none;
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .btn-login {
            background: #fff;
            color: #3b82f6;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            transition: background-color .2s ease, color .2s ease, box-shadow .2s ease;
        }
        .btn-login:hover { background: #eff6ff; color: #1e40af; box-shadow: 0 2px 8px rgba(59,130,246,.15); }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: var(--secondary-color);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .input-group-text {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group .form-control:focus {
            border-left: none;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: var(--primary-color);
        }

        .logo {
            margin-bottom: 2px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pln-logo {
            height: 104px;
            width: auto;
            max-width: 140px;
            object-fit: contain;
            background: transparent;
            border-radius: 8px;
            padding: 0;
        }


        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            color: #357ABD;
            text-decoration: underline;
        }

        /* Keep alert area height constant so form size doesn't change */
        .alert-fixed { min-height: 56px; }
        .alert-fixed:empty { display:block; min-height:56px; }

        /* Desain Responsive */
        @media (max-width: 768px) {
            .login-container {
                max-width: 95%;
                margin: 20px;
            }
            
            .login-header {
                padding: 25px 20px;
            }
            
            .login-body {
                padding: 25px 20px;
            }
            
            .pln-logo {
                height: 50px;
                max-width: 70px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 6px;
                padding: 4px;
            }
            
            .login-header h2 {
                font-size: 1.5rem;
            }
            
            .login-header p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 20px 15px;
            }
            
            .login-body {
                padding: 20px 15px;
            }
            
            .pln-logo {
                height: 45px;
                max-width: 60px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 5px;
                padding: 3px;
            }
            
            .form-control {
                font-size: 16px;
                padding: 10px 12px;
            }
            
            .btn-login {
                padding: 12px 25px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
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
                                    <i class="fas fa-user me-2"></i>Username
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
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
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
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
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
        // Auto-hide alert
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);

        // Validasi form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const username = document.getElementById('username');
            const password = document.getElementById('password');

            form.addEventListener('submit', function(e) {
                if (!username.value.trim()) {
                    e.preventDefault();
                    username.focus();
                    return false;
                }
                if (!password.value.trim()) {
                    e.preventDefault();
                    password.focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>