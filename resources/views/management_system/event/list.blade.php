@extends('layouts.management')

@section('title', $category->name . ' Events')

@section('styles')
<style>
    .event-table-card {
        background: #fff;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 25px;
    }
    .table-event {
        border-collapse: separate;
        border-spacing: 0 15px;
    }
    .table-event thead th {
        border: none;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 0 20px;
    }
    .table-event tbody tr {
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    .table-event tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    .table-event tbody td {
        border: none;
        padding: 20px;
        vertical-align: middle;
    }
    .table-event tbody td:first-child {
        border-radius: 15px 0 0 15px;
    }
    .table-event tbody td:last-child {
        border-radius: 0 15px 15px 0;
    }
    .status-badge {
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .status-upcoming { background: #EBF8FF; color: #3182CE; }
    .status-inprogress { background: #FEF3C7; color: #D97706; }
    .status-completed { background: #F0FFF4; color: #38A169; }
    .status-inqueue { background: #FEE2E2; color: #EF4444; }
    
    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        margin: 0 2px;
    }
    .btn-view { background: #EBF8FF; color: #3182CE; }
    .btn-edit { background: #F0FFF4; color: #38A169; }
    .btn-delete { background: #FFF5F5; color: #E53E3E; }
    
    .action-btn:hover {
        transform: translateY(-2px);
        filter: brightness(0.95);
    }

    .td-action {
        display: flex;
        align-items: center;      /* sejajar vertikal */
        justify-content: center;  /* sejajar horizontal */
        gap: 8px;                 /* jarak antar tombol */
        height: 100%;
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
        background: #6D9C4C !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(109, 156, 76, 0.3) !important;
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
        border-color: #6D9C4C !important;
        box-shadow: 0 0 0 0.2rem rgba(109, 156, 76, 0.15) !important;
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
        border-color: #6D9C4C !important;
        box-shadow: 0 0 0 0.2rem rgba(109, 156, 76, 0.15) !important;
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('management.event') }}" class="text-decoration-none text-muted">Event</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            <h1 class="page-title m-0">{{ $category->name }} Events</h1>
        </div>
        @if(auth()->guard('management')->user()->hasPermission('event-create'))
            <button class="btn btn-primary rounded-pill px-4 fw-bold" style="background-color: #6D9C4C; border-color: #6D9C4C;" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="bi bi-plus-lg me-2"></i> Add Event
            </button>
        @endif
    </div>

    <div class="event-table-card">
        <!-- Search and Filter Header (1 baris desktop) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <h6 class="m-0 text-muted fw-bold">List of Active Events</h6>
            <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap w-100 w-md-auto justify-content-md-end">
                <!-- Bulan & Tahun Filter -->
                <div style="width: 180px;">
                    <input type="month" id="filterMonthYear" class="form-control filter-select">
                </div>
                <!-- Status Filter -->
                <div style="width: 140px;">
                    <select id="filterStatus" class="form-select filter-select">
                        <option value="">Semua Status</option>
                        <option value="In Queue">In Queue</option>
                        <option value="Upcoming">Upcoming</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <!-- Search Box -->
                <div style="width: 240px;">
                    <div class="input-group search-input-group">
                        <span class="input-group-text text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="eventSearchInput" placeholder="Cari nama, client...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-event">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody id="eventTableBody">
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

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('management.event.store') }}" method="POST" id="addEventForm">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold" id="addEventModalTitle">Step 1: Event Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Step 1: Event Information -->
                <div id="step1">
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Event Name</label>
                                <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Johan & Grace Wedding" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Client Name</label>
                                <input type="text" name="client_name" class="form-control rounded-3" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Client Phone (WhatsApp)</label>
                                <input type="text" name="client_phone" class="form-control rounded-3" placeholder="e.g. 08123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Client Email</label>
                                <input type="email" name="client_email" class="form-control rounded-3" placeholder="e.g. client@email.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Groom Name (Pria)</label>
                                <input type="text" name="groom_name" class="form-control rounded-3" placeholder="Nama Mempelai Pria">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Bride Name (Wanita)</label>
                                <input type="text" name="bride_name" class="form-control rounded-3" placeholder="Nama Mempelai Wanita">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Event Date</label>
                                <input type="date" name="date" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Venue</label>
                                <input type="text" name="venue" class="form-control rounded-3" placeholder="Hotel / Hall Name" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Google Maps Link</label>
                                <input type="text" name="google_maps_link" class="form-control rounded-3" placeholder="https://goo.gl/maps/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Event Type</label>
                                <input type="text" name="type" class="form-control rounded-3" value="{{ $category->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Wedding Package</label>
                                <select name="package_id" id="package_select" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach($packages ?? [] as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->final_price }}">{{ $p->name }} - Rp {{ number_format($p->final_price, 0, ',', '.') }}</option>
                                    @endforeach
                                    <option value="custom" data-price="0">Paket Kustom (Custom)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="btnNext" class="btn btn-primary px-4 fw-bold rounded-pill" style="background-color: #6D9C4C; border-color: #6D9C4C;">Next</button>
                    </div>
                </div>

                <!-- Step 2: Payment Information -->
                <div id="step2" style="display: none;">
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Payment Type</label>
                                <select name="payment_type" id="payment_type" class="form-select rounded-3" required>
                                    <option value="dp">Down Payment (DP)</option>
                                    <option value="partial">Partial Payment</option>
                                    <option value="full">Full Payment</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Payment Amount (Rp)</label>
                                <input type="number" name="payment_amount" id="payment_amount" class="form-control rounded-3" min="0" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Event Notes (Opsional)</label>
                                <textarea name="event_notes" class="form-control rounded-3" rows="3" placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" id="btnBack" class="btn btn-light px-4 fw-bold rounded-pill">Back</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill" style="background-color: #6D9C4C; border-color: #6D9C4C;">Create Event & Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="editEventForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Event Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Client Name</label>
                            <input type="text" name="client_name" id="edit_client" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Client Phone (WhatsApp)</label>
                            <input type="text" name="client_phone" id="edit_phone" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Client Email</label>
                            <input type="email" name="client_email" id="edit_email" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Groom Name (Pria)</label>
                            <input type="text" name="groom_name" id="edit_groom" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Bride Name (Wanita)</label>
                            <input type="text" name="bride_name" id="edit_bride" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Event Date</label>
                            <input type="date" name="date" id="edit_date" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Venue</label>
                            <input type="text" name="venue" id="edit_venue" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Google Maps Link</label>
                            <input type="text" name="google_maps_link" id="edit_google_maps_link" class="form-control rounded-3" placeholder="https://goo.gl/maps/...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Event Type</label>
                            <input type="text" name="type" id="edit_type" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Status</label>
                            <select name="status" id="edit_status" class="form-select rounded-3">
                                <option value="In Queue">In Queue</option>
                                <option value="Upcoming">Upcoming</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill" style="background-color: #6D9C4C; border-color: #6D9C4C;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let searchQuery = '';
        let filterStatus = '';
        let filterMonthYear = '';
        let debounceTimer = null;
        const categorySlug = "{{ $category->slug }}";
        const tableBody = document.getElementById('eventTableBody');
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

        // Fetch events function
        function fetchEvents(page = 1) {
            // Show loading spinner
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

            const url = `/management-system/event/${categorySlug}?page=${page}&search=${encodeURIComponent(searchQuery)}&status=${encodeURIComponent(filterStatus)}&month_year=${encodeURIComponent(filterMonthYear)}`;

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
                console.error('Error fetching events:', error);
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
                            <i class="bi bi-calendar-x fs-1 d-block mb-3 text-secondary"></i>
                            Tidak ada event yang ditemukan.
                        </td>
                    </tr>
                `;
                return;
            }

            let rowsHtml = '';
            data.data.forEach(event => {
                const statusLower = event.status.toLowerCase().replace(/\s+/g, '');
                const statusClass = 'status-' + statusLower;

                rowsHtml += `
                    <tr>
                        <td class="fw-bold text-dark">${escapeHtml(event.name)}</td>
                        <td>${escapeHtml(event.client_name)}</td>
                        <td>${escapeHtml(event.formatted_date)}</td>
                        <td>${escapeHtml(event.venue)}</td>
                        <td>
                            <span class="status-badge ${statusClass}">${escapeHtml(event.status)}</span>
                        </td>
                        <td class="text-center td-action">
                            ${event.can_view ? `
                                <a href="${event.detail_url}" class="action-btn btn-view" title="View Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            ` : ''}
                            ${event.can_edit ? `
                                <button class="action-btn btn-edit edit-event-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editEventModal"
                                        data-id="${event.id}"
                                        data-name="${escapeHtml(event.name)}"
                                        data-client="${escapeHtml(event.client_name)}"
                                        data-date="${event.raw_date}"
                                        data-venue="${escapeHtml(event.venue)}"
                                        data-google-maps-link="${escapeHtml(event.google_maps_link || '')}"
                                        data-type="${escapeHtml(event.type)}"
                                        data-status="${escapeHtml(event.status)}"
                                        data-groom="${escapeHtml(event.groom_name || '')}"
                                        data-bride="${escapeHtml(event.bride_name || '')}"
                                        data-phone="${escapeHtml(event.client_phone || '')}"
                                        data-email="${escapeHtml(event.client_email || '')}"
                                        title="Edit Event">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            ` : ''}
                            ${event.can_delete ? `
                                <form action="${event.destroy_url}" method="POST" class="d-inline delete-event-form">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="action-btn btn-delete delete-btn" title="Delete Event">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            ` : ''}
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = rowsHtml;
        }

        // Render Pagination Controls
        function renderPagination(data) {
            // Update Summary Info
            if (data.total === 0) {
                paginationInfo.textContent = 'Showing 0 to 0 of 0 entries';
                paginationControls.innerHTML = '';
                return;
            }

            const from = data.from;
            const to = data.to;
            const total = data.total;
            paginationInfo.textContent = `Showing ${from} to ${to} of ${total} entries`;

            // Draw Pagination Links
            let paginationHtml = '';

            // Prev Button
            paginationHtml += `
                <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo; Prev</a>
                </li>
            `;

            // Page Numbers (dynamic and simplified range)
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
                fetchEvents(currentPage);
            }
        });

        // Filter Bulan & Tahun Listener
        document.getElementById('filterMonthYear').addEventListener('change', function() {
            filterMonthYear = this.value;
            currentPage = 1;
            fetchEvents(currentPage);
        });

        // Filter Status Listener
        document.getElementById('filterStatus').addEventListener('change', function() {
            filterStatus = this.value;
            currentPage = 1;
            fetchEvents(currentPage);
        });

        // Debounced Search Input Listener
        document.getElementById('eventSearchInput').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            searchQuery = this.value;
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                fetchEvents(currentPage);
            }, 300);
        });

        // Event Delegation for Table Row Actions (Edit and Delete)
        tableBody.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-event-btn');
            if (editBtn) {
                const id = editBtn.getAttribute('data-id');
                const name = editBtn.getAttribute('data-name');
                const client = editBtn.getAttribute('data-client');
                const date = editBtn.getAttribute('data-date');
                const venue = editBtn.getAttribute('data-venue');
                const googleMapsLink = editBtn.getAttribute('data-google-maps-link');
                const type = editBtn.getAttribute('data-type');
                const status = editBtn.getAttribute('data-status');
                const groom = editBtn.getAttribute('data-groom');
                const bride = editBtn.getAttribute('data-bride');
                const phone = editBtn.getAttribute('data-phone');
                const email = editBtn.getAttribute('data-email');

                const editForm = document.getElementById('editEventForm');
                editForm.action = "{{ route('management.event.update', ':id') }}".replace(':id', id);
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_client').value = client;
                document.getElementById('edit_phone').value = phone || '';
                document.getElementById('edit_email').value = email || '';
                document.getElementById('edit_date').value = date;
                document.getElementById('edit_venue').value = venue;
                document.getElementById('edit_google_maps_link').value = googleMapsLink || '';
                document.getElementById('edit_type').value = type;
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_groom').value = groom;
                document.getElementById('edit_bride').value = bride;
                return;
            }

            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                const form = deleteBtn.closest('.delete-event-form');
                Swal.fire({
                    title: 'Delete Event?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#E53E3E',
                    cancelButtonColor: '#718096',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        // Initial Data Load
        fetchEvents(1);

        // Success Notification
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        // Stepper Logic for Add Event Modal
        const btnNext = document.getElementById('btnNext');
        const btnBack = document.getElementById('btnBack');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const modalTitle = document.getElementById('addEventModalTitle');
        const packageSelect = document.getElementById('package_select');
        const paymentTypeSelect = document.getElementById('payment_type');
        const paymentAmountInput = document.getElementById('payment_amount');
        const dpNominal = {{ $dpNominal ?? 5000000 }};
        
        if (btnNext) {
            btnNext.addEventListener('click', function() {
                // Basic validation for Step 1
                let isValid = true;
                const reqInputs = document.querySelectorAll('#step1 [required]');
                reqInputs.forEach(input => {
                    if (!input.value) {
                        input.style.borderColor = '#E53E3E';
                        isValid = false;
                    } else {
                        input.style.borderColor = '';
                    }
                });

                if (isValid) {
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                    modalTitle.textContent = 'Step 2: Payment Information';
                    calculatePayment();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan isi semua field yang diwajibkan di Step 1.',
                    });
                }
            });
        }

        if (btnBack) {
            btnBack.addEventListener('click', function() {
                step2.style.display = 'none';
                step1.style.display = 'block';
                modalTitle.textContent = 'Step 1: Event Information';
            });
        }

        function calculatePayment() {
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const type = paymentTypeSelect.value;
            const packagePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            if (type === 'dp') {
                paymentAmountInput.value = dpNominal;
            } else if (type === 'full') {
                paymentAmountInput.value = packagePrice;
            } else if (type === 'partial') {
                if (!paymentAmountInput.value) {
                    paymentAmountInput.value = dpNominal;
                }
            }
        }

        if (packageSelect) packageSelect.addEventListener('change', calculatePayment);
        if (paymentTypeSelect) paymentTypeSelect.addEventListener('change', calculatePayment);

        // Auto-show Add Modal if parameter exists
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('showModal')) {
            const addEventModal = new bootstrap.Modal(document.getElementById('addEventModal'));
            addEventModal.show();

            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
    });
</script>
@endsection