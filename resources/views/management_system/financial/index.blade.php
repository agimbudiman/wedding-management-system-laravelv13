@extends('layouts.management')

@section('title', 'Financial Statements')

@section('styles')
<style>
    .financial-header {
        font-size: 2.5rem;
        color: #999;
        font-weight: 300;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: #fff;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        transition: transform 0.3s;
    }

    .metric-card:hover {
        transform: translateY(-5px);
    }

    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1.2rem;
    }

    .icon-revenue {
        background-color: rgba(65, 97, 42, 0.1);
        color: #41612A;
    }

    .icon-unpaid {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .metric-info h5 {
        color: #888;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }

    .metric-info h2 {
        color: #333;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .toggle-eye {
        color: #888;
        padding: 4px 8px;
    }
    .toggle-eye:hover {
        color: #444;
    }

    .chart-card, .table-card {
        background: #fff;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .card-title-custom {
        font-size: 1.2rem;
        font-weight: 700;
        color: #444;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        border-bottom: 2px solid #f1f1f1;
        padding-bottom: 1rem;
    }

    /* Table Styles */
    .table-unpaid thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: none;
        padding: 15px;
    }

    .table-unpaid tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
        color: #555;
    }

    .badge-remaining {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    /* Premium Alignments for Select Filters and Search */
    .filter-select {
        height: 40px !important;
        border-radius: 30px !important;
        border: 1px solid #ddd !important;
        font-size: 0.9rem !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        background-color: #fff !important;
        color: #555 !important;
    }
    .filter-select:focus {
        border-color: #41612A !important;
        box-shadow: 0 0 0 0.2rem rgba(65, 97, 42, 0.15) !important;
    }

    .search-input-group {
        height: 40px !important;
        border: 1px solid #ddd !important;
        border-radius: 30px !important;
        background: #fff;
        display: flex;
        align-items: center;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .search-input-group:focus-within {
        border-color: #41612A !important;
        box-shadow: 0 0 0 0.2rem rgba(65, 97, 42, 0.15) !important;
    }
    .search-input-group .form-control {
        height: 100% !important;
        border: none !important;
        box-shadow: none !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        font-size: 0.95rem !important;
    }
    .search-input-group .input-group-text {
        background: transparent !important;
        border: none !important;
        padding-right: 5px !important;
        padding-left: 15px !important;
    }

    /* Modern Rounded Pagination Styles */
    .pagination-rounded .page-link {
        border-radius: 12px !important;
        margin: 0 4px;
        border: none;
        color: #4a5568;
        font-weight: 600;
        padding: 9px 18px;
        background: #f7fafc;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        cursor: pointer;
    }
    .pagination-rounded .page-item.active .page-link {
        background: #41612A !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(65, 97, 42, 0.3) !important;
    }
    .pagination-rounded .page-item.disabled .page-link {
        background: #edf2f7;
        color: #a0aec0;
        cursor: not-allowed;
    }
    .pagination-rounded .page-link:hover:not(.disabled) {
        background: #edf2f7;
        color: #2d3748;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <h1 class="financial-header">Financial Statements</h1>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-md-6">
            <div class="metric-card">
                <div class="metric-icon icon-revenue">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="metric-info w-100">
                    <h5>Total Collected Revenue</h5>
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="nominal-text" data-real="Rp {{ number_format($totalRevenue, 0, ',', '.') }}">Rp ********</h2>
                        <button class="btn btn-sm btn-light toggle-eye">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="metric-card">
                <div class="metric-icon icon-unpaid">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="metric-info w-100">
                    <h5>Total Remaining (Unpaid)</h5>
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="nominal-text" data-real="Rp {{ number_format($totalRemaining, 0, ',', '.') }}">Rp ********</h2>
                        <button class="btn btn-sm btn-light toggle-eye">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <h4 class="card-title-custom">Revenue Overview - {{ $currentYear }}</h4>
                <div style="height: 400px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Unpaid Events Table Row -->
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                    <h4 class="card-title-custom m-0 border-0 pb-0">Events with Unpaid Balance (Belum Lunas)</h4>
                    <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap w-100 w-md-auto justify-content-md-end">
                        <!-- Search Box -->
                        <div style="width: 240px;">
                            <div class="input-group search-input-group">
                                <span class="input-group-text text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="unpaidSearch" placeholder="Cari event, client...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-unpaid w-100" id="unpaidTable">
                        <thead>
                            <tr>
                                <th>EVENT NAME</th>
                                <th>CLIENT</th>
                                <th>TOTAL PACKAGE PRICE</th>
                                <th>PAID SO FAR</th>
                                <th>REMAINING BALANCE</th>
                                <th class="text-center" style="width: 150px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="unpaidTableBody">
                            <!-- Dynamic Rows Loaded via AJAX -->
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="d-block mt-2 small fw-semibold text-secondary">Sedang memuat data...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination and Summary Footer -->
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3 border-top pt-3">
                    <div class="text-muted small fw-semibold" id="paginationInfo">
                        Showing 0 to 0 of 0 entries
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-rounded m-0" id="paginationControls">
                            <!-- Dynamic Page Buttons -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Add Payment Modal -->
    <div class="modal fade" id="inlinePaymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="paymentForm" action="{{ route('management.payment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="event_id" id="modal_event_id">
                    <input type="hidden" name="package_id" id="modal_package_id">
                    <input type="hidden" name="custom_package_name" id="modal_custom_name">
                    <input type="hidden" name="custom_package_price" id="modal_custom_price">
                    
                    <div class="modal-header border-0 p-4 pb-2">
                        <h5 class="modal-title fw-bold">Add Payment (Pelunasan)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Event Name</label>
                            <input type="text" id="modal_event_name" class="form-control rounded-3 bg-light" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Client Name</label>
                            <input type="text" id="modal_client_name" class="form-control rounded-3 bg-light" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Payment Type</label>
                            <select name="payment_type" id="modal_payment_type" class="form-select rounded-3" required>
                                <option value="Partial">Partial Payment</option>
                                <option value="Final" selected>Final Payment (Pelunasan)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Remaining Balance (Sisa Pembayaran)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">Rp</span>
                                <input type="number" name="amount" id="modal_amount" class="form-control rounded-3 border-start-0" min="0" required>
                            </div>
                            <small class="text-muted mt-1 d-block" id="modal_remaining_help">Full pelunasan amount is pre-filled.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Proof of Payment (Opsional)</label>
                            <input type="file" name="proof_document" id="modal_proof_document" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="invalid-feedback text-danger small mt-1" id="proof_document_feedback" style="display: none;"></div>
                            <small class="text-muted mt-1 d-block">Format: JPG, JPEG, PNG, PDF (Maks. 2MB)</small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-muted">Notes (Opsional)</label>
                            <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="Catatan pembayaran..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill" style="background-color: #6D9C4C; border-color: #6D9C4C;">Save Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Success Notification from Session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // Error Notification from Session
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ $errors->first() }}",
                confirmButtonColor: '#41612A'
            });
        @endif

        // Handle Payment Form Submission with Confirmation
        const paymentForm = document.getElementById('paymentForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const eventName = document.getElementById('modal_event_name').value;
                const amount = document.getElementById('modal_amount').value;
                const paymentType = document.getElementById('modal_payment_type').value;
                
                const formattedAmount = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(amount);

                Swal.fire({
                    title: 'Konfirmasi Pembayaran',
                    html: `Apakah Anda yakin ingin menyimpan pembayaran untuk <b>${eventName}</b>?<br><br>` +
                           `Tipe: <b>${paymentType === 'Final' ? 'Pelunasan (Final)' : 'Cicilan (Partial)'}</b><br>` +
                           `Jumlah: <b class="text-success">${formattedAmount}</b>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6D9C4C',
                    cancelButtonColor: '#718096',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        paymentForm.submit();
                    }
                });
            });
        }

        // Toggle nominal visibility
        document.querySelectorAll('.toggle-eye').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const textEl = this.previousElementSibling;
                const isHidden = textEl.textContent.includes('*');
                
                if (isHidden) {
                    textEl.textContent = textEl.getAttribute('data-real');
                    icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                } else {
                    textEl.textContent = 'Rp ********';
                    icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                }
            });
        });

        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Data passed from controller
        const labels = {!! json_encode($chartLabels) !!};
        const dataValues = {!! json_encode($chartData) !!};

        // Create gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(65, 97, 42, 0.5)');   
        gradient.addColorStop(1, 'rgba(65, 97, 42, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: dataValues,
                    borderColor: '#41612A',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#41612A',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 14 },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f1f1',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000) + ' K';
                                }
                                return 'Rp ' + value;
                            },
                            font: { size: 11, color: '#888' }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 12, color: '#888' }
                        }
                    }
                }
            }
        });

        // ----------------------------------------------------
        // DYNAMIC UNPAID EVENTS TABLE LOGIC (AJAX)
        // ----------------------------------------------------
        let currentPage = 1;
        let searchQuery = '';
        let debounceTimer = null;
        const tableBody = document.getElementById('unpaidTableBody');
        const paginationInfo = document.getElementById('paginationInfo');
        const paginationControls = document.getElementById('paginationControls');

        // Helper to escape HTML and prevent XSS
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

        // Fetch unpaid events function
        function fetchUnpaidEvents(page = 1) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="d-block mt-2 small fw-semibold text-secondary">Sedang memuat data...</span>
                    </td>
                </tr>
            `;

            // Note the endpoint is '/management-system/financials' as per http://127.0.0.1:8000/management-system/financials
            const url = `/management-system/financials?page=${page}&search=${encodeURIComponent(searchQuery)}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                renderTable(data);
                renderPagination(data);
            })
            .catch(error => {
                console.error('Error fetching unpaid events:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5 text-danger">
                            <i class="bi bi-exclamation-triangle-fill fs-2 d-block mb-2"></i>
                            Gagal memuat data. Silakan coba lagi nanti.
                        </td>
                    </tr>
                `;
            });
        }

        // Render Table Rows
        function renderTable(data) {
            if (!data.data || data.data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle fs-1 d-block mb-3 text-success"></i>
                            Semua event telah lunas! Kerja bagus!
                        </td>
                    </tr>
                `;
                return;
            }

            let rowsHtml = '';
            data.data.forEach(item => {
                rowsHtml += `
                    <tr>
                        <td class="fw-bold text-dark">${escapeHtml(item.event_name)}</td>
                        <td>${escapeHtml(item.client_name)}</td>
                        <td class="fw-bold">${escapeHtml(item.formatted_package_price)}</td>
                        <td class="text-success fw-bold">${escapeHtml(item.formatted_total_paid)}</td>
                        <td><span class="badge-remaining">${escapeHtml(item.formatted_remaining)}</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-success open-payment-modal" 
                                data-bs-toggle="modal" 
                                data-bs-target="#inlinePaymentModal"
                                data-event-id="${item.event_id}" 
                                data-event-name="${escapeHtml(item.event_name)}" 
                                data-client-name="${escapeHtml(item.client_name)}" 
                                data-package-id="${item.package_id}"
                                data-custom-name="${escapeHtml(item.custom_package_name || '')}"
                                data-custom-price="${item.custom_package_price || 0}"
                                data-remaining="${item.remaining}"
                                style="border-radius: 6px;">
                                <i class="bi bi-plus"></i> Add Payment
                            </button>
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = rowsHtml;
        }

        // Render Pagination Controls
        function renderPagination(data) {
            if (data.total === 0) {
                paginationInfo.textContent = 'Showing 0 to 0 of 0 entries';
                paginationControls.innerHTML = '';
                return;
            }

            const from = data.from;
            const to = data.to;
            const total = data.total;
            paginationInfo.textContent = `Showing ${from} to ${to} of ${total} entries`;

            let paginationHtml = '';

            // Prev Button
            paginationHtml += `
                <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo; Prev</a>
                </li>
            `;

            // Page Numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, data.current_page - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(data.last_page, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <li class="page-item ${data.current_page === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Next Button
            paginationHtml += `
                <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page + 1}">Next &raquo;</a>
                </li>
            `;

            paginationControls.innerHTML = paginationHtml;
        }

        // Pagination Click Listener
        paginationControls.addEventListener('click', function(e) {
            e.preventDefault();
            const link = e.target.closest('.page-link');
            if (!link) return;

            const pageItem = link.closest('.page-item');
            if (pageItem.classList.contains('disabled') || pageItem.classList.contains('active')) return;

            const targetPage = parseInt(link.getAttribute('data-page'));
            if (targetPage) {
                currentPage = targetPage;
                fetchUnpaidEvents(currentPage);
            }
        });

        // Debounced Search Input Listener
        document.getElementById('unpaidSearch').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            searchQuery = this.value;
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                fetchUnpaidEvents(currentPage);
            }, 300);
        });
        
        // Populate Modal logic using event delegation on tableBody
        tableBody.addEventListener('click', function(e) {
            const btn = e.target.closest('.open-payment-modal');
            if (btn) {
                document.getElementById('modal_event_id').value = btn.getAttribute('data-event-id');
                document.getElementById('modal_event_name').value = btn.getAttribute('data-event-name');
                document.getElementById('modal_client_name').value = btn.getAttribute('data-client-name');
                document.getElementById('modal_package_id').value = btn.getAttribute('data-package-id');
                document.getElementById('modal_custom_name').value = btn.getAttribute('data-custom-name');
                document.getElementById('modal_custom_price').value = btn.getAttribute('data-custom-price');
                
                const remaining = btn.getAttribute('data-remaining');
                document.getElementById('modal_amount').value = remaining;
                
                // Store the full remaining amount as a dataset on the input for partial payment calculations
                document.getElementById('modal_amount').dataset.maxRemaining = remaining;
                
                // Default to Final
                document.getElementById('modal_payment_type').value = 'Final';
                document.getElementById('modal_amount').readOnly = true;
            }
        });
        
        // Handle Payment Type change in modal
        document.getElementById('modal_payment_type').addEventListener('change', function() {
            const amountInput = document.getElementById('modal_amount');
            const maxRemaining = amountInput.dataset.maxRemaining;
            
            if (this.value === 'Final') {
                amountInput.value = maxRemaining;
                amountInput.readOnly = true;
                amountInput.max = maxRemaining;
            } else {
                amountInput.readOnly = false;
                amountInput.max = maxRemaining; // Ensure they don't overpay
            }
        });
        
        // Validate Proof of Payment File Upload
        const proofDocumentInput = document.getElementById('modal_proof_document');
        const proofFeedback = document.getElementById('proof_document_feedback');
        
        if (proofDocumentInput) {
            proofDocumentInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) {
                    proofFeedback.style.display = 'none';
                    proofFeedback.textContent = '';
                    this.classList.remove('is-invalid');
                    return;
                }
                
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                let errorMsg = '';
                if (!allowedExtensions.includes(fileExtension)) {
                    errorMsg = 'Format file tidak sesuai! Hanya diperbolehkan file .jpg, .jpeg, .png, atau .pdf.';
                } else if (file.size > maxSize) {
                    errorMsg = 'Ukuran file terlalu besar! Maksimal ukuran file adalah 2MB.';
                }
                
                if (errorMsg) {
                    this.value = ''; // Clear file input
                    this.classList.add('is-invalid');
                    proofFeedback.textContent = errorMsg;
                    proofFeedback.style.display = 'block';
                    
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Tidak Sesuai',
                        text: errorMsg,
                        confirmButtonColor: '#6D9C4C'
                    });
                } else {
                    this.classList.remove('is-invalid');
                    proofFeedback.style.display = 'none';
                    proofFeedback.textContent = '';
                }
            });
        }

        // Initial Data Load
        fetchUnpaidEvents(currentPage);
    });
</script>
@endsection
