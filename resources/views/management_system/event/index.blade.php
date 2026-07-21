@extends('layouts.management')

@section('title', 'Event Categories')

@section('styles')
<style>
    .event-card {
        background: #fff;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
    }
    .event-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .event-image-wrapper {
        height: 180px;
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    .event-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .event-content {
        padding: 20px;
    }
    .event-name {
        font-weight: 700;
        font-size: 1.25rem;
        color: #2d3748;
        margin-bottom: 8px;
    }
    .event-description {
        color: #718096;
        font-size: 0.9rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .add-category-card {
        background: #e2e8f0;
        border-radius: 25px;
        height: 100%;
        min-height: 280px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px dashed #cbd5e0;
        transition: all 0.3s ease;
        color: #718096;
    }
    .add-category-card:hover {
        background: #edf2f7;
        border-color: var(--brilliant-green);
        color: var(--brilliant-green);
    }
    .add-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }
    .edit-category-btn {
        position: absolute;
        top: 15px;
        right: 55px;
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: rgba(124, 163, 97, 0.9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: all 0.3s ease;
        border: none;
        z-index: 5;
    }
    .event-card:hover .edit-category-btn {
        opacity: 1;
    }
    .edit-category-btn:hover {
        background: var(--brilliant-green);
        transform: scale(1.1);
    }
    .delete-category-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: rgba(255, 77, 77, 0.9);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: all 0.3s ease;
        border: none;
        z-index: 5;
    }
    .event-card:hover .delete-category-btn {
        opacity: 1;
    }
    .delete-category-btn:hover {
        background: #ff4d4d;
        transform: scale(1.1);
    }
    .modal-content {
        border-radius: 25px;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #edf2f7;
        padding: 25px;
    }
    .modal-footer {
        border-top: 1px solid #edf2f7;
        padding: 20px 25px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title m-0">Event</h1>
    </div>

    <div class="row g-4">
        @foreach($categories as $category)
        <div class="col-md-6 col-lg-4 col-xl-4">
            <div class="event-card">
                <a href="{{ route('management.event.list', $category->slug) }}" class="text-decoration-none">
                    <div class="event-image-wrapper">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="event-image">
                        @else
                            <div class="event-image d-flex align-items-center justify-content-center bg-light">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <div class="event-content">
                        <h5 class="event-name">{{ $category->name }}</h5>
                        <p class="event-description">{{ $category->description ?? 'No description provided.' }}</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editCategory(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const description = button.getAttribute('data-description');
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        
        const form = document.getElementById('editCategoryForm');
        form.action = "{{ route('management.event.category.update', ':id') }}".replace(':id', id);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Handle Delete Confirmation
        const deleteButtons = document.querySelectorAll('.delete-category-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Delete Category?',
                    text: "All events under this category might be affected.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4d4d',
                    cancelButtonColor: '#718096',
                    confirmButtonText: 'Yes, Delete!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Success Notification
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    });
</script>
@endsection
