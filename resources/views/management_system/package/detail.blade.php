@extends('layouts.management')

@section('title', 'Package Detail')

@section('styles')
<style>
    .package-header {
        font-size: 2.5rem;
        color: #999;
        font-weight: 300;
        margin-bottom: 1.5rem;
    }

    .card-detail {
        background: #fff;
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .card-header-custom {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        text-transform: uppercase;
        color: #555;
        font-size: 1rem;
    }

    .card-body-custom {
        padding: 2rem;
    }

    .form-group-custom {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-group-custom label {
        width: 150px;
        color: #666;
        margin: 0;
        font-weight: 500;
    }

    .form-group-custom .control-wrapper {
        flex: 1;
        max-width: 400px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 15px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #41612A;
        box-shadow: 0 0 0 0.2rem rgba(65, 97, 42, 0.15);
    }

    .package-items-wrapper {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    
    .package-items-wrapper label {
        width: 150px;
        color: #666;
        margin: 0;
        font-weight: 500;
        padding-top: 5px;
    }

    .package-items-content {
        flex: 1;
    }

    .item-pill {
        display: inline-flex;
        align-items: center;
        background-color: transparent;
        color: #555;
        font-weight: 500;
        margin-right: 15px;
        margin-bottom: 10px;
    }

    .item-pill i {
        color: #41612A;
        margin-right: 5px;
        font-size: 1.1rem;
    }

    .btn-add-item {
        background-color: #41612A;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 6px 15px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
        margin-top: 5px;
    }

    .btn-add-item:hover {
        background-color: #2d441d;
        color: #fff;
    }

    .btn-save {
        background-color: #41612A;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 25px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-save:hover {
        background-color: #2d441d;
    }

    .delete-item-btn {
        background: none;
        border: none;
        color: #dc3545;
        margin-left: 5px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .delete-item-btn:hover {
        color: #c82333;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="package-header mb-0">Package - Detail</h1>
        <a href="{{ route('management.package.index') }}" class="btn btn-outline-secondary" style="border-radius: 8px; font-weight: 500;">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card card-detail">
        <form action="{{ route('management.package.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-header-custom">
                <h5>Edit Package Form</h5>
                <button type="submit" class="btn btn-link text-muted p-0" title="Save Changes">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            </div>

            <div class="card-body-custom">
                <div class="form-group-custom">
                    <label>Package Name <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label>Category <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $package->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label>Original Price <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-radius: 8px 0 0 8px;">Rp</span>
                            <input type="number" name="original_price" min="0" class="form-control" style="border-radius: 0 8px 8px 0;" value="{{ round($package->original_price) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label>Final Price <span class="float-end">:</span></label>
                    <div class="control-wrapper ms-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white" style="border-radius: 8px 0 0 8px;">Rp</span>
                            <input type="number" name="final_price" min="0" class="form-control" style="border-radius: 0 8px 8px 0;" value="{{ round($package->final_price) }}" required>
                        </div>
                    </div>
                </div>

                <div class="package-items-wrapper mt-4">
                    <label>Package Items <span class="float-end">:</span></label>
                    <div class="package-items-content ms-3">
                        <div>
                            @foreach($package->items as $item)
                                <div class="item-pill group-item">
                                    <i class="bi bi-check-circle-fill"></i> 
                                    <span>{{ $item->name }}</span>
                                    <button type="button" class="delete-item-btn" data-id="{{ $item->id }}" data-package="{{ $package->id }}">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @endforeach
                            @if($package->items->isEmpty())
                                <span class="text-muted fst-italic me-3 mb-2 d-inline-block">No items added yet.</span>
                            @endif
                        </div>
                        <button type="button" class="btn-add-item" data-bs-toggle="modal" data-bs-target="#addItemModal">
                            <i class="bi bi-plus-circle"></i> Add Items
                        </button>
                    </div>
                </div>
                
                <div class="mt-4 ms-3 ps-1" style="margin-left: 165px !important;">
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Package Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('management.package.item.add', $package->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Name</label>
                        <input type="text" name="items[0][name]" class="form-control" placeholder="e.g. Photography 1 Day" required>
                    </div>
                    <div id="additionalItems"></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="addMoreItemBtn">+ Add another item field</button>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Items</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form to delete item -->
<form id="deleteItemForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle adding more item fields dynamically
        let itemIndex = 1;
        document.getElementById('addMoreItemBtn').addEventListener('click', function() {
            const container = document.getElementById('additionalItems');
            const html = `
                <div class="mb-3 mt-3 d-flex align-items-center gap-2">
                    <input type="text" name="items[${itemIndex}][name]" class="form-control" placeholder="Enter item name" required>
                    <button type="button" class="btn btn-danger btn-sm remove-field-btn"><i class="bi bi-trash"></i></button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        });

        document.getElementById('additionalItems').addEventListener('click', function(e) {
            if(e.target.closest('.remove-field-btn')) {
                e.target.closest('.mb-3').remove();
            }
        });

        // Handle item deletion
        document.querySelectorAll('.delete-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                const pkgId = this.getAttribute('data-package');
                const form = document.getElementById('deleteItemForm');
                
                // Construct URL correctly based on your routes
                let url = "{{ route('management.package.item.remove', ['id' => ':pkg', 'itemId' => ':item']) }}";
                url = url.replace(':pkg', pkgId).replace(':item', itemId);
                
                form.action = url;

                Swal.fire({
                    title: 'Remove item?',
                    text: "You are about to remove this package item.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#41612A',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

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

        // Client-side price validation for Edit Package form
        const editPackageForm = document.querySelector('form[action*="package/update"]');
        if (editPackageForm) {
            editPackageForm.addEventListener('submit', function(e) {
                const originalPriceInput = editPackageForm.querySelector('input[name="original_price"]');
                const finalPriceInput = editPackageForm.querySelector('input[name="final_price"]');
                
                const originalPrice = parseFloat(originalPriceInput.value) || 0;
                const finalPrice = parseFloat(finalPriceInput.value) || 0;
                
                if (finalPrice >= originalPrice) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harga final harus lebih kecil dari harga original!',
                        confirmButtonColor: '#41612A'
                    });
                }
            });
        }
    });
</script>
@endsection
