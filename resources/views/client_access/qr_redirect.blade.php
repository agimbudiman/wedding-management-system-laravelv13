<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type }} Access - Brilliant Management System</title>
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
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --brilliant-bg: #f8faf8;
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        /* Photo Personalization CSS */
        .photo-upload-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 for Hero */
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }

        .photo-upload-container.square {
            padding-bottom: 100%; /* 1:1 for Couple */
        }

        .photo-upload-container:hover {
            border-color: var(--brilliant-green);
            background: #f1f5f9;
        }

        .photo-upload-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.2);
            color: white;
            opacity: 0;
            transition: all 0.3s;
        }

        .photo-upload-container:hover .photo-upload-overlay {
            opacity: 1;
        }

        .photo-upload-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
        }

        .btn-remove-photo {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            padding: 5px 10px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            display: none;
        }

        .photo-upload-container.has-image .btn-remove-photo {
            display: block;
        }

        #cropperModal .modal-body {
            padding: 0;
            overflow: hidden;
        }

        .img-container {
            width: 100%;
            height: 400px;
            max-height: 400px;
            background-color: #f7f7f7;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .img-container img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        body {
            background-color: var(--brilliant-bg);
            background-image:
                radial-gradient(at 0% 0%, rgba(124, 163, 97, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(124, 163, 97, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
            padding-bottom: 40px;
        }

        h1,
        h2,
        h3,
        .outfit {
            font-family: 'Outfit', sans-serif;
        }

        .dashboard-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 20px;
        }

        .welcome-badge {
            display: inline-block;
            padding: 8px 20px;
            background: var(--brilliant-green-light);
            color: var(--brilliant-green-dark);
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .event-name {
            font-weight: 800;
            font-size: 2rem;
            color: #1a202c;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .event-details {
            color: #718096;
            font-size: 1rem;
        }

        .event-details i {
            margin-right: 5px;
            color: var(--brilliant-green);
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .menu-item {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 30px 20px;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .menu-item:hover {
            transform: translateY(-8px);
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(124, 163, 97, 0.1);
            border-color: var(--brilliant-green-light);
            color: var(--brilliant-green-dark);
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--brilliant-green);
            transform: scaleX(0);
            transition: transform 0.4s ease;
            transform-origin: left;
        }

        .menu-item:hover::before {
            transform: scaleX(1);
        }

        .menu-icon {
            width: 70px;
            height: 70px;
            background: var(--brilliant-green-light);
            color: var(--brilliant-green);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .menu-item:hover .menu-icon {
            background: var(--brilliant-green);
            color: #ffffff;
            transform: rotate(10deg);
        }

        .menu-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .menu-desc {
            font-size: 0.8rem;
            color: #a0aec0;
        }

        /* Wide item for Dokumentasi */
        .menu-item-wide {
            grid-column: span 2;
            flex-direction: row;
            text-align: left;
            padding: 25px 30px;
            justify-content: flex-start;
            gap: 25px;
        }

        .menu-item-wide .menu-icon {
            margin-bottom: 0;
        }

        .menu-item-wide .menu-content {
            flex: 1;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .delay-4 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .delay-5 {
            animation-delay: 0.5s;
            opacity: 0;
        }

        @media (max-width: 480px) {
            .event-name {
                font-size: 1.6rem;
            }

            .menu-grid {
                gap: 15px;
            }

            .menu-item {
                padding: 25px 15px;
            }

            .menu-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <!-- Header -->
        <div class="header-section animate-fade-in">
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 mb-3">
                <span class="welcome-badge mb-0">Welcome to {{ $type }} Portal</span>
                @if($type === 'Client')
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold shadow-sm d-inline-flex align-items-center gap-1 border-0" data-bs-toggle="modal" data-bs-target="#personalizeModal" style="background: var(--brilliant-green); font-size: 0.75rem;">
                        <i class="bi bi-gear-fill"></i> Personalize Invitation
                    </button>
                @endif
            </div>
            <h1 class="event-name">{{ $event->name }}</h1>
            <div class="event-details">
                <p class="mb-1"><i class="bi bi-person-heart"></i> {{ $event->client_name }}</p>
                <p class="mb-0">
                    <i class="bi bi-calendar3"></i> {{ $event->date ? $event->date->format('d M Y') : 'Date TBD' }}
                    <span class="mx-2 text-muted">|</span>
                    <i class="bi bi-geo-alt"></i> {{ $event->venue ?? 'Venue TBD' }}
                </p>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid">
            @if ($type === 'Guest')
                <!-- Guest Portal Menu -->
                <!-- 1. Isi Buku Tamu (Wide) -->
                <a href="{{ route('client.guest_book.form', $token) }}" class="menu-item menu-item-wide animate-fade-in delay-1" style="background: var(--brilliant-green); color: white; border-color: var(--brilliant-green-dark);">
                    <div class="menu-icon" style="background: rgba(255,255,255,0.2); color: white;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div class="menu-content">
                        <div class="menu-title">Isi Buku Tamu</div>
                        <div class="menu-desc" style="color: rgba(255,255,255,0.8);">Sign our digital guest book</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto"></i>
                </a>

                <!-- 2. Undangan -->
                <a href="{{ route('client.invitation', $token) }}" class="menu-item animate-fade-in delay-2">
                    <div class="menu-icon">
                        <i class="bi bi-envelope-heart"></i>
                    </div>
                    <div class="menu-title">Undangan</div>
                    <div class="menu-desc">Digital Invitation</div>
                </a>

                <!-- 3. Buku Tamu (Lihat Daftar) -->
                <a href="{{ route('client.guest_book', $token) }}" class="menu-item animate-fade-in delay-3">
                    <div class="menu-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="menu-title">Buku Tamu</div>
                    <div class="menu-desc">View Guest List</div>
                </a>

                <!-- 4. Rundown -->
                <a href="{{ route('client.rundown', $token) }}" class="menu-item animate-fade-in delay-4">
                    <div class="menu-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="menu-title">Rundown</div>
                    <div class="menu-desc">Event Schedule</div>
                </a>

                <!-- 5. Dokumentasi -->
                <a href="{{ route('client.documentation', $token) }}" class="menu-item animate-fade-in delay-5">
                    <div class="menu-icon">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <div class="menu-title">Dokumentasi</div>
                    <div class="menu-desc">Photos Gallery</div>
                </a>
            @else
                <!-- Client Portal Menu -->
                <!-- 1. Undangan -->
                <a href="{{ route('client.invitation.slug', $event->slug) }}" class="menu-item animate-fade-in delay-1">
                    <div class="menu-icon">
                        <i class="bi bi-envelope-heart"></i>
                    </div>
                    <div class="menu-title">Undangan</div>
                    <div class="menu-desc">Digital Invitation</div>
                </a>

                <!-- 2. Rundown -->
                <a href="{{ route('client.rundown', $token) }}" class="menu-item animate-fade-in delay-2">
                    <div class="menu-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="menu-title">Rundown</div>
                    <div class="menu-desc">Event Schedule</div>
                </a>

                <!-- 3. Dokumentasi (Wide) -->
                <a href="{{ route('client.documentation', $token) }}" class="menu-item menu-item-wide animate-fade-in delay-3">
                    <div class="menu-icon">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <div class="menu-content">
                        <div class="menu-title">Dokumentasi</div>
                        <div class="menu-desc">Photos & Videos Gallery</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto opacity-50"></i>
                </a>

                <!-- 4. Buku Tamu -->
                <a href="{{ route('client.guest_book', $token) }}" class="menu-item animate-fade-in delay-4">
                    <div class="menu-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div class="menu-title">Buku Tamu</div>
                    <div class="menu-desc">View Guest List</div>
                </a>

                <!-- 5. Saran & Testimoni -->
                <a href="{{ route('client.testimonial', $token) }}" class="menu-item animate-fade-in delay-5">
                    <div class="menu-icon">
                        <i class="bi bi-chat-heart"></i>
                    </div>
                    <div class="menu-title">Saran</div>
                    <div class="menu-desc">Testimonials</div>
                </a>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-5 text-muted small animate-fade-in delay-5" style="opacity: 0.6;">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Logo"
                style="height: 30px; margin-bottom: 15px; filter: grayscale(1); opacity: 0.5;">
            <p>&copy; {{ date('Y') }} Brilliant Event & Wedding Organizer<br>Crafted for your perfect moment.</p>
        </div>
    </div>

    @if($type === 'Client')
        <!-- Personalize Modal -->
        <div class="modal fade" id="personalizeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <form id="personalizeForm" action="{{ route('client.personalize', $token) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header border-0 p-4 pb-2">
                            <h5 class="modal-title fw-bold">Portal Personalization</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="px-4 mb-3">
                            <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill" id="personalizeTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill fw-bold" id="invitation-tab" data-bs-toggle="tab"
                                        data-bs-target="#invitation-pane" type="button" role="tab" aria-controls="invitation-pane"
                                        aria-selected="true">
                                        <i class="bi bi-envelope-heart me-1"></i> Invitation
                                    </button>
                                </li>
                                <!-- <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill fw-bold" id="documentation-tab" data-bs-toggle="tab"
                                        data-bs-target="#documentation-pane" type="button" role="tab"
                                        aria-controls="documentation-pane" aria-selected="false">
                                        <i class="bi bi-camera-reels me-1"></i> Documentation
                                    </button>
                                </li> -->
                            </ul>
                        </div>
                        <div class="modal-body p-4 pt-0">
                            @php
                                $p = $event->personalization ?? [];
                            @endphp
                            <div class="tab-content" id="personalizeTabContent">
                                <!-- Invitation Tab -->
                                <div class="tab-pane fade show active" id="invitation-pane" role="tabpanel" aria-labelledby="invitation-tab">
                                    <div class="row g-4 pt-3">
                                        <!-- Left Side: Toggles -->
                                        <div class="col-md-3">
                                            <div class="p-3 bg-light rounded-4 h-100">
                                                <h6 class="fw-bold mb-3"><i class="bi bi-eye me-2"></i>Show Sections</h6>
                                                @php
                                                    $sections = ['Hero', 'Couple', 'Story', 'Gallery', 'Event', 'Footer'];
                                                @endphp
                                                @foreach($sections as $section)
                                                    @php
                                                        $default = in_array($section, ['Hero', 'Couple', 'Event', 'Footer']);
                                                    @endphp
                                                    <div class="form-check form-switch mb-2">
                                                        <input type="hidden" name="sections[{{ $section }}]" value="0">
                                                        <input class="form-check-input" type="checkbox" name="sections[{{ $section }}]"
                                                            value="1" id="switch{{ $section }}" {{ ($p['sections'][$section] ?? $default) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="switch{{ $section }}">{{ $section }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Right Side: Content -->
                                        <div class="col-md-9">
                                            <!-- Photo Personalization -->
                                            <div class="mb-4">
                                                <h6 class="fw-bold mb-3"><i class="bi bi-images me-2"></i>Photo Personalization</h6>
                                                <div class="row g-3">
                                                    <!-- Hero Photo -->
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold mb-2">Hero Background</label>
                                                        <div class="photo-upload-container {{ isset($p['photos']['hero']) && $p['photos']['hero'] ? 'has-image' : '' }}" id="container-hero" onclick="triggerUpload('hero')">
                                                            <div class="photo-upload-placeholder">
                                                                <i class="bi bi-image fs-1"></i>
                                                                <span class="small">Upload</span>
                                                            </div>
                                                            <img src="{{ isset($p['photos']['hero']) ? (str_starts_with($p['photos']['hero'], 'http') ? $p['photos']['hero'] : asset($p['photos']['hero'])) : '' }}" 
                                                                 id="preview-hero" class="{{ !isset($p['photos']['hero']) || !$p['photos']['hero'] ? 'd-none' : '' }}">
                                                            <div class="photo-upload-overlay"><i class="bi bi-pencil-square fs-3"></i></div>
                                                            <button type="button" class="btn-remove-photo" onclick="removePhoto(event, 'hero')"><i class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <input type="file" id="input-hero" class="d-none" accept="image/*" onchange="handleFileSelect(this, 'hero', 16/9)">
                                                        <input type="hidden" name="photos[hero]" id="hidden-hero" value="{{ $p['photos']['hero'] ?? '' }}">
                                                    </div>

                                                    <!-- Groom Photo -->
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold mb-2">Groom Photo</label>
                                                        <div class="photo-upload-container square {{ isset($p['photos']['groom']) && $p['photos']['groom'] ? 'has-image' : '' }}" id="container-groom" onclick="triggerUpload('groom')">
                                                            <div class="photo-upload-placeholder">
                                                                <i class="bi bi-person fs-1"></i>
                                                                <span class="small">Upload</span>
                                                            </div>
                                                            <img src="{{ isset($p['photos']['groom']) ? (str_starts_with($p['photos']['groom'], 'http') ? $p['photos']['groom'] : asset($p['photos']['groom'])) : '' }}" 
                                                                 id="preview-groom" class="{{ !isset($p['photos']['groom']) || !$p['photos']['groom'] ? 'd-none' : '' }}">
                                                            <div class="photo-upload-overlay"><i class="bi bi-pencil-square fs-3"></i></div>
                                                            <button type="button" class="btn-remove-photo" onclick="removePhoto(event, 'groom')"><i class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <input type="file" id="input-groom" class="d-none" accept="image/*" onchange="handleFileSelect(this, 'groom', 1)">
                                                        <input type="hidden" name="photos[groom]" id="hidden-groom" value="{{ $p['photos']['groom'] ?? '' }}">
                                                    </div>

                                                    <!-- Bride Photo -->
                                                    <div class="col-md-4">
                                                        <label class="small fw-bold mb-2">Bride Photo</label>
                                                        <div class="photo-upload-container square {{ isset($p['photos']['bride']) && $p['photos']['bride'] ? 'has-image' : '' }}" id="container-bride" onclick="triggerUpload('bride')">
                                                            <div class="photo-upload-placeholder">
                                                                <i class="bi bi-person-heart fs-1"></i>
                                                                <span class="small">Upload</span>
                                                            </div>
                                                            <img src="{{ isset($p['photos']['bride']) ? (str_starts_with($p['photos']['bride'], 'http') ? $p['photos']['bride'] : asset($p['photos']['bride'])) : '' }}" 
                                                                 id="preview-bride" class="{{ !isset($p['photos']['bride']) || !$p['photos']['bride'] ? 'd-none' : '' }}">
                                                            <div class="photo-upload-overlay"><i class="bi bi-pencil-square fs-3"></i></div>
                                                            <button type="button" class="btn-remove-photo" onclick="removePhoto(event, 'bride')"><i class="bi bi-x-lg"></i></button>
                                                        </div>
                                                        <input type="file" id="input-bride" class="d-none" accept="image/*" onchange="handleFileSelect(this, 'bride', 1)">
                                                        <input type="hidden" name="photos[bride]" id="hidden-bride" value="{{ $p['photos']['bride'] ?? '' }}">
                                                    </div>
                                                </div>

                                                <!-- Descriptions -->
                                                <div class="row g-3 mt-2">
                                                    <div class="col-md-6">
                                                        <label class="small fw-bold mb-2">Groom Description</label>
                                                        <textarea name="couple_desc[groom]" class="form-control rounded-3" rows="3" placeholder="Description for groom...">{{ $p['couple_desc']['groom'] ?? '' }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="small fw-bold mb-2">Bride Description</label>
                                                        <textarea name="couple_desc[bride]" class="form-control rounded-3" rows="3" placeholder="Description for bride...">{{ $p['couple_desc']['bride'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gallery -->
                                            <div class="mb-4">
                                                <h6 class="fw-bold mb-3"><i class="bi bi-images me-2"></i>Gallery Photos (Min. 3)</h6>
                                                <div class="row g-2">
                                                    @for($i = 0; $i < 6; $i++)
                                                        <div class="col-md-2 col-4 mb-2">
                                                            <div class="photo-upload-container square {{ isset($p['gallery'][$i]) && $p['gallery'][$i] ? 'has-image' : '' }}" 
                                                                 id="container-gallery-{{ $i }}" onclick="triggerUpload('gallery-{{ $i }}')">
                                                                <div class="photo-upload-placeholder">
                                                                    <i class="bi bi-plus-lg fs-4"></i>
                                                                    <span class="d-block x-small" style="font-size: 0.6rem;">Slot {{ $i+1 }}</span>
                                                                </div>
                                                                <img src="{{ isset($p['gallery'][$i]) ? (str_starts_with($p['gallery'][$i], 'http') ? $p['gallery'][$i] : asset($p['gallery'][$i])) : '' }}" 
                                                                     id="preview-gallery-{{ $i }}" class="{{ !isset($p['gallery'][$i]) || !$p['gallery'][$i] ? 'd-none' : '' }}">
                                                                <div class="photo-upload-overlay"><i class="bi bi-pencil-square fs-5"></i></div>
                                                                <button type="button" class="btn-remove-photo" onclick="removePhoto(event, 'gallery-{{ $i }}')"><i class="bi bi-x-lg"></i></button>
                                                            </div>
                                                            <input type="file" id="input-gallery-{{ $i }}" class="d-none" accept="image/*" onchange="handleFileSelect(this, 'gallery-{{ $i }}', 3/4)">
                                                            <input type="hidden" name="gallery[{{ $i }}]" id="hidden-gallery-{{ $i }}" value="{{ $p['gallery'][$i] ?? '' }}" class="gallery-input">
                                                        </div>
                                                    @endfor
                                                </div>
                                                <div id="gallery-validation-msg" class="text-danger small mt-1 d-none">Please upload at least 3 photos for the gallery.</div>
                                            </div>

                                            <!-- Event Times -->
                                            <div class="mb-4">
                                                <h6 class="fw-bold mb-2"><i class="bi bi-clock me-2"></i>Event Times</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="small fw-bold">Wedding Reception Time</label>
                                                        <input type="text" name="event_times[reception]" class="form-control rounded-3" value="{{ $p['event_times']['reception'] ?? '11:00 AM — Finish' }}" placeholder="e.g. 11:00 AM — Finish">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Story Timeline -->
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="fw-bold m-0"><i class="bi bi-journal-text me-2"></i>Story Timeline</h6>
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="addStoryItem()"><i class="bi bi-plus-lg me-1"></i> Add</button>
                                                </div>
                                                <div id="story-repeater-container">
                                                    @if(isset($p['stories']) && is_array($p['stories']))
                                                        @foreach($p['stories'] as $index => $story)
                                                            <div class="story-item bg-light p-3 rounded-4 mb-2 position-relative">
                                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 small" onclick="this.parentElement.remove()"></button>
                                                                <div class="row g-2">
                                                                    <div class="col-md-2">
                                                                        <label class="small fw-bold">Year</label>
                                                                        <input type="text" name="stories[{{ $index }}][year]" class="form-control form-control-sm rounded-3" value="{{ $story['year'] ?? '' }}" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label class="small fw-bold">Title</label>
                                                                        <input type="text" name="stories[{{ $index }}][title]" class="form-control form-control-sm rounded-3" value="{{ $story['title'] ?? '' }}" required>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <label class="small fw-bold">Story</label>
                                                                        <textarea name="stories[{{ $index }}][text]" class="form-control form-control-sm rounded-3" rows="1" required>{{ $story['text'] ?? '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="story-item bg-light p-3 rounded-4 mb-2 position-relative">
                                                            <div class="row g-2">
                                                                <div class="col-md-2"><label class="small fw-bold">Year</label><input type="text" name="stories[0][year]" class="form-control form-control-sm rounded-3" value="2020" required></div>
                                                                <div class="col-md-3"><label class="small fw-bold">Title</label><input type="text" name="stories[0][title]" class="form-control form-control-sm rounded-3" value="First Meet" required></div>
                                                                <div class="col-md-7"><label class="small fw-bold">Story</label><textarea name="stories[0][text]" class="form-control form-control-sm rounded-3" rows="1" required>It was a simple encounter.</textarea></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> <!-- End Content col-md-9 -->
                                    </div> <!-- End row g-4 -->
                                </div> <!-- End invitation-pane -->

                                <!-- Documentation Tab
                                <div class="tab-pane fade" id="documentation-pane" role="tabpanel" aria-labelledby="documentation-tab">
                                    <div class="p-4 bg-light rounded-4 mt-3">
                                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-camera-reels me-2"></i>Portal Documentation Settings</h6>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="small fw-bold mb-2">Google Drive Folder Link</label>
                                                <input type="url" name="documentation_link" class="form-control rounded-3" 
                                                       value="{{ $p['documentation_link'] ?? '' }}" 
                                                       placeholder="https://drive.google.com/drive/folders/...">
                                                <div class="form-text small text-muted mt-2">Paste your Google Drive folder link here. It will be accessible from the Documentation menu in the portal.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div> <!-- End tab-content -->
                        </div> <!-- End modal-body -->
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4 fw-bold rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill">Save
                                Personalization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cropper Modal -->
        <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Crop Image</h5>
                        <button type="button" class="btn-close" onclick="cancelCrop()"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="img-container p-3">
                            <img id="cropperImage" src="" style="max-width: 100%; display: block;">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" onclick="cancelCrop()">Cancel</button>
                        <button type="button" class="btn btn-primary rounded-pill px-4" onclick="applyCrop()">Apply Crop</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if($type === 'Client')
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Cropper.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

        <script>
            // Photo Personalization & Cropping Logic
            let cropper = null;
            let currentType = '';
            let currentAspectRatio = 1;

            function triggerUpload(type) {
                document.getElementById(`input-${type}`).click();
            }

            function handleFileSelect(input, type, aspectRatio) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        currentType = type;
                        currentAspectRatio = aspectRatio;
                        
                        const cropperImage = document.getElementById('cropperImage');
                        cropperImage.src = e.target.result;
                        
                        const modal = new bootstrap.Modal(document.getElementById('cropperModal'));
                        modal.show();

                        document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
                            if (cropper) cropper.destroy();
                            cropper = new Cropper(cropperImage, {
                                aspectRatio: aspectRatio,
                                viewMode: 2,
                                dragMode: 'move',
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        }, { once: true });
                    };
                    reader.readAsDataURL(input.files[0]);
                    // Reset input so same file can be selected again
                    input.value = '';
                }
            }

            function cancelCrop() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('cropperModal'));
                modal.hide();
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            }

            function applyCrop() {
                if (!cropper) return;

                let canvasOptions = {
                    width: currentType === 'hero' ? 1200 : 500,
                    height: currentType === 'hero' ? 675 : 500
                };

                const canvas = cropper.getCroppedCanvas(canvasOptions);
                const base64Data = canvas.toDataURL('image/jpeg', 0.85);

                const previewImg = document.getElementById(`preview-${currentType}`);
                const hiddenInput = document.getElementById(`hidden-${currentType}`);
                const container = document.getElementById(`container-${currentType}`);

                previewImg.src = base64Data;
                previewImg.classList.remove('d-none');
                hiddenInput.value = base64Data;
                container.classList.add('has-image');

                cancelCrop();
            }

            function removePhoto(event, type) {
                event.stopPropagation();
                
                Swal.fire({
                    title: 'Remove photo?',
                    text: "The photo will be deleted when you save personalization.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#636e72',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const previewImg = document.getElementById(`preview-${type}`);
                        const hiddenInput = document.getElementById(`hidden-${type}`);
                        const container = document.getElementById(`container-${type}`);

                        previewImg.src = '';
                        previewImg.classList.add('d-none');
                        hiddenInput.value = '__REMOVE__'; // Special flag for backend
                        container.classList.remove('has-image');
                    }
                });
            }

            let storyIndex = {{ isset($p['stories']) && is_array($p['stories']) ? count($p['stories']) : 1 }};

            function addStoryItem() {
                const container = document.getElementById('story-repeater-container');
                const newItem = document.createElement('div');
                newItem.className = 'story-item bg-light p-3 rounded-4 mb-2 position-relative';
                newItem.innerHTML = `
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 small" onclick="this.parentElement.remove()"></button>
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <label class="small fw-bold">Year/Label</label>
                                    <input type="text" name="stories[${storyIndex}][year]" class="form-control form-control-sm rounded-3" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="small fw-bold">Title</label>
                                    <input type="text" name="stories[${storyIndex}][title]" class="form-control form-control-sm rounded-3" required>
                                </div>
                                <div class="col-md-7">
                                    <label class="small fw-bold">Story</label>
                                    <textarea name="stories[${storyIndex}][text]" class="form-control form-control-sm rounded-3" rows="1" required></textarea>
                                </div>
                            </div>
                        `;
                container.appendChild(newItem);
                storyIndex++;
            }

            // Gallery Validation
            document.getElementById('personalizeForm').addEventListener('submit', function(e) {
                const galleryToggle = document.getElementById('switchGallery');
                if (galleryToggle && !galleryToggle.checked) {
                    return; // Skip validation if gallery is off
                }

                const galleryInputs = document.querySelectorAll('.gallery-input');
                let count = 0;
                galleryInputs.forEach(input => {
                    if (input.value && input.value !== '__REMOVE__') count++;
                });

                if (count < 3) {
                    e.preventDefault();
                    document.getElementById('gallery-validation-msg').classList.remove('d-none');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gallery requirement',
                        text: 'You must upload at least 3 photos for the gallery.',
                        confirmButtonColor: '#7ca361'
                    });
                } else {
                    document.getElementById('gallery-validation-msg').classList.add('d-none');
                }
            });
        </script>
    @endif

    @if(session('success'))
        <!-- SweetAlert2 (Fall-through in case not already loaded) -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
</body>

</html>