<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $payment->invoice_no }} - Brilliant Event & Wedding Organizer</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Midtrans Snap Pop-up Payment Gateway -->
    <script type="text/javascript"
        src="https://app.{{ config('midtrans.is_production') ? 'midtrans' : 'sandbox.midtrans' }}.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        :root {
            --primary: #d4af37;
            --primary-dark: #b5922c;
            --secondary: #1e293b;
            --text: #334155;
            --text-light: #64748b;
            --bg: #f8fafc;
            --white: #ffffff;
            --border: #e2e8f0;
            --success: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.5;
            padding: 25px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: var(--white);
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border);
        }

        .btn-back {
            color: var(--text-light);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: var(--secondary);
        }

        .btn-action {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2);
            text-decoration: none;
        }

        .btn-action:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .invoice-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
            position: relative;
        }

        .invoice-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), #ecd58c, var(--primary));
        }

        .invoice-header {
            padding: 25px 35px 20px;
            border-bottom: 1px dashed var(--border);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-info img {
            height: 42px;
            margin-bottom: 8px;
        }

        .company-title {
            font-family: 'Playfair Display', serif;
            color: var(--secondary);
            font-size: 18px;
            margin-bottom: 2px;
        }

        .company-subtitle {
            color: var(--text-light);
            font-size: 12px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: var(--secondary);
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .invoice-no {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            background: rgba(212, 175, 55, 0.08);
            padding: 3px 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .invoice-body {
            padding: 25px 35px;
        }

        .grid-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }

        .info-block h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-light);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .info-block p {
            font-size: 13px;
            color: var(--secondary);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .info-block strong {
            color: var(--secondary);
            font-weight: 700;
        }

        .table-wrap {
            margin-bottom: 25px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .invoice-table th {
            background: #f8fafc;
            color: var(--secondary);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 15px;
            border-bottom: 1px solid var(--border);
        }

        .invoice-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            color: var(--text);
            line-height: 1.4;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-table .amount {
            text-align: right;
            font-weight: 600;
            color: var(--secondary);
        }

        .total-summary {
            background: #f8fafc;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid var(--border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            border-top: 1px solid var(--border);
            padding-top: 8px;
            margin-top: 8px;
        }

        .summary-row span {
            color: var(--text-light);
        }

        .summary-row strong {
            color: var(--secondary);
            font-weight: 600;
        }

        .summary-row.total span {
            font-weight: 700;
            color: var(--secondary);
            font-size: 14px;
        }

        .summary-row.total strong {
            color: var(--primary-dark);
            font-size: 16px;
            font-weight: 700;
        }

        .invoice-notes {
            background: rgba(212, 175, 55, 0.03);
            border-left: 3px solid var(--primary);
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 25px;
        }

        .invoice-notes h5 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-dark);
            margin-bottom: 4px;
            font-weight: 700;
        }

        .invoice-notes p {
            font-size: 12px;
            color: var(--text-light);
            line-height: 1.4;
        }

        .invoice-footer {
            text-align: center;
            border-top: 1px solid var(--border);
            padding-top: 20px;
            margin-top: 25px;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: var(--secondary);
            margin-bottom: 6px;
            font-weight: 700;
        }

        .footer-contact {
            color: var(--text-light);
            font-size: 12px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .badge-paid {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-transform: uppercase;
        }

        @media (max-width: 600px) {
            .invoice-header {
                flex-direction: column;
                gap: 15px;
                padding: 20px 25px;
            }

            .invoice-meta {
                text-align: left;
            }

            .invoice-body {
                padding: 20px 25px;
            }

            .grid-info {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .company-info img {
                height: 36px;
            }
        }

        /* PRINT STYLE SHEETS RULES */
        @media print {
            body {
                background: none !important;
                color: #000 !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 12px !important;
            }

            .container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .action-bar {
                display: none !important;
            }

            .invoice-card {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .invoice-card::before {
                height: 4px !important;
            }

            .invoice-header {
                padding: 15px 20px 10px !important;
            }

            .company-info img {
                height: 35px !important;
                margin-bottom: 5px !important;
            }

            .company-title {
                font-size: 16px !important;
            }

            .invoice-title {
                font-size: 20px !important;
            }

            .invoice-no {
                font-size: 13px !important;
                padding: 2px 8px !important;
            }

            .invoice-body {
                padding: 15px 20px !important;
            }

            .grid-info {
                margin-bottom: 15px !important;
                gap: 20px !important;
            }

            .info-block h4 {
                margin-bottom: 6px !important;
            }

            .info-block p {
                margin-bottom: 3px !important;
                font-size: 12px !important;
            }

            .table-wrap {
                margin-bottom: 15px !important;
            }

            .invoice-table th {
                padding: 8px 12px !important;
                font-size: 11px !important;
            }

            .invoice-table td {
                padding: 8px 12px !important;
                font-size: 11px !important;
            }

            .total-summary {
                background: none !important;
                border: 1px solid #ccc !important;
                padding: 10px 15px !important;
                margin-bottom: 15px !important;
            }

            .summary-row {
                margin-bottom: 6px !important;
                font-size: 11px !important;
            }

            .summary-row:last-child {
                padding-top: 8px !important;
                margin-top: 8px !important;
            }

            .summary-row.total span {
                font-size: 13px !important;
            }

            .summary-row.total strong {
                font-size: 14px !important;
            }

            .invoice-notes {
                background: none !important;
                border-left: 2px solid #000 !important;
                padding: 8px 12px !important;
                margin-bottom: 15px !important;
            }

            .invoice-notes h5 {
                margin-bottom: 4px !important;
            }

            .invoice-notes p {
                font-size: 11px !important;
            }

            .invoice-footer {
                text-align: center !important;
                border-top: 1px solid #ccc !important;
                padding-top: 15px !important;
                margin-top: 15px !important;
            }

            .footer-logo {
                font-size: 14px !important;
                margin-bottom: 4px !important;
            }

            .footer-contact {
                font-size: 11px !important;
                gap: 15px !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Action Top Bar -->
        <div class="action-bar">
            <a href="{{ url('/') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
            <div style="display: flex; gap: 10px;">
                @if($payment->status === 'Pending' && $payment->snap_token)
                    <button id="pay-button" class="btn-action"
                        style="background-color: #2563eb; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);">
                        <i class="bi bi-wallet2"></i> Bayar Sekarang
                    </button>
                @endif
                <a href="{{ route('landing.reservasi.invoice.download', $payment->invoice_no) }}" class="btn-action" style="background-color: #1e293b; box-shadow: 0 4px 10px rgba(30, 41, 59, 0.2);">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>
                <button onclick="window.print()" class="btn-action">
                    <i class="bi bi-printer"></i> Cetak Invoice
                </button>
            </div>
        </div>

        <!-- Premium Invoice Card -->
        <div class="invoice-card">
            <div class="invoice-header">
                <div class="company-info">
                    <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Brilliant Logo"
                        onerror="this.style.display='none'">
                    <div class="company-title">Brilliant Event & Wedding Organizer</div>
                    <div class="company-subtitle">CV. Brilliant Bertaqwa Berdaya</div>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-no">{{ $payment->invoice_no }}</div>
                    <div style="margin-top: 10px;">
                        @if($payment->status === 'Paid')
                            <span class="badge-paid"><i class="bi bi-check-circle-fill"></i> Terbayar ({{ $payment->payment_type === 'DP' ? 'DP' : ($payment->payment_type === 'Pelunasan' ? 'Pelunasan' : 'Partial') }})</span>
                        @elseif($payment->status === 'Pending')
                            <span class="badge-pending"
                                style="background: rgba(245, 158, 11, 0.1); color: #d97706; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; text-transform: uppercase;">
                                <i class="bi bi-hourglass-split"></i> Menunggu Pembayaran
                            </span>
                        @else
                            <span class="badge-failed"
                                style="background: rgba(239, 68, 68, 0.1); color: #dc2626; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; text-transform: uppercase;">
                                <i class="bi bi-x-circle-fill"></i> Gagal / Kedaluwarsa
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="invoice-body">
                <!-- Grid Info Pemesan -->
                <div class="grid-info">
                    <div class="info-block">
                        <h4>Rincian Acara & Pasangan</h4>
                        <p><strong>Nama Acara:</strong> {{ $payment->event->name }}</p>
                        <p><strong>Pasangan Pengantin:</strong>
                            {{ $payment->event->groom_name ?: '-' }} & {{ $payment->event->bride_name ?: '-' }}
                        </p>
                        <p><strong>Tanggal Pernikahan:</strong>
                            {{ \Carbon\Carbon::parse($payment->event->date)->format('d F Y') }}</p>
                    </div>
                    <div class="info-block" style="text-align: right;">
                        <h4>Informasi Pemesan</h4>
                        <p><strong>Nama Pemesan:</strong> {{ $payment->event->client_name }}</p>
                        <p><strong>Tanggal Terbit:</strong>
                            {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}</p>
                        <p><strong>Metode Pembayaran:</strong> Transfer Bank</p>
                    </div>
                </div>

                <!-- Tabel Detail Item -->
                <div class="table-wrap">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Deskripsi Pekerjaan / Layanan</th>
                                <th style="text-align: right;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{{ $payment->package_id ? $payment->package->name : $payment->custom_package_name }}</strong><br>
                                    <span style="font-size: 12px; color: var(--text-light);">
                                        Paket wedding coordinator, katering, MC, dekorasi dan dokumentasi standar.
                                    </span>
                                </td>
                                <td class="amount">Rp {{ number_format($packagePrice, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        @if($payment->payment_type === 'DP')
                                            Komitmen Fee / Down Payment (DP)
                                        @elseif($payment->payment_type === 'Pelunasan')
                                            Pelunasan
                                        @elseif($payment->payment_type === 'Partial')
                                            Pembayaran Sebagian (Partial)
                                        @else
                                            {{ ucfirst($payment->payment_type) }}
                                        @endif
                                    </strong><br>
                                    <span style="font-size: 12px; color: var(--text-light);">
                                        @if($payment->payment_type === 'DP')
                                            Pembayaran awal tanda jadi pemesanan slot tanggal pernikahan.
                                        @elseif($payment->payment_type === 'Pelunasan')
                                            Pembayaran sisa tagihan untuk pelunasan biaya layanan.
                                        @elseif($payment->payment_type === 'Partial')
                                            Pembayaran sebagian dari total tagihan layanan.
                                        @else
                                            Pembayaran layanan.
                                        @endif
                                    </span>
                                </td>
                                <td class="amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Total Breakdown -->
                <div class="total-summary">
                    <div class="summary-row">
                        <span>Total Harga Paket:</span>
                        <strong>Rp {{ number_format($packagePrice, 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-row">
                        <span>
                            @if($payment->payment_type === 'DP')
                                Down Payment (DP) Terbayar:
                            @elseif($payment->payment_type === 'Pelunasan')
                                Pelunasan Terbayar:
                            @elseif($payment->payment_type === 'Partial')
                                Pembayaran Sebagian Terbayar:
                            @else
                                Pembayaran Terbayar:
                            @endif
                        </span>
                        <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="summary-row total">
                        <span>Sisa Pelunasan H-14:</span>
                        <strong>Rp {{ number_format($remainingBalance, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- Notes -->
                @if($payment->notes)
                    <div class="invoice-notes">
                        <h5>Catatan Khusus:</h5>
                        <p>{!! nl2br(e($payment->notes)) !!}</p>
                    </div>
                @endif

                <!-- Invoice Footer -->
                <div class="invoice-footer">
                    <div class="footer-logo">
                        {{ \App\Models\WebsiteSetting::get('footer_brand', 'Brilliant Event & Wedding Organizer') }}
                    </div>
                    <div class="footer-contact">
                        <span><i class="bi bi-telephone-fill"></i>
                            {{ \App\Models\WebsiteSetting::get('contact_phone', '021 323 842') }}</span>
                        <span><i class="bi bi-whatsapp"></i>
                            {{ \App\Models\WebsiteSetting::get('contact_whatsapp', '-') }}</span>
                        <span><i class="bi bi-envelope-fill"></i> {{ \App\Models\WebsiteSetting::get('contact_email',
                            'brilliant@mail.com') }}</span>
                        <span><i class="bi bi-geo-alt-fill"></i>
                            {{ \App\Models\WebsiteSetting::get('contact_address', 'Jakarta, Indonesia') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog if ?print=true parameter is present in URL
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('print')) {
                setTimeout(function () {
                    window.print();
                }, 500);
            }

            // Handle pay button click on invoice
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', function () {
                    snap.pay('{{ $payment->snap_token }}', {
                        onSuccess: function (result) {
                            console.log('Payment success:', result);
                            window.location.reload();
                        },
                        onPending: function (result) {
                            console.log('Payment pending:', result);
                            window.location.reload();
                        },
                        onError: function (result) {
                            console.error('Payment error:', result);
                            alert('Pembayaran gagal dilakukan. Silakan coba kembali.');
                        },
                        onClose: function () {
                            console.log('Customer closed payment popup');
                        }
                    });
                });
            }
        };
    </script>
</body>

</html>