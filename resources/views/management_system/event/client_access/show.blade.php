@extends('layouts.management')

@section('title', 'Manage Access - ' . $event->name)

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        .access-card {
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
            height: 100%;
        }

        .qr-container {
            background: #f8fafc;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            border: 2px dashed #e2e8f0;
        }

        .qr-image {
            width: 200px;
            height: 200px;
            background: #fff;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .status-active {
            background-color: #38A169;
            color: white;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-inactive {
            background-color: #E53E3E;
            color: white;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Photo Upload Styling */
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
            border-color: #7ca361;
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

        .access-action-btn {
            width: 100%;
            margin-bottom: 10px;
            border-radius: 12px;
            padding: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            transition: all 0.3s;
        }

        .btn-download {
            background-color: #4a5568;
            color: white;
        }

        .btn-copy {
            background-color: #6b8e52;
            color: white;
        }

        .btn-regenerate {
            background-color: #2d3748;
            color: white;
        }

        .btn-toggle-disable {
            background-color: #e53e3e;
            color: white;
        }

        .btn-toggle-enable {
            background-color: #38a169;
            color: white;
        }

        .btn-open {
            background-color: #7ca361;
            color: white;
        }

        .access-action-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            color: white;
        }

        /* QR Card Export Styles */
        #qr-card-template {
            width: 800px;
            padding: 80px 60px;
            background: #ffffff;
            position: relative;
            font-family: 'Inter', sans-serif;
            text-align: center;
        }

        .qr-card-border {
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            bottom: 25px;
            border: 2px solid #7ca361;
            border-radius: 40px;
        }

        .qr-card-logo {
            height: 70px;
            margin-bottom: 50px;
        }

        .qr-card-label {
            color: #7ca361;
            font-weight: 600;
            font-size: 1.4rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
        }

        .qr-card-event-name {
            font-size: 3.8rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 10px;
            line-height: 1.1;
        }

        .qr-card-divider {
            width: 120px;
            height: 5px;
            background: #7ca361;
            margin: 40px auto;
            border-radius: 10px;
        }

        .qr-card-code-container {
            display: inline-block;
            padding: 25px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            margin-bottom: 50px;
            min-width: 350px;
            min-height: 350px;
        }

        #qr-code-target canvas,
        #qr-code-target img {
            margin: 0 auto;
        }

        .qr-card-footer {
            color: #718096;
            font-size: 1.3rem;
            font-weight: 500;
        }

        .qr-card-system {
            margin-top: 60px;
            font-size: 1rem;
            color: #a0aec0;
            letter-spacing: 1px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('management.client-access.index') }}"
                            class="text-decoration-none text-muted">Client Access</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $event->name }}</li>
                </ol>
            </nav>
            <h1 class="page-title m-0">Event - Access Control</h1>
        </div>



        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="fw-bold m-0">CLIENT & GUEST QR ACCESS</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" data-bs-toggle="modal"
                            data-bs-target="#personalizeModal">
                            <i class="bi bi-gear-fill me-1"></i> Portal Personalization
                        </button>
                        <!-- <a href="{{ route('management.client-access.preview', $event->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        <i class="bi bi-eye me-1"></i> Preview QR Design
                                                    </a> -->
                    </div>
                </div>
                <p class="text-muted small">Manage client access, guest check-in, and event QR controls for
                    <strong>{{ $event->name }}</strong>.
                </p>
                <hr>

                <div class="row g-4 mt-2">
                    <!-- Client Access QR -->
                    <div class="col-md-6">
                        <div class="access-card">
                            <h6 class="text-center fw-bold mb-4">Client Access QR</h6>
                            <div class="qr-container">
                                @php
                                    $invitationUrl = route('client.invitation.slug', $event->slug);
                                    $clientUrl = route('qr.client.redirect', $event->client_qr_token);
                                    $clientQr = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($clientUrl);
                                @endphp
                                <img src="{{ $clientQr }}" alt="Client QR" class="qr-image">
                                <div class="mt-3">
                                    <span class="text-muted small">Status: </span>
                                    <span class="{{ $event->is_client_qr_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $event->is_client_qr_active ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row gx-2 mb-2">
                                <div class="col-12">
                                    <a href="{{ $clientUrl }}" target="_blank"
                                        class="access-action-btn btn-open text-decoration-none">
                                        <i class="bi bi-box-arrow-up-right"></i> Open Client Portal
                                    </a>
                                </div>
                            </div>

                            <div class="row gx-2">
                                <div class="col-6">
                                    <button type="button"
                                        onclick="downloadQRCard('client', '{{ $event->name }}', '{{ $clientUrl }}')"
                                        class="access-action-btn btn-download text-decoration-none">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="access-action-btn btn-copy"
                                        onclick="copyToClipboard('{{ $clientUrl }}')">
                                        <i class="bi bi-link-45deg"></i> Copy Link
                                    </button>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('management.client-access.regenerate', $event->id) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="client">
                                        <button type="submit" class="access-action-btn btn-regenerate"
                                            onclick="return confirm('Regenerate QR? The old one will stop working.')">
                                            <i class="bi bi-arrow-clockwise"></i> Regenerate
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('management.client-access.toggle', $event->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="client">
                                        <button type="submit"
                                            class="access-action-btn {{ $event->is_client_qr_active ? 'btn-toggle-disable' : 'btn-toggle-enable' }}">
                                            @if($event->is_client_qr_active)
                                                <i class="bi bi-x-circle"></i> Disable Access
                                            @else
                                                <i class="bi bi-check-circle"></i> Enable Access
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Access QR -->
                    <div class="col-md-6">
                        <div class="access-card">
                            <h6 class="text-center fw-bold mb-4">Guest Access QR (Guest Book)</h6>
                            <div class="qr-container">
                                @php
                                    $guestUrl = route('qr.guest.redirect', $event->guest_qr_token);
                                    $guestQr = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($guestUrl);
                                @endphp
                                <img src="{{ $guestQr }}" alt="Guest QR" class="qr-image">
                                <div class="mt-3">
                                    <span class="text-muted small">Status: </span>
                                    <span class="{{ $event->is_guest_qr_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $event->is_guest_qr_active ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row gx-2 mb-2">
                                <div class="col-12">
                                    <a href="{{ $guestUrl }}" target="_blank"
                                        class="access-action-btn btn-open text-decoration-none">
                                        <i class="bi bi-box-arrow-up-right"></i> Open Guest Portal
                                    </a>
                                </div>
                            </div>

                            <div class="row gx-2">
                                <div class="col-6">
                                    <button type="button"
                                        onclick="downloadQRCard('guest', '{{ $event->name }}', '{{ $guestUrl }}')"
                                        class="access-action-btn btn-download text-decoration-none">
                                        <i class="bi bi-download"></i> Download
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="access-action-btn btn-copy" onclick="copyToClipboard('{{ $guestUrl }}')">
                                        <i class="bi bi-link-45deg"></i> Copy Link
                                    </button>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('management.client-access.regenerate', $event->id) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="guest">
                                        <button type="submit" class="access-action-btn btn-regenerate"
                                            onclick="return confirm('Regenerate QR? The old one will stop working.')">
                                            <i class="bi bi-arrow-clockwise"></i> Regenerate
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('management.client-access.toggle', $event->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="guest">
                                        <button type="submit"
                                            class="access-action-btn {{ $event->is_guest_qr_active ? 'btn-toggle-disable' : 'btn-toggle-enable' }}">
                                            @if($event->is_guest_qr_active)
                                                <i class="bi bi-x-circle"></i> Disable Access
                                            @else
                                                <i class="bi bi-check-circle"></i> Enable Access
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personalize Modal -->
        <div class="modal fade" id="personalizeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <form id="personalizeForm" action="{{ route('management.client-access.personalize', $event->id) }}" method="POST" enctype="multipart/form-data">
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
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill fw-bold" id="documentation-tab" data-bs-toggle="tab"
                                        data-bs-target="#documentation-pane" type="button" role="tab"
                                        aria-controls="documentation-pane" aria-selected="false">
                                        <i class="bi bi-camera-reels me-1"></i> Documentation
                                    </button>
                                </li>
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

                                <!-- Documentation Tab -->
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
                                </div>
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
        <!-- Hidden QR Card Template -->
        <div style="position: absolute; left: -9999px; top: 0; z-index: -1000;">
            <div id="qr-card-template">
                <div class="qr-card-border"></div>
                <div style="position: relative; z-index: 1;">
                    <img src="{{ asset('assets/Brilliant_Logo.png') }}" class="qr-card-logo">

                    <div class="qr-card-label" id="qr-card-type-label">Digital Invitation Access</div>

                    <h1 class="qr-card-event-name" id="qr-card-event-name-text">Event Name</h1>

                    <div class="qr-card-divider"></div>

                    <div class="qr-card-code-container" id="qr-code-target">
                        <!-- QR Code will be rendered here -->
                    </div>

                    <div class="qr-card-footer">
                        <i class="bi bi-upc-scan" style="margin-right: 10px;"></i>
                        Scan to access our digital services
                    </div>

                    <div class="qr-card-system">
                        BRILLIANT EVENT MANAGEMENT SYSTEM
                    </div>
                </div>
            </div>
        </div>
@endsection

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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

                // Get cropped canvas
                // For avatars, 500x500 is plenty. For hero, maybe 1200x675.
                let canvasOptions = {
                    width: currentType === 'hero' ? 1200 : 500,
                    height: currentType === 'hero' ? 675 : 500
                };

                const canvas = cropper.getCroppedCanvas(canvasOptions);
                const base64Data = canvas.toDataURL('image/jpeg', 0.85);

                // Update preview and hidden input
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

            async function downloadQRCard(type, eventName, url) {
                // Show loading
                Swal.fire({
                    title: 'Preparing your QR Card...',
                    text: 'Please wait while we generate the high-quality image.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const wait = (ms) => new Promise(resolve => setTimeout(resolve, ms));

                try {
                    const label = type === 'client' ? 'Client Portal Access' : 'Guest Book Access';

                    // 1) Generate QR first (off-DOM), then convert to data URL
                    const qrTemp = document.createElement('div');
                    new QRCode(qrTemp, {
                        text: url,
                        width: 350,
                        height: 350,
                        colorDark: "#1a202c",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    let qrDataUrl = '';
                    for (let i = 0; i < 40; i++) {
                        const qrCanvas = qrTemp.querySelector('canvas');
                        const qrImg = qrTemp.querySelector('img');
                        if (qrCanvas) {
                            qrDataUrl = qrCanvas.toDataURL('image/png');
                            break;
                        }
                        if (qrImg && qrImg.src) {
                            qrDataUrl = qrImg.src;
                            break;
                        }
                        await wait(50);
                    }
                    if (!qrDataUrl) throw new Error('QR image generation failed');

                    // 2) Compose final card directly in canvas (no html2canvas dependency)
                    const outputCanvas = document.createElement('canvas');
                    outputCanvas.width = 800;
                    outputCanvas.height = 1100;
                    const ctx = outputCanvas.getContext('2d');

                    // Background
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, outputCanvas.width, outputCanvas.height);

                    // Rounded border helper
                    function roundedRect(x, y, w, h, r) {
                        ctx.beginPath();
                        ctx.moveTo(x + r, y);
                        ctx.lineTo(x + w - r, y);
                        ctx.quadraticCurveTo(x + w, y, x + w, y + r);
                        ctx.lineTo(x + w, y + h - r);
                        ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
                        ctx.lineTo(x + r, y + h);
                        ctx.quadraticCurveTo(x, y + h, x, y + h - r);
                        ctx.lineTo(x, y + r);
                        ctx.quadraticCurveTo(x, y, x + r, y);
                        ctx.closePath();
                    }

                    // Border
                    ctx.strokeStyle = '#7ca361';
                    ctx.lineWidth = 2;
                    roundedRect(25, 25, 750, 1050, 40);
                    ctx.stroke();

                    // Load helper
                    function loadImage(src) {
                        return new Promise((resolve, reject) => {
                            const img = new Image();
                            img.crossOrigin = 'anonymous';
                            img.onload = () => resolve(img);
                            img.onerror = reject;
                            img.src = src;
                        });
                    }

                    // Draw logo (best effort)
                    const logoUrl = @json(asset('assets/Brilliant_Logo.png'));
                    let cursorY = 95;
                    try {
                        const logo = await loadImage(logoUrl);
                        const logoH = 70;
                        const logoW = (logo.width / logo.height) * logoH;
                        const logoX = (800 - logoW) / 2;
                        ctx.drawImage(logo, logoX, cursorY, logoW, logoH);
                    } catch (_) { }

                    // Label
                    cursorY = 210;
                    ctx.fillStyle = '#7ca361';
                    ctx.font = '600 32px Inter, Arial, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.letterSpacing = '2px';
                    ctx.fillText(label.toUpperCase(), 400, cursorY);

                    // Event name (wrap lines)
                    function wrapCenteredText(text, x, y, maxWidth, lineHeight, maxLines) {
                        const words = text.split(' ');
                        const lines = [];
                        let line = '';

                        for (let i = 0; i < words.length; i++) {
                            const test = line ? `${line} ${words[i]}` : words[i];
                            if (ctx.measureText(test).width <= maxWidth) {
                                line = test;
                            } else {
                                if (line) lines.push(line);
                                line = words[i];
                            }
                        }
                        if (line) lines.push(line);

                        const finalLines = lines.slice(0, maxLines);
                        finalLines.forEach((ln, idx) => {
                            ctx.fillText(ln, x, y + (idx * lineHeight));
                        });
                        return finalLines.length;
                    }

                    ctx.fillStyle = '#1a202c';
                    ctx.font = '800 72px Inter, Arial, sans-serif';
                    const lineCount = wrapCenteredText(eventName, 400, 300, 660, 84, 3);

                    // Divider
                    const dividerY = 330 + (lineCount * 84);
                    ctx.strokeStyle = '#7ca361';
                    ctx.lineWidth = 6;
                    ctx.lineCap = 'round';
                    ctx.beginPath();
                    ctx.moveTo(340, dividerY);
                    ctx.lineTo(460, dividerY);
                    ctx.stroke();

                    // QR container
                    const boxY = dividerY + 45;
                    ctx.fillStyle = '#ffffff';
                    roundedRect(225, boxY, 350, 350, 25);
                    ctx.fill();

                    // QR image
                    const qrImgObj = await loadImage(qrDataUrl);
                    ctx.drawImage(qrImgObj, 225, boxY, 350, 350);

                    // Footer
                    ctx.fillStyle = '#718096';
                    ctx.font = '500 36px Inter, Arial, sans-serif';
                    ctx.fillText('Scan to access our digital services', 400, boxY + 420);

                    ctx.fillStyle = '#a0aec0';
                    ctx.font = '500 24px Inter, Arial, sans-serif';
                    ctx.fillText('BRILLIANT EVENT MANAGEMENT SYSTEM', 400, boxY + 500);

                    // 3) Download
                    const link = document.createElement('a');
                    link.download = `${type}_qr_${eventName.toLowerCase().replace(/[^a-z0-9]/g, '_')}.png`;
                    link.href = outputCanvas.toDataURL('image/png');
                    link.click();

                    Swal.close();
                } catch (err) {
                    console.error(err);
                    Swal.fire('Error', 'Failed to export QR card image.', 'error');
                }
            }

            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Link has been copied to clipboard.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                });
            }

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        </script>
    @endsection