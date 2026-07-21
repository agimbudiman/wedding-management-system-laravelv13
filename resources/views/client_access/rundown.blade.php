<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Rundown - {{ $event->name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --brilliant-bg: #f8faf8;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
            --timeline-color: #e2e8f0;
        }

        body {
            background-color: var(--brilliant-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 163, 97, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(124, 163, 97, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
            padding-bottom: 50px;
        }

        h1, h2, h3, .outfit {
            font-family: 'Outfit', sans-serif;
        }

        .container-narrow {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Sticky Header */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(248, 250, 248, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .btn-back {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: white;
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brilliant-green);
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .btn-back:hover {
            background: var(--brilliant-green);
            color: white;
            transform: translateX(-3px);
        }

        .page-title {
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0;
            color: #1a202c;
        }

        /* Timeline Styles */
        .timeline-container {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }

        .timeline-container::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to bottom, var(--brilliant-green-light), var(--timeline-color));
            border-radius: 1px;
        }

        .day-group {
            margin-bottom: 30px;
            position: relative;
        }

        .day-badge {
            display: inline-block;
            padding: 6px 16px;
            background: var(--brilliant-green);
            color: white;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(124, 163, 97, 0.3);
            position: relative;
            z-index: 2;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 25px;
            width: 16px;
            height: 16px;
            background: white;
            border: 3px solid var(--brilliant-green);
            border-radius: 50%;
            z-index: 2;
        }

        .timeline-item:hover {
            transform: translateY(-5px);
            border-color: var(--brilliant-green-light);
            box-shadow: 0 15px 35px rgba(124, 163, 97, 0.1);
        }

        .item-time {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--brilliant-green-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .item-activity {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1a202c;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        /* Animations */
        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal {
            animation: slideInUp 0.6s ease forwards;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: var(--glass-bg);
            border-radius: 24px;
            border: 1px dashed var(--brilliant-green-light);
            margin-top: 40px;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--brilliant-green-light);
            margin-bottom: 20px;
            display: block;
        }

        @media (max-width: 480px) {
            .container-narrow {
                padding: 15px;
            }
            .timeline-item {
                padding: 18px;
            }
            .page-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

    <div class="container-narrow">
        <!-- Header -->
        <div class="sticky-header">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('qr.client.redirect', $token) }}" class="btn-back">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <div>
                    <h1 class="page-title outfit">Acara & Rundown</h1>
                    <div class="small text-muted">Jadwal acara hari istimewa Anda</div>
                </div>
            </div>
        </div>

        @php $currentDay = 0; @endphp
        
        <div class="timeline-container">
            @forelse($event->rundowns as $index => $rundown)
                @if($rundown->day != $currentDay)
                    @php $currentDay = $rundown->day; @endphp
                    <div class="day-group mt-4 animate-fade-in">
                        <span class="day-badge">Hari Ke-{{ $currentDay }}</span>
                    </div>
                @endif

                <div class="timeline-item reveal" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="item-time">
                        <i class="bi bi-alarm"></i>
                        {{ \Carbon\Carbon::parse($rundown->time_start)->format('H:i') }}
                        @if($rundown->time_end)
                            <span class="text-muted fw-normal mx-1">-</span>
                            {{ \Carbon\Carbon::parse($rundown->time_end)->format('H:i') }}
                        @endif
                    </div>
                    <div class="item-activity">{{ $rundown->activity }}</div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h5 class="fw-bold outfit">Belum Ada Jadwal</h5>
                    <p class="text-muted small">Jadwal acara belum diinput oleh panitia. Silakan hubungi Wedding Organizer untuk informasi lebih lanjut.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="text-center mt-5 text-muted small opacity-50">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Logo" style="height: 25px; margin-bottom: 10px; filter: grayscale(1);">
            <p>&copy; {{ date('Y') }} Brilliant Event Management<br>Crafting perfect moments for you.</p>
        </div>
    </div>

    <!-- Scroll Reveal Activation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.timeline-item');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.classList.add('reveal');
                }, index * 100);
            });
        });
    </script>

</body>

</html>
