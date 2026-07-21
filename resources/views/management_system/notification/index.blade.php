@extends('layouts.management')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-0">Semua Notifikasi</h1>
            <p class="text-muted">Pusat informasi terbaru tentang akun dan event Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('management.notification.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                    <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                </button>
            </form>
            <form action="{{ route('management.notification.clear-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold">
                    <i class="bi bi-trash me-1"></i> Hapus Semua
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-widget p-0 border-0 overflow-hidden">
        <div class="list-group list-group-flush rounded-4">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <div class="list-group-item list-group-item-action p-4 border-bottom {{ $isUnread ? 'bg-light' : 'bg-white' }}">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 48px; height: 48px; background-color: {{ $isUnread ? 'var(--brilliant-green-light)' : '#f8f9fa' }}; color: {{ $isUnread ? 'var(--brilliant-green)' : '#6c757d' }};">
                            <i class="bi bi-bell-fill fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold {{ $isUnread ? 'text-dark' : 'text-muted' }}">{{ $data['title'] ?? 'Notifikasi Baru' }}</h6>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-2 text-secondary" style="font-size: 0.95rem;">
                                {{ $data['message'] ?? '' }}
                            </p>
                            <div class="d-flex gap-2">
                                @if(isset($data['url']))
                                    <a href="{{ $data['url'] }}" class="btn btn-sm btn-primary rounded-pill px-3" style="font-size: 0.8rem;">
                                        Lihat Detail
                                    </a>
                                @endif
                                
                                @if($isUnread)
                                    <form action="{{ route('management.notification.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.8rem;">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('management.notification.destroy', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 30px; height: 30px; font-size: 0.8rem;" title="Hapus Notifikasi">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($isUnread)
                            <div class="align-self-center ms-3">
                                <span class="badge bg-danger rounded-circle p-1"><span class="visually-hidden">Unread</span></span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-bell-slash fs-1 text-light mb-3"></i>
                    <h5>Tidak Ada Notifikasi</h5>
                    <p class="mb-0">Anda tidak memiliki notifikasi saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
