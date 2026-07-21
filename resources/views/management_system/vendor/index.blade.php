@extends('layouts.management')

@section('title', 'Vendor Management')

@section('styles')
<style>
    .vendor-header {
        font-size: 2.5rem;
        color: #999;
        font-weight: 300;
        margin-bottom: 1.5rem;
    }

    .card-vendor {
        background: #fff;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .btn-add-vendor {
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

    .btn-add-vendor:hover {
        background-color: #2d441d;
        color: #fff;
        transform: translateY(-2px);
    }

    .table-vendor thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: none;
        padding: 15px;
    }

    .table-vendor tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
        color: #555;
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

    .btn-edit { background-color: #41612A; }
    .btn-edit:hover { background-color: #2d441d; }
    .btn-delete { background-color: #dc3545; }
    .btn-delete:hover { background-color: #c82333; }

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

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #ddd;
    }

    .form-control:focus, .form-select:focus {
        border-color: #41612A;
        box-shadow: 0 0 0 0.2rem rgba(65, 97, 42, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <h1 class="vendor-header">Vendor</h1>

    <div class="card-vendor">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <button class="btn-add-vendor" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                <i class="bi bi-plus-circle"></i> Add Vendor
            </button>
            <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap w-100 w-md-auto justify-content-md-end">
                <!-- Category Filter -->
                <div style="width: 160px;">
                    <select id="filterCategory" class="form-select filter-select">
                        <option value="">Semua Kategori</option>
                        <option value="Photography">Photography</option>
                        <option value="Catering">Catering</option>
                        <option value="Make up Artist">Make up Artist</option>
                        <option value="Decoration">Decoration</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Venue">Venue</option>
                    </select>
                </div>
                <!-- Search Box -->
                <div style="width: 240px;">
                    <div class="input-group search-input-group">
                        <span class="input-group-text text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="vendorSearch" placeholder="Cari vendor, telepon...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-vendor">
                <thead>
                    <tr>
                        <th style="width: 80px;">Logo</th>
                        <th>Vendor Name</th>
                        <th>Category</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th class="text-center" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody id="vendorTableBody">
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

<!-- Add Vendor Modal -->
<div class="modal fade" id="addVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('management.vendor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vendor Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter vendor name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="" disabled selected>Select category</option>
                                <option value="Photography">Photography</option>
                                <option value="Catering">Catering</option>
                                <option value="Make up Artist">Make up Artist</option>
                                <option value="Decoration">Decoration</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Venue">Venue</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 08123456789" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Enter full address" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Vendor Logo (Optional)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">Max size 2MB. Format: JPG, PNG, WEBP.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-add-vendor">Save Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Vendor Modal -->
<div class="modal fade" id="editVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editVendorForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vendor Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" id="edit_category" class="form-select" required>
                                <option value="Photography">Photography</option>
                                <option value="Catering">Catering</option>
                                <option value="Make up Artist">Make up Artist</option>
                                <option value="Decoration">Decoration</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Venue">Venue</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Address</label>
                            <textarea name="address" id="edit_address" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Vendor Logo (Optional)</label>
                            <div id="edit_logo_preview_container" class="mb-2 d-none">
                                <img id="edit_logo_preview" src="" alt="Logo Preview" class="img-thumbnail" style="height: 100px; width: 100px; object-fit: contain;">
                                <div class="small text-muted mt-1">Current Logo</div>
                            </div>
                            <input type="file" name="logo" id="edit_logo_input" class="form-control" accept="image/*">
                            <div class="form-text">Max size 2MB. Leave blank if not changing.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-add-vendor">Update Vendor</button>
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
        let filterCategory = '';
        let debounceTimer = null;
        const tableBody = document.getElementById('vendorTableBody');
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

        // Fetch vendors function
        function fetchVendors(page = 1) {
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

            const url = `/management-system/vendor?page=${page}&search=${encodeURIComponent(searchQuery)}&category=${encodeURIComponent(filterCategory)}`;

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
                console.error('Error fetching vendors:', error);
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
                            <i class="bi bi-shop fs-1 d-block mb-3 text-secondary"></i>
                            Tidak ada vendor yang ditemukan.
                        </td>
                    </tr>
                `;
                return;
            }

            let rowsHtml = '';
            data.data.forEach(vendor => {
                // Determine Logo
                let logoHtml = '';
                if (vendor.logo) {
                    logoHtml = `<img src="${vendor.logo_url}" alt="${escapeHtml(vendor.name)}" class="img-thumbnail" style="width: 45px; height: 45px; object-fit: contain;">`;
                } else {
                    logoHtml = `
                        <div class="d-flex align-items-center justify-content-center bg-light img-thumbnail" style="width: 45px; height: 45px;">
                            <i class="bi bi-shop text-muted fs-5"></i>
                        </div>
                    `;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const deleteActionUrl = `/management-system/vendor/${vendor.id}`;

                rowsHtml += `
                    <tr>
                        <td>${logoHtml}</td>
                        <td class="fw-bold text-dark">${escapeHtml(vendor.name)}</td>
                        <td>
                            <span class="badge bg-light text-dark rounded-pill px-3 fw-semibold">${escapeHtml(vendor.category)}</span>
                        </td>
                        <td>${escapeHtml(vendor.phone)}</td>
                        <td>${escapeHtml(vendor.address)}</td>
                        <td class="text-center">
                            <button class="action-btn btn-edit" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editVendorModal"
                                    data-id="${vendor.id}"
                                    data-name="${escapeHtml(vendor.name)}"
                                    data-category="${escapeHtml(vendor.category)}"
                                    data-phone="${escapeHtml(vendor.phone)}"
                                    data-address="${escapeHtml(vendor.address)}"
                                    data-logo="${vendor.logo ? vendor.logo_url : ''}"
                                    title="Edit Vendor">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <form action="${deleteActionUrl}" method="POST" class="d-inline delete-form">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" class="action-btn btn-delete delete-vendor-btn" title="Delete Vendor">
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
        paginationControls.addEventListener('click', function(e) {
            e.preventDefault();
            const link = e.target.closest('.page-link');
            if (!link) return;

            const pageItem = link.closest('.page-item');
            if (pageItem.classList.contains('disabled') || pageItem.classList.contains('active')) return;

            const targetPage = parseInt(link.getAttribute('data-page'));
            if (targetPage) {
                currentPage = targetPage;
                fetchVendors(currentPage);
            }
        });

        // Filter change listener
        document.getElementById('filterCategory').addEventListener('change', function() {
            filterCategory = this.value;
            currentPage = 1;
            fetchVendors(currentPage);
        });

        // Debounced Search Input Listener
        document.getElementById('vendorSearch').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            searchQuery = this.value;
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                fetchVendors(currentPage);
            }, 300);
        });

        // Event Delegation for Delete Confirmation
        tableBody.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-vendor-btn');
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
        const editVendorModal = document.getElementById('editVendorModal');
        if (editVendorModal) {
            editVendorModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const category = button.getAttribute('data-category');
                const phone = button.getAttribute('data-phone');
                const address = button.getAttribute('data-address');
                const logo = button.getAttribute('data-logo');

                const form = document.getElementById('editVendorForm');
                form.action = "{{ route('management.vendor.update', ':id') }}".replace(':id', id);

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_category').value = category;
                document.getElementById('edit_phone').value = phone;
                document.getElementById('edit_address').value = address;

                const previewContainer = document.getElementById('edit_logo_preview_container');
                const previewImg = document.getElementById('edit_logo_preview');
                if (logo) {
                    previewImg.src = logo;
                    previewContainer.classList.remove('d-none');
                } else {
                    previewImg.src = '';
                    previewContainer.classList.add('d-none');
                }
            });
        }

        // Live Preview for Edit Logo
        const editLogoInput = document.getElementById('edit_logo_input');
        if (editLogoInput) {
            editLogoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewContainer = document.getElementById('edit_logo_preview_container');
                        const previewImg = document.getElementById('edit_logo_preview');
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                }
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
        fetchVendors(currentPage);
    });
</script>
@endsection
