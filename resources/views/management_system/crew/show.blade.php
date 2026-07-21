@extends('layouts.management')

@section('title', 'Crew Detail - ' . $crew->name)

@section('styles')
<style>
    .crew-header {
        font-size: 2.5rem;
        color: #999;
        font-weight: 300;
        margin-bottom: 1.5rem;
    }

    .detail-card {
        background: #fff;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-title {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        color: #555;
        letter-spacing: 0.5px;
    }

    .card-body-custom {
        padding: 2.5rem 1.5rem;
        text-align: center;
    }

    .detail-avatar-wrapper {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
    }

    .detail-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #eee;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .status-available { background-color: #e8f5e9; color: #2e7d32; }
    .status-busy { background-color: #ffebee; color: #c62828; }
    .status-off { background-color: #f5f5f5; color: #616161; }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-available .status-dot { background-color: #2e7d32; }
    .status-busy .status-dot { background-color: #c62828; }
    .status-off .status-dot { background-color: #616161; }

    .join-date {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
    }

    .total-events {
        color: #555;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 140px 1fr;
        text-align: left;
        gap: 12px;
        max-width: 400px;
        margin: 0 auto;
    }

    .info-label {
        color: #555;
        font-weight: 400;
    }

    .info-value {
        color: #333;
        font-weight: 400;
    }

    .btn-edit-float {
        color: #999;
        background: none;
        border: none;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .btn-edit-float:hover {
        color: #41612A;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #41612A;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }

    .back-btn:hover {
        color: #2d441d;
        transform: translateX(-5px);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <a href="{{ route('management.crew.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
    
    <h1 class="crew-header">Crew - Detail</h1>

    <div class="detail-card">
        <div class="card-header-custom">
            <span class="card-header-title">PROFILE CARD</span>
            <button class="btn-edit-float" title="Edit Profile">
                <i class="bi bi-pencil-fill"></i>
            </button>
        </div>
        <div class="card-body-custom">
            <div class="detail-avatar-wrapper">
                @if($crew->avatar)
                    <img src="{{ asset('storage/' . $crew->avatar) }}" alt="{{ $crew->name }}" class="detail-avatar">
                @else
                    <div class="detail-avatar d-flex align-items-center justify-content-center bg-light">
                        <i class="bi bi-person text-muted fs-1"></i>
                    </div>
                @endif
            </div>

            <div class="status-badge status-{{ strtolower($crew->status) }}">
                <span class="status-dot"></span> {{ $crew->status }}
            </div>

            <div class="join-date">Joined Since {{ $crew->joined_at ? $crew->joined_at->format('Y') : '-' }}</div>
            <div class="total-events">{{ $crew->total_events_handled ?? 0 }} Total Event Handled</div>

            <div class="info-grid mt-4">
                <div class="info-label">Name</div>
                <div class="info-value">: {{ $crew->name }}</div>

                <div class="info-label">Birth Date</div>
                <div class="info-value">: {{ $crew->birth_date ? $crew->birth_date->format('d/m/Y') : '-' }}</div>

                <div class="info-label">Age</div>
                <div class="info-value">: {{ $crew->birth_date ? $crew->birth_date->age : '-' }}</div>

                <div class="info-label">Address</div>
                <div class="info-value">: {{ $crew->address ?? '-' }}</div>

                <div class="info-label">Gender</div>
                <div class="info-value">: {{ $crew->gender ?? '-' }}</div>

                <div class="info-label">Email Address</div>
                <div class="info-value">: {{ $crew->email }}</div>

                <div class="info-label">Phone Number</div>
                <div class="info-value">: {{ $crew->phone_number ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="detail-card mt-4">
        <div class="card-header-custom">
            <span class="card-header-title">EVENT HISTORY</span>
        </div>
        <div class="card-body-custom" style="text-align: left;">
            @if($eventHistory->isEmpty())
                <div class="text-muted">Belum ada riwayat event yang diselesaikan.</div>
            @else
                <div class="list-group">
                    @foreach($eventHistory as $event)
                        @php
                            $pivotCrew = $event->crews->firstWhere('id', $crew->id);
                            $isLeader = $pivotCrew ? (bool)$pivotCrew->pivot->is_leader : false;
                            $roleText = $isLeader ? 'Leader' : 'Crew';
                            $roleClass = $isLeader ? 'bg-warning-subtle text-warning' : 'bg-primary-subtle text-primary';
                        @endphp
                        <a href="{{ route('management.event.show', $event->id) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $event->name }}</div>
                                <div class="small text-muted">
                                    {{ $event->date->format('d F Y') }} • {{ $event->venue }}
                                </div>
                            </div>
                            <span class="badge {{ $roleClass }}">{{ $roleText }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
