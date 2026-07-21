<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Brilliant Event & Wedding Organizer</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

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
            opacity: 0.3;
            background-image: url("{{ asset('assets/pattern1.png') }}");
            background-repeat: repeat;
        }
        body::before { left: 0; }
        body::after { right: 0; }

        .forgot-card {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 420px;
            padding: 3rem 2rem;
            text-align: center;
        }

        .forgot-header {
            color: #28a745; /* Adjusted to match the reference image green */
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .phone-icon-wrapper {
            margin-bottom: 2rem;
        }

        .phone-icon {
            font-size: 5rem;
            color: #28a745;
        }

        .forgot-text {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .btn-back {
            color: var(--brilliant-green);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-back:hover {
            color: var(--brilliant-green-dark);
            transform: translateX(-3px);
        }
    </style>
</head>
<body>

    <div class="forgot-card">
        <h1 class="forgot-header">Forgot Password</h1>

        <div class="phone-icon-wrapper">
            <i class="bi bi-telephone-outbound-fill phone-icon"></i>
        </div>

        <p class="forgot-text">
            Please contact your administration to reset your password.
        </p>

        <a href="{{ route('management.login') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to Login
        </a>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
