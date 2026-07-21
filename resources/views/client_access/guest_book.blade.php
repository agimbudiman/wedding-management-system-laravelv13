<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Book - {{ $event->name }}</title>
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
            max-width: 800px;
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
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .guest-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 25px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .guest-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            border-color: var(--brilliant-green-light);
        }

        .guest-avatar {
            width: 55px;
            height: 55px;
            background: var(--brilliant-green-light);
            color: var(--brilliant-green);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .guest-info h4 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0 0 4px 0;
            color: #1a202c;
        }

        .guest-info p {
            font-size: 0.85rem;
            color: #718096;
            margin: 0;
        }

        .guest-time {
            margin-left: auto;
            font-size: 0.75rem;
            color: #a0aec0;
            text-align: right;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--glass-bg);
            border-radius: 30px;
            border: 1px dashed var(--glass-border);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--brilliant-green);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .btn-fill {
            background: var(--brilliant-green);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(124, 163, 97, 0.2);
        }

        .btn-fill:hover {
            background: var(--brilliant-green-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(124, 163, 97, 0.3);
        }

        /* Search Bar */
        .search-container .input-group-text {
            border: 1px solid var(--glass-border);
            border-right: none;
        }

        .search-container .form-control {
            border: 1px solid var(--glass-border);
            border-left: none;
            background: white;
        }

        .search-container .form-control:focus {
            box-shadow: none;
            border-color: var(--glass-border);
        }

        /* Pagination Styling */
        .pagination {
            margin-top: 30px;
            justify-content: center;
            gap: 8px;
        }

        .pagination .page-item .page-link {
            border-radius: 12px;
            border: 1px solid var(--glass-border);
            background: var(--glass-bg);
            color: var(--brilliant-green-dark);
            padding: 10px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
        }

        .pagination .page-item.active .page-link {
            background: var(--brilliant-green);
            border-color: var(--brilliant-green);
            color: white;
            box-shadow: 0 8px 20px rgba(124, 163, 97, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            background: #f1f5f9;
            color: #cbd5e1;
            border-color: #e2e8f0;
        }

        .pagination .page-item .page-link:hover:not(.active):not(.disabled) {
            background: white;
            color: var(--brilliant-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('qr.client.redirect', $token) }}" class="back-btn">
            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
        </a>

        <div class="header-section">
            <h1>Guest Book</h1>
            <p class="text-muted">A list of guests who have joined our celebration</p>
            @if(!$is_client)
                <a href="{{ route('client.guest_book.form', $token) }}" class="btn-fill">
                    <i class="bi bi-pencil-square me-2"></i> Sign Guest Book
                </a>
            @endif
        </div>

        <!-- Search Bar -->
        <div class="search-container mb-4">
            <form action="{{ route('client.guest_book', $token) }}" method="GET" class="d-flex gap-2">
                <div class="input-group shadow-sm rounded-pill overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 py-2"
                        placeholder="Search guests by name or address..." value="{{ request('search') }}">
                </div>
                @if(request('search'))
                    <a href="{{ route('client.guest_book', $token) }}"
                        class="btn btn-light rounded-pill px-3 d-flex align-items-center border">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>

        @if($guests->count() > 0)
            <div class="guest-list">
                @foreach($guests as $guest)
                    <div class="guest-card">
                        <div class="guest-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="guest-info">
                            <h4>{{ $guest->name }}</h4>
                            <p><i class="bi bi-geo-alt me-1"></i> {{ $guest->address }}</p>
                        </div>
                        <div class="guest-time text-end">
                            <!-- <div class="fw-medium text-dark small">{{ $guest->created_at->format('H:i') }}</div> -->
                            <div class="x-small">{{ $guest->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $guests->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-journal-text"></i>
                <h3>No Guests Found</h3>
                <p>
                    @if(request('search'))
                        We couldn't find any guests matching "{{ request('search') }}".
                    @else
                        Be the first one to sign our guest book!
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('client.guest_book', $token) }}" class="btn btn-link text-success fw-bold text-decoration-none">
                        Clear Search
                    </a>
                @endif
            </div>
        @endif

        <div class="text-center mt-5">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Logo" style="height: 30px; opacity: 0.5;">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
