@extends('layouts.management')

@section('title', 'Event Detail - ' . $event->name)

@section('styles')
    <style>
        :root {
            --brilliant-green: #6D9C4C;
            --brilliant-green-light: #F0F4ED;
            --brilliant-green-dark: #41612A;
        }

        .detail-card {
            background: #fff;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 30px;
            height: 100%;
        }

        .nav-tabs-custom {
            border: none;
            background: var(--brilliant-green-light);
            padding: 5px;
            border-radius: 15px;
            display: inline-flex;
            margin-bottom: 25px;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            color: #718096;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-tabs-custom .nav-link.active {
            background: var(--brilliant-green);
            color: #fff;
        }

        .info-label {
            color: #a0aec0;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .status-badge-large {
            padding: 10px 25px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
        }

        .crew-item,
        .vendor-item {
            background: #f8fafc;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
        }

        .crew-item:hover,
        .vendor-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .todo-item {
            border-bottom: 1px solid #edf2f7;
            padding: 15px 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .todo-item:last-child {
            border: none;
        }

        .todo-checkbox {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            border: 2px solid var(--brilliant-green);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .todo-checkbox.disabled {
            border-color: #cbd5e0;
            cursor: not-allowed;
            background: #f7fafc;
            color: #cbd5e0;
        }

        .todo-checkbox.checked {
            background: var(--brilliant-green);
            color: #fff;
        }

        .todo-text.completed {
            text-decoration: line-through;
            color: #a0aec0;
        }

        .rundown-timeline {
            position: relative;
            padding-left: 30px;
        }

        .rundown-timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #edf2f7;
        }

        .rundown-item {
            position: relative;
            margin-bottom: 30px;
        }

        .rundown-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--brilliant-green);
            border: 2px solid #fff;
            box-shadow: 0 0 0 4px var(--brilliant-green-light);
        }

        .note-editor {
            border: none;
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            min-height: 400px;
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.6;
        }

        .note-editor:focus {
            outline: none;
            background: #f1f5f9;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            color: #718096;
            transition: all 0.2s;
        }

        .btn-icon:hover {
            background: #ff4d4d;
            color: #fff;
            transform: scale(1.1);
        }

        .crew-select-card,
        .vendor-select-card {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid #edf2f7 !important;
        }

        .crew-select-card:hover,
        .vendor-select-card:hover {
            background-color: var(--brilliant-green-light);
            border-color: var(--brilliant-green) !important;
        }

        .crew-select-card input[type="checkbox"]:checked,
        .vendor-select-card input[type="checkbox"]:checked {
            background-color: var(--brilliant-green);
            border-color: var(--brilliant-green);
        }

        .repeater-item {
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }

        .btn-remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #ef4444;
            cursor: pointer;
            background: #fee2e2;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.2s;
        }

        .btn-remove-item:hover {
            background: #ef4444;
            color: #fff;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmStartEvent(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Event akan dimulai!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, mulai event!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
        function confirmEndEvent(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Event akan diakhiri!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, akhiri event!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
    </script>
@endpush

@section('content')
    @php
        $currentUser = auth()->guard('management')->user();
        $isAdministrator = $currentUser && $currentUser->role === 'admin';
        $isTeamLeader = $event->crews->contains(function ($crew) use ($currentUser) {
            return $currentUser && $crew->id === $currentUser->id && $crew->pivot->is_leader;
        });
        $hasEditPermission = $currentUser && $currentUser->hasPermission('event-edit');
        $canEdit = $isAdministrator || $isTeamLeader || $hasEditPermission;
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('management.event') }}"
                                class="text-decoration-none text-muted">Event</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('management.event.list', $event->category->slug) }}"
                                class="text-decoration-none text-muted">{{ $event->category->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $event->name }}</li>
                    </ol>
                </nav>
                <h1 class="page-title m-0">{{ $event->name }}</h1>
            </div>
            <div>
                @php
                    $statusClass = 'status-' . strtolower(str_replace(' ', '', $event->status));
                    $btnClass = '';
                    if ($event->status == 'Upcoming')
                        $btnClass = 'bg-primary-subtle text-primary';
                    elseif ($event->status == 'In Progress')
                        $btnClass = 'bg-warning-subtle text-warning';
                    else
                        $btnClass = 'bg-success-subtle text-success';
                @endphp
                <span class="status-badge-large {{ $btnClass }}">{{ $event->status }}</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Navigation & Content -->
            <div class="col-lg-8">
                <div class="detail-card">
                    <ul class="nav nav-tabs-custom" id="eventTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                                type="button">Info</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="crew-tab" data-bs-toggle="tab" data-bs-target="#crew"
                                type="button">Crew & Vendor</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="todo-tab" data-bs-toggle="tab" data-bs-target="#todo"
                                type="button">To-Do List</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="rundown-tab" data-bs-toggle="tab" data-bs-target="#rundown"
                                type="button">Rundown</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes"
                                type="button">Notes</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="eventTabsContent">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-label">Client Name</div>
                                    <div class="info-value">{{ $event->client_name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Event Date</div>
                                    <div class="info-value">{{ $event->date->format('l, d F Y') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Venue</div>
                                    <div class="info-value">{{ $event->venue }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Event Type</div>
                                    <div class="info-value">{{ $event->type }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Client Phone</div>
                                    <div class="info-value">{{ $event->client_phone ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Client Email</div>
                                    <div class="info-value">{{ $event->client_email ?? '-' }}</div>
                                </div>
                                <!-- <div class="col-md-12 mt-4">
                                    <div class="info-label">Client Address</div>
                                    <div class="info-value fw-normal">{{ $event->client_address ?? '-' }}</div>
                                </div> -->
                            </div>
                        </div>

                        <!-- Crew & Vendor Tab -->
                        <div class="tab-pane fade" id="crew">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold m-0">Crew Team</h5>
                                        @if($canEdit && $event->status == 'Upcoming')
                                            <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#addCrewModal">
                                                <i class="bi bi-plus"></i> Add Crew
                                            </button>
                                        @endif
                                    </div>
                                    <div class="crew-list">
                                        @forelse($event->crews as $crew)
                                            <div class="crew-item">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar-sm bg-light rounded-circle overflow-hidden"
                                                        style="width: 40px; height: 40px;">
                                                        @if($crew->avatar)
                                                            <img src="{{ asset('storage/' . $crew->avatar) }}" alt=""
                                                                style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <div
                                                                class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                                <i class="bi bi-person"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $crew->name }}</div>
                                                        @if($crew->pivot->is_leader)
                                                            <span class="badge bg-warning-subtle text-warning small">Team
                                                                Leader</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($canEdit)
                                                    <form
                                                        action="{{ route('management.event.crew.remove', [$event->id, $crew->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-icon" title="Remove Crew">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @empty
                                            <p class="text-muted small">No crew assigned yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-md-6 ps-md-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold m-0">Vendors</h5>
                                        @if($canEdit && $event->status == 'Upcoming')
                                            <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#addVendorModal">
                                                <i class="bi bi-plus"></i> Add Vendor
                                            </button>
                                        @endif
                                    </div>
                                    <div class="vendor-list">
                                        @forelse($event->vendors as $vendor)
                                            <div class="vendor-item">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar-sm bg-light rounded-3 overflow-hidden"
                                                        style="width: 40px; height: 40px;">
                                                        @if($vendor->logo)
                                                            <img src="{{ asset('storage/' . $vendor->logo) }}" alt=""
                                                                style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <div
                                                                class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                                <i class="bi bi-shop"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $vendor->name }}</div>
                                                        <div class="text-muted small">{{ $vendor->category }}</div>
                                                    </div>
                                                </div>
                                                @if($canEdit)
                                                    <form
                                                        action="{{ route('management.event.vendor.remove', [$event->id, $vendor->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-icon" title="Remove Vendor">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @empty
                                            <p class="text-muted small">No vendors assigned yet.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- To-Do Tab -->
                        <div class="tab-pane fade" id="todo">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold m-0">Task List</h5>
                                @if($canEdit && $event->status == 'Upcoming')
                                    <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#addTodoModal">
                                        <i class="bi bi-plus"></i> New Task
                                    </button>
                                @endif
                            </div>
                            <div class="todo-list">
                                @forelse($event->todos as $todo)
                                    @php
                                        $isAssigned = $todo->management_user_id === auth()->guard('management')->id();
                                        $canToggle = $canEdit || $isAssigned;
                                    @endphp
                                    <div class="todo-item d-flex align-items-center">
                                        <div class="todo-checkbox {{ $todo->is_completed ? 'checked' : '' }} {{ !$canToggle ? 'disabled' : '' }}"
                                            @if($canToggle) onclick="toggleTodo({{ $todo->id }}, this)" @endif
                                            title="{{ !$canToggle ? 'Hanya crew yang di-assign atau Team Leader/Administrator yang dapat mengubah status tugas' : '' }}">
                                            <i class="bi bi-check-lg {{ $todo->is_completed ? '' : 'd-none' }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="todo-text fw-bold {{ $todo->is_completed ? 'completed' : '' }}">
                                                {{ $todo->title }}</div>
                                            <div class="small text-muted">
                                                {{ $todo->category }} • Assigned to:
                                                <span class="{{ $isAssigned ? 'text-primary fw-bold' : '' }}">
                                                    {{ $todo->assignedTo->name ?? 'Unassigned' }}
                                                </span>
                                                @if($todo->due_date)
                                                    • Due: {{ $todo->due_date->format('d M Y') }}
                                                @endif
                                            </div>
                                        </div>
                                        @if($canEdit && $event->status != 'Completed')
                                            <div class="d-flex gap-2 ms-3">
                                                <button class="btn-icon" title="Edit Task"
                                                    onclick="editTodo({{ $todo->id }}, '{{ addslashes($todo->title) }}', '{{ $todo->category }}', '{{ $todo->management_user_id }}', '{{ $todo->due_date ? $todo->due_date->format('Y-m-d') : '' }}')">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <form action="{{ route('management.event.todo.destroy', $todo->id) }}" method="POST"
                                                    class="delete-todo-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-icon text-danger" title="Delete Task"
                                                        onclick="confirmDeleteTodo(this)">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-4">No tasks added yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Rundown Tab -->
                        <div class="tab-pane fade" id="rundown">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold m-0">Event Rundown</h5>
                                @if($canEdit && $event->status == 'Upcoming')
                                    <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal"
                                        data-bs-target="#addRundownModal">
                                        <i class="bi bi-plus"></i> Add Item
                                    </button>
                                @endif
                            </div>
                            <div class="rundown-timeline">
                                @php $currentDay = 0; @endphp
                                @forelse($event->rundowns->sortBy(['day', 'time_start']) as $rundown)
                                    @if($rundown->day != $currentDay)
                                        @php $currentDay = $rundown->day; @endphp
                                        <div class="fw-bold text-primary mb-3 mt-4">DAY {{ $currentDay }}</div>
                                    @endif
                                    <div class="rundown-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-bold">
                                                    {{ \Carbon\Carbon::parse($rundown->time_start)->format('H:i') }} -
                                                    {{ $rundown->time_end ? \Carbon\Carbon::parse($rundown->time_end)->format('H:i') : 'Selesai' }}
                                                </div>
                                                <div class="text-dark">{{ $rundown->activity }}</div>
                                            </div>
                                            @if($canEdit && $event->status != 'Completed')
                                                <div class="d-flex gap-2">
                                                    <button class="btn-icon" title="Edit Rundown"
                                                        onclick="editRundown({{ $rundown->id }}, {{ $rundown->day }}, '{{ $rundown->time_start }}', '{{ $rundown->time_end }}', '{{ addslashes($rundown->activity) }}')">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <form action="{{ route('management.event.rundown.destroy', $rundown->id) }}"
                                                        method="POST" class="delete-rundown-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn-icon text-danger" title="Delete Rundown"
                                                            onclick="confirmDeleteRundown(this)">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-4">No rundown items added yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Notes Tab -->
                        <div class="tab-pane fade" id="notes">
                            <form action="{{ route('management.event.notes.update', $event->id) }}" method="POST">
                                @csrf
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold m-0">Event Notes</h5>
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill">
                                        <i class="bi bi-save"></i> Save Notes
                                    </button>
                                </div>
                                <textarea name="content" class="note-editor"
                                    placeholder="Type event details, special requests, or internal notes here...">{{ $event->notes->content ?? '' }}</textarea>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary Widgets -->
            <div class="col-lg-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="detail-card">
                            <h5 class="fw-bold mb-3">Progress</h5>
                            @php
                                $totalTasks = $event->todos->count();
                                $completedTasks = $event->todos->where('is_completed', true)->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small fw-bold">{{ $percentage }}% Tasks Completed</span>
                                <span class="small text-muted">{{ $completedTasks }}/{{ $totalTasks }}</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-card" style="background: var(--brilliant-green); color: #fff;">
                            <h5 class="fw-bold mb-3">Quick Actions</h5>
                            <div class="d-grid gap-2">
                                @if($canEdit)
                                    @if($event->status == 'Upcoming')
                                        <form action="{{ route('management.event.start', $event->id) }}" method="POST"
                                            onsubmit="confirmStartEvent(event)">
                                            @csrf
                                            <button type="submit" class="btn btn-light text-start fw-bold rounded-3 w-100 mb-2">
                                                <i class="bi bi-play-fill me-2"></i> Start Event
                                            </button>
                                        </form>
                                    @elseif($event->status == 'In Progress')
                                        <form action="{{ route('management.event.end', $event->id) }}" method="POST"
                                            onsubmit="confirmEndEvent(event)">
                                            @csrf
                                            <button type="submit" class="btn btn-warning text-start fw-bold rounded-3 w-100 mb-2">
                                                <i class="bi bi-stop-fill me-2"></i> End Event
                                            </button>
                                        </form>
                                    @endif

                                    <button class="btn btn-light text-start fw-bold rounded-3" data-bs-toggle="modal"
                                        data-bs-target="#editEventModal" {{ $event->status == 'Completed' ? 'disabled' : '' }}>
                                        <i class="bi bi-pencil-square me-2"></i> Edit Event Detail
                                    </button>
                                @endif
                                <a href="{{ route('management.event.export-pdf', $event->id) }}"
                                    class="btn btn-light text-start fw-bold rounded-3">
                                    <i class="bi bi-file-earmark-pdf me-2"></i> Download Event Brief
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Crew Modal -->
    <div class="modal fade" id="addCrewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('management.event.crew.add', $event->id) }}" method="POST" id="addCrewForm">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Assign Crew Members</h5>
                            <p class="text-muted small mb-0">Select one or more crew members to assign to this event.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 overflow-auto" style="max-height: 400px;">
                            @foreach($availableCrews as $crew)
                                @if(!$event->crews->contains($crew->id))
                                    <div class="col-md-6">
                                        <label class="crew-select-card d-flex align-items-center p-3 rounded-4">
                                            <input type="checkbox" name="management_user_ids[]" value="{{ $crew->id }}"
                                                class="form-check-input me-3">
                                            <div class="avatar-sm bg-light rounded-circle overflow-hidden me-3"
                                                style="width: 45px; height: 45px; min-width: 45px;">
                                                @if($crew->avatar)
                                                    <img src="{{ asset('storage/' . $crew->avatar) }}" alt=""
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div
                                                        class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-secondary-subtle">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="fw-bold text-truncate">{{ $crew->name }}</div>
                                                <div class="text-muted small text-truncate">{{ $crew->role }}</div>
                                            </div>
                                            @if(!$event->crews->contains(function ($c) {
                                                return $c->pivot->is_leader; }))
                                                <div class="ms-2">
                                                    <div class="form-check form-switch" title="Set as Leader">
                                                        <input class="form-check-input" type="radio" name="leader_id"
                                                            value="{{ $crew->id }}" id="leader_{{ $crew->id }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2">Add Selected
                            Crew</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Vendor Modal -->
    <div class="modal fade" id="addVendorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('management.event.vendor.add', $event->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Assign Vendors</h5>
                            <p class="text-muted small mb-0">Select one or more vendors to assign to this event.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 overflow-auto" style="max-height: 400px;">
                            @foreach($vendors as $vendor)
                                @if(!$event->vendors->contains($vendor->id))
                                    <div class="col-md-6">
                                        <label class="vendor-select-card d-flex align-items-center p-3 rounded-4">
                                            <input type="checkbox" name="vendor_ids[]" value="{{ $vendor->id }}"
                                                class="form-check-input me-3">
                                            <div class="avatar-sm bg-light rounded-3 overflow-hidden me-3"
                                                style="width: 45px; height: 45px; min-width: 45px;">
                                                @if($vendor->logo)
                                                    <img src="{{ asset('storage/' . $vendor->logo) }}" alt=""
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div
                                                        class="w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-secondary-subtle">
                                                        <i class="bi bi-shop"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="fw-bold text-truncate">{{ $vendor->name }}</div>
                                                <div class="text-muted small text-truncate">{{ $vendor->category }}</div>
                                            </div>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2">Assign Selected
                            Vendors</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add To-Do Modal -->
    <div class="modal fade" id="addTodoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('management.event.todo.add', $event->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Add Multiple Tasks</h5>
                            <p class="text-muted small mb-0">Create multiple tasks at once for this event.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="todo-repeater-container">
                            <div class="repeater-item" data-index="0">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Task Title</label>
                                        <input type="text" name="todos[0][title]" class="form-control rounded-3" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Category</label>
                                        <select name="todos[0][category]" class="form-select rounded-3" required>
                                            <option value="Crew">Crew</option>
                                            <option value="Vendor">Vendor</option>
                                            <option value="Client">Client</option>
                                            <option value="Documentation">Documentation</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Assign To</label>
                                        <select name="todos[0][management_user_id]" class="form-select rounded-3">
                                            <option value="">Unassigned</option>
                                            @foreach($event->crews as $crew)
                                                <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Due Date</label>
                                        <input type="date" name="todos[0][due_date]" class="form-control rounded-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill fw-bold"
                            onclick="addTodoItem()">
                            <i class="bi bi-plus"></i> Add More Task
                        </button>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2">Create All
                            Tasks</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Rundown Modal -->
    <div class="modal fade" id="addRundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="{{ route('management.event.rundown.add', $event->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 p-4 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold">Add Multiple Rundown Items</h5>
                            <p class="text-muted small mb-0">Create the event schedule in bulk.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="rundown-repeater-container">
                            <div class="repeater-item" data-index="0">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold">Day</label>
                                        <input type="number" name="rundowns[0][day]" class="form-control rounded-3"
                                            value="1" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Time Start</label>
                                        <input type="time" name="rundowns[0][time_start]" class="form-control rounded-3"
                                            required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Time End</label>
                                        <input type="time" name="rundowns[0][time_end]" class="form-control rounded-3">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Activity</label>
                                        <input type="text" name="rundowns[0][activity]" class="form-control rounded-3"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill fw-bold"
                            onclick="addRundownItem()">
                            <i class="bi bi-plus"></i> Add More Rundown
                        </button>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2">Create All
                            Items</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Rundown Modal -->
    <div class="modal fade" id="editRundownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="editRundownForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 p-4">
                        <h5 class="modal-title fw-bold">Edit Rundown Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Day</label>
                                <input type="number" name="day" id="edit_rundown_day" class="form-control rounded-3" min="1"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Time Start</label>
                                <input type="time" name="time_start" id="edit_rundown_start" class="form-control rounded-3"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Time End</label>
                                <input type="time" name="time_end" id="edit_rundown_end" class="form-control rounded-3">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Activity</label>
                                <input type="text" name="activity" id="edit_rundown_activity" class="form-control rounded-3"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit To-Do Modal -->
    <div class="modal fade" id="editTodoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form id="editTodoForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 p-4">
                        <h5 class="modal-title fw-bold">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Task Title</label>
                                <input type="text" name="title" id="edit_todo_title" class="form-control rounded-3"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Category</label>
                                <select name="category" id="edit_todo_category" class="form-select rounded-3" required>
                                    <option value="Crew">Crew</option>
                                    <option value="Vendor">Vendor</option>
                                    <option value="Client">Client</option>
                                    <option value="Documentation">Documentation</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Assign To</label>
                                <select name="management_user_id" id="edit_todo_assignee" class="form-select rounded-3">
                                    <option value="">Unassigned</option>
                                    @foreach($event->crews as $crew)
                                        <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Due Date</label>
                                <input type="date" name="due_date" id="edit_todo_due_date" class="form-control rounded-3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill" data-bs-modal="modal"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('management.event.update', $event->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 p-4">
                        <h5 class="modal-title fw-bold">Edit Event Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Event Name</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ $event->name }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Client Name</label>
                                <input type="text" name="client_name" class="form-control rounded-3"
                                    value="{{ $event->client_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Client Phone (WhatsApp)</label>
                                <input type="text" name="client_phone" class="form-control rounded-3"
                                    value="{{ $event->client_phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Client Email</label>
                                <input type="email" name="client_email" class="form-control rounded-3"
                                    value="{{ $event->client_email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Groom Name (Pria)</label>
                                <input type="text" name="groom_name" class="form-control rounded-3"
                                    value="{{ $event->groom_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Bride Name (Wanita)</label>
                                <input type="text" name="bride_name" class="form-control rounded-3"
                                    value="{{ $event->bride_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Event Date</label>
                                <input type="date" name="date" class="form-control rounded-3"
                                    value="{{ $event->date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Venue</label>
                                <input type="text" name="venue" class="form-control rounded-3" value="{{ $event->venue }}"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">Google Maps Link</label>
                                <input type="text" name="google_maps_link" class="form-control rounded-3"
                                    value="{{ $event->google_maps_link }}" placeholder="https://goo.gl/maps/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Event Type</label>
                                <input type="text" name="type" class="form-control rounded-3" value="{{ $event->type }}"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Status</label>
                                <select name="status" class="form-select rounded-3">
                                    <option value="Upcoming" {{ $event->status == 'Upcoming' ? 'selected' : '' }}>Upcoming
                                    </option>
                                    <option value="In Progress" {{ $event->status == 'In Progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="Completed" {{ $event->status == 'Completed' ? 'selected' : '' }}>Completed
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 fw-bold rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleTodo(id, element) {
            const isChecking = !element.classList.contains('checked');
            const title = isChecking ? 'Selesaikan Tugas?' : 'Batalkan Penyelesaian?';
            const text = isChecking ? 'Apakah Anda sudah menyelesaikan tugas ini?' : 'Tugas akan dikembalikan ke status pending.';
            const confirmButtonText = isChecking ? 'Ya, Selesai!' : 'Ya, Batalkan!';

            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6D9C4C',
                cancelButtonColor: '#718096',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    const originalContent = element.innerHTML;
                    element.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

                    const url = "{{ route('management.event.todo.toggle', ':id') }}".replace(':id', id);

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; }).catch(() => {
                                    throw { message: `Server error: ${response.status} ${response.statusText}` };
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Reset innerHTML
                                element.innerHTML = '<i class="bi bi-check-lg ' + (data.is_completed ? '' : 'd-none') + '"></i>';

                                if (data.is_completed) {
                                    element.classList.add('checked');
                                    element.nextElementSibling.querySelector('.todo-text').classList.add('completed');
                                } else {
                                    element.classList.remove('checked');
                                    element.nextElementSibling.querySelector('.todo-text').classList.remove('completed');
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    // Reload to update progress bar if needed
                                    window.location.reload();
                                });
                            } else {
                                element.innerHTML = originalContent;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan saat memperbarui status tugas.',
                                });
                            }
                        })
                        .catch(error => {
                            element.innerHTML = originalContent;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: error.message || 'Terjadi kesalahan pada server.',
                            });
                        });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle anchor in URL to open specific tab
            const hash = window.location.hash;
            if (hash) {
                const tabEl = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (tabEl) {
                    const tab = new bootstrap.Tab(tabEl);
                    tab.show();
                }
            }

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#6D9C4C'
                });
            @endif

            const addCrewForm = document.getElementById('addCrewForm');
            if (addCrewForm) {
                addCrewForm.addEventListener('submit', function (e) {
                    const checkedCrews = document.querySelectorAll('input[name="management_user_ids[]"]:checked');
                    const checkedLeader = document.querySelector('input[name="leader_id"]:checked');
                    const hasExistingLeader = {{ $event->crews->contains(function ($crew) {
        return $crew->pivot->is_leader; }) ? 'true' : 'false' }};

                    if (checkedCrews.length > 0 && !checkedLeader && !hasExistingLeader) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Leader Diperlukan',
                            text: 'Harap pilih satu Leader untuk tim crew ini!',
                            confirmButtonColor: '#6D9C4C'
                        });
                    } else if (checkedCrews.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Crew',
                            text: 'Harap pilih setidaknya satu crew!',
                            confirmButtonColor: '#6D9C4C'
                        });
                    }
                });
            }
        });

        let todoIndex = 1;
        function addTodoItem() {
            const container = document.getElementById('todo-repeater-container');
            const newItem = document.createElement('div');
            newItem.className = 'repeater-item';
            newItem.dataset.index = todoIndex;
            newItem.innerHTML = `
                <div class="btn-remove-item" onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Task Title</label>
                        <input type="text" name="todos[${todoIndex}][title]" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Category</label>
                        <select name="todos[${todoIndex}][category]" class="form-select rounded-3" required>
                            <option value="Crew">Crew</option>
                            <option value="Vendor">Vendor</option>
                            <option value="Client">Client</option>
                            <option value="Documentation">Documentation</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Assign To</label>
                        <select name="todos[${todoIndex}][management_user_id]" class="form-select rounded-3">
                            <option value="">Unassigned</option>
                            @foreach($event->crews as $crew)
                                <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Due Date</label>
                        <input type="date" name="todos[${todoIndex}][due_date]" class="form-control rounded-3">
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            todoIndex++;
        }

        let rundownIndex = 1;
        function addRundownItem() {
            const container = document.getElementById('rundown-repeater-container');

            // Get the last item's time end and day
            const items = container.querySelectorAll('.repeater-item');
            const lastItem = items[items.length - 1];
            let lastTimeEnd = '';
            let lastDay = '1';

            if (lastItem) {
                const timeEndInput = lastItem.querySelector('input[name*="[time_end]"]');
                const dayInput = lastItem.querySelector('input[name*="[day]"]');
                if (timeEndInput) lastTimeEnd = timeEndInput.value;
                if (dayInput) lastDay = dayInput.value;
            }

            const newItem = document.createElement('div');
            newItem.className = 'repeater-item';
            newItem.dataset.index = rundownIndex;
            newItem.innerHTML = `
                <div class="btn-remove-item" onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Day</label>
                        <input type="number" name="rundowns[${rundownIndex}][day]" class="form-control rounded-3" value="${lastDay}" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Time Start</label>
                        <input type="time" name="rundowns[${rundownIndex}][time_start]" class="form-control rounded-3" value="${lastTimeEnd}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Time End</label>
                        <input type="time" name="rundowns[${rundownIndex}][time_end]" class="form-control rounded-3">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Activity</label>
                        <input type="text" name="rundowns[${rundownIndex}][activity]" class="form-control rounded-3" required>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            rundownIndex++;
        }

        function editTodo(id, title, category, assignee, dueDate) {
            const modal = new bootstrap.Modal(document.getElementById('editTodoModal'));
            const form = document.getElementById('editTodoForm');

            form.action = "{{ route('management.event.todo.update', ':id') }}".replace(':id', id);
            document.getElementById('edit_todo_title').value = title;
            document.getElementById('edit_todo_category').value = category;
            document.getElementById('edit_todo_assignee').value = assignee || '';
            document.getElementById('edit_todo_due_date').value = dueDate;

            modal.show();
        }

        function confirmDeleteTodo(button) {
            const form = button.closest('.delete-todo-form');
            Swal.fire({
                title: 'Hapus Tugas?',
                text: "Tugas akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#718096',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function editRundown(id, day, start, end, activity) {
            const modal = new bootstrap.Modal(document.getElementById('editRundownModal'));
            const form = document.getElementById('editRundownForm');

            form.action = "{{ route('management.event.rundown.update', ':id') }}".replace(':id', id);
            document.getElementById('edit_rundown_day').value = day;
            document.getElementById('edit_rundown_start').value = start.substring(0, 5); // Format HH:mm
            document.getElementById('edit_rundown_end').value = end ? end.substring(0, 5) : '';
            document.getElementById('edit_rundown_activity').value = activity;

            modal.show();
        }

        function confirmDeleteRundown(button) {
            const form = button.closest('.delete-rundown-form');
            Swal.fire({
                title: 'Hapus Item Rundown?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#718096',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection