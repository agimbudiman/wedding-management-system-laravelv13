@extends('layouts.management')

@section('title', 'Payment')

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
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .btn-add-payment {
            height: 40px;
            padding: 0 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: #41612A;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-add-payment:hover {
            background-color: #2d441d;
            color: #fff;
            transform: translateY(-2px);
        }

        .table-payment thead th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            border: none;
            padding: 15px;
        }

        .table-payment tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f1;
            color: #555;
        }

        .btn-view,
        .btn-print {
            border: none;
            border-radius: 6px;
            padding: 4px 15px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin: 0 2px;
            transition: opacity 0.2s;
        }

        .btn-view {
            background-color: #6b82a3;
        }

        .btn-view:hover {
            background-color: #566c89;
            color: #fff;
        }

        .btn-print {
            background-color: #6b82a3;
        }

        .btn-print:hover {
            background-color: #566c89;
            color: #fff;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-paid {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
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
        <h1 class="payment-header">Payment</h1>

        <div class="card-payment">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <!-- <a href="{{ route('management.payment.create') }}" class="btn-add-payment">
                    <i class="bi bi-plus-circle"></i> Add Payment
                </a> -->
                <div
                    class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap w-100 w-md-auto justify-content-md-end">
                    <!-- Type Filter -->
                    <div style="width: 140px;">
                        <select id="filterType" class="form-select filter-select">
                            <option value="">Semua Tipe</option>
                            <option value="DP">DP</option>
                            <option value="Partial">Partial</option>
                            <option value="Final">Final</option>
                        </select>
                    </div>
                    <!-- Search Box -->
                    <div style="width: 240px;">
                        <div class="input-group search-input-group">
                            <span class="input-group-text text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="paymentSearch" placeholder="Cari invoice, event...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-payment" id="paymentTable">
                    <thead>
                        <tr>
                            <th>INVOICE NO</th>
                            <th>EVENT NAME</th>
                            <th>CLIENT NAME</th>
                            <th>TYPE</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>PAYMENT DATE</th>
                            <th class="text-center" style="width: 180px;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="paymentTableBody">
                        <!-- Dynamic Rows Loaded via AJAX -->
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
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

    <!-- View Modal -->
    <div class="modal fade" id="viewOptionsModal" tabindex="-1" aria-labelledby="viewOptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="viewOptionsModalLabel">Opsi Dokumen Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="text-muted mb-4">Silakan pilih dokumen yang ingin Anda lihat untuk pembayaran ini.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" id="btnViewInvoice" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="background-color: #41612A; border-color: #41612A;">
                            <i class="bi bi-receipt"></i> Lihat Invoice
                        </a>
                        <a href="#" id="btnViewProof" target="_blank" class="btn btn-outline-secondary px-4 py-2 d-flex align-items-center gap-2" style="display: none;">
                            <i class="bi bi-file-earmark-image"></i> Bukti Transfer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            let searchQuery = '';
            let filterType = '';
            let debounceTimer = null;
            const tableBody = document.getElementById('paymentTableBody');
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

            // Fetch payments function
            function fetchPayments(page = 1) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="d-block mt-2 small fw-semibold text-secondary">Sedang memuat data...</span>
                        </td>
                    </tr>
                `;

                const url = `/management-system/payment?page=${page}&search=${encodeURIComponent(searchQuery)}&type=${encodeURIComponent(filterType)}`;

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
                        console.error('Error fetching payments:', error);
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-5 text-danger">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-wallet2 fs-1 d-block mb-3 text-secondary"></i>
                                Tidak ada pembayaran yang ditemukan.
                            </td>
                        </tr>
                    `;
                    return;
                }

                let rowsHtml = '';
                data.data.forEach(payment => {
                    let statusBadgeHtml = '';
                    if (payment.status.toLowerCase() === 'paid') {
                        statusBadgeHtml = `<span class="status-badge status-paid">Paid</span>`;
                    } else {
                        statusBadgeHtml = `<span class="status-badge status-pending">${escapeHtml(payment.status)}</span>`;
                    }

                    rowsHtml += `
                        <tr>
                            <td class="fw-bold text-dark">${escapeHtml(payment.invoice_no)}</td>
                            <td>${escapeHtml(payment.event ? payment.event.name : '-')}</td>
                            <td>${escapeHtml(payment.event ? payment.event.client_name : '-')}</td>
                            <td>
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill fw-bold">${escapeHtml(payment.payment_type)}</span>
                            </td>
                            <td class="fw-bold text-success">${escapeHtml(payment.formatted_amount)}</td>
                            <td>${statusBadgeHtml}</td>
                            <td>${escapeHtml(payment.formatted_date)}</td>
                            <td class="text-center">
                                <button type="button" onclick="openViewModal('${payment.show_url}', '${payment.proof_url || ''}')" class="btn-view">View</button>
                                <a href="${payment.download_url}" class="btn-print" style="line-height: 1.5; padding: 5px 15px;">Print</a>
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
            paginationControls.addEventListener('click', function (e) {
                e.preventDefault();
                const link = e.target.closest('.page-link');
                if (!link) return;

                const pageItem = link.closest('.page-item');
                if (pageItem.classList.contains('disabled') || pageItem.classList.contains('active')) return;

                const targetPage = parseInt(link.getAttribute('data-page'));
                if (targetPage) {
                    currentPage = targetPage;
                    fetchPayments(currentPage);
                }
            });

            // Filter change listener
            document.getElementById('filterType').addEventListener('change', function () {
                filterType = this.value;
                currentPage = 1;
                fetchPayments(currentPage);
            });

            // Debounced Search Input Listener
            document.getElementById('paymentSearch').addEventListener('input', function () {
                clearTimeout(debounceTimer);
                searchQuery = this.value;
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    fetchPayments(currentPage);
                }, 300);
            });

            // Modal Function
            window.openViewModal = function(invoiceUrl, proofUrl) {
                const btnViewInvoice = document.getElementById('btnViewInvoice');
                let btnViewProof = document.getElementById('btnViewProof');

                btnViewInvoice.href = invoiceUrl;

                // Selalu tampilkan tombol bukti
                btnViewProof.style.display = 'inline-flex';

                // Clone node untuk mereset event listener yang lama (mencegah bug bukti nyangkut)
                const newBtnViewProof = btnViewProof.cloneNode(true);
                btnViewProof.parentNode.replaceChild(newBtnViewProof, btnViewProof);
                btnViewProof = newBtnViewProof;

                if (proofUrl && proofUrl !== 'null' && proofUrl !== '') {
                    btnViewProof.href = proofUrl;
                    btnViewProof.target = '_blank';
                } else {
                    btnViewProof.href = 'javascript:void(0)';
                    btnViewProof.removeAttribute('target');
                    btnViewProof.addEventListener('click', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Ada Bukti',
                            text: 'Pembayaran ini tidak memiliki bukti yang diunggah.',
                            confirmButtonColor: '#41612A'
                        });
                    });
                }

                const modal = new bootstrap.Modal(document.getElementById('viewOptionsModal'));
                modal.show();
            };

            // Initial Data Load
            fetchPayments(currentPage);
        });
    </script>
@endsection