<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $payment->invoice_no }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 30px;
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .logo-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .logo {
            max-height: 50px;
        }
        .invoice-no {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding-bottom: 5px;
        }
        .info-left {
            width: 60%;
        }
        .info-right {
            width: 40%;
            text-align: right;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }
        .invoice-table th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 12px 15px;
            color: #555;
            font-weight: 600;
            text-align: left;
            text-transform: uppercase;
            font-size: 12px;
        }
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            color: #444;
        }
        .notes-section {
            margin-bottom: 40px;
        }
        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .footer {
            color: #999;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .footer p {
            margin: 0 0 3px 0;
        }
        .amount-col {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header-title">Payment Invoice</div>

    <table class="logo-section">
        <tr>
            <td>
                <img src="{{ public_path('assets/Brilliant_Logo.png') }}" alt="Logo" class="logo">
            </td>
            <td class="invoice-no">
                {{ $payment->invoice_no }}
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-left">
                <p>EVENT NAME &nbsp;: {{ $payment->event->name ?? 'N/A' }}</p>
                <p>CLIENT NAME &nbsp;&nbsp;: {{ $payment->event->client_name ?? 'N/A' }}</p>
                <p>PAYMENT TYPE &nbsp;: {{ $payment->payment_type }}</p>
                <p>PACKAGE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $payment->package_id ? $payment->package->name : $payment->custom_package_name }}</p>
            </td>
            <td class="info-right">
                <p>ISSUE DATE : {{ \Carbon\Carbon::parse($payment->created_at)->format('d / m / Y') }}</p>
                <p>DUE DATE &nbsp;&nbsp;&nbsp;: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d / m / Y') }}</p>
                <p>STATUS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $payment->status }}</p>
            </td>
        </tr>
    </table>

    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width: 60%">DESCRIPTION</th>
                <th style="width: 40%; text-align: right;">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PACKAGE PRICE</td>
                <td class="amount-col">Rp {{ number_format($packagePrice, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-transform: uppercase;">{{ $payment->payment_type }}</td>
                <td class="amount-col">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>TOTAL PAID (INCLUDING THIS)</td>
                <td class="amount-col">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="fw-bold">REMAINING BALANCE</td>
                <td class="amount-col fw-bold">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="notes-section">
        <div class="notes-title">Notes :</div>
        <div>{!! nl2br(e($payment->notes ?: '-')) !!}</div>
    </div>

    <div class="footer">
        <p>{{ \App\Models\WebsiteSetting::get('footer_brand', 'CV. Brilliant Bertaqwa Berdaya') }}</p>
        <p>Telephone: {{ \App\Models\WebsiteSetting::get('contact_phone', '021 323 842') }} | WhatsApp: {{ \App\Models\WebsiteSetting::get('contact_whatsapp', '-') }}</p>
        <p>Email: {{ \App\Models\WebsiteSetting::get('contact_email', 'brilliant@mail.com') }}</p>
        <p>Address: {{ \App\Models\WebsiteSetting::get('contact_address', 'Your Company Address Here') }}</p>
    </div>
</body>
</html>
