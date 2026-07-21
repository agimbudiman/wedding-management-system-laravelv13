@extends('layouts.management')

@section('title', 'QR Card Preview - ' . $event->name)

@section('styles')
<style>
    .preview-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 50px;
        padding: 50px 0;
    }

    .preview-card-wrapper {
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
    }

    /* Reuse the QR Card Export Styles */
    #qr-card-template-preview-1, #qr-card-template-preview-2 {
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
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        margin-bottom: 50px;
        min-width: 350px;
        min-height: 350px;
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
                <li class="breadcrumb-item"><a href="{{ route('management.client-access.index') }}" class="text-decoration-none text-muted">Client Access</a></li>
                <li class="breadcrumb-item"><a href="{{ route('management.client-access.show', $event->id) }}" class="text-decoration-none text-muted">{{ $event->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">QR Preview</li>
            </ol>
        </nav>
        <h1 class="page-title m-0">QR Design Preview</h1>
    </div>

    <div class="alert alert-info rounded-4 border-0 shadow-sm">
        <i class="bi bi-info-circle-fill me-2"></i>
        This is a live preview of how your QR cards will look when downloaded. If the QR code is visible here but missing in the download, it might be a browser rendering delay.
    </div>

    <div class="preview-container">
        <!-- Client QR Preview -->
        <div class="text-center">
            <h4 class="fw-bold mb-3">Client Portal Preview</h4>
            <div class="preview-card-wrapper">
                <div id="qr-card-template-preview-1">
                    <div class="qr-card-border"></div>
                    <div style="position: relative; z-index: 1;">
                        <img src="{{ asset('assets/Brilliant_Logo.png') }}" class="qr-card-logo">
                        <div class="qr-card-label">Client Portal Access</div>
                        <h1 class="qr-card-event-name">{{ $event->name }}</h1>
                        <div class="qr-card-divider"></div>
                        <div class="qr-card-code-container" id="qr-code-client"></div>
                        <div class="qr-card-footer">
                            <i class="bi bi-upc-scan" style="margin-right: 10px;"></i>
                            Scan to access our digital services
                        </div>
                        <div class="qr-card-system">BRILLIANT EVENT MANAGEMENT SYSTEM</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guest QR Preview -->
        <div class="text-center">
            <h4 class="fw-bold mb-3">Guest Book Preview</h4>
            <div class="preview-card-wrapper">
                <div id="qr-card-template-preview-2">
                    <div class="qr-card-border"></div>
                    <div style="position: relative; z-index: 1;">
                        <img src="{{ asset('assets/Brilliant_Logo.png') }}" class="qr-card-logo">
                        <div class="qr-card-label">Guest Book Access</div>
                        <h1 class="qr-card-event-name">{{ $event->name }}</h1>
                        <div class="qr-card-divider"></div>
                        <div class="qr-card-code-container" id="qr-code-guest"></div>
                        <div class="qr-card-footer">
                            <i class="bi bi-upc-scan" style="margin-right: 10px;"></i>
                            Scan to access our digital services
                        </div>
                        <div class="qr-card-system">BRILLIANT EVENT MANAGEMENT SYSTEM</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generate Client QR
        new QRCode(document.getElementById('qr-code-client'), {
            text: "{{ route('qr.client.redirect', $event->client_qr_token) }}",
            width: 350,
            height: 350,
            colorDark: "#1a202c",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Generate Guest QR
        new QRCode(document.getElementById('qr-code-guest'), {
            text: "{{ route('qr.guest.redirect', $event->guest_qr_token) }}",
            width: 350,
            height: 350,
            colorDark: "#1a202c",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
@endsection
