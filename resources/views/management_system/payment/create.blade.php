@extends('layouts.management')

@section('title', 'Add Payment')

@section('styles')
<style>
    .payment-header {
        font-size: 2.5rem;
        color: #999;
        font-weight: 300;
        margin-bottom: 1.5rem;
    }

    .card-payment {
        background: #fff;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .card-header-custom {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        text-transform: uppercase;
        color: #555;
        font-size: 1rem;
    }

    .card-body-custom {
        padding: 2rem;
    }

    .form-group-custom {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .form-group-custom label {
        width: 150px;
        color: #666;
        margin: 0;
        font-weight: 500;
        padding-top: 8px;
    }

    .form-group-custom .control-wrapper {
        flex: 1;
        max-width: 450px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 15px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #41612A;
        box-shadow: 0 0 0 0.2rem rgba(65, 97, 42, 0.15);
    }
    
    .btn-save {
        background-color: #5e7d48;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save:hover {
        background-color: #4a6339;
        color: #fff;
    }

    .custom-package-fields {
        display: none;
        margin-top: 10px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #eee;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="payment-header mb-0">Payment - Add Payment</h1>
        <a href="{{ route('management.payment.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px; font-weight: 500;">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card card-payment">
        <form action="{{ route('management.payment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card-header-custom">
                <h5>Payment Form</h5>
            </div>

            <div class="card-body-custom">
                <!-- Event Selection -->
                <div class="form-group-custom">
                    <label>Event Category <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <select name="event_id" class="form-select @error('event_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Select Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ (old('event_id') ?? ($selectedEventId ?? '')) == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} - {{ $event->client_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Payment Type -->
                <div class="form-group-custom">
                    <label>Payment Type <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <select name="payment_type" id="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                            <option value="" disabled selected>Select Payment Type</option>
                            <option value="DP" {{ old('payment_type') == 'DP' ? 'selected' : '' }}>DP</option>
                            <option value="Partial" {{ old('payment_type') == 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option value="Final" {{ old('payment_type') == 'Final' ? 'selected' : '' }}>Final</option>
                        </select>
                        @error('payment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Package Source -->
                <div class="form-group-custom">
                    <label>Package <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        @php
                            $defaultPackageId = old('package_id', isset($previousPayment) ? ($previousPayment->package_id ?? 'custom') : '');
                        @endphp
                        <select name="package_id" id="package_id" class="form-select @error('package_id') is-invalid @enderror" required>
                            <option value="" disabled {{ $defaultPackageId === '' ? 'selected' : '' }}>Select Package</option>
                            <option value="custom" {{ $defaultPackageId === 'custom' ? 'selected' : '' }} class="fw-bold">-- Custom Package --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" data-price="{{ round($pkg->final_price) }}" {{ $defaultPackageId == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        
                        @php
                            $defaultCustomPackageName = old('custom_package_name', isset($previousPayment) ? $previousPayment->custom_package_name : '');
                        @endphp
                        <div class="mt-2" id="custom_package_wrapper" style="display: none;">
                            <input type="text" name="custom_package_name" id="custom_package_name" class="form-control" placeholder="Input Custom Package Name" value="{{ $defaultCustomPackageName }}">
                            @error('custom_package_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Package Price (Amount) -->
                <div class="form-group-custom">
                    <label>Package Amount <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        @php
                            $defaultPackagePrice = old('custom_package_price', isset($previousPayment) ? round($previousPayment->custom_package_price) : '');
                        @endphp
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="custom_package_price" id="custom_package_price" class="form-control @error('custom_package_price') is-invalid @enderror" value="{{ $defaultPackagePrice }}" required>
                        </div>
                        <div class="form-text text-muted">Total amount of the selected package.</div>
                        @error('custom_package_price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Pay Amount (DP/Partial) -->
                <div class="form-group-custom" id="payment_amount_wrapper" style="display: none;">
                    <label>Pay Amount <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="amount" id="payment_amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}">
                        </div>
                        <div class="form-text text-muted">Amount to pay right now (Bayar berapa dulu).</div>
                        @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Payment Date -->
                <div class="form-group-custom">
                    <label>Payment Date <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group-custom">
                    <label>Notes <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Proof Document -->
                <div class="form-group-custom">
                    <label>Proof Document <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <div class="input-group">
                            <input type="file" name="proof_document" class="form-control @error('proof_document') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        <div class="form-text">Optional. Max 2MB (JPG, PNG, PDF)</div>
                        @error('proof_document')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mt-4 ms-3 ps-1" style="margin-left: 165px !important;">
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-plus-circle"></i> Generate Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentType = document.getElementById('payment_type');
        const paymentAmountWrapper = document.getElementById('payment_amount_wrapper');
        const paymentAmountInput = document.getElementById('payment_amount');

        const packageIdSelect = document.getElementById('package_id');
        const customPackageWrapper = document.getElementById('custom_package_wrapper');
        const customPackageName = document.getElementById('custom_package_name');
        const packagePriceInput = document.getElementById('custom_package_price');

        function togglePaymentType() {
            if (paymentType.value === 'DP' || paymentType.value === 'Partial') {
                paymentAmountWrapper.style.display = 'flex';
                paymentAmountInput.required = true;
            } else {
                paymentAmountWrapper.style.display = 'none';
                paymentAmountInput.required = false;
            }
        }

        function togglePackage(isInit = false) {
            if (packageIdSelect.value === 'custom') {
                customPackageWrapper.style.display = 'block';
                customPackageName.required = true;
                
                packagePriceInput.readOnly = false;
                if (!isInit && !packagePriceInput.value) packagePriceInput.value = '';
            } else if (packageIdSelect.value !== '') {
                customPackageWrapper.style.display = 'none';
                customPackageName.required = false;

                // Auto fill package price
                const selectedOption = packageIdSelect.options[packageIdSelect.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (!isInit && price) {
                    packagePriceInput.value = price;
                }
                packagePriceInput.readOnly = true; 
            } else {
                customPackageWrapper.style.display = 'none';
            }
        }

        paymentType.addEventListener('change', () => togglePaymentType());
        packageIdSelect.addEventListener('change', () => togglePackage(false));

        togglePaymentType();
        togglePackage(true);
    });
</script>
@endsection
