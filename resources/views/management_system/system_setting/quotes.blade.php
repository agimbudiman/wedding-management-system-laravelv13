@extends('layouts.management')

@section('title', 'Quotes Settings')

@section('styles')
    <style>
        .quote-row-number {
            font-size: 0.95rem;
            color: #a0aec0;
            font-weight: 600;
        }
        .quote-content {
            font-size: 1.05rem;
            color: #2d3748;
            line-height: 1.6;
        }
        .quote-author {
            color: var(--brilliant-green-dark);
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        /* Custom Switch Styling */
        .form-switch-lg .form-check-input {
            width: 3.5rem;
            height: 1.75rem;
            cursor: pointer;
        }
        .form-switch-lg .form-check-input:checked {
            background-color: var(--brilliant-green);
            border-color: var(--brilliant-green);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h1 class="page-title mb-0">Quotes Settings</h1>
                <p class="text-muted">Manage daily motivational quotes displayed on the dashboard.</p>
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

        <!-- Slideshow Configuration Card -->
        <div class="card-widget mb-4">
            <form action="{{ route('management.system-setting.quotes.config.update') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="bi bi-play-circle me-1 text-success"></i> Status Slideshow
                        </label>
                        <p class="small text-muted mb-2">Jika dinonaktifkan, kutipan akan tetap statis dan hanya berganti saat halaman dimuat ulang.</p>
                        <div class="form-check form-switch form-switch-lg d-flex align-items-center">
                            <input type="hidden" name="slideshow_active" value="0">
                            <input class="form-check-input" type="checkbox" name="slideshow_active" id="slideshowActiveToggle" value="1" {{ $slideshowActive == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark ms-3" for="slideshowActiveToggle" id="slideshowActiveLabel">
                                {{ $slideshowActive == '1' ? 'Aktif (On)' : 'Nonaktif (Off)' }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-bold text-dark mb-1" for="slideshowDurationInput">
                            <i class="bi bi-stopwatch me-1 text-success"></i> Durasi Transisi (detik)
                        </label>
                        <p class="small text-muted mb-2">Tentukan jeda waktu perputaran antar kutipan.</p>
                        <input type="number" name="slideshow_duration" id="slideshowDurationInput" class="form-control rounded-3" value="{{ $slideshowDuration }}" min="1" max="60" required {{ $slideshowActive == '0' ? 'disabled' : '' }}>
                    </div>
                    <div class="col-md-3 text-md-end pt-md-4">
                        <button type="submit" class="btn btn-primary px-4 rounded-pill w-100">
                            <i class="bi bi-save me-2"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-widget">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Manage Daily Quotes</h5>
                <button class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addQuoteModal">
                    <i class="bi bi-plus-lg me-2"></i> Add New Quote
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start px-4" style="width: 80px;">No</th>
                            <th class="border-0">Quote Text</th>
                            <th class="border-0" style="width: 220px;">Author</th>
                            <th class="border-0" style="width: 150px;">Status</th>
                            <th class="border-0 rounded-end text-end px-4" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotes as $index => $quote)
                            <tr>
                                <td class="px-4 quote-row-number">{{ $index + 1 }}</td>
                                <td class="quote-content fst-italic">"{{ $quote['text'] }}"</td>
                                <td class="quote-author">{{ $quote['author'] }}</td>
                                <td>
                                    <form action="{{ route('management.system-setting.quote.toggle', $index) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                id="quoteActiveToggle{{ $index }}" 
                                                onchange="this.form.submit()" 
                                                style="cursor: pointer;"
                                                {{ (!isset($quote['active']) || $quote['active']) ? 'checked' : '' }}>
                                            <span class="small fw-semibold {{ (!isset($quote['active']) || $quote['active']) ? 'text-success' : 'text-secondary' }} ms-2">
                                                {{ (!isset($quote['active']) || $quote['active']) ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                            data-bs-toggle="modal" data-bs-target="#editQuoteModal{{ $index }}">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </button>
                                        <form action="{{ route('management.system-setting.quote.destroy', $index) }}"
                                            method="POST" class="delete-quote-form d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Quote Modal -->
                            <div class="modal fade" id="editQuoteModal{{ $index }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <form action="{{ route('management.system-setting.quote.update', $index) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 p-4 pb-0">
                                                <h5 class="fw-bold">Edit Quote #{{ $index + 1 }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Quote Text</label>
                                                    <textarea name="text" class="form-control rounded-3" rows="3" required placeholder="Type quote text here...">{{ $quote['text'] }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Author</label>
                                                    <input type="text" name="author" class="form-control rounded-3" value="{{ $quote['author'] }}" required placeholder="e.g. Steve Jobs">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Status</label>
                                                    <div class="form-check form-switch mt-1">
                                                        <input type="hidden" name="active" value="0">
                                                        <input class="form-check-input" type="checkbox" name="active" id="editQuoteActive{{ $index }}" value="1" {{ (!isset($quote['active']) || $quote['active']) ? 'checked' : '' }} style="cursor: pointer;">
                                                        <label class="form-check-label fw-semibold text-dark ms-2" for="editQuoteActive{{ $index }}">Aktif (Tampilkan di Dashboard)</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 p-4 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-left-quote fs-1 d-block mb-3 opacity-50 text-success"></i>
                                    Belum ada quote yang tersimpan. Silakan tambahkan quote baru!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Quote Modal -->
    <div class="modal fade" id="addQuoteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('management.system-setting.quote.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <h5 class="fw-bold">Add New Quote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Quote Text</label>
                            <textarea name="text" class="form-control rounded-3" rows="3" required placeholder="Type quote text here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Author</label>
                            <input type="text" name="author" class="form-control rounded-3" required placeholder="e.g. Albert Einstein">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="form-check form-switch mt-1">
                                <input type="hidden" name="active" value="0">
                                <input class="form-check-input" type="checkbox" name="active" id="addQuoteActive" value="1" checked style="cursor: pointer;">
                                <label class="form-check-label fw-semibold text-dark ms-2" for="addQuoteActive">Aktif (Tampilkan di Dashboard)</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Create Quote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Confirmation for deleting quote
            document.querySelectorAll('.delete-quote-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Quote?',
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

            // Interactive Toggle logic
            const toggle = document.getElementById('slideshowActiveToggle');
            const label = document.getElementById('slideshowActiveLabel');
            const durationInput = document.getElementById('slideshowDurationInput');

            if (toggle && label && durationInput) {
                toggle.addEventListener('change', function () {
                    if (this.checked) {
                        label.textContent = "Aktif (On)";
                        durationInput.removeAttribute('disabled');
                    } else {
                        label.textContent = "Nonaktif (Off)";
                        durationInput.setAttribute('disabled', 'true');
                    }
                });
            }
        });
    </script>
@endsection
