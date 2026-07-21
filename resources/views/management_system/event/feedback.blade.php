@extends('layouts.management')

@section('title', 'Client Feedback')

@section('styles')
    <style>
        .feedback-table-container {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .rating-stars {
            color: #ffc107;
            white-space: nowrap;
        }

        .rating-stars i {
            margin-right: 2px;
        }

        .event-badge {
            background-color: var(--brilliant-green-light);
            color: var(--brilliant-green-dark);
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .testimony-text {
            color: #4a5568;
            font-size: 0.95rem;
            line-height: 1.5;
            max-width: 400px;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-top: none;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1.2rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }

        .pagination-container {
            margin-top: 2rem;
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title m-0">Feedback</h1>
                <p class="text-muted">Daftar testimoni dan masukan dari klien untuk setiap event.</p>
            </div>
        </div>

        <div class="feedback-table-container">
            <!-- Search and Info Header -->
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 text-muted fw-bold">Klien Testimonial & Rating</h6>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-start mt-md-0 mt-3">
                    <div class="search-container w-100">
                        <div class="input-group search-input-group">
                            <span class="input-group-text bg-white border-0 px-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="feedbackSearchInput" class="form-control border-0 ps-0" placeholder="Cari testimoni, klien, rating, event..." style="box-shadow: none; font-size: 0.95rem;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="8%">No</th>
                            <th width="22%">Event</th>
                            <th width="20%">Rating</th>
                            <th width="35%">Testimoni</th>
                            <th width="15%">Tanggal Input</th>
                        </tr>
                    </thead>
                    <tbody id="feedbackTableBody">
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
        const tableBody = document.getElementById('feedbackTableBody');
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

        // Fetch feedback function
        function fetchFeedback(page = 1) {
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

            const url = `/management-system/event/feedback?page=${page}&search=${encodeURIComponent(searchQuery)}`;

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
                console.error('Error fetching feedback:', error);
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
                        <td colspan="5">
                            <div class="empty-state py-5">
                                <i class="bi bi-chat-left-text text-secondary fs-1 d-block mb-3"></i>
                                <h5 class="text-muted">Tidak ada feedback ditemukan</h5>
                                <p class="text-secondary small">Belum ada testimoni klien yang cocok dengan pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            let rowsHtml = '';
            data.data.forEach((testimonial, index) => {
                const globalIndex = (data.current_page - 1) * data.per_page + (index + 1);
                
                // Build Rating Stars
                let starsHtml = '';
                const rating = parseInt(testimonial.rating);
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<i class="bi bi-star-fill text-warning"></i>';
                    } else {
                        starsHtml += '<i class="bi bi-star text-muted"></i>';
                    }
                }

                rowsHtml += `
                    <tr>
                        <td class="fw-semibold text-secondary">${globalIndex}</td>
                        <td>
                            <div class="event-badge text-truncate" style="max-width: 200px;">
                                ${escapeHtml(testimonial.event_name)}
                            </div>
                            <div class="small text-muted mt-1">Klien: ${escapeHtml(testimonial.client_name)}</div>
                        </td>
                        <td>
                            <div class="rating-stars">
                                ${starsHtml}
                                <span class="ms-1 text-dark fw-bold">(${rating}/5)</span>
                            </div>
                        </td>
                        <td>
                            <div class="testimony-text italic">
                                "${escapeHtml(testimonial.testimony)}"
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-secondary small">
                                ${escapeHtml(testimonial.formatted_date)}
                            </div>
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
                fetchFeedback(currentPage);
            }
        });

        // Debounced Search Input Listener
        document.getElementById('feedbackSearchInput').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            searchQuery = this.value;
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                fetchFeedback(currentPage);
            }, 300);
        });

        // Initial Data Load
        fetchFeedback(currentPage);
    });
</script>
@endsection