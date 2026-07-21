<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Requirement Document (PRD)</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-primary: #020a06;
            --bg-secondary: #05140d;
            --bg-glass: rgba(255, 255, 255, 0.02);
            --border-glass: rgba(255, 255, 255, 0.08);
            --accent-green: #10b981;
            --accent-mint: #34d399;
            --accent-teal: #14b8a6;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --glow-color: rgba(16, 185, 129, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(16, 185, 129, 0.12) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(20, 184, 166, 0.08) 0%, transparent 40%);
            background-attachment: fixed;
        }

        /* Ambient Glow Element */
        .ambient-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            top: -200px;
            left: 30%;
            z-index: -1;
            pointer-events: none;
            filter: blur(80px);
        }

        /* Container Layout */
        .container {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            gap: 2.5rem;
        }

        /* Sidebar Navigation */
        aside {
            width: 320px;
            position: sticky;
            top: 2rem;
            height: calc(100vh - 4rem);
            background: var(--bg-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 2.5rem 1.8rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
        }

        .sidebar-brand-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--accent-green), var(--accent-mint));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        }

        .sidebar-brand-logo svg {
            width: 22px;
            height: 22px;
            fill: #fff;
        }

        .sidebar-brand h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(to right, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            flex-grow: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem 1.2rem;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 14px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            transition: var(--transition);
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            box-shadow: inset 0 0 12px rgba(16, 185, 129, 0.05);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.15) 0%, rgba(20, 184, 166, 0.05) 100%);
            border-left: 3px solid var(--accent-green);
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-glass);
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
        }

        /* Main Content */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3rem;
            padding-bottom: 5rem;
        }

        /* Hero Section */
        .hero {
            position: relative;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 32px;
            padding: 3.5rem 3rem;
            overflow: hidden;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-green), var(--accent-mint), var(--accent-teal));
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 1rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(16, 185, 129, 0.15);
            color: #a7f3d0;
            border: 1px solid rgba(16, 185, 129, 0.3);
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #a7f3d0 50%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p.subtitle {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 800px;
        }

        /* Section Styling */
        section {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }

        section:hover {
            border-color: rgba(16, 185, 129, 0.2);
            box-shadow: 0 8px 32px 0 rgba(16, 185, 129, 0.05);
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -0.5rem;
            width: 40px;
            height: 3px;
            background: var(--accent-green);
            border-radius: 99px;
        }

        .text-content {
            color: var(--text-muted);
            line-height: 1.75;
            font-size: 1rem;
        }

        /* Persona Grid */
        .persona-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .persona-card {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 2rem;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .persona-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), transparent);
            opacity: 0;
            transition: var(--transition);
        }

        .persona-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-green);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.1);
        }

        .persona-card:hover::before {
            opacity: 1;
        }

        .persona-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: var(--transition);
        }

        .persona-card:hover .persona-icon {
            background: var(--accent-green);
            color: #fff;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
        }

        .persona-card h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #fff;
        }

        .persona-card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Modal Overlay for Persona Details */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(2, 6, 4, 0.85);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            z-index: 1000;
            transition: var(--transition);
        }

        .modal.active {
            opacity: 1;
            pointer-events: all;
        }

        .modal-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            max-width: 600px;
            width: 90%;
            padding: 2.5rem;
            position: relative;
            transform: scale(0.9);
            transition: var(--transition);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5);
        }

        .modal.active .modal-content {
            transform: scale(1);
        }

        .close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .close-btn:hover {
            color: #fff;
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }

        .modal-body ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .modal-body li {
            position: relative;
            padding-left: 1.75rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--text-muted);
        }

        .modal-body li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--accent-mint);
            font-weight: bold;
        }

        /* Interactive Accordion for Features */
        .feature-accordion {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .accordion-item {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            overflow: hidden;
            transition: var(--transition);
        }

        .accordion-item:hover {
            border-color: rgba(16, 185, 129, 0.3);
        }

        .accordion-header {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
            transition: var(--transition);
        }

        .accordion-header:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .accordion-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 600;
        }

        .accordion-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-green);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .accordion-arrow {
            width: 20px;
            height: 20px;
            stroke: var(--text-muted);
            fill: none;
            transition: transform 0.3s ease;
        }

        .accordion-item.active .accordion-arrow {
            transform: rotate(180deg);
            stroke: #fff;
        }

        .accordion-item.active {
            border-color: var(--accent-green);
            background: rgba(16, 185, 129, 0.02);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            padding: 0 1.5rem;
        }

        .accordion-item.active .accordion-content {
            padding-bottom: 1.5rem;
            max-height: 500px; /* Adjust according to contents */
        }

        .feature-list {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-glass);
        }

        .feature-subitem {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            transition: var(--transition);
        }

        .feature-subitem:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(20, 184, 166, 0.3);
            transform: translateX(3px);
        }

        .feature-subitem-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-mint);
            margin-top: 6px;
            flex-shrink: 0;
            box-shadow: 0 0 8px var(--accent-mint);
        }

        .feature-subitem-text {
            font-size: 0.92rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Methodology Showcase */
        .methodology-flow {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            position: relative;
        }

        .methodology-step {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            position: relative;
        }

        .methodology-step::after {
            content: '→';
            position: absolute;
            right: -1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: var(--accent-green);
            opacity: 0.7;
        }

        .methodology-step:last-child::after {
            display: none;
        }

        .methodology-step:hover {
            border-color: var(--accent-mint);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(52, 211, 153, 0.1);
        }

        .methodology-num {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-green), var(--accent-mint));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            margin: 0 auto 1rem auto;
            box-shadow: 0 0 15px rgba(52, 211, 153, 0.3);
        }

        .methodology-step h4 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .methodology-step p {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.3);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.5);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }
            aside {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }
            .methodology-step::after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 2.5rem 1.5rem;
            }
            .hero h1 {
                font-size: 2rem;
            }
            section {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>

    <div class="container">
        <!-- Sidebar Navigation -->
        <aside>
            <div>
                <div class="sidebar-brand">
                    <div class="sidebar-brand-logo">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <h2>Wedding System</h2>
                </div>
                <ul class="nav-list">
                    <li>
                        <a href="#pendahuluan" class="nav-link active">
                            <svg viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Pendahuluan
                        </a>
                    </li>
                    <li>
                        <a href="#pengguna" class="nav-link">
                            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="7" r="4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 3.13a4 4 0 0 1 0 7.75" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Pengguna Sistem
                        </a>
                    </li>
                    <li>
                        <a href="#fitur" class="nav-link">
                            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="14" y="3" width="7" height="5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="14" y="12" width="7" height="9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="16" width="7" height="5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Fitur Utama
                        </a>
                    </li>
                    <li>
                        <a href="#metodologi" class="nav-link">
                            <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Metode Prototype
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sidebar-footer">
                PRD Page &copy; 2026<br>
                CV. Brilliant Bertaqwa Berdaya
            </div>
        </aside>

        <!-- Main Content Area -->
        <main>
            <!-- Hero -->
            <div class="hero">
                <span class="badge">Laravel 13 System</span>
                <h1>Product Requirement Document</h1>
                <p class="subtitle">Sistem Informasi Manajemen Wedding Organizer menggunakan Metode Prototype dengan Studi Kasus pada CV. Brilliant Bertaqwa Berdaya.</p>
            </div>

            <!-- Pendahuluan -->
            <section id="pendahuluan">
                <h2 class="section-title">1. Pendahuluan & Latar Belakang</h2>
                <div class="text-content">
                    <p style="margin-bottom: 1.25rem;">
                        <strong>Sistem Manajemen Pernikahan dan Acara (Wedding & Event Management System)</strong> adalah platform berbasis web yang dirancang untuk membantu Event Organizer (EO) dalam mengelola seluruh siklus hidup perencanaan acara secara profesional dan efisien.
                    </p>
                    <p>
                        Dengan cakupan fitur mulai dari manajemen paket, penugasan kru (crew assignment), koordinasi vendor, penyusunan rundown, hingga pelacakan tugas (to-do list) dan pembayaran. Selain itu, sistem ini menyediakan portal akses bagi Klien (calon pengantin/penyelenggara) dan Tamu berbasis QR Code untuk memudahkan akses informasi seperti undangan digital, susunan acara (rundown), dokumentasi, buku tamu digital (guest book), dan testimoni secara instan dan tanpa ribet.
                    </p>
                </div>
            </section>

            <!-- Pengguna Sistem -->
            <section id="pengguna">
                <h2 class="section-title">2. Pengguna Sistem (User Personas & Roles)</h2>
                <div class="text-content">
                    <p>Klik pada salah satu kartu peran pengguna di bawah ini untuk melihat daftar rincian hak aksesnya:</p>
                    
                    <div class="persona-grid">
                        <!-- Admin Card -->
                        <div class="persona-card" onclick="openModal('admin')">
                            <div class="persona-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                            </div>
                            <h3>Admin</h3>
                            <p>Memiliki kontrol penuh atas sistem, manajemen master data, pengaturan web, dan otorisasi pengguna.</p>
                        </div>

                        <!-- Kru Acara Card -->
                        <div class="persona-card" onclick="openModal('kru')">
                            <div class="persona-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <h3>Kru Acara</h3>
                            <p>Terdiri atas Leader (penanggung jawab event) & Member yang bertugas mengelola serta mengeksekusi jalannya acara.</p>
                        </div>

                        <!-- Klien Card -->
                        <div class="persona-card" onclick="openModal('klien')">
                            <div class="persona-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h3>Klien</h3>
                            <p>Mengakses portal khusus undangan secara publik dengan QR Code token tanpa perlu proses registrasi akun manual.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Fitur Utama -->
            <section id="fitur">
                <h2 class="section-title">3. Fitur Utama</h2>
                
                <div class="feature-accordion">
                    <!-- Feature Item 1 -->
                    <div class="accordion-item active">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <div class="accordion-title">
                                <div class="accordion-icon-wrap">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M21 12H3"/></svg>
                                </div>
                                Manajemen Paket & Item Layanan
                            </div>
                            <svg class="accordion-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="accordion-content">
                            <div class="feature-list">
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Kategori Acara:</strong> Pengelompokan jenis acara secara kustom (seperti Wedding, Engagement, Corporate Event).
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Paket Layanan:</strong> Pembuatan paket promo/layanan dengan penentuan harga awal (original price) dan harga coret (final price).
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Rincian Item:</strong> Menentukan daftar detail fasilitas atau benefit apa saja yang akan didapatkan pada paket tersebut.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Item 2 -->
                    <div class="accordion-item">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <div class="accordion-title">
                                <div class="accordion-icon-wrap">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                Manajemen Acara (Event Management)
                            </div>
                            <svg class="accordion-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="accordion-content">
                            <div class="feature-list">
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Pendaftaran Acara Baru:</strong> Form komprehensif mencakup data klien, pengantin pria/wanita, tanggal acara, venue, link GMaps, serta kategori.
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Token QR Otomatis:</strong> Generate otomatis token akses QR untuk Klien dan Tamu lengkap dengan konfigurasi aktif/non-aktifkan akses.
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Penugasan Kru:</strong> Penunjukan anggota kru dalam suatu event dan pengangkatan salah satunya sebagai Leader (penanggung jawab).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Item 3 -->
                    <div class="accordion-item">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <div class="accordion-title">
                                <div class="accordion-icon-wrap">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                Event Planning Tools
                            </div>
                            <svg class="accordion-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="accordion-content">
                            <div class="feature-list">
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Susunan Acara (Rundown):</strong> Menyusun jadwal acara harian secara sistematis dengan waktu mulai, waktu selesai, dan penanggung jawab aktivitas.
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Manajemen Tugas (Todos):</strong> Delegasi to-do list untuk kru lengkap dengan status pengerjaan dan tanggal batas waktu (due date).
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Manajemen Vendor & Catatan:</strong> Integrasi vendor eksternal serta penyediaan catatan internal interaktif bagi kru untuk meminimalkan miskomunikasi.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Item 4 -->
                    <div class="accordion-item">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <div class="accordion-title">
                                <div class="accordion-icon-wrap">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </div>
                                Sistem Pembayaran (Payment System)
                            </div>
                            <svg class="accordion-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="accordion-content">
                            <div class="feature-list">
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Penerbitan Invoice:</strong> Pembuatan berkas tagihan invoice otomatis setiap pemesanan event baru terdaftar.
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Pelacakan Transaksi:</strong> Pencatatan status pembayaran (DP, Angsuran, Pelunasan) beserta unggah bukti bayar dan persetujuan manual admin.
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Opsi Integrasi Pembayaran:</strong> Kolom parameter untuk kemudahan perluasan pembayaran online (Gateway Midtrans).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Item 5 -->
                    <div class="accordion-item">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <div class="accordion-title">
                                <div class="accordion-icon-wrap">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12 7.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 9a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                </div>
                                Portal Akses Klien & Tamu
                            </div>
                            <svg class="accordion-arrow" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="accordion-content">
                            <div class="feature-list">
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Undangan & Rundown Digital:</strong> Tampilan informasi pengantin dan jadwal acara yang diperbarui secara langsung (real-time).
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Buku Tamu & Testimoni:</strong> Tamu dapat mengisi daftar kehadiran, mengunggah dokumentasi, serta memberikan ulasan/rating pelayanan.
                                    </div>
                                </div>
                                <div class="feature-subitem">
                                    <div class="feature-subitem-dot"></div>
                                    <div class="feature-subitem-text">
                                        <strong>Tanpa Registrasi:</strong> Seluruh akses dilakukan dengan memindai kode QR unik yang langsung merujuk ke token parameter dinamis.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Metodologi Prototype -->
            <section id="metodologi">
                <h2 class="section-title">4. Metode Pengembangan Prototype</h2>
                <div class="text-content">
                    <p style="margin-bottom: 2rem;">Pendekatan prototype memungkinkan iterasi yang cepat demi memastikan kualitas aplikasi yang sesuai dengan kebutuhan CV. Brilliant Bertaqwa Berdaya.</p>
                    
                    <div class="methodology-flow">
                        <div class="methodology-step">
                            <div class="methodology-num">1</div>
                            <h4>Analisis Kebutuhan</h4>
                            <p>Menggali detail informasi kebutuhan sistem secara lengkap.</p>
                        </div>
                        <div class="methodology-step">
                            <div class="methodology-num">2</div>
                            <h4>Desain Cepat</h4>
                            <p>Merancang sketsa dasar, alur data, serta visualisasi antarmuka kasar.</p>
                        </div>
                        <div class="methodology-step">
                            <div class="methodology-num">3</div>
                            <h4>Bangun Prototype</h4>
                            <p>Pembuatan versi awal produk dengan Laravel untuk diuji.</p>
                        </div>
                        <div class="methodology-step">
                            <div class="methodology-num">4</div>
                            <h4>Evaluasi Klien</h4>
                            <p>Review langsung oleh Klien, dilanjutkan dengan perbaikan berkala.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modals for User Personas -->
    <div id="modal-admin" class="modal" onclick="closeModalOnOuterClick(event, 'admin')">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('admin')">&times;</button>
            <div class="modal-header">
                <div class="persona-icon" style="margin-bottom: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                </div>
                <h3>Detail Otorisasi: Admin</h3>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Memiliki kontrol administratif penuh atas keseluruhan sistem dan modul.</li>
                    <li>Mengelola master data utama: kategori acara, paket layanan, vendor eksternal, dan pertanyaan umum (FAQ).</li>
                    <li>Mengelola konfigurasi tampilan website landing page utama.</li>
                    <li>Mengatur hak akses dan penugasan peran manajemen (Roles & Permissions) untuk kru.</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="modal-kru" class="modal" onclick="closeModalOnOuterClick(event, 'kru')">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('kru')">&times;</button>
            <div class="modal-header">
                <div class="persona-icon" style="margin-bottom: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/></svg>
                </div>
                <h3>Detail Otorisasi: Kru Acara</h3>
            </div>
            <div class="modal-body">
                <ul>
                    <li><strong>Leader:</strong> Bertanggung jawab penuh sebagai pimpinan proyek di lapangan. Berhak mengedit susunan rundown, delegasi tugas (todos), catatan internal, serta memvalidasi pembayaran klien.</li>
                    <li><strong>Member:</strong> Anggota operasional lapangan yang dapat melihat informasi utama event, mencentang progress tugas yang telah didelegasikan kepadanya, serta berkirim catatan koordinasi.</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="modal-klien" class="modal" onclick="closeModalOnOuterClick(event, 'klien')">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal('klien')">&times;</button>
            <div class="modal-header">
                <div class="persona-icon" style="margin-bottom: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>Detail Otorisasi: Klien / Tamu</h3>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Mengakses portal undangan digital, rundown, serta galeri foto secara instan melalui pemindaian tautan QR.</li>
                    <li>Klien dapat memantau tagihan, mengunduh file invoice resmi PDF, serta melacak daftar nama tamu undangan yang telah hadir di lokasi.</li>
                    <li>Tamu dapat mengisi buku kehadiran tamu (Guest Book) digital serta memberikan rating & review kepuasan acara setelah selesai.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Scrollspy Navigation highlight
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 250)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Click handler to manually make links active immediately
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.forEach(item => item.classList.remove('active'));
                link.classList.add('active');
            });
        });

        // Accordion Toggle function
        function toggleAccordion(header) {
            const item = header.parentElement;
            const items = document.querySelectorAll('.accordion-item');
            
            // Toggle active state
            if (item.classList.contains('active')) {
                item.classList.remove('active');
            } else {
                // Optional: Close other open items
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            }
        }

        // Modal triggers
        function openModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            if (modal) {
                modal.classList.add('active');
            }
        }

        // Close Modal
        function closeModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function closeModalOnOuterClick(event, id) {
            const modal = document.getElementById(`modal-${id}`);
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        }
    </script>
</body>
</html>
