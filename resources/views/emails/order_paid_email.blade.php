<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Pernikahan Berhasil</title>
    <style>
        body {
            font-family: 'Outfit', 'Inter', 'Segoe UI', Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            color: #2d3748;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #edf2f7;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .invoice-card {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .invoice-number {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 15px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .info-item {
            font-size: 14px;
        }
        .info-label {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-value {
            font-weight: 600;
            color: #334155;
        }
        .payment-amount {
            font-size: 24px;
            font-weight: 800;
            color: #10b981;
            margin-top: 10px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th {
            text-align: left;
            padding: 12px;
            background-color: #f1f5f9;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            font-weight: 600;
        }
        .details-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reservasi & Pembayaran Berhasil!</h1>
            <p>Sistem Wedding Organizer - Notifikasi Pesanan Baru</p>
        </div>
        
        <div class="content">
            <p>Halo,</p>
            <p>Berikut adalah rincian pesanan reservasi baru yang berhasil dibayarkan oleh klien melalui Midtrans.</p>
            
            <div class="invoice-card">
                <div class="invoice-title">Invoice No</div>
                <div class="invoice-number">{{ $payment->invoice_no }}</div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nama Klien</div>
                        <div class="info-value">{{ $event->client_name ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">WhatsApp</div>
                        <div class="info-value">{{ $clientWhatsapp ?? '-' }}</div>
                    </div>
                    <div class="info-item" style="grid-column: span 2;">
                        <div class="info-label">Email Klien</div>
                        <div class="info-value">{{ $clientEmail ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <h3 style="color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 16px;">Detail Acara & Paket</h3>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Detail</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Nama Acara</strong></td>
                        <td>{{ $event->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Acara</strong></td>
                        <td>{{ $event->date ? $event->date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pilihan Paket</strong></td>
                        <td>
                            @if($payment->package_id && $payment->package)
                                {{ $payment->package->name }}
                            @else
                                {{ $payment->custom_package_name ?? 'Paket Kustom (Custom)' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Metode Pembayaran</strong></td>
                        <td>DP (Down Payment) via Midtrans</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Dibayar</strong></td>
                        <td>
                            <div class="payment-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>

            @if($payment->notes)
                <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 4px; font-size: 14px; margin-bottom: 30px;">
                    <strong style="color: #b45309; display: block; margin-bottom: 4px;">Catatan:</strong>
                    {{ $payment->notes }}
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('management.payment.show', $payment->id) }}" class="btn" style="color: #ffffff;">Lihat Invoice di Panel</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh Sistem Wedding Organizer.</p>
            <p>&copy; {{ date('Y') }} Brilliant Wedding Organizer. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
