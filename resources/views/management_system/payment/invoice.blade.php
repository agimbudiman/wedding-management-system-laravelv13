@extends('layouts.management')

@section('title', 'Payment Invoice')

@section('styles')
<style>
    .payment-header {
        font-size: 2.5rem;
        color: #999;
        font-weight: 300;
        margin-bottom: 1.5rem;
    }

    .invoice-card {
        background: #fff;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 0;
        overflow: hidden;
    }

    .invoice-header-title {
        background-color: #fcfcfc;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #eee;
        font-weight: 700;
        text-transform: uppercase;
        color: #555;
        font-size: 1rem;
    }

    .invoice-body {
        padding: 2.5rem;
    }

    .company-logo {
        max-height: 50px;
        margin-bottom: 2rem;
    }

    .invoice-number {
        font-weight: 700;
        font-size: 1.1rem;
        text-align: right;
        color: #333;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .info-left p, .info-right p {
        margin-bottom: 0.3rem;
        color: #555;
        font-weight: 500;
    }

    .info-right {
        text-align: right;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
        border: 1px solid #ddd;
    }

    .invoice-table th {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        padding: 12px 15px;
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .invoice-table td {
        border: 1px solid #ddd;
        padding: 12px 15px;
        color: #444;
    }

    .invoice-notes {
        margin-top: 1rem;
        margin-bottom: 3rem;
        color: #666;
    }

    .invoice-footer {
        color: #999;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    .invoice-footer p {
        margin-bottom: 0.2rem;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .invoice-card, .invoice-card * {
            visibility: visible;
        }
        .invoice-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
        }
        .top-bar, .sidebar, .payment-header, .btn-back {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="payment-header mb-0">Payment - Invoice</h1>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-back" style="border-radius: 8px; font-weight: 500;">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card invoice-card" id="invoiceArea">
        <div class="invoice-header-title">
            PAYMENT INVOICE
            <div class="float-end">
                <a href="{{ route('management.payment.export-pdf', $payment->invoice_no) }}" class="btn btn-sm btn-primary btn-back px-3" style="background-color: #41612A; border-color: #41612A;">
                    <i class="bi bi-file-earmark-pdf"></i> Download PDF
                </a>
                <button class="btn btn-sm btn-outline-secondary btn-back px-3" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Browser
                </button>
            </div>
        </div>

        <div class="invoice-body">
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Brilliant Logo" class="company-logo">
                </div>
                <div class="col-md-6 invoice-number d-flex justify-content-end align-items-center">
                    {{ $payment->invoice_no }}
                </div>
            </div>

            <div class="info-grid">
                <div class="info-left">
                    <p>EVENT NAME &nbsp;: {{ $payment->event->name ?? 'N/A' }}</p>
                    <p>CLIENT NAME &nbsp;&nbsp;: {{ $payment->event->client_name ?? 'N/A' }}</p>
                    <p>PAYMENT TYPE &nbsp;: {{ $payment->payment_type }}</p>
                    <p>PACKAGE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $payment->package_id ? $payment->package->name : $payment->custom_package_name }}</p>
                </div>
                <div class="info-right">
                    <p>ISSUE DATE : {{ \Carbon\Carbon::parse($payment->created_at)->format('d / m / Y') }}</p>
                    <p>DUE DATE &nbsp;&nbsp;&nbsp;: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d / m / Y') }}</p>
                    <p>STATUS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $payment->status }}</p>
                </div>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 60%">DESCRIPTION</th>
                        <th style="width: 40%">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PACKAGE PRICE</td>
                        <td>Rp {{ number_format($packagePrice, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="text-transform: uppercase;">{{ $payment->payment_type }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>TOTAL PAID (INCLUDING THIS)</td>
                        <td>Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>REMAINING BALANCE</td>
                        <td class="fw-bold">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="invoice-notes">
                <strong>Notes :</strong><br>
                {!! nl2br(e($payment->notes ?: '-')) !!}
            </div>

            <div class="invoice-footer">
                <p>{{ \App\Models\WebsiteSetting::get('footer_brand', 'CV. Brilliant Bertaqwa Berdaya') }}</p>
                <p>Telephone: {{ \App\Models\WebsiteSetting::get('contact_phone', '021 323 842') }} | WhatsApp: {{ \App\Models\WebsiteSetting::get('contact_whatsapp', '-') }}</p>
                <p>Email: {{ \App\Models\WebsiteSetting::get('contact_email', 'brilliant@mail.com') }}</p>
                <p>Address: {{ \App\Models\WebsiteSetting::get('contact_address', 'Your Company Address Here') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto print if ?print=true is in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('print')) {
            window.print();
        }
    });
</script>
@endsection
