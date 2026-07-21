<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $event->name }} - Wedding Invitation</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animate on Scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --bg-floral: #fdfdfd;
            --text-dark: #2d3436;
            --text-muted: #636e72;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-floral);
            color: var(--text-dark);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            top: 0;
            width: 300px;
            height: 100%;
            z-index: -1;
            opacity: 0.30;
            background-image: url("{{ asset('assets/pattern1.png') }}");
            background-repeat: repeat;
            pointer-events: none;
        }

        body::before {
            left: 0;
        }

        body::after {
            right: 0;
        }

        .font-script {
            font-family: 'Great Vibes', cursive;
        }

        /* Hero Section */
        .hero-section {
            height: 100vh;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('{{ asset('invitations/template1/img/carousel-1.jpg') }}') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 20px;
        }

        .hero-content h1 {
            font-size: 5rem;
            margin-bottom: 10px;
        }

        .hero-content h3 {
            font-weight: 300;
            letter-spacing: 5px;
            text-transform: uppercase;
            font-size: 1.2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
            padding: 10px 30px;
            margin: 20px 0;
        }

        /* Countdown */
        .countdown-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 15px;
            border-radius: 15px;
            min-width: 80px;
            margin: 0 5px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .countdown-item h2 {
            font-weight: 700;
            margin-bottom: 0;
            font-size: 1.8rem;
        }

        .countdown-item span {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        /* Section Styling */
        section {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h6 {
            color: var(--brilliant-green);
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .section-title h1 {
            font-size: 3rem;
            font-weight: 300;
        }

        /* Couple Section */
        .couple-card {
            text-align: center;
            padding: 30px;
        }

        .couple-img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 25px;
            border: 8px solid white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .couple-img:hover {
            transform: scale(1.05);
        }

        .couple-name {
            font-size: 2.5rem;
            color: var(--brilliant-green);
            margin-bottom: 10px;
        }

        .ampersand {
            font-size: 4rem;
            color: var(--brilliant-green-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Story Section */
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 2px;
            background: var(--brilliant-green-light);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -1px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            right: -10px;
            background-color: white;
            border: 4px solid var(--brilliant-green);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .left {
            left: 0;
            text-align: right;
        }

        .right {
            left: 50%;
        }

        .right::after {
            left: -10px;
        }

        .timeline-content {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            border-top: 4px solid var(--brilliant-green);
        }

        /* Gallery */
        .gallery-container {
            columns: 3 300px;
            column-gap: 20px;
        }

        .gallery-item {
            margin-bottom: 20px;
            break-inside: avoid;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .gallery-item img {
            width: 100%;
            transition: all 0.5s;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Event Details */
        .event-card {
            background: white;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            text-align: center;
            height: 100%;
        }

        .event-icon {
            font-size: 3rem;
            color: var(--brilliant-green);
            margin-bottom: 25px;
        }

        .btn-maps {
            background-color: var(--brilliant-green);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .btn-maps:hover {
            background-color: var(--brilliant-green-dark);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(124, 163, 97, 0.3);
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .footer h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 3.5rem;
            }

            .timeline::after {
                left: 31px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
                text-align: left;
            }

            .timeline-item::after {
                left: 21px;
            }

            .right {
                left: 0;
            }
        }
    </style>
</head>

@php
    $p = $event->personalization ?? [];
    $showHero = $p['sections']['Hero'] ?? true;
    $showCouple = $p['sections']['Couple'] ?? true;
    $showStory = $p['sections']['Story'] ?? false;
    $showGallery = $p['sections']['Gallery'] ?? false;
    $showEvent = $p['sections']['Event'] ?? true;
    $showFooter = $p['sections']['Footer'] ?? true;

    $heroPhoto = !empty($p['photos']['hero']) ? (str_starts_with($p['photos']['hero'], 'http') ? $p['photos']['hero'] : asset($p['photos']['hero'])) : asset('invitations/template1/img/carousel-1.jpg');
    $groomPhoto = !empty($p['photos']['groom']) ? (str_starts_with($p['photos']['groom'], 'http') ? $p['photos']['groom'] : asset($p['photos']['groom'])) : asset('invitations/template1/img/about-1.jpg');
    $bridePhoto = !empty($p['photos']['bride']) ? (str_starts_with($p['photos']['bride'], 'http') ? $p['photos']['bride'] : asset($p['photos']['bride'])) : asset('invitations/template1/img/about-2.jpg');

    $receptionTime = $p['event_times']['reception'] ?? '11:00 AM — Finish';

    $stories = $p['stories'] ?? [
        ['year' => 'The Beginning', 'title' => 'First Meet', 'text' => 'It was a simple encounter that changed everything.'],
        ['year' => 'Getting Closer', 'title' => 'The First Date', 'text' => 'Hours felt like minutes as we talked.'],
        ['year' => 'She Said Yes!', 'title' => 'Proposal', 'text' => 'Under the soft glow of the moon, a promise was made.']
    ];
    $galleryPhotos = [];
    if (!empty($p['gallery'])) {
        foreach ($p['gallery'] as $gp) {
            if (!empty($gp)) {
                $galleryPhotos[] = str_starts_with($gp, 'http') ? $gp : asset($gp);
            }
        }
    }
    if (empty($galleryPhotos)) {
        $galleryPhotos = [
            asset('invitations/template1/img/gallery-1.jpg'),
            asset('invitations/template1/img/gallery-2.jpg'),
            asset('invitations/template1/img/gallery-3.jpg'),
            asset('invitations/template1/img/gallery-4.jpg'),
            asset('invitations/template1/img/carousel-2.jpg'),
            asset('invitations/template1/img/carousel-1.jpg'),
        ];
    }

    $brideDesc = $p['couple_desc']['bride'] ?? 'A woman of grace whose heart is full of joy. Ready to start the most beautiful chapter of her life';
    $groomDesc = $p['couple_desc']['groom'] ?? 'A man of honor who has found his missing puzzle piece. Ready to lead and love for a lifetime.';
@endphp

<body style="{{ !$showHero ? 'padding-top: 0;' : '' }}">
    <!-- Hero Section -->
    @if($showHero)
        <div class="hero-section"
            style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ $heroPhoto }}') no-repeat center center; background-size: cover;">
            <div class="hero-content" data-aos="fade-up" data-aos-duration="1500">
                @if(!empty($guestName))
                    <p class="mb-2 fw-light" style="letter-spacing: 3px; text-transform: uppercase; font-size: 0.85rem; opacity: 0.85;">Dear</p>
                    <h2 class="font-script mb-3" style="font-size: 2.8rem;">{{ $guestName }}</h2>
                    <p class="mb-4 fw-light" style="letter-spacing: 4px; text-transform: uppercase; font-size: 0.8rem; opacity: 0.8;">You Are Invited</p>
                @endif
                <h3 class="animate-fade-in">The Wedding Of</h3>
                <h1 class="font-script">{{ $bride }} &nbsp;&nbsp; & &nbsp;&nbsp; {{ $groom }}</h1>
                <p class="mt-4 lead fw-light px-3">{{ $event->date->format('l, d F Y') }} <br>— {{ $event->venue }} —</p>

                <div class="d-flex justify-content-center mt-5 countdown-container"
                    data-date="{{ $event->date->format('M d, Y') }} 00:00:00">
                    <div class="countdown-item">
                        <h2 class="countdown-days">00</h2>
                        <span>Days</span>
                    </div>
                    <div class="countdown-item">
                        <h2 class="countdown-hours">00</h2>
                        <span>Hours</span>
                    </div>
                    <div class="countdown-item">
                        <h2 class="countdown-minutes">00</h2>
                        <span>Min</span>
                    </div>
                    <div class="countdown-item">
                        <h2 class="countdown-seconds">00</h2>
                        <span>Sec</span>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="#couple" class="text-white text-decoration-none">
                        <i class="bi bi-chevron-double-down fs-2 animate-bounce"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Couple Section -->
    @if($showCouple)
        <section id="couple">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h6>Bride & Groom</h6>
                    <h1 class="font-script">Getting Married</h1>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-5" data-aos="fade-right">
                        <div class="couple-card">
                            <img src="{{ $bridePhoto }}" class="couple-img" alt="bride">
                            <h2 class="couple-name font-script">{{ $bride }}</h2>
                            <p class="text-muted px-lg-5">{{ $brideDesc }}</p>

                        </div>
                    </div>
                    <div class="col-md-2 ampersand font-script d-none d-md-flex" data-aos="zoom-in">&</div>
                    <div class="col-md-5" data-aos="fade-left">
                        <div class="couple-card">
                            <img src="{{ $groomPhoto }}" class="couple-img" alt="Groom">
                            <h2 class="couple-name font-script">{{ $groom ?: $event->client_name }}</h2>
                            <p class="text-muted px-lg-5">{{ $groomDesc }}</p>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Story Section -->
    @if($showStory)
        <section id="story" style="background-color: rgba(248, 250, 252, 0.5);">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h6>Our Path to Love</h6>
                    <h1 class="font-script">Our Beautiful Story</h1>
                </div>
                <div class="timeline">
                    @foreach($stories as $index => $story)
                        <div class="timeline-item {{ $index % 2 == 0 ? 'left' : 'right' }}"
                            data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}">
                            <div class="timeline-content">
                                <h4 class="fw-bold">{{ $story['title'] }}</h4>
                                <span
                                    class="text-primary small fw-bold text-uppercase letter-spacing-1">{{ $story['year'] }}</span>
                                <p class="mt-3 text-muted">{{ $story['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Gallery Section -->
    @if($showGallery)
        <section id="gallery">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h6>Moments Captured</h6>
                    <h1 class="font-script">Our Gallery</h1>
                </div>
                <div class="gallery-container">
                    @foreach($galleryPhotos as $index => $photo)
                        <div class="gallery-item" data-aos="zoom-in" data-aos-delay="{{ ($index % 3) * 100 }}">
                            <img src="{{ $photo }}" alt="Gallery {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Event Details -->
    @if($showEvent)
        <section id="event" style="background-color: rgba(253, 253, 253, 0.5);">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h6>Join Our Celebration</h6>
                    <h1 class="font-script">Wedding Event</h1>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-5" data-aos="fade-up">
                        <div class="event-card">
                            <i class="bi bi-calendar-heart event-icon"></i>
                            <h4 class="fw-bold">Wedding Reception</h4>
                            <p class="text-muted mt-3">
                                {{ $event->date->format('l, d F Y') }} <br>
                                {{ $receptionTime }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-5" data-aos="fade-up" data-aos-delay="200">
                        <div class="event-card">
                            <i class="bi bi-geo-alt event-icon"></i>
                            <h4 class="fw-bold">Location</h4>
                            <p class="text-muted mt-3">
                                {{ $event->venue }} <br>
                                Click below to open maps
                            </p>
                            <div class="rounded-4 overflow-hidden mt-4" style="height: 200px;">
                                @if($event->google_maps_link && str_contains($event->google_maps_link, '<iframe'))
                                    {!! str_replace(['width="100%"', 'height="100%"', 'width="600"', 'height="450"'], ['width="100%"', 'height="100%"', 'width="100%"', 'height="100%"'], $event->google_maps_link) !!}
                                @else
                                    <iframe src="https://maps.google.com/maps?q={{ urlencode($event->venue) }}&output=embed"
                                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                @endif
                            </div>
                            <a href="{{ ($event->google_maps_link && !str_contains($event->google_maps_link, '<iframe')) ? $event->google_maps_link : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($event->venue) }}"
                                target="_blank" class="btn-maps">
                                <i class="bi bi-map-fill me-2"></i> Open Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    @if($showFooter)
        <div class="footer">
            <div class="container" data-aos="zoom-in">
                <h1 class="font-script">Thank You</h1>
                <p class="lead opacity-75 fw-light">We can't wait to see you on our special day!</p>
                <div class="mt-5 pt-5 opacity-50 small">
                    &copy; {{ date('Y') }} Powered by Brilliant Management System
                </div>
            </div>
        </div>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true });

        // Countdown Timer
        (function () {
            const container = document.querySelector('.countdown-container');
            if (container) {
                const targetDateStr = container.getAttribute('data-date');
                const second = 1000, minute = second * 60, hour = minute * 60, day = hour * 24;
                let countDown = new Date(targetDateStr).getTime();

                let x = setInterval(function () {
                    let now = new Date().getTime(), distance = countDown - now;

                    document.querySelectorAll(".countdown-days").forEach(el => el.innerText = Math.max(0, Math.floor(distance / (day))));
                    document.querySelectorAll(".countdown-hours").forEach(el => el.innerText = Math.max(0, Math.floor((distance % (day)) / (hour))));
                    document.querySelectorAll(".countdown-minutes").forEach(el => el.innerText = Math.max(0, Math.floor((distance % (hour)) / (minute))));
                    document.querySelectorAll(".countdown-seconds").forEach(el => el.innerText = Math.max(0, Math.floor((distance % (minute)) / second)));

                    if (distance < 0) clearInterval(x);
                }, 1000);
            }
        })();
    </script>
</body>

</html>