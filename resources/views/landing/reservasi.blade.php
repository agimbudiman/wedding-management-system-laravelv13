@php
    $packages = \App\Models\Package::with('items')->get();
    $packagesJson = $packages->map(function ($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'price' => $p->final_price,
            'formatted_price' => 'IDR ' . number_format($p->final_price, 0, ',', '.'),
            'items' => $p->items->pluck('name')->toArray()
        ];
    })->toJson();

    // Get dynamic DP nominal from settings
    $dpNominal = (float) \App\Models\WebsiteSetting::get('reservation_dp_nominal', 5000000);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reservasi Pernikahan - Brilliant Event & Wedding Organizer</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Midtrans Snap Pop-up Payment Gateway -->
    <script type="text/javascript"
        src="https://app.{{ config('midtrans.is_production') ? 'midtrans' : 'sandbox.midtrans' }}.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        /* Specific Styles for Reservation Page */
        .reservation-container {
            margin-top: 120px;
            margin-bottom: 80px;
            padding: 0 20px;
            min-height: calc(100vh - 400px);
        }

        .booking-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            max-width: 1100px;
            margin: 40px auto 0;
            transition: var(--transition);
        }

        @media (max-width: 991px) {
            .booking-card {
                grid-template-columns: 1fr;
            }
        }

        .booking-main {
            padding: 40px;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 991px) {
            .booking-main {
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                padding: 30px 20px;
            }
        }

        .booking-sidebar {
            background: var(--bg-alt);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        @media (max-width: 991px) {
            .booking-sidebar {
                padding: 30px 20px;
            }
        }

        /* Stepper Styling */
        .stepper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 400px;
            margin: 0 auto 40px;
            position: relative;
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 15%;
            right: 15%;
            height: 2px;
            background: rgba(0, 0, 0, 0.08);
            z-index: 1;
        }

        .stepper-progress {
            position: absolute;
            top: 25px;
            left: 15%;
            width: 0%;
            height: 2px;
            background: var(--primary);
            z-index: 1;
            transition: var(--transition);
        }

        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--white);
            border: 2px solid rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--text-light);
            font-size: 16px;
            transition: var(--transition);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
        }

        .step-item.active .step-circle {
            border-color: var(--primary);
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .step-item.completed .step-circle {
            border-color: var(--primary);
            background: var(--white);
            color: var(--primary);
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            color: var(--text-light);
            transition: var(--transition);
        }

        .step-item.active .step-label {
            color: var(--primary);
        }

        /* Form Styling */
        .form-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            margin-bottom: 25px;
            color: var(--secondary);
            border-bottom: 2px solid rgba(212, 175, 55, 0.15);
            padding-bottom: 8px;
            display: inline-block;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        @media (max-width: 576px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }

        .form-control-wrap {
            margin-bottom: 20px;
        }

        .form-control-wrap label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            transition: var(--transition);
            background: #FAFAFA;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .form-input::placeholder {
            color: #A0A0A0;
        }

        /* Summary Panel styling */
        .summary-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--secondary);
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding-bottom: 12px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            margin-top: 25px;
            padding-top: 20px;
            font-weight: 700;
            font-size: 18px;
            color: var(--primary);
        }

        .summary-badge {
            background: rgba(212, 175, 55, 0.1);
            color: var(--primary);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            margin-top: 5px;
        }

        /* Success Animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            background: rgba(212, 175, 55, 0.1);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            animation: popCheck 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        @keyframes popCheck {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

        /* Navigation Button wrap */
        .btn-wrap {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .btn-secondary {
            background: var(--white);
            color: var(--text);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: #FAFAFA;
            transform: translateY(-2px);
        }

        /* Error notification */
        .error-banner {
            background: #FFF0F2;
            color: #D32F2F;
            border: 1px solid #FFCDD2;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 13px;
            display: none;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        /* Mock Midtrans Overlay */
        .payment-loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--bg-alt);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        .midtrans-badge {
            background: #1a73e8;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <!-- Header / Navigation -->
    <header>
        <nav>
            <a href="{{ url('/') }}" class="brand">
                <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Brilliant Logo"
                    style="height: 40px; vertical-align: middle;">
            </a>
            <ul class="nav-links">
                <li><a href="{{ url('/') }}#hero">Beranda</a></li>
                <li><a href="{{ url('/') }}#about">Tentang Kami</a></li>
                <li><a href="{{ url('/') }}#packages">Paket</a></li>
                <li><a href="{{ url('/') }}#reservation">Reservasi</a></li>
                <li><a href="{{ url('/') }}#gallery">Galeri</a></li>
                <li><a href="{{ url('/') }}#faq">FAQ</a></li>
                <li><a href="{{ url('/') }}#contact">Kontak</a></li>
            </ul>
            <div class="nav-auth">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/management-system/dashboard') }}" class="nav-cta">Dashboard</a>
                    @else
                        <a href="{{ route('management.login') }}" class="btn" style="color: var(--text)">Masuk</a>
                        <a href="{{ url('/') }}#contact" class="nav-cta">Mulai Sekarang</a>
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <!-- Midtrans Sandbox Sim Overlay -->
    <div class="payment-loader-overlay" id="paymentLoader">
        <span class="midtrans-badge">Midtrans</span>
        <div class="spinner"></div>
        <h3
            style="font-family: 'Playfair Display', serif; font-size: 22px; color: var(--secondary); margin-bottom: 8px;">
            Menghubungkan ke Midtrans ...</h3>
        <p
            style="color: var(--text-light); font-size: 14px; text-align: center; max-width: 380px; padding: 0 20px; line-height: 1.5;">
            Sistem sedang membuat tautan transaksi aman dan mengalihkan Anda ke gerbang pembayaran Midtrans.
        </p>
    </div>

    <!-- Reservation Content Area -->
    <div class="reservation-container">
        <!-- Title Banner -->
        <div style="text-align: center; max-width: 700px; margin: 0 auto 40px;">
            <span class="section-subtitle">Pemesanan Online</span>
            <h2 class="section-title" style="font-size: 38px; margin-bottom: 15px;">Reservasi Pernikahan Anda</h2>
            <p style="color: var(--text-light); font-size: 15px;">
                Isi data formulir pernikahan Anda di bawah ini dan lakukan pembayaran instan secara aman.
            </p>
        </div>

        <!-- Multi-step Stepper -->
        <div class="stepper">
            <div class="stepper-progress" id="stepProgress"></div>
            <div class="step-item active" id="stepNav1">
                <div class="step-circle">1</div>
                <div class="step-label">Isi Form</div>
            </div>
            <div class="step-item" id="stepNav2">
                <div class="step-circle">2</div>
                <div class="step-label">Selesai</div>
            </div>
        </div>

        <!-- Alert Banner -->
        <div class="error-banner" id="errorBanner">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span id="errorMessage">Silakan lengkapi kolom yang wajib diisi.</span>
        </div>

        <!-- Booking Main Layout Card -->
        <div class="booking-card" id="bookingCard">
            <!-- Left Side: Interactive Steps Form -->
            <div class="booking-main">
                <!-- STEP 1: FORM FILLING -->
                <div id="stepContent1">
                    <h3 class="form-section-title">Informasi Pemesan</h3>
                    <div class="form-grid-2">
                        <div class="form-control-wrap">
                            <label for="client_name">Nama Lengkap Pemohon *</label>
                            <input type="text" id="client_name" class="form-input" placeholder="Contoh: Rian Hidayat"
                                required>
                        </div>
                        <div class="form-control-wrap">
                            <label for="client_whatsapp">Nomor WhatsApp *</label>
                            <input type="tel" id="client_whatsapp" class="form-input" placeholder="Contoh: 081234567890"
                                required>
                        </div>
                    </div>
                    <div class="form-control-wrap">
                        <label for="client_email">Alamat Email *</label>
                        <input type="email" id="client_email" class="form-input" placeholder="Contoh: rian@example.com"
                            required>
                    </div>

                    <h3 class="form-section-title" style="margin-top: 20px;">Detail Pasangan & Acara</h3>
                    <div class="form-grid-2">
                        <div class="form-control-wrap">
                            <label for="groom_name">Nama Pengantin Pria</label>
                            <input type="text" id="groom_name" class="form-input" placeholder="Nama Calon Pria">
                        </div>
                        <div class="form-control-wrap">
                            <label for="bride_name">Nama Pengantin Wanita</label>
                            <input type="text" id="bride_name" class="form-input" placeholder="Nama Calon Wanita">
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-control-wrap">
                            <label for="event_date">Tanggal Acara *</label>
                            <input type="date" id="event_date" class="form-input" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-control-wrap">
                            <label for="package_select">Pilih Paket Pernikahan *</label>
                            <select id="package_select" class="form-input"
                                style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\'%3E%3Cpath fill=\'none\' stroke=\'%23343a40\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2 5l6 6 6-6\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 15px center; background-size: 12px; -webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 40px;"
                                required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($packages as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                                <option value="custom">Paket Kustom (Custom)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-control-wrap" style="margin-bottom: 0;">
                        <label for="event_notes">Keterangan Tambahan / Detail Kustomisasi</label>
                        <textarea id="event_notes" class="form-input"
                            placeholder="Tuliskan detail kustomisasi paket atau catatan tambahan Anda di sini..."
                            rows="4"></textarea>
                    </div>

                    <div class="btn-wrap">
                        <div></div> <!-- placeholder -->
                        <button type="button" class="btn btn-primary" onclick="processPayment()"
                            style="display: inline-flex; align-items: center; gap: 8px;">
                            Bayar Sekarang <i class="bi bi-wallet2"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: RESERVATION SUCCESS & MIDTRANS SIMULATOR -->
                <div id="stepContent2" style="display: none; text-align: center; padding: 20px 0;">
                    <div class="success-checkmark">
                        <i class="bi bi-check2"></i>
                    </div>
                    <h3
                        style="font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 10px; color: var(--secondary);">
                        Reservasi Berhasil Diajukan!</h3>
                    <p
                        style="color: var(--text-light); max-width: 550px; margin: 0 auto 20px; font-size: 14px; line-height: 1.6;">
                        Terima kasih! Kami telah menerima formulir reservasi Anda. Pembayaran Anda akan diproses, Kami
                        akan segera menghubungi anda via email atau Whatsapp untuk informasi lebih lanjut.
                    </p>



                    <!-- Receipt Details Box -->
                    <div
                        style="background: var(--bg-alt); border-radius: 12px; border: 1px solid rgba(212, 175, 55, 0.15); max-width: 480px; margin: 0 auto 40px; padding: 25px; text-align: left;">
                        <div
                            style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(0,0,0,0.06); padding-bottom: 12px; margin-bottom: 12px;">
                            <span style="font-size: 13px; color: var(--text-light); font-weight: 600;">KODE
                                RESERVASI</span>
                            <span style="font-weight: 700; color: var(--primary);"
                                id="resCodeVal">BR-20260518-9321</span>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                            <span style="color: var(--text-light);">Nama Pemesan:</span>
                            <strong style="color: var(--secondary);" id="resNameVal">-</strong>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                            <span style="color: var(--text-light);">Paket Pernikahan:</span>
                            <strong style="color: var(--secondary);" id="resPackageVal">-</strong>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                            <span style="color: var(--text-light);">Tanggal Pernikahan:</span>
                            <strong style="color: var(--secondary);" id="resDateVal">-</strong>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                            <span style="color: var(--text-light);">Biaya Komitmen (DP):</span>
                            <strong style="color: var(--secondary);">IDR
                                {{ number_format($dpNominal, 0, ',', '.') }}</strong>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; font-size: 13px; border-top: 1px solid rgba(0,0,0,0.06); padding-top: 12px; margin-top: 12px;">
                            <span style="color: var(--text-light);">Status Reservasi:</span>
                            <span id="paymentStatusText"
                                style="font-weight: 700; color: #1a73e8; display: flex; align-items: center; gap: 5px;">
                                <i class="bi bi-clock-history"></i> Menunggu Pembayaran Midtrans
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 15px; max-width: 300px; margin: 0 auto;">
                        <a id="downloadInvoiceBtn" href="#" target="_blank" class="btn btn-primary"
                            style="background: #1a73e8; border: none; color: white; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Unduh Invoice (PDF)
                        </a>
                        <button type="button" class="btn btn-primary" onclick="redirectToWhatsApp()"
                            style="background: #25D366; border: none; color: white; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="bi bi-whatsapp"></i> Konfirmasi Via WhatsApp
                        </button>
                        <a href="{{ url('/') }}" class="btn btn-secondary"
                            style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <i class="bi bi-house-door"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side: dynamic Checkout Summary Sidebar -->
            <div class="booking-sidebar" id="bookingSidebar">
                <div>
                    <h4 class="summary-title">Rincian Paket</h4>
                    <div style="text-align: center; padding: 20px 0; color: var(--text-light);" id="summaryEmptyState">
                        <i class="bi bi-cart3"
                            style="font-size: 40px; opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                        <p style="font-size: 13px;">Silakan pilih paket pernikahan di formulir kiri untuk melihat
                            rincian.</p>
                    </div>

                    <div id="summaryContent" style="display: none;">
                        <div class="summary-row">
                            <span style="font-weight: 600; color: var(--secondary);" id="summaryPackageName">Paket
                                Premium</span>
                            <span style="font-weight: 600; color: var(--text);" id="summaryPackagePrice">IDR
                                45.000.000</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-light); margin-top: -10px; margin-bottom: 15px;"
                            id="summaryPackageDesc">
                            Termasuk dekorasi, katering 500 pax, dokumentasi, MC, dan wedding coordinator.
                        </div>

                        <!-- Selected Package Items -->
                        <div id="summaryPackageItemsContainer"
                            style="margin-bottom: 20px; padding: 12px; background: rgba(65, 97, 42, 0.04); border-radius: 8px; border: 1px solid rgba(65, 97, 42, 0.08);">
                            <h5
                                style="font-size: 11px; font-weight: 700; color: var(--secondary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.8px;">
                                Item Termasuk:</h5>
                            <ul id="summaryPackageItems"
                                style="list-style: none; padding-left: 0; margin-bottom: 0; display: flex; flex-direction: column; gap: 5px;">
                                <!-- Dynamic Items list -->
                            </ul>
                        </div>

                        <div class="summary-row">
                            <span>Down Payment (DP)</span>
                            <strong>IDR {{ number_format($dpNominal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row" id="pelunasanRow">
                            <span>Pelunasan H-14</span>
                            <span id="summaryPelunasan">IDR 40.000.000</span>
                        </div>

                        <div class="summary-badge">Termasuk Biaya Pajak & Layanan</div>
                    </div>
                </div>

                <div id="summaryTotalBox" style="display: none;">
                    <div class="summary-row total">
                        <span>Total Pembayaran (DP)</span>
                        <span>IDR {{ number_format($dpNominal, 0, ',', '.') }}</span>
                    </div>
                    <p style="font-size: 11px; color: var(--text-light); margin-top: 5px; line-height: 1.4;">
                        * Pembayaran DP senilai IDR {{ number_format($dpNominal, 0, ',', '.') }} dilakukan untuk memesan
                        slot tanggal Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content"
            style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 10px; margin-bottom: 30px;">
            <div>
                <h2 class="brand" style="color: var(--white); margin-bottom: 10px;">
                    {{ \App\Models\WebsiteSetting::get('footer_brand', 'Brilliant Event') }}
                </h2>
                <p style="opacity: 0.7; max-width: 600px; margin: 0 auto;">
                    {{ \App\Models\WebsiteSetting::get('footer_description', 'Menjadikan setiap pernikahan sebuah mahakarya cinta dan koordinasi.') }}
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }}
                {{ \App\Models\WebsiteSetting::get('footer_copyright', 'Brilliant Event & Wedding Organizer. Seluruh hak cipta dilindungi.') }}
            </p>
        </div>
    </footer>

    <!-- Scripts for Multi-step Form & Interaction -->
    <script>
        // Parse packages from controller/database
        const appPackages = JSON.parse('{!! addslashes($packagesJson) !!}');
        const dpNominalVal = {{ $dpNominal }};

        let activeStep = 1;
        let bookingCode = '';

        // Helper to escape HTML
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            return text
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Handle Package Selection Change
        const packageSelect = document.getElementById('package_select');
        const eventDateInput = document.getElementById('event_date');

        eventDateInput.addEventListener('change', function () {
            const selectedDate = this.value;
            if (!selectedDate) return;

            fetch(`{{ route('landing.reservasi.check-availability') }}?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Fullbook!',
                            text: data.message,
                            confirmButtonColor: '#d4af37'
                        });
                        this.value = ''; // Reset input
                    }
                })
                .catch(error => console.error('Error checking availability:', error));
        });

        packageSelect.addEventListener('change', function () {
            const selectedVal = this.value;

            const summaryEmptyState = document.getElementById('summaryEmptyState');
            const summaryContent = document.getElementById('summaryContent');
            const summaryTotalBox = document.getElementById('summaryTotalBox');
            const itemsContainer = document.getElementById('summaryPackageItemsContainer');
            const itemsUl = document.getElementById('summaryPackageItems');

            if (!selectedVal) {
                summaryEmptyState.style.display = 'block';
                summaryContent.style.display = 'none';
                summaryTotalBox.style.display = 'none';
                return;
            }

            if (selectedVal === 'custom') {
                summaryEmptyState.style.display = 'none';
                summaryContent.style.display = 'block';
                summaryTotalBox.style.display = 'block';

                document.getElementById('summaryPackageName').textContent = 'Paket Kustom (Custom)';
                document.getElementById('summaryPackagePrice').textContent = 'Disesuaikan';
                document.getElementById('summaryPackageDesc').textContent = 'Katering, dekorasi, dokumentasi, dan layanan lainnya disesuaikan dengan anggaran Anda.';
                itemsContainer.style.display = 'none';
                document.getElementById('pelunasanRow').style.display = 'none';
            } else {
                // Find selected package details
                const matchedPkg = appPackages.find(p => p.id == selectedVal);
                if (matchedPkg) {
                    summaryEmptyState.style.display = 'none';
                    summaryContent.style.display = 'block';
                    summaryTotalBox.style.display = 'block';
                    document.getElementById('pelunasanRow').style.display = 'flex';

                    document.getElementById('summaryPackageName').textContent = matchedPkg.name;
                    document.getElementById('summaryPackagePrice').textContent = formatIDRCurrency(matchedPkg.price);
                    document.getElementById('summaryPackageDesc').textContent = 'Berikut rincian item layanan yang tercakup di dalam paket ini:';

                    // Populate Package Items list dynamically
                    itemsUl.innerHTML = '';
                    if (matchedPkg.items && matchedPkg.items.length > 0) {
                        itemsContainer.style.display = 'block';
                        matchedPkg.items.forEach(item => {
                            const li = document.createElement('li');
                            li.style.fontSize = '12px';
                            li.style.color = 'var(--text-light)';
                            li.style.display = 'flex';
                            li.style.alignItems = 'center';
                            li.style.gap = '6px';
                            li.innerHTML = `<i class="bi bi-check-circle-fill text-success" style="font-size: 11px;"></i> <span>${escapeHtml(item)}</span>`;
                            itemsUl.appendChild(li);
                        });
                    } else {
                        itemsContainer.style.display = 'none';
                    }

                    // Calculate outstanding payment (Total - DP)
                    const outstanding = Math.max(0, matchedPkg.price - dpNominalVal);
                    document.getElementById('summaryPelunasan').textContent = formatIDRCurrency(outstanding);
                }
            }
        });

        // Stepper Navigation Logic & Midtrans simulation
        function processPayment() {
            // Validate Step 1 form fields
            const clientName = document.getElementById('client_name').value.trim();
            const clientWhatsapp = document.getElementById('client_whatsapp').value.trim();
            const clientEmail = document.getElementById('client_email').value.trim();
            const eventDate = document.getElementById('event_date').value;
            const packageSelectVal = document.getElementById('package_select').value;
            const groomName = document.getElementById('groom_name').value.trim();
            const brideName = document.getElementById('bride_name').value.trim();
            const eventNotes = document.getElementById('event_notes').value.trim();

            if (!clientName || !clientWhatsapp || !clientEmail || !eventDate || !packageSelectVal) {
                showError('Silakan lengkapi seluruh kolom formulir bertanda bintang (*) sebelum melanjutkan.');
                scrollToTopForm();
                return;
            }

            // If email is invalid
            if (!validateEmail(clientEmail)) {
                showError('Format alamat email yang Anda masukkan tidak valid.');
                scrollToTopForm();
                return;
            }

            // Valid! Show Simulated Midtrans loader
            hideError();

            const loader = document.getElementById('paymentLoader');
            loader.style.display = 'flex';

            // Post to backend to request Snap Token
            const tokenPayload = {
                client_name: clientName,
                client_email: clientEmail,
                client_whatsapp: clientWhatsapp,
                package_id: packageSelectVal,
                event_date: eventDate
            };

            // Set dynamic loader text
            document.querySelector('#paymentLoader h3').textContent = "Menghubungkan ke Gerbang Pembayaran Midtrans...";
            loader.style.display = 'flex';

            fetch('{{ route("landing.reservasi.token") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(tokenPayload)
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(tokenData => {
                    loader.style.display = 'none';

                    if (tokenData.success && tokenData.snap_token) {
                        // Trigger the premium Midtrans Snap pop-up window
                        snap.pay(tokenData.snap_token, {
                            onSuccess: function (result) {
                                console.log('Payment success:', result);

                                // Now that payment is successful, store the reservation and payment in the database!
                                document.querySelector('#paymentLoader h3').textContent = "Memverifikasi Pembayaran & Menyimpan Data...";
                                loader.style.display = 'flex';

                                const storePayload = {
                                    client_name: clientName,
                                    client_whatsapp: clientWhatsapp,
                                    client_email: clientEmail,
                                    groom_name: groomName,
                                    bride_name: brideName,
                                    event_date: eventDate,
                                    package_id: packageSelectVal,
                                    event_notes: eventNotes,
                                    invoice_no: tokenData.invoice_no,
                                    snap_token: tokenData.snap_token
                                };

                                fetch('{{ route("landing.reservasi.store") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(storePayload)
                                })
                                    .then(storeResponse => {
                                        if (!storeResponse.ok) {
                                            return storeResponse.json().then(err => { throw err; });
                                        }
                                        return storeResponse.json();
                                    })
                                    .then(storeData => {
                                        loader.style.display = 'none';
                                        if (storeData.success) {
                                            showSuccessStep(storeData, 'Lunas');
                                        } else {
                                            showError(storeData.message || 'Terjadi kesalahan saat menyimpan data reservasi Anda.');
                                            scrollToTopForm();
                                        }
                                    })
                                    .catch(err => {
                                        loader.style.display = 'none';
                                        console.error('Store error:', err);
                                        showError(err.message || 'Pembayaran berhasil, namun terjadi kendala saat menyimpan reservasi Anda. Silakan hubungi admin.');
                                        scrollToTopForm();
                                    });
                            },
                            onPending: function (result) {
                                console.log('Payment pending:', result);
                                showError('Silakan selesaikan pembayaran Anda di jendela Midtrans untuk memproses reservasi.');
                                scrollToTopForm();
                            },
                            onError: function (result) {
                                console.error('Payment error:', result);
                                showError('Pembayaran gagal. Silakan coba kembali.');
                                scrollToTopForm();
                            },
                            onClose: function () {
                                console.log('Customer closed the payment popup without finishing');
                            }
                        });
                    } else {
                        showError(tokenData.message || 'Terjadi kesalahan saat memproses token pembayaran.');
                        scrollToTopForm();
                    }
                })
                .catch(err => {
                    loader.style.display = 'none';
                    console.error('Error:', err);
                    showError(err.message || 'Gagal terhubung dengan server untuk memproses pembayaran Anda. Silakan coba kembali.');
                    scrollToTopForm();
                });
        }

        // Helper to load success step and update DOM values
        function showSuccessStep(data, paymentStatus) {
            bookingCode = data.booking_code;

            // Load backend values into final step receipt
            document.getElementById('resCodeVal').textContent = data.booking_code;
            document.getElementById('resNameVal').textContent = data.client_name;
            document.getElementById('resPackageVal').textContent = data.package_name;
            document.getElementById('resDateVal').textContent = formatDateString(data.event_date);

            const statusText = document.getElementById('paymentStatusText') || document.querySelector('#stepContent2 span[style*="Menunggu Pembayaran"]');
            if (statusText) {
                if (paymentStatus === 'Lunas') {
                    statusText.innerHTML = '<i class="bi bi-patch-check-fill" style="color: #10b981;"></i> Pembayaran Berhasil (Lunas)';
                    statusText.style.color = '#10b981';
                } else {
                    statusText.innerHTML = '<i class="bi bi-clock-history"></i> Menunggu Pembayaran Midtrans';
                    statusText.style.color = '#1a73e8';
                }
            }

            // Set the dynamic URL for invoice download / view
            document.getElementById('downloadInvoiceBtn').href = '{{ url("/reservasi/invoice") }}/' + data.invoice_no;

            // Move form display
            document.getElementById('stepContent1').style.display = 'none';
            document.getElementById('stepContent2').style.display = 'block';

            document.getElementById('stepNav1').classList.remove('active');
            document.getElementById('stepNav1').classList.add('completed');
            document.getElementById('stepNav2').classList.add('active');

            document.getElementById('stepProgress').style.width = '100%';

            // Hide sidebar summary for success display
            document.getElementById('bookingSidebar').style.display = 'none';
            document.getElementById('bookingCard').style.gridTemplateColumns = '1fr';

            activeStep = 2;
            scrollToTopForm();
        }

        // WhatsApp redirect pre-filled trigger
        function redirectToWhatsApp() {
            const clientName = document.getElementById('client_name').value.trim();
            const clientWhatsapp = document.getElementById('client_whatsapp').value.trim();
            const packageSelectVal = document.getElementById('package_select').value;
            const eventDate = document.getElementById('event_date').value;

            let packageName = 'Custom';
            if (packageSelectVal === 'custom') {
                packageName = 'Paket Kustom (Custom)';
            } else {
                const matchedPkg = appPackages.find(p => p.id == packageSelectVal);
                packageName = matchedPkg ? matchedPkg.name : 'Custom';
            }

            const waNumber = "{{ \App\Models\WebsiteSetting::get('contact_whatsapp', '6281234567890') }}"; // Admin WA number

            let msg = `Halo Brilliant Event, saya baru saja melakukan reservasi online dengan detail berikut:\n\n`;
            msg += `*Kode Booking:* _${bookingCode}_\n`;
            msg += `*Nama Pemesan:* ${clientName}\n`;
            msg += `*Kontak Pemesan:* ${clientWhatsapp}\n`;
            msg += `*Paket Pernikahan:* ${packageName}\n`;
            msg += `*Tanggal Pernikahan:* ${formatDateString(eventDate)}\n\n`;
            msg += `Saya ingin mengonfirmasi detail reservasi saya untuk diproses lebih lanjut. Terima kasih!`;

            const encoded = encodeURIComponent(msg);
            const waUrl = `https://wa.me/${waNumber}?text=${encoded}`;
            window.open(waUrl, '_blank');
        }

        // Helper Utilities
        function showError(msg) {
            const banner = document.getElementById('errorBanner');
            const message = document.getElementById('errorMessage');
            message.textContent = msg;
            banner.style.display = 'flex';
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                // If on separate page, redirect to home page with hash
                if (window.location.pathname !== '/' && window.location.pathname !== '/index.php') {
                    // Let normal navigation happen if it's pointing to homepage anchors
                    const href = this.getAttribute('href');
                    if (href.startsWith('#')) {
                        e.preventDefault();
                        window.location.href = '/' + href;
                    }
                }
            });
        });

        function hideError() {
            const banner = document.getElementById('errorBanner');
            banner.style.display = 'none';
        }

        function scrollToTopForm() {
            window.scrollTo({
                top: document.querySelector('.stepper').offsetTop - 120,
                behavior: 'smooth'
            });
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function formatIDRCurrency(num) {
            return 'IDR ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(num);
        }

        function formatDateString(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
        }
    </script>
</body>

</html>