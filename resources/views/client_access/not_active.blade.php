<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Disabled - Brilliant Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7f4;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .disabled-card {
            background: white;
            border-radius: 30px;
            padding: 50px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .icon-box {
            width: 100px;
            height: 100px;
            background: #ffe5e5;
            color: #e53e3e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 30px;
        }
    </style>
</head>
<body>
    <div class="disabled-card">
        <div class="icon-box">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h2 class="fw-bold mb-3">Access Disabled</h2>
        <p class="text-muted mb-4">Sorry, access to this QR code has been disabled by the administrator. Please contact the event organizer for more information.</p>
        <div class="d-grid">
            <button class="btn btn-secondary rounded-pill py-3 fw-bold" onclick="window.close()">Close This Tab</button>
        </div>
        <div class="mt-4 small text-muted">
            &copy; {{ date('Y') }} Brilliant Event & Wedding Organizer
        </div>
    </div>
</body>
</html>
