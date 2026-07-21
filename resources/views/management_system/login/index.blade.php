<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Brilliant Event & Wedding Organizer</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --brilliant-bg: #f4f7f4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brilliant-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle Background Pattern */
        body::before, body::after {
            content: "";
            position: fixed;
            top: 0;
            width: 300px;
            height: 100%;
            z-index: -1;
            opacity: 0.3; /* Adjusted opacity for better visibility of the image pattern */
            background-image: url("{{ asset('assets/pattern1.png') }}");
            background-repeat: repeat;
        }
        body::before { left: 0; }
        body::after { right: 0; }

        .login-card {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
        }

        .login-logo {
            max-height: 70px;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background-color: var(--brilliant-green-light);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--brilliant-green);
            box-shadow: 0 0 0 4px rgba(124, 163, 97, 0.1);
            outline: none;
        }

        .btn-primary {
            background-color: var(--brilliant-green);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--brilliant-green-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 163, 97, 0.2);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .password-toggle {
            cursor: pointer;
            color: #718096;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: var(--brilliant-green);
        }

        .input-group-text {
            background-color: var(--brilliant-green-light);
            border: none;
            border-radius: 0 12px 12px 0;
            padding-right: 1rem;
        }

        .form-control.with-toggle {
            border-radius: 12px 0 0 12px;
        }

        .login-header {
            color: var(--brilliant-green);
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        .forgot-password {
            font-size: 0.875rem;
            color: var(--brilliant-green);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: var(--brilliant-green-dark);
            text-decoration: underline;
        }

        .error-feedback {
            font-size: 0.8rem;
            color: #e53e3e;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo Section -->
        <div class="text-center">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Brilliant Logo" class="login-logo">
        </div>

        <h1 class="login-header">Login</h1>

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 d-flex align-items-center" style="background-color: #fff5f5; color: #c53030; font-size: 0.875rem;">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('management.login.post') }}" method="POST">
            @csrf
            
            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    placeholder="name@company.com" 
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label">Password</label>
                    <a href="{{ route('management.forgot_password') }}" class="forgot-password">Forgot Password?</a>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" 
                        class="form-control with-toggle @error('password') is-invalid @enderror" 
                        placeholder="••••••••" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>
                @error('password')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- reCAPTCHA Field -->
            <div class="mb-4 d-flex flex-column align-items-center">
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                @error('g-recaptcha-response')
                    <div class="error-feedback mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    Sign In
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>