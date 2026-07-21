@extends('layouts.management')

@section('title', 'My Profile')

@section('styles')
<style>
    .profile-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .profile-header {
        background: var(--brilliant-green);
        height: 120px;
        position: relative;
    }
    .profile-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: -60px auto 0 auto;
        z-index: 5;
    }
    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 35px;
        border: 6px solid #fff;
        background: #fff;
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .avatar-edit-btn {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: var(--brilliant-green);
        color: #fff;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(124, 163, 97, 0.3);
    }
    .avatar-edit-btn:hover {
        background: var(--brilliant-green-dark);
        transform: scale(1.1) rotate(5deg);
    }
    .avatar-delete-btn {
        position: absolute;
        bottom: -5px;
        left: -5px;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: #ff4d4d;
        color: #fff;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.3);
        display: {{ $user->avatar ? 'flex' : 'none' }};
    }
    .avatar-delete-btn:hover {
        background: #cc0000;
        transform: scale(1.1);
    }
    .avatar-action-row {
        display: none;
    }
    #cropperModal .modal-body {
        padding: 0;
        overflow: hidden;
    }
    .img-container {
        height: 400px;
        max-height: 400px;
        width: 100%;
        background-color: #f7f7f7;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .img-container img {
        max-width: 100%;
        max-height: 100%;
        display: block;
    }
    .profile-info-section {
        padding-top: 10px;
        text-align: center;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 10px;
    }
    .status-available {
        background: var(--brilliant-green-light);
        color: var(--brilliant-green-dark);
    }
    .status-busy {
        background: #fff4e5;
        color: #ff9800;
    }
    .status-off {
        background: #f0f0f0;
        color: #666;
    }
    .status-unavailable {
        background: #ffe5e5;
        color: #ff4d4d;
    }
    .profile-stat-box {
        background: var(--brilliant-bg);
        border-radius: 15px;
        padding: 15px;
        margin-top: 20px;
    }
    .profile-stat-item {
        flex: 1;
    }
    .profile-stat-value {
        display: block;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--brilliant-green-dark);
    }
    .profile-stat-label {
        font-size: 0.8rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-label {
        color: #718096;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .info-value {
        color: #2d3748;
        font-weight: 600;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
    }
    .form-control:focus {
        border-color: var(--brilliant-green);
        box-shadow: 0 0 0 3px rgba(124, 163, 97, 0.1);
    }
    .profile-tabs .nav-link {
        color: #718096;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: none;
    }
    .profile-tabs .nav-link.active {
        background-color: var(--brilliant-green) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(124, 163, 97, 0.2);
    }
    .profile-tabs .nav-link:hover:not(.active) {
        background-color: var(--brilliant-green-light);
        color: var(--brilliant-green-dark);
    }
    .nav-tabs-custom {
        border-bottom: 2px solid #f7fafc;
        margin-bottom: 25px;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #718096;
        font-weight: 600;
        padding: 10px 20px;
        position: relative;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--brilliant-green);
        background: none;
    }
    .nav-tabs-custom .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--brilliant-green);
    }
    
    /* Custom style for status toggle buttons */
    .status-toggle-group {
        display: flex;
        background: #f1f3f5;
        padding: 4px;
        border-radius: 12px;
        gap: 4px;
        margin-top: 15px;
    }
    .status-toggle-item {
        flex: 1;
        text-align: center;
    }
    .status-toggle-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 8px 6px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #495057;
        margin-bottom: 0;
        border: 1px solid transparent;
    }
    .status-toggle-btn {
        display: none;
    }
    .status-toggle-btn:checked + .label-available {
        background: var(--brilliant-green-light);
        color: var(--brilliant-green-dark);
        box-shadow: 0 4px 10px rgba(124, 163, 97, 0.15);
        border-color: rgba(124, 163, 97, 0.3);
    }
    .status-toggle-btn:checked + .label-busy {
        background: #fff4e5;
        color: #ff9800;
        box-shadow: 0 4px 10px rgba(255, 152, 0, 0.15);
        border-color: rgba(255, 152, 0, 0.3);
    }
    .status-toggle-btn:checked + .label-off {
        background: #ffe5e5;
        color: #ff4d4d;
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.15);
        border-color: rgba(255, 77, 77, 0.3);
    }
    .status-toggle-label:hover:not(.status-toggle-btn:checked + .status-toggle-label) {
        background: rgba(0, 0, 0, 0.05);
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-4">
            <div class="profile-card mb-4">
                <div class="profile-header"></div>
                <div class="profile-avatar-wrapper">
                    <img id="avatar-preview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=7ca361&color=fff&size=128' }}" alt="Avatar" class="profile-avatar">
                    <label for="avatar-input" class="avatar-edit-btn" title="Change Avatar">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                    <div id="delete-avatar-btn" class="avatar-delete-btn" title="Delete Avatar">
                        <i class="bi bi-trash-fill"></i>
                    </div>
                </div>
                <div class="profile-info-section p-4">
                    <h4 class="mt-3 mb-1 fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                    
                    @php
                        $statusClass = 'status-available';
                        if($user->status == 'Busy') $statusClass = 'status-busy';
                        elseif($user->status == 'Off') $statusClass = 'status-off';
                        elseif($user->status == 'Unavailable') $statusClass = 'status-unavailable';
                    @endphp
                    <div id="status-badge-container">
                        <div class="status-badge {{ $statusClass }}">
                            <i class="bi bi-circle-fill" style="font-size: 8px;"></i> <span id="status-text">{{ $user->status }}</span>
                        </div>
                    </div>

                    <!-- Quick Status Toggle -->
                    <div class="status-toggle-group">
                        <div class="status-toggle-item">
                            <input type="radio" class="status-toggle-btn" name="status_quick_toggle" id="toggle_available" value="Available" {{ $user->status == 'Available' ? 'checked' : '' }}>
                            <label class="status-toggle-label label-available" for="toggle_available">
                                <i class="bi bi-check-circle-fill"></i> Available
                            </label>
                        </div>
                        <div class="status-toggle-item">
                            <input type="radio" class="status-toggle-btn" name="status_quick_toggle" id="toggle_busy" value="Busy" {{ $user->status == 'Busy' ? 'checked' : '' }}>
                            <label class="status-toggle-label label-busy" for="toggle_busy">
                                <i class="bi bi-dash-circle-fill"></i> Busy
                            </label>
                        </div>
                        <div class="status-toggle-item">
                            <input type="radio" class="status-toggle-btn" name="status_quick_toggle" id="toggle_off" value="Off" {{ ($user->status == 'Off' || $user->status == 'Unavailable') ? 'checked' : '' }}>
                            <label class="status-toggle-label label-off" for="toggle_off">
                                <i class="bi bi-x-circle-fill"></i> Off
                            </label>
                        </div>
                    </div>

                    <div class="profile-stat-box d-flex">
                        <div class="profile-stat-item border-end">
                            <span class="profile-stat-value">{{ $user->joined_at ? $user->joined_at->format('Y') : $user->created_at->format('Y') }}</span>
                            <span class="profile-stat-label">Joined</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value">{{ $user->total_events_handled }}</span>
                            <span class="profile-stat-label">Events</span>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="text-start">
                        <div class="mb-3">
                            <div class="info-label mb-1">Birth Date</div>
                            <div class="info-value">{{ $user->birth_date ? $user->birth_date->format('d F Y') : '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label mb-1">Age</div>
                            <div class="info-value">{{ $user->birth_date ? $user->birth_date->age . ' Years Old' : '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label mb-1">Gender</div>
                            <div class="info-value">{{ $user->gender ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label mb-1">Phone Number</div>
                            <div class="info-value">{{ $user->phone_number ?? '-' }}</div>
                        </div>
                        <div class="mb-0">
                            <div class="info-label mb-1">Address</div>
                            <div class="info-value small">{{ $user->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-lg-8">
            <div class="card card-widget p-4">
                <ul class="nav nav-pills profile-tabs mb-4" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link active"
                            id="edit-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#edit"
                            type="button"
                            role="tab"
                        >
                            <i class="bi bi-person me-1"></i> Edit Profile
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link"
                            id="security-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#security"
                            type="button"
                            role="tab"
                        >
                            <i class="bi bi-shield-lock me-1"></i> Security Settings
                        </button>
                    </li>
                </ul>
                
                <form id="profileUpdateForm" action="{{ route('management.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="tab-content" id="profileTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="edit" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}">
                                    @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Gender</label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Birth Date</label>
                                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
                                    @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="Available" {{ old('status', $user->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Busy" {{ old('status', $user->status) == 'Busy' ? 'selected' : '' }}>Busy</option>
                                        <option value="Off" {{ old('status', $user->status) == 'Off' ? 'selected' : '' }}>Off</option>
                                        <option value="Unavailable" {{ old('status', $user->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 d-none">
                                    <input type="file" id="avatar-input" accept="image/*" class="form-control">
                                    <input type="hidden" name="avatar_base64" id="avatar-base64">
                                    <input type="hidden" name="delete_avatar" id="delete-avatar-input" value="0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Home Address</label>
                                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="alert alert-success border-0 rounded-3 mb-4" style="background-color: var(--brilliant-green-light); color: var(--brilliant-green-dark);">
                                <i class="bi bi-info-circle-fill me-2"></i> Leave password fields empty if you don't want to change it.
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">New Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-3">
                            <i class="bi bi-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropperModalLabel">Crop Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="cropper-image" src="" alt="Image to crop">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light px-4 fw-bold rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4 fw-bold rounded-3" id="crop-button">Crop & Apply</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profileUpdateForm');
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarBase64Input = document.getElementById('avatar-base64');
        const deleteAvatarBtn = document.getElementById('delete-avatar-btn');
        const deleteAvatarInput = document.getElementById('delete-avatar-input');
        const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
        const cropperImage = document.getElementById('cropper-image');
        const cropButton = document.getElementById('crop-button');
        let cropper;

        // Handle Avatar Input
        avatarInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                
                // Validate Size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran foto profil tidak boleh lebih dari 2MB.'
                    });
                    avatarInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    cropperImage.src = event.target.result;
                    cropperModal.show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Initialize Cropper when modal is shown
        document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1,
            });
        });

        // Destroy Cropper when modal is hidden
        document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Clear input if cancelled
            if (!avatarBase64Input.value) {
                avatarInput.value = '';
            }
        });

        // Handle Crop Button
        cropButton.addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            });
            
            const base64Data = canvas.toDataURL('image/png');
            avatarPreview.src = base64Data;
            avatarBase64Input.value = base64Data;
            deleteAvatarInput.value = '0'; // Reset delete if new image uploaded
            deleteAvatarBtn.style.display = 'flex';
            
            cropperModal.hide();
        });

        // Handle Delete Avatar
        deleteAvatarBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Foto Profil?',
                text: "Foto akan dihapus saat Anda menekan Save Changes.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4d4d',
                cancelButtonColor: '#718096',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteAvatarInput.value = '1';
                    avatarBase64Input.value = '';
                    avatarInput.value = '';
                    avatarPreview.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent('{{ $user->name }}') + '&background=7ca361&color=fff&size=128';
                    deleteAvatarBtn.style.display = 'none';
                }
            });
        });

        // Success Notification
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Error Notification
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
            });
        @endif

        // Validation Errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Validasi',
                html: '<ul class="text-start">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            });
        @endif

        // Confirm Save Changes
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan data yang Anda masukkan sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#7ca361',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Harap tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    profileForm.submit();
                }
            });
        });

        // Handle Quick Status Toggle
        const statusToggleBtns = document.querySelectorAll('.status-toggle-btn');
        const statusBadgeContainer = document.getElementById('status-badge-container');
        const statusSelectInForm = document.querySelector('select[name="status"]');
        let previousStatus = document.querySelector('.status-toggle-btn:checked')?.value || 'Available';

        function revertRadio() {
            const btnId = previousStatus === 'Unavailable' ? 'toggle_off' : `toggle_${previousStatus.toLowerCase()}`;
            const radioToCheck = document.getElementById(btnId);
            if (radioToCheck) {
                radioToCheck.checked = true;
            }
        }

        statusToggleBtns.forEach(btn => {
            btn.addEventListener('change', function() {
                const newStatus = this.value;
                if (newStatus === previousStatus) return;

                const statusLabel = this.nextElementSibling.textContent.trim();

                Swal.fire({
                    title: 'Ubah Status?',
                    text: `Apakah Anda yakin ingin mengubah status menjadi "${statusLabel}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#7ca361',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show Loading
                        Swal.fire({
                            title: 'Mengubah Status...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send AJAX Request
                        fetch("{{ route('management.profile.status.update') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: newStatus })
                        })
                        .then(response => {
                            return response.text().then(text => {
                                try {
                                    const data = JSON.parse(text);
                                    if (response.ok) {
                                        return data;
                                    } else {
                                        throw new Error(data.message || 'Gagal memperbarui status');
                                    }
                                } catch (e) {
                                    console.error('Server Response:', text);
                                    
                                    // Try to parse HTML error message
                                    const tempDiv = document.createElement('div');
                                    tempDiv.innerHTML = text;
                                    const title = tempDiv.querySelector('title')?.textContent || 'Server Error';
                                    
                                    // Extract exception message if Laravel debug page
                                    const laravelMsg = tempDiv.querySelector('.exception-message, h1')?.textContent;
                                    if (laravelMsg) {
                                        throw new Error(`${title}: ${laravelMsg.trim()}`);
                                    }
                                    
                                    // Fallback to stripped text
                                    const cleanText = tempDiv.textContent.replace(/\s+/g, ' ').trim().substring(0, 150);
                                    throw new Error(cleanText || 'Response is not valid JSON');
                                }
                            });
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Status Diperbarui!',
                                    text: `Status berhasil diubah menjadi ${data.status}.`,
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                previousStatus = data.status;

                                // Update Status Badge classes and text
                                const badgeText = document.getElementById('status-text');
                                if (badgeText) badgeText.textContent = data.status;
                                
                                const badgeDiv = statusBadgeContainer.querySelector('.status-badge');
                                if (badgeDiv) {
                                    badgeDiv.className = 'status-badge';
                                    let statusClass = 'status-available';
                                    if (data.status === 'Busy') statusClass = 'status-busy';
                                    else if (data.status === 'Off') statusClass = 'status-off';
                                    else if (data.status === 'Unavailable') statusClass = 'status-unavailable';
                                    badgeDiv.classList.add(statusClass);
                                }

                                // Also sync with the dropdown inside the edit form
                                if (statusSelectInForm) {
                                    statusSelectInForm.value = data.status;
                                }
                            } else {
                                throw new Error(data.message || 'Gagal memperbarui status');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat memperbarui status.'
                            });
                            revertRadio();
                        });
                    } else {
                        revertRadio();
                    }
                });
            });
        });
    });
</script>
@endsection
