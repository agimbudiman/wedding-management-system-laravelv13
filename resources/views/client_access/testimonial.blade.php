<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saran & Testimoni - {{ $event->name }}</title>
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
            --star-color: #ffc107;
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
            max-width: 650px;
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
            font-size: 2.2rem;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .testimonial-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        }

        /* Star Rating */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2.5rem;
            color: #e2e8f0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: var(--star-color);
            transform: scale(1.1);
        }

        .form-label {
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: 20px;
            padding: 15px 20px;
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            font-size: 1rem;
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
            margin-top: 25px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(124, 163, 97, 0.3);
        }

        .btn-submit:hover {
            background: var(--brilliant-green-dark);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(124, 163, 97, 0.4);
        }

        .success-badge {
            background: #f0fff4;
            color: #2f855a;
            padding: 15px 20px;
            border-radius: 20px;
            margin-bottom: 25px;
            border: 1px solid #c6f6d5;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .rating-desc {
            text-align: center;
            font-weight: 600;
            color: var(--brilliant-green-dark);
            margin-top: -20px;
            margin-bottom: 25px;
            height: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('qr.client.redirect', $token) }}" class="back-btn">
            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
        </a>

        <div class="header-section">
            <h1>Saran & Testimoni</h1>
            <p class="text-muted">Bagikan pengalaman Anda menggunakan jasa kami</p>
        </div>

        @if(session('success'))
            <div class="success-badge">
                <i class="bi bi-check-circle-fill fs-4"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <div class="testimonial-card">
            <form action="{{ route('client.testimonial.store', $token) }}" method="POST">
                @csrf
                
                <label class="form-label d-block text-center mb-3">Seberapa puas Anda dengan layanan kami?</label>
                
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" {{ (old('rating', $testimonial->rating ?? 0) == 5) ? 'checked' : '' }} required />
                    <label for="star5" title="Sangat Puas"><i class="bi bi-star-fill"></i></label>
                    
                    <input type="radio" id="star4" name="rating" value="4" {{ (old('rating', $testimonial->rating ?? 0) == 4) ? 'checked' : '' }} />
                    <label for="star4" title="Puas"><i class="bi bi-star-fill"></i></label>
                    
                    <input type="radio" id="star3" name="rating" value="3" {{ (old('rating', $testimonial->rating ?? 0) == 3) ? 'checked' : '' }} />
                    <label for="star3" title="Cukup"><i class="bi bi-star-fill"></i></label>
                    
                    <input type="radio" id="star2" name="rating" value="2" {{ (old('rating', $testimonial->rating ?? 0) == 2) ? 'checked' : '' }} />
                    <label for="star2" title="Kurang"><i class="bi bi-star-fill"></i></label>
                    
                    <input type="radio" id="star1" name="rating" value="1" {{ (old('rating', $testimonial->rating ?? 0) == 1) ? 'checked' : '' }} />
                    <label for="star1" title="Sangat Kurang"><i class="bi bi-star-fill"></i></label>
                </div>
                
                <div id="ratingText" class="rating-desc"></div>

                <div class="mb-4">
                    <label for="testimony" class="form-label">Saran atau Testimoni Anda</label>
                    <textarea name="testimony" id="testimony" rows="5" class="form-control" 
                        placeholder="Tuliskan saran, kritik, atau testimoni Anda di sini..." required>{{ old('testimony', $testimonial->testimony ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">
                    @if($testimonial)
                        <i class="bi bi-pencil-square me-2"></i> Update Testimoni
                    @else
                        <i class="bi bi-send-fill me-2"></i> Kirim Testimoni
                    @endif
                </button>
            </form>
        </div>

        <div class="text-center mt-5 text-muted small">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Logo" style="height: 30px; opacity: 0.5; margin-bottom: 10px;">
            <p>&copy; {{ date('Y') }} Brilliant Event & Wedding Organizer</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ratingLabels = {
            '5': 'Sangat Puas! ⭐⭐⭐⭐⭐',
            '4': 'Puas! ⭐⭐⭐⭐',
            '3': 'Cukup ⭐⭐⭐',
            '2': 'Kurang ⭐⭐',
            '1': 'Sangat Kurang ⭐'
        };

        const stars = document.querySelectorAll('.star-rating input');
        const ratingText = document.getElementById('ratingText');

        function updateRatingText(val) {
            if(val) {
                ratingText.textContent = ratingLabels[val];
            }
        }

        stars.forEach(star => {
            star.addEventListener('change', (e) => {
                updateRatingText(e.target.value);
            });
        });

        // Initialize if already checked
        const checked = document.querySelector('.star-rating input:checked');
        if(checked) {
            updateRatingText(checked.value);
        }
    </script>
</body>

</html>
