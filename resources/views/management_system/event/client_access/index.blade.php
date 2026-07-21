@extends('layouts.management')

@section('title', 'Client Access')

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
    
    .btn-view-access {
        background-color: var(--brilliant-green);
        color: white;
        border-radius: 12px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-view-access:hover {
        background-color: var(--brilliant-green-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(124, 163, 97, 0.3);
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
    
    /* Search Box styling */
    .search-container {
        max-width: 360px;
    }
    .search-input-group {
        border: 2px solid rgba(109, 156, 76, 0.18);
        border-radius: 50px;
        overflow: hidden;
        background: #fff;
        transition: all 0.3s ease;
    }
    .search-input-group:focus-within {
        border-color: #6D9C4C;
        box-shadow: 0 0 10px rgba(109, 156, 76, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="page-title m-0">Client Access</h1>
        <p class="text-muted">Manage QR code access for clients and guests for each event.</p>
    </div>

    <div class="event-table-card">
        <!-- Search and Info Header -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h6 class="m-0 text-muted fw-bold">Daftar Acara & Akses Klien</h6>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end justify-content-start mt-md-0 mt-3">
                <div class="search-container w-100">
                    <div class="input-group search-input-group">
                        <span class="input-group-text bg-white border-0 px-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="clientAccessSearchInput" class="form-control border-0 ps-0" placeholder="Cari nama event, klien, atau kategori..." style="box-shadow: none; font-size: 0.95rem;">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-event">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Category</th>
                        <th>Client Name</th>
                        <th>Event Date</th>
                        <th class="text-center" style="width: 180px;">Action</th>
                    </tr>
                </thead>
                <tbody id="clientAccessTableBody">
                    <!-- Dynamic Rows Loaded via AJAX -->
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let searchQuery = '';
        let debounceTimer = null;
        const tableBody = document.getElementById('clientAccessTableBody');
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
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="d-block mt-2 small fw-semibold text-secondary">Sedang memuat data...</span>
                    </td>
                </tr>
            `;

            const url = `/management-system/client-access?page=${page}&search=${encodeURIComponent(searchQuery)}`;

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
                console.error('Error fetching client access:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-danger">
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-3 text-secondary"></i>
                            Tidak ada event yang ditemukan.
                        </td>
                    </tr>
                `;
                return;
            }

            let rowsHtml = '';
            data.data.forEach(event => {
                rowsHtml += `
                    <tr>
                        <td class="fw-bold text-dark">${escapeHtml(event.name)}</td>
                        <td>
                            <span class="badge bg-light text-dark rounded-pill px-3 fw-semibold">${escapeHtml(event.category_name)}</span>
                        </td>
                        <td>${escapeHtml(event.client_name)}</td>
                        <td>${escapeHtml(event.formatted_date)}</td>
                        <td class="text-center">
                            <a href="${event.show_url}" class="btn-view-access">
                                <i class="bi bi-qr-code"></i> View Access
                            </a>
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
                fetchEvents(currentPage);
            }
        });

        // Debounced Search Input Listener
        document.getElementById('clientAccessSearchInput').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            searchQuery = this.value;
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                fetchEvents(currentPage);
            }, 300);
        });

        // Initial Data Load
        fetchEvents(currentPage);
    });
</script>
@endsection
