<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Brilliant Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --brilliant-bg: #f4f7f4;
            --sidebar-width: 260px;
            --sidebar-minimized-width: 80px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brilliant-bg);
            margin: 0;
            overflow-x: hidden;
            transition: all 0.3s;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #ffffff;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 1000;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
        }

        .sidebar.minimized {
            width: var(--sidebar-minimized-width);
            padding: 1.5rem 0.5rem;
        }

        .sidebar-logo {
            padding: 0 1rem 2rem 1rem;
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-logo img {
            max-height: 50px;
            width: auto;
            transition: all 0.3s ease;
        }

        .sidebar-logo .mini-logo {
            display: none;
        }

        .sidebar.minimized .sidebar-logo .full-logo {
            display: none;
        }

        .sidebar.minimized .sidebar-logo {
            padding: 0 0.5rem 2rem 0.5rem;
        }

        .sidebar.minimized .sidebar-logo .mini-logo {
            display: block;
            max-height: 45px;
            width: auto;
            margin: 0 auto;
        }

        .nav-link {
            color: #4a5568;
            font-weight: 500;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.minimized .nav-link {
            padding: 0.8rem 0;
            justify-content: center;
            gap: 0;
        }

        .sidebar.minimized .nav-link span {
            display: none;
        }

        .sidebar.minimized .nav-link .dropdown-icon {
            display: none !important;
        }

        .sidebar.minimized .submenu-container {
            display: none !important;
        }

        .nav-link i {
            font-size: 1.2rem;
            min-width: 24px;
            text-align: center;
        }

        .nav-link .dropdown-icon {
            font-size: 0.8rem;
            min-width: auto;
            transition: transform 0.2s ease;
        }

        .nav-link[aria-expanded="true"] .dropdown-icon {
            transform: rotate(180deg);
        }

        .submenu-link:hover {
            color: var(--brilliant-green) !important;
        }

        .nav-link:hover {
            background-color: var(--brilliant-green-light);
            color: var(--brilliant-green-dark);
        }

        .nav-link.active {
            background-color: var(--brilliant-green);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(124, 163, 97, 0.2);
        }

        /* Toggle Button Styling */
        .sidebar-toggle {
            background: none;
            border: none;
            color: #4a5568;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background-color: var(--brilliant-green-light);
            color: var(--brilliant-green-dark);
        }

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .sidebar.minimized+.main-content {
            margin-left: var(--sidebar-minimized-width);
        }

        .top-bar {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .welcome-text {
            font-weight: 500;
            color: #4a5568;
        }

        .welcome-text span {
            color: var(--brilliant-green);
            font-weight: 700;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            color: #4a5568;
            font-size: 1.4rem;
            padding: 0.5rem;
            transition: color 0.2s;
        }

        .notification-btn:hover {
            color: var(--brilliant-green);
        }

        .notification-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background-color: #e53e3e;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        .user-profile-btn {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.3rem 0.5rem;
            border-radius: 12px;
            transition: background-color 0.2s;
        }

        .user-profile-btn:hover {
            background-color: var(--brilliant-green-light);
        }

        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            background-color: var(--brilliant-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 10px !important;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 500;
            color: #4a5568;
        }

        .dropdown-item:hover {
            background-color: var(--brilliant-green-light);
            color: var(--brilliant-green-dark);
        }

        .dropdown-item i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .page-title {
            font-weight: 700;
            font-size: 2.5rem;
            color: #2d3748;
            margin-bottom: 2rem;
            opacity: 0.6;
        }

        /* Global Button Overrides */
        .btn-primary {
            background-color: var(--brilliant-green);
            border-color: var(--brilliant-green);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--brilliant-green-dark) !important;
            border-color: var(--brilliant-green-dark) !important;
            box-shadow: 0 4px 12px rgba(124, 163, 97, 0.3) !important;
        }

        /* Card Widget Styling */
        .card-widget {
            background-color: #ffffff;
            border: none;
            border-radius: 20px;
            padding: 1.5rem;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .card-green {
            background-color: var(--brilliant-green-dark);
            color: #ffffff;
        }

        .stat-item {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            min-width: 80px;
            text-align: center;
            font-weight: 700;
            margin-right: 1rem;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }

            .sidebar.mobile-active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
            
            /* Override minimized state on mobile */
            .sidebar.minimized {
                width: var(--sidebar-width);
                padding: 1.5rem 1rem;
            }
            .sidebar.minimized .sidebar-logo {
                padding: 0 1rem 2rem 1rem;
            }
            .sidebar.minimized .sidebar-logo .full-logo {
                display: block;
            }
            .sidebar.minimized .sidebar-logo .mini-logo {
                display: none;
            }
            .sidebar.minimized .nav-link {
                padding: 0.8rem 1.2rem;
                justify-content: flex-start;
                gap: 12px;
            }
            .sidebar.minimized .nav-link span {
                display: block;
            }
            .sidebar.minimized .nav-link .dropdown-icon {
                display: block !important;
            }
            .sidebar.minimized .submenu-container {
                display: flex !important;
            }
            .sidebar.minimized + .main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Brilliant Logo" class="full-logo">
            <img src="{{ asset('assets/icon.png') }}" alt="Brilliant Icon" class="mini-logo">
        </div>

        <nav class="nav flex-column">
            @if(Auth::guard('management')->user()->hasPermission('dashboard-view'))
                <a href="{{ route('management.dashboard') }}"
                    class="nav-link {{ request()->routeIs('management.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                </a>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('event-view') || Auth::guard('management')->user()->hasPermission('client-access-manage'))
                <a href="#eventMenu" data-bs-toggle="collapse"
                    class="nav-link {{ request()->routeIs('management.event*') || request()->routeIs('management.client-access*') ? 'active' : '' }}"
                    aria-expanded="{{ request()->routeIs('management.event*') || request()->routeIs('management.client-access*') ? 'true' : 'false' }}">
                    <i class="bi bi-calendar-event"></i> <span>Event</span>
                    <i class="bi bi-chevron-down dropdown-icon ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('management.event*') || request()->routeIs('management.client-access*') ? 'show' : '' }}" id="eventMenu">
                    <div class="d-flex flex-column ps-4 ms-2 mt-1 mb-2 submenu-container">
                        @if(Auth::guard('management')->user()->hasPermission('event-view'))
                            <a href="{{ route('management.event') }}" class="text-decoration-none mb-2 submenu-link"
                                style="color: {{ request()->routeIs('management.event') ? 'var(--brilliant-green)' : '#4a5568' }}; font-weight: 500; font-size: 0.95rem;">
                                <i class="bi bi-chevron-right fw-bold me-1" style="font-size: 0.65rem;"></i> Event Detail
                            </a>
                        @endif
                        @if(Auth::guard('management')->user()->hasPermission('client-access-manage'))
                            <a href="{{ route('management.client-access.index') }}"
                                class="text-decoration-none mb-2 submenu-link"
                                style="color: {{ request()->routeIs('management.client-access*') ? 'var(--brilliant-green)' : '#4a5568' }}; font-weight: 500; font-size: 0.95rem;">
                                <i class="bi bi-chevron-right fw-bold me-1" style="font-size: 0.65rem;"></i> Client Access
                            </a>
                        @endif
                        @if(Auth::guard('management')->user()->hasPermission('event-view'))
                            <a href="{{ route('management.event.feedback') }}" class="text-decoration-none submenu-link"
                                style="color: {{ request()->routeIs('management.event.feedback') ? 'var(--brilliant-green)' : '#4a5568' }}; font-weight: 500; font-size: 0.95rem;">
                                <i class="bi bi-chevron-right fw-bold me-1" style="font-size: 0.65rem;"></i> Feedback
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('crew-manage'))
                <a href="{{ route('management.crew.index') }}"
                    class="nav-link {{ request()->routeIs('management.crew*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>Crew</span>
                </a>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('vendor-manage'))
                <a href="{{ route('management.vendor.index') }}"
                    class="nav-link {{ request()->routeIs('management.vendor*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i> <span>Vendor</span>
                </a>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('package-manage'))
                <a href="{{ route('management.package.index') }}"
                    class="nav-link {{ request()->routeIs('management.package*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> <span>Package</span>
                </a>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('payment-manage'))
                <a href="{{ route('management.payment.index') }}"
                    class="nav-link {{ request()->routeIs('management.payment*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> <span>Payment</span>
                </a>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('financial-view'))
                <a href="{{ route('management.financial.index') }}"
                    class="nav-link {{ request()->routeIs('management.financial*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i> <span>Financial Statements</span>
                </a>
            @endif
            @if(Auth::guard('management')->user()->hasPermission('system-setting'))
                <a href="#systemMenu" data-bs-toggle="collapse"
                    class="nav-link mt-4 {{ request()->routeIs('management.system-setting*') ? 'active' : '' }}"
                    aria-expanded="{{ request()->routeIs('management.system-setting*') ? 'true' : 'false' }}">
                    <i class="bi bi-shield-lock"></i> <span>System Setting</span>
                    <i class="bi bi-chevron-down dropdown-icon ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('management.system-setting*') ? 'show' : '' }}" id="systemMenu">
                    <div class="d-flex flex-column ps-4 ms-2 mt-1 mb-2 submenu-container">
                        <a href="{{ route('management.system-setting.index') }}"
                            class="text-decoration-none mb-2 submenu-link"
                            style="color: {{ request()->routeIs('management.system-setting.index') ? 'var(--brilliant-green)' : '#4a5568' }}; font-weight: 500; font-size: 0.95rem;">
                            <i class="bi bi-gear fw-bold me-1" style="font-size: 0.65rem;"></i> Role Access
                        </a>
                        <a href="{{ route('management.system-setting.event-category.index') }}"
                            class="text-decoration-none mb-2 submenu-link"
                            style="color: {{ request()->routeIs('management.system-setting.event-category.index') ? 'var(--brilliant-green)' : '#4a5568' }}; font-weight: 500; font-size: 0.95rem;">
                            <i class="bi bi-grid fw-bold me-1" style="font-size: 0.65rem;"></i> Event Category
                        </a>
                        <a href="{{ route('management.system-setting.quotes.index') }}"
                            class="text-decoration-none submenu-link"
                            style="color: {{ request()->routeIs('management.system-setting.quotes.index') ? 'var(--brilliant-green)' : '#4a5568' }}; font-weight: 500; font-size: 0.95rem;">
                            <i class="bi bi-chat-quote fw-bold me-1" style="font-size: 0.65rem;"></i> Quotes
                        </a>
                    </div>
                </div>
            @endif

            @if(Auth::guard('management')->user()->hasPermission('system-setting'))
                <a href="{{ route('management.website-setting.index') }}"
                    class="nav-link {{ request()->routeIs('management.website-setting.index') ? 'active' : '' }}">
                    <i class="bi bi-window"></i> <span>Website Settings</span>
                </a>
            @endif

            <form action="{{ route('management.logout') }}" method="POST" class="mt-auto logout-form">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="welcome-text">
                    Welcome back <span>{{ Auth::guard('management')->user()->name }}</span> ❤️
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Notification Dropdown -->
                <div class="dropdown">
                    <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdownBtn">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge d-none" id="notificationBadge"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0" style="width: 320px; overflow: hidden;" id="notificationMenu">
                        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold">Notifikasi</h6>
                            <span class="badge bg-primary rounded-pill" id="notificationCountBadge">0</span>
                        </div>
                        <div id="notificationList" style="max-height: 350px; overflow-y: auto;">
                            <!-- Notifications will be loaded here via AJAX -->
                            <div class="text-center p-4 text-muted">
                                <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                                <div class="small">Memuat notifikasi...</div>
                            </div>
                        </div>
                        <div class="p-2 bg-light border-top text-center">
                            <a class="text-decoration-none small fw-bold text-primary" href="{{ route('management.notification.index') }}">
                                Lihat Semua Notifikasi <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="user-profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-img">
                            @if(Auth::guard('management')->user()->avatar)
                                <img src="{{ asset('storage/' . Auth::guard('management')->user()->avatar) }}" alt="Avatar"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                            @else
                                {{ substr(Auth::guard('management')->user()->name, 0, 1) }}
                            @endif
                        </div>
                        <i class="bi bi-chevron-down small text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="px-3 py-2">
                                <div class="fw-bold text-dark">{{ Auth::guard('management')->user()->name }}</div>
                                <div class="small text-muted">{{ Auth::guard('management')->user()->email }}</div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('management.profile') }}"><i
                                    class="bi bi-person"></i> My Profile</a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('management.logout') }}" method="POST" class="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/management.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var sidebar = document.querySelector('.sidebar');
            var toggleBtn = document.getElementById('sidebarToggle');
            var overlay = document.getElementById('sidebarOverlay');
            if (!sidebar || !toggleBtn) return;

            var saved = localStorage.getItem('sidebar-minimized');
            if (saved === 'true') {
                sidebar.classList.add('minimized');
            }

            toggleBtn.addEventListener('click', function () {
                if (window.innerWidth <= 992) {
                    sidebar.classList.toggle('mobile-active');
                    if (sidebar.classList.contains('mobile-active')) {
                        if(overlay) overlay.classList.add('show');
                        document.body.style.overflow = 'hidden';
                    } else {
                        if(overlay) overlay.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                } else {
                    sidebar.classList.toggle('minimized');
                    localStorage.setItem('sidebar-minimized', sidebar.classList.contains('minimized'));
                }
            });

            if (overlay) {
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('mobile-active');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                });
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('mobile-active');
                    if(overlay) overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });

            // Notification System
            function fetchNotifications() {
                fetch('{{ route("management.notification.recent") }}')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('notificationBadge');
                        const countBadge = document.getElementById('notificationCountBadge');
                        const list = document.getElementById('notificationList');
                        
                        if (data.unread_count > 0) {
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }
                        countBadge.textContent = data.unread_count;

                        if (data.notifications.length === 0) {
                            list.innerHTML = `
                                <div class="text-center p-4 text-muted">
                                    <i class="bi bi-bell-slash fs-4 mb-2 d-block"></i>
                                    <div class="small">Belum ada notifikasi</div>
                                </div>
                            `;
                            return;
                        }

                        list.innerHTML = data.notifications.map(notif => {
                            const isUnread = notif.read_at === null;
                            const d = notif.data;
                            
                            // Format date roughly (for simplicity just show basic string, in a real app use moment.js or backend formatted string)
                            // But for now, we just show title and message
                            return `
                                <a class="dropdown-item p-3 border-bottom text-wrap ${isUnread ? 'bg-light' : ''}" href="${d.url ? d.url : '#'}">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="text-primary mt-1"><i class="bi ${isUnread ? 'bi-circle-fill' : 'bi-circle'}" style="font-size: 0.5rem;"></i></div>
                                        <div>
                                            <div class="fw-bold small ${isUnread ? 'text-dark' : 'text-muted'}">${d.title}</div>
                                            <div class="text-muted" style="font-size: 0.75rem; line-height: 1.4;">${d.message}</div>
                                        </div>
                                    </div>
                                </a>
                            `;
                        }).join('');
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            }

            // Fetch on load
            fetchNotifications();
            // Fetch every 30 seconds
            setInterval(fetchNotifications, 30000);

            // Handle clicking nav-links with collapse while minimized
            document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (sidebar.classList.contains('minimized')) {
                        sidebar.classList.remove('minimized');
                        localStorage.setItem('sidebar-minimized', 'false');
                    }
                });
            });

            // Logout Confirmation
            document.querySelectorAll('.logout-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda akan keluar dari sesi ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7ca361',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Logout!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>