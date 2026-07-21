@extends('layouts.management')

@section('title', 'System Settings')

@section('styles')
    <style>
        .nav-tabs-custom {
            border-bottom: 2px solid #e2e8f0;
            gap: 2rem;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #718096;
            font-weight: 600;
            padding: 1rem 0;
            position: relative;
            background: none;
        }

        .nav-tabs-custom .nav-link.active {
            color: var(--brilliant-green);
            background: none;
        }

        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: var(--brilliant-green);
        }

        .role-card {
            transition: all 0.3s ease;
            border: 1px solid #edf2f7;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border-color: var(--brilliant-green-light);
        }

        .permission-badge {
            background-color: #f7fafc;
            color: #4a5568;
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid #edf2f7;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h1 class="page-title mb-0">Role Access Settings</h1>
                <p class="text-muted">Manage roles, permissions, and user access levels.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-widget mb-4">
            <ul class="nav nav-tabs nav-tabs-custom mb-4" id="settingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles"
                        type="button" role="tab">
                        <i class="bi bi-shield-lock me-2"></i> Roles & Permissions
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button"
                        role="tab">
                        <i class="bi bi-people me-2"></i> User Roles
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="settingTabsContent">
                <!-- Roles & Permissions Tab -->
                <div class="tab-pane fade show active" id="roles" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Role Management</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="bi bi-plus-lg me-2"></i> Add New Role
                        </button>
                    </div>

                    <div class="row g-4">
                        @foreach($roles as $role)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 role-card rounded-4 p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $role->display_name }}</h5>
                                            <span class="badge bg-light text-dark text-uppercase"
                                                style="font-size: 0.65rem; letter-spacing: 0.05em;">{{ $role->name }}</span>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-circle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editRoleModal{{ $role->id }}"><i
                                                            class="bi bi-pencil me-2"></i> Edit Role</a></li>
                                                @if($role->name !== 'admin')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('management.system-setting.role.destroy', $role->id) }}"
                                                            method="POST" class="delete-role-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"><i
                                                                    class="bi bi-trash me-2"></i> Delete Role</button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-4">{{ $role->description ?: 'No description provided.' }}</p>

                                    <div class="mb-3">
                                        <h6 class="fw-bold small text-uppercase text-muted mb-2">Permissions</h6>
                                        <div class="d-flex flex-wrap">
                                            @forelse($role->permissions as $permission)
                                                <span class="permission-badge">{{ $permission->display_name }}</span>
                                            @empty
                                                <span class="text-muted small italic">No permissions assigned.</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Role Modal -->
                            <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <form action="{{ route('management.system-setting.role.update', $role->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 p-4 pb-0">
                                                <h5 class="fw-bold">Edit Role: {{ $role->display_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Display Name</label>
                                                    <input type="text" name="display_name" class="form-control"
                                                        value="{{ $role->display_name }}" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label fw-semibold">Description</label>
                                                    <textarea name="description" class="form-control"
                                                        rows="2">{{ $role->description }}</textarea>
                                                </div>

                                                <h6 class="fw-bold mb-3">Configure Permissions</h6>
                                                <div class="row">
                                                    @foreach($permissions as $module => $modulePermissions)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="p-3 bg-light rounded-3">
                                                                <h6 class="fw-bold small text-uppercase mb-3 text-primary">
                                                                    {{ $module }}</h6>
                                                                @foreach($modulePermissions as $perm)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="permissions[]" value="{{ $perm->id }}"
                                                                            id="perm{{ $role->id }}{{ $perm->id }}" {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                                                                        <label class="form-check-label small"
                                                                            for="perm{{ $role->id }}{{ $perm->id }}">
                                                                            {{ $perm->display_name }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Users Tab -->
                <div class="tab-pane fade" id="users" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                        <h5 class="fw-bold mb-0">User Roles Management</h5>
                        <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap w-100 w-md-auto">
                            <!-- Role Filter -->
                            <div style="width: 180px;">
                                <select id="filterRole" class="form-select rounded-pill border-2" style="font-size: 0.9rem; height: 40px;">
                                    <option value="">All Roles</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Search Box -->
                            <div style="width: 240px;">
                                <div class="input-group" style="height: 40px;">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control border-start-0 rounded-end-pill" id="userSearchInput" placeholder="Search name or email...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start px-4">User</th>
                                    <th class="border-0">Email</th>
                                    <th class="border-0">Current Role</th>
                                    <th class="border-0 rounded-end text-end px-4" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <!-- Dynamic Content via AJAX -->
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="d-block mt-2">Loading data...</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Custom Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3 border-top pt-3">
                        <div class="text-muted small fw-semibold" id="paginationInfo">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-rounded m-0" id="paginationControls">
                                <!-- Dynamic Pagination Buttons -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (placed outside the loop) -->
    <div id="roleModalsContainer">
        <!-- Change Role Modal -->
        <div class="modal fade" id="changeUserRoleModal" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content rounded-4 border-0 shadow">
                    <form id="changeUserRoleForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 p-4 pb-0">
                            <h5 class="fw-bold">Change Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="small text-muted mb-3">Assign a new role to <strong id="changeUserRoleName">User</strong>.</p>
                            <select name="role_id" id="changeUserRoleSelect" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="submit" class="btn btn-primary w-100">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('management.system-setting.role.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-bold">Create New Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Role Code (unique)</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g., manager" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Display Name</label>
                                <input type="text" name="display_name" class="form-control"
                                    placeholder="e.g., Project Manager" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="2"
                                placeholder="Brief description of what this role can do..."></textarea>
                        </div>

                        <h6 class="fw-bold mb-3">Assign Permissions</h6>
                        <div class="row">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="col-md-6 mb-4">
                                    <div class="p-3 bg-light rounded-3">
                                        <h6 class="fw-bold small text-uppercase mb-3 text-primary">{{ $module }}</h6>
                                        @foreach($modulePermissions as $perm)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $perm->id }}" id="newPerm{{ $perm->id }}">
                                                <label class="form-check-label small" for="newPerm{{ $perm->id }}">
                                                    {{ $perm->display_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ----------------------------------------------------
            // DYNAMIC USER ROLES TABLE LOGIC (AJAX)
            // ----------------------------------------------------
            let currentPage = 1;
            let searchQuery = '';
            let filterRole = '';
            let debounceTimer = null;
            const tableBody = document.getElementById('userTableBody');
            const paginationInfo = document.getElementById('paginationInfo');
            const paginationControls = document.getElementById('paginationControls');

            function fetchUsers(page = 1) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="d-block mt-2">Loading data...</span>
                        </td>
                    </tr>
                `;

                const url = `{{ route('management.system-setting.index') }}?page=${page}&search=${encodeURIComponent(searchQuery)}&role_id=${filterRole}`;

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-5 text-danger">
                                <i class="bi bi-exclamation-triangle-fill fs-2 d-block mb-2"></i>
                                Failed to load data.
                            </td>
                        </tr>
                    `;
                });
            }

            function renderTable(data) {
                if (!data.data || data.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                No users found.
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                data.data.forEach(user => {
                    const avatar = user.avatar_url 
                        ? `<img src="${user.avatar_url}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">`
                        : user.initial;

                    html += `
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-img shadow-sm d-flex align-items-center justify-content-center bg-light" style="width: 35px; height: 35px; border-radius: 10px; font-weight: bold;">
                                        ${avatar}
                                    </div>
                                    <div class="fw-bold text-dark">${user.name}</div>
                                </div>
                            </td>
                            <td>${user.email}</td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border px-3">
                                    ${user.role_display}
                                </span>
                            </td>
                            <td class="text-end px-4">
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                    data-bs-toggle="modal" data-bs-target="#changeUserRoleModal"
                                    data-user-id="${user.id}"
                                    data-user-name="${user.name}"
                                    data-user-role-id="${user.role_id}">
                                    Change Role
                                </button>
                            </td>
                        </tr>
                    `;
                });
                tableBody.innerHTML = html;
            }

            function renderPagination(data) {
                if (data.total === 0) {
                    paginationInfo.textContent = 'Showing 0 to 0 of 0 entries';
                    paginationControls.innerHTML = '';
                    return;
                }

                paginationInfo.textContent = `Showing ${data.from} to ${data.to} of ${data.total} entries`;

                let html = '';
                // Prev
                html += `
                    <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo; Prev</a>
                    </li>
                `;

                // Pages
                const maxVisible = 5;
                let start = Math.max(1, data.current_page - 2);
                let end = Math.min(data.last_page, start + maxVisible - 1);
                if (end - start + 1 < maxVisible) start = Math.max(1, end - maxVisible + 1);

                for (let i = start; i <= end; i++) {
                    html += `
                        <li class="page-item ${data.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                // Next
                html += `
                    <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${data.current_page + 1}">Next &raquo;</a>
                    </li>
                `;
                paginationControls.innerHTML = html;
            }

            paginationControls.addEventListener('click', function(e) {
                e.preventDefault();
                const link = e.target.closest('.page-link');
                if (!link || link.closest('.page-item').classList.contains('disabled')) return;
                currentPage = parseInt(link.dataset.page);
                fetchUsers(currentPage);
            });

            document.getElementById('userSearchInput').addEventListener('input', function() {
                clearTimeout(debounceTimer);
                searchQuery = this.value;
                debounceTimer = setTimeout(() => {
                    currentPage = 1;
                    fetchUsers(currentPage);
                }, 300);
            });

            document.getElementById('filterRole').addEventListener('change', function() {
                filterRole = this.value;
                currentPage = 1;
                fetchUsers(currentPage);
            });

            // Initial load
            fetchUsers(1);

            // Handle dynamic change role modal population
            const changeUserRoleModal = document.getElementById('changeUserRoleModal');
            if (changeUserRoleModal) {
                changeUserRoleModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');
                    const userName = button.getAttribute('data-user-name');
                    const roleId = button.getAttribute('data-user-role-id');
                    
                    const form = changeUserRoleModal.querySelector('#changeUserRoleForm');
                    const nameSpan = changeUserRoleModal.querySelector('#changeUserRoleName');
                    const select = changeUserRoleModal.querySelector('#changeUserRoleSelect');
                    
                    // Set action URL dynamically
                    let actionUrl = `{{ route('management.system-setting.user.role.update', ':id') }}`;
                    actionUrl = actionUrl.replace(':id', userId);
                    form.setAttribute('action', actionUrl);
                    
                    // Set name and select value
                    nameSpan.textContent = userName;
                    select.value = roleId;
                });
            }

            // Confirmation for deleting role
            document.querySelectorAll('.delete-role-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Role?',
                        text: "Tindakan ini tidak dapat dibatalkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });

            // Maintain active tab on reload
            let activeTab = localStorage.getItem('activeSettingTab');
            if (activeTab) {
                let tabEl = document.querySelector('#' + activeTab);
                if (tabEl) {
                    let tab = new bootstrap.Tab(tabEl);
                    tab.show();
                }
            }

            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (e) {
                    localStorage.setItem('activeSettingTab', e.target.id);
                });
            });
        });
    </script>
@endsection