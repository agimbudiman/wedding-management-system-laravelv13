<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Guest Book - {{ $event->name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --brilliant-bg: #f8faf8;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            background-color: var(--brilliant-bg);
            background-image:
                radial-gradient(at 0% 0%, rgba(124, 163, 97, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(124, 163, 97, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
            padding: 20px 0 40px;
        }

        .container {
            max-width: 600px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            color: var(--brilliant-green-dark);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            background: var(--glass-bg);
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .back-btn:hover {
            transform: translateX(-5px);
            color: var(--brilliant-green);
        }

        .header-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .header-section h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .form-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 15px;
            padding: 15px 20px;
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: white;
            border-color: var(--brilliant-green);
            box-shadow: 0 0 0 4px rgba(124, 163, 97, 0.1);
        }

        .btn-submit {
            background: var(--brilliant-green);
            color: white;
            border: none;
            width: 100%;
            padding: 18px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 1.1rem;
            margin-top: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(124, 163, 97, 0.3);
        }

        .btn-submit:hover {
            background: var(--brilliant-green-dark);
            transform: scale(1.02);
            box-shadow: 0 15px 35px rgba(124, 163, 97, 0.4);
        }

        .form-icon {
            font-size: 3.5rem;
            color: var(--brilliant-green);
            margin-bottom: 20px;
            opacity: 0.8;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease forwards;
        }
    </style>
</head>

<body>
    <div class="container">
        @if(!isset($is_guest_portal) || !$is_guest_portal)
            <a href="{{ route('qr.client.redirect', $token) }}" class="back-btn">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
        @endif

        <div class="header-section">
            <div class="form-icon">
                <i class="bi bi-journal-text"></i>
            </div>
            <h1>Digital Guest Book</h1>
            <p class="text-muted">Please fill in your details to join our celebration</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm p-5 mb-4 animate-fade-in text-center">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: var(--brilliant-green);"></i>
                </div>
                <h2 class="fw-bold mb-3 outfit">Thank You!</h2>
                <p class="lead mb-0 text-muted">{{ session('success') }}</p>
                <div class="mt-4">
                    <p class="small text-muted">You can now close this page or enjoy the celebration.</p>
                </div>
            </div>
        @else
            <div class="form-card">
                <form action="{{ route('client.guest_book.store', $token) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Address / City</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Enter your city or address" required>
                    </div>

                    <button type="submit" class="btn-submit">
                        Sign Guest Book <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </form>
            </div>
        @endif

        <div class="text-center mt-5">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Logo" style="height: 30px; opacity: 0.5;">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
