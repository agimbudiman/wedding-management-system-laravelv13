@extends('layouts.management')

@section('title', 'Crew Management')

@section('styles')
    <style>
        .crew-header {
            font-size: 2.5rem;
            color: #999;
            font-weight: 300;
            margin-bottom: 1.5rem;
        }

        .card-crew {
            background: #fff;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .btn-add-crew {
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

        .btn-add-crew:hover {
            background-color: #2d441d;
            color: #fff;
            transform: translateY(-2px);
        }

        .table-crew thead th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            border: none;
            padding: 15px;
        }

        .table-crew tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f1;
            color: #555;
        }

        .crew-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #eee;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-available .status-dot {
            background-color: #28a745;
        }

        .status-busy .status-dot {
            background-color: #dc3545;
        }

        .status-off .status-dot {
            background-color: #6c757d;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            border: none;
            transition: all 0.2s;
            margin: 0 2px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #41612A;
        }

        .btn-edit:hover {
            background-color: #2d441d;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-show {
            background-color: #17a2b8;
        }

        .btn-show:hover {
            background-color: #138496;
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

        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #f1f1f1;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #41612A;
            box-shadow: 0 0 0 0.2rem rgba(65, 97, 42, 0.15);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid p-4">
        <h1 class="crew-header">Crew</h1>

        <div class="card-crew">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <button class="btn-add-crew" data-bs-toggle="modal" data-bs-target="#addCrewModal">
                    <i class="bi bi-plus-circle"></i> Add Crew
                </button>
                <div
                    class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap w-100 w-md-auto justify-content-md-end">
                    <!-- Status Filter -->
                    <div style="width: 140px;">
                        <select id="filterStatus" class="form-select filter-select">
                            <option value="">Semua Status</option>
                            <option value="Available">Available</option>
                            <option value="Busy">Busy</option>
                            <option value="Off">Off</option>
                        </select>
                    </div>

                    <!-- Search Box -->
                    <div style="width: 240px;">
                        <div class="input-group search-input-group">
                            <span class="input-group-text text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="crewSearch" placeholder="Cari nama, email...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-crew">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Photo</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Assigned Event</th>
                            <th class="text-center" style="width: 160px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="crewTableBody">
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

    <!-- Add Crew Modal -->
    <div class="modal fade" id="addCrewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Crew Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('management.crew.store') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min 8 characters"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="e.g. 08123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Birth Date</label>
                                <input type="date" name="birth_date" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" class="form-control" rows="2"
                                    placeholder="Enter full address"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Photo Profile</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                                <div class="form-text">Max size 2MB. Format: JPG, PNG, WEBP.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-add-crew">Save Crew</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Crew Modal -->
    <div class="modal fade" id="editCrewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Crew Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCrewForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Password (Leave blank if not changing)</label>
                                <input type="password" name="password" class="form-control" placeholder="Min 8 characters">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="Available">Available</option>
                                    <option value="Busy">Busy</option>
                                    <option value="Off">Off</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" id="edit_gender" class="form-select" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone_number" id="edit_phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Birth Date</label>
                                <input type="date" name="birth_date" id="edit_birth" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Address</label>
                                <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Photo Profile</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                                <div class="form-text">Max size 2MB. Leave blank if not changing.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-add-crew">Update Crew</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            let searchQuery = '';
            let filterStatus = '';
            let debounceTimer = null;
            const tableBody = document.getElementById('crewTableBody');
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

            // Fetch crews function
            function fetchCrews(page = 1) {
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

                const url = `/management-system/crew?page=${page}&search=${encodeURIComponent(searchQuery)}&status=${encodeURIComponent(filterStatus)}`;

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
                        console.error('Error fetching crews:', error);
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
                                <i class="bi bi-person-x fs-1 d-block mb-3 text-secondary"></i>
                                Tidak ada kru yang ditemukan.
                            </td>
                        </tr>
                    `;
                    return;
                }

                let rowsHtml = '';
                data.data.forEach(crew => {
                    // Determine Avatar
                    let avatarHtml = '';
                    if (crew.avatar) {
                        avatarHtml = `<img src="${crew.avatar_url}" alt="${escapeHtml(crew.name)}" class="crew-avatar">`;
                    } else {
                        avatarHtml = `
                            <div class="crew-avatar d-flex align-items-center justify-content-center bg-light">
                                <i class="bi bi-person text-muted fs-4"></i>
                            </div>
                        `;
                    }

                    // Determine Assigned Event Count Badge
                    let eventBadgeHtml = '<span class="text-muted">N/a</span>';
                    if (crew.events_count > 0) {
                        const pluralText = crew.events_count > 1 ? 'Events' : 'Event';
                        eventBadgeHtml = `
                            <span class="badge text-white" style="background-color: #41612A; padding: 0.5em 0.8em; border-radius: 20px;">
                                ${crew.events_count} ${pluralText}
                            </span>
                        `;
                    }

                    // Build action forms
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const deleteActionUrl = `/management-system/crew/${crew.id}`;

                    rowsHtml += `
                        <tr>
                            <td>${avatarHtml}</td>
                            <td>
                                <div class="fw-bold text-dark">${escapeHtml(crew.name)}</div>
                                <div class="small text-muted">${escapeHtml(crew.email)}</div>
                            </td>
                            <td>
                                <span class="status-${crew.status.toLowerCase()}">
                                    <span class="status-dot"></span> ${escapeHtml(crew.status)}
                                </span>
                            </td>
                            <td>${eventBadgeHtml}</td>
                            <td class="text-center">
                                <a href="${crew.show_url}" class="action-btn btn-show" title="Show Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <button class="action-btn btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editCrewModal"
                                        data-id="${crew.id}"
                                        data-name="${escapeHtml(crew.name)}"
                                        data-email="${escapeHtml(crew.email)}"
                                        data-gender="${escapeHtml(crew.gender)}"
                                        data-phone="${escapeHtml(crew.phone_number)}"
                                        data-address="${escapeHtml(crew.address)}"
                                        data-birth="${escapeHtml(crew.formatted_birth)}"
                                        data-status="${escapeHtml(crew.status)}"
                                        title="Edit Crew">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="${deleteActionUrl}" method="POST" class="d-inline delete-form">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="action-btn btn-delete delete-crew-btn" title="Delete Crew">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
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
                    fetchCrews(currentPage);
                }
            });

            // Filter change listeners
            document.getElementById('filterStatus').addEventListener('change', function () {
                filterStatus = this.value;
                currentPage = 1;
                fetchCrews(currentPage);
            });



            // Debounced Search Input Listener
            document.getElementById('crewSearch').addEventListener('input', function () {
                clearTimeout(debounceTimer);
                searchQuery = this.value;
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    fetchCrews(currentPage);
                }, 300);
            });

            // Event Delegation for Delete Confirmation
            tableBody.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.delete-crew-btn');
                if (deleteBtn) {
                    e.preventDefault();
                    const form = deleteBtn.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#41612A',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // Handle Edit Modal Data
            const editCrewModal = document.getElementById('editCrewModal');
            if (editCrewModal) {
                editCrewModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const email = button.getAttribute('data-email');
                    const gender = button.getAttribute('data-gender');
                    const phone = button.getAttribute('data-phone');
                    const address = button.getAttribute('data-address');
                    const birth = button.getAttribute('data-birth');
                    const status = button.getAttribute('data-status');

                    const form = document.getElementById('editCrewForm');
                    form.action = "{{ route('management.crew.update', ':id') }}".replace(':id', id);

                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_gender').value = gender;
                    document.getElementById('edit_phone').value = phone;
                    document.getElementById('edit_address').value = address;
                    document.getElementById('edit_birth').value = birth;
                    document.getElementById('edit_status').value = status;
                });
            }

            // Display success message from session
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Display error message from session
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ $errors->first() }}",
                });
            @endif

            // Initial Data Load
            fetchCrews(currentPage);
        });
    </script>
@endsection