<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SumselPeduli') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style>
        .transition-all { transition: all 0.3s ease; }
        .hover-opacity:hover { opacity: 0.8; }
        :root {
            --primary-color: #243E36;
            --secondary-color: #7CA982;
            --accent-color: #C2A83E;
            --light-color: #F1F7ED;
            --muted-color: #E0EEC6;
            --bg-color: #f8faf7;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --sidebar-width-wide: 240px;
            --sidebar-width-collapsed: 64px;
            --bottom-nav-height: 75px;
        }

        .required::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-color);
            margin: 0;
            overflow-x: hidden;
            transition: padding 0.3s ease;
        }

        /* ── Desktop Sidebar ── */
        .sidebar {
            background-color: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 10px 0 30px rgba(36, 62, 54, 0.05);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 120px; height: 120px;
            background: rgba(124, 169, 130, 0.15);
            border-radius: 50%;
            transition: transform 0.6s ease;
            pointer-events: none;
        }

        .sidebar:hover::before {
            transform: scale(1.4);
        }

        .sidebar.wide { width: var(--sidebar-width-wide); }
        .sidebar.collapsed { width: var(--sidebar-width-collapsed); }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 30px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none !important;
        }

        .sidebar-logo-container {
            width: 36px;
            height: 36px;
            background: var(--secondary-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, background 0.3s;
            flex-shrink: 0;
        }

        .sidebar-brand:hover .sidebar-logo-container {
            transform: rotate(15deg) scale(1.1);
            background: var(--accent-color);
        }

        .sidebar-logo {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 30px 0 20px;
        }

        .sidebar-brand-text-container {
            transition: all 0.3s;
        }

        .sidebar-brand-text {
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            color: var(--secondary-color);
            font-size: 8px;
            letter-spacing: 1.5px;
            margin: 0;
            font-weight: 600;
        }

        .sidebar.collapsed .sidebar-brand-text-container,
        .sidebar.collapsed .nav-label {
            display: none;
        }

        .sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 16px 14px;
            padding: 10px;
            border: none;
            border-radius: 10px;
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.6);
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.15);
            color: var(--accent-color);
        }

        .nav-items {
            flex: 1;
            padding: 8px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        .nav-item-link::before {
            content: '';
            position: absolute;
            left: -100%; top: 0;
            width: 100%; height: 100%;
            background: rgba(124, 169, 130, 0.1);
            transition: left 0.3s ease;
            z-index: 0;
        }

        .nav-item-link:hover::before { left: 0; }

        .nav-item-link::after {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            width: 3px; height: 0;
            background: var(--accent-color);
            border-radius: 0 3px 3px 0;
            transform: translateY(-50%);
            transition: height 0.3s ease;
            z-index: 1;
        }

        .nav-item-link:hover::after,
        .nav-item-link.active::after { height: 60%; }

        .nav-item-link:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.05);
        }

        .nav-item-link.active {
            background: rgba(124, 169, 130, 0.15);
            color: var(--muted-color);
        }

        .nav-item-link i {
            font-size: 20px;
            transition: transform 0.3s ease;
            position: relative; z-index: 2;
        }

        .nav-item-link:hover i { transform: translateX(2px) scale(1.1); }
        .nav-item-link span { position: relative; z-index: 2; }

        .nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.1);
        }

        /* ── Main Content Area ── */
        .main-content {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            min-height: 100vh;
            background-color: var(--bg-color);
            position: relative;
        }

        .main-content.wide-space { padding-left: calc(var(--sidebar-width-wide) + 40px); }
        .main-content.collapsed-space { padding-left: calc(var(--sidebar-width-collapsed) + 40px); }

        /* ── Top Bar ── */
        .top-bar {
            height: 70px;
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .search-container {
            position: relative;
            width: 400px;
        }

        .search-container input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            background-color: #f5f7f5;
            border: 1.5px solid transparent;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-container input:focus {
            outline: none;
            background-color: white;
            border-color: var(--secondary-color);
            box-shadow: 0 4px 15px rgba(107, 143, 113, 0.1);
        }

        .search-container .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: var(--secondary-color);
            z-index: 10;
        }

        .search-container .search-icon svg {
            width: 20px;
            height: 20px;
        }

        .profile-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            border: 2px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Bottom Nav Mobile */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background-color: white;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            z-index: 1100;
            padding-bottom: env(safe-area-inset-bottom);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .bottom-nav-item {
            text-decoration: none;
            color: #888;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            transition: color 0.2s;
            width: 20%;
        }

        .bottom-nav-item i {
            width: 24px;
            height: 24px;
            margin-bottom: 4px;
        }

        .bottom-nav-item.active {
            color: var(--secondary-color);
        }

        /* Mobile specific header */
        .mobile-header {
            height: 65px;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            color: white;
        }

        /* Desktop specific Margin */
        @media (min-width: 992px) {
            .bottom-nav { display: none; }
            .sidebar { display: flex; }
        }

        @media (max-width: 991px) {
            .sidebar { display: none !important; }
            .main-content { padding-left: 0 !important; padding-bottom: var(--bottom-nav-height); }
            .top-bar { display: none !important; }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .role-switcher {
            position: fixed;
            bottom: 25px;
            right: 25px;
            z-index: 2000;
        }

        .btn-role-toggle {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            border: 2px solid var(--accent-color);
            transition: all 0.3s;
        }

        .btn-role-toggle:hover {
            transform: scale(1.05);
            background-color: #2c4a40;
        }

        /* ── Buttons Custom ── */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: var(--light-color);
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.3s;
        }
        .btn-primary-custom::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--secondary-color);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
            z-index: -1;
        }
        .btn-primary-custom:hover::before { transform: scaleX(1); }
        .btn-primary-custom:hover { color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(36, 62, 54, 0.2); }

        .btn-accent-custom {
            background-color: var(--accent-color);
            color: white;
            border: none;
            transition: all 0.3s;
        }
        .btn-accent-custom:hover {
            background-color: #a8902f;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 18px rgba(194,168,62,0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-secondary-custom::after {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--primary-color);
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform 0.3s ease;
            z-index: -1;
        }
        .btn-secondary-custom:hover::after { transform: scaleY(1); }
        .btn-secondary-custom:hover { color: var(--light-color); }

        /* ripple */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
            transform: scale(0);
            animation: rippleAnim 0.6s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim {
            to { transform: scale(4); opacity: 0; }
        }

        /* ── Campaign Cards ── */
        .campaign-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(36,62,54,0.05);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
        }
        .campaign-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(36, 62, 54, 0.1);
            border-color: var(--secondary-color);
        }
        .card-img-container {
            position: relative;
            padding-top: 60%;
            overflow: hidden;
        }
        .card-img-container img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .campaign-card:hover .card-img-container img { transform: scale(1.1); }
        
        .card-img-container::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
            transition: left 0.6s ease;
            z-index: 1;
        }
        .campaign-card:hover .card-img-container::before { left: 150%; }

        .progress-custom {
            height: 6px;
            background: var(--muted-color);
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar-custom {
            height: 100%;
            background: var(--secondary-color);
            border-radius: 10px;
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        @keyframes shimmerBar {
            to { left: 150%; }
        }
        .progress-bar-custom::after {
            content: '';
            position: absolute;
            top: 0; left: -50%;
            width: 50%; height: 100%;
            background: rgba(255,255,255,0.3);
            animation: shimmerBar 2s infinite;
        }

        /* Global Modal Styling */
        .modal {
            z-index: 2000 !important;
        }
        .modal-backdrop {
            z-index: 1070 !important;
        }
        .modal-content {
            border-radius: 28px !important;
            border: none !important;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2) !important;
        }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none sticky-top" style="z-index: 1060;">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white hover-opacity transition-all">
            <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" style="height: 30px; object-fit: contain;">
            <h5 class="m-0 fw-bold tracking-wider">SELUNA</h5>
        </a>
        <div class="profile-area gap-3">
            <button class="btn btn-link p-0 text-white opacity-75" data-bs-toggle="modal" data-bs-target="#infoModal">
                <i data-lucide="info" style="width: 22px;"></i>
            </button>
            @auth
            <a href="{{ route('profile.show') }}">
                <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.Auth::user()->username.'&background=7CA982' }}" class="avatar shadow-sm" style="width: 35px; height: 35px;">
            </a>
            @else
            <a href="{{ route('login') }}" class="text-white text-decoration-none small fw-bold">LOGIN</a>
            @endauth
        </div>
    </div>

    <!-- Sidebar (Desktop Only) -->
    <aside id="sidebar" class="sidebar wide d-none d-lg-flex">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <div class="sidebar-logo-container">
                <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" class="sidebar-logo">
            </div>
            <div class="sidebar-brand-text-container">
                <h1 class="sidebar-brand-text">SELUNA</h1>
                <p class="sidebar-brand-sub">CAHAYA UNTUK SETIAP HARAPAN</p>
            </div>
        </a>

        <button onclick="toggleSidebar()" class="sidebar-toggle">
            <i data-lucide="menu"></i>
        </button>

        <nav class="nav-items" id="sidebar-nav-items">
            <!-- Items injected by JS -->
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item-link logout-item w-100 border-0 text-start bg-transparent">
                    <span class="nav-icon"><i data-lucide="log-out"></i></span>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
            
            <div class="mt-4 px-3 pb-2 nav-label" style="font-size: 10px; opacity: 0.4;">
                <p class="mb-1">&copy; SELUNA 2026</p>
                <p class="mb-2">All rights reserved</p>
                <div class="d-flex gap-2">
                    <a href="https://doc-hosting.flycricket.io/seluna-privacy-policy/26c20c6d-945f-4cfb-a752-a4e69c511147/privacy" target="_blank" class="text-white text-decoration-none hover-white">Privacy Policy</a>
                </div>
                <div class="mt-1">
                    <a href="https://doc-hosting.flycricket.io/seluna-terms-of-use/01968fad-012c-4ea3-af57-ba843e46d7e5/terms" target="_blank" class="text-white text-decoration-none hover-white">Terms of Use</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div id="main-content" class="main-content wide-space">
        <!-- Top Bar (Desktop Only) -->
        <header class="top-bar d-none d-lg-flex">
            <form action="{{ route('campaigns.search') }}" method="GET" class="search-container">
                <span class="search-icon">
                    <i data-lucide="search"></i>
                </span>
                <input type="text" id="main-search-input" name="query" placeholder="Cari kampanye donasi..." value="{{ request('query') }}" autocomplete="off">
                <div id="search-results" class="search-results-dropdown">
                    <!-- Results injected here -->
                </div>
            </form>

            <div class="profile-area">
                @auth
                <a href="{{ route('profile.show') }}" class="text-decoration-none d-flex align-items-center gap-3">
                    <div class="d-flex flex-column align-items-end me-1">
                        <span class="fw-bold small text-primary-custom">{{ Auth::user()->username }}</span>
                        <span class="text-muted" style="font-size: 10px;">{{ Auth::user()->email }}</span>
                    </div>
                    <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.Auth::user()->username.'&background=7CA982' }}" class="avatar">
                </a>
                @else
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm fw-bold">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold">Daftar</a>
                </div>
                @endauth
            </div>
        </header>

        <div class="container-fluid px-lg-5 py-4">
            {{-- Global Alerts --}}
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm animate__animated animate__fadeInDown">
                    <i data-lucide="check-circle" class="me-2" style="width: 18px;"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm animate__animated animate__fadeInDown">
                    <i data-lucide="alert-circle" class="me-2" style="width: 18px;"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Info Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-5 shadow-lg">
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" style="height: 45px;">
                        </div>
                        <h3 class="fw-bold text-primary-custom">Tentang SELUNA</h3>
                        <p class="text-muted">Versi 1.0.4 - 2026</p>
                    </div>
                    
                    <div class="d-grid gap-3 mb-5">
                        <a href="https://doc-hosting.flycricket.io/seluna-privacy-policy/26c20c6d-945f-4cfb-a752-a4e69c511147/privacy" target="_blank" 
                           class="btn btn-light py-3 rounded-4 fw-bold border-0 d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="lock" style="width: 18px;"></i> Privacy Policy
                        </a>
                        <a href="https://doc-hosting.flycricket.io/seluna-terms-of-use/01968fad-012c-4ea3-af57-ba843e46d7e5/terms" target="_blank" 
                           class="btn btn-light py-3 rounded-4 fw-bold border-0 d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="file-text" style="width: 18px;"></i> Terms of Use
                        </a>
                    </div>
                    
                    <div class="pt-4 border-top">
                        <p class="mb-0 text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 1px;">
                            &copy; SELUNA 2026 - ALL RIGHTS RESERVED
                        </p>
                    </div>
                    
                    <button type="button" class="btn btn-primary w-100 mt-5 rounded-pill py-3 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Nav (Mobile/Tablet Only) -->
    <nav class="bottom-nav d-lg-none">
        <!-- Items via JS -->
    </nav>

    <script>
        let currentRole = '{{ Auth::check() ? Auth::user()->role : 'donatur' }}';
        let isSidebarCollapsed = false;

        const campaignUrl = '{{ route('campaigns.index') }}';
        const isCampaignActive = {{ request()->routeIs('campaigns.*') ? 'true' : 'false' }};

        const navItems = [
            { id: 'home', label: 'Home', icon: 'home', url: '{{ route('home') }}', active: {{ request()->routeIs('home') ? 'true' : 'false' }} },
            { id: 'follow', label: 'Follow', icon: 'heart', url: '{{ route('campaigns.followed') }}', active: {{ request()->routeIs('campaigns.followed') ? 'true' : 'false' }} },
            { id: 'campaign', label: 'Your Campaign', icon: 'layout-grid', url: '{{ route('campaigns.index') }}', active: {{ request()->routeIs('campaigns.index') ? 'true' : 'false' }} },
            { id: 'archive', label: 'Archive', icon: 'archive', url: '{{ route('profile.archived') }}', active: {{ request()->routeIs('profile.archived') ? 'true' : 'false' }} }
        ];

        function renderNav() {
            const sidebar = document.getElementById('sidebar-nav-items');
            const bottomNav = document.querySelector('.bottom-nav');
            const items = navConfig[currentRole];

            sidebar.innerHTML = items.map(item => `
                <a href="${item.url}" class="nav-item-link ${item.active ? 'active' : ''}">
                    <span class="nav-icon"><i data-lucide="${item.icon}"></i></span>
                    <span class="nav-label">${item.label}</span>
                </a>
            `).join('');

            bottomNav.innerHTML = items.map(item => `
                <a href="${item.url}" class="bottom-nav-item ${item.active ? 'active' : ''}">
                    <span class="nav-icon"><i data-lucide="${item.icon}" style="width: 22px;"></i></span>
                    <span class="mt-1">${item.label}</span>
                </a>
            `).join('');

            lucide.createIcons();
        }

        @guest
        const navConfig = { donatur: navItems, fundraiser: navItems };
        @endguest
        @auth
        const navConfig = { donatur: navItems, fundraiser: navItems };
        @endauth

        function renderNav() {
            const sidebar = document.getElementById('sidebar-nav-items');
            const bottomNav = document.querySelector('.bottom-nav');
            const items = navConfig[currentRole];

            sidebar.innerHTML = items.map(item => `
                <a href="${item.url}" class="nav-item-link ${item.active ? 'active' : ''}">
                    <span class="nav-icon"><i data-lucide="${item.icon}"></i></span>
                    <span class="nav-label">${item.label}</span>
                </a>
            `).join('');

            bottomNav.innerHTML = items.map(item => `
                <a href="${item.url}" class="bottom-nav-item ${item.active ? 'active' : ''}">
                    <span class="nav-icon"><i data-lucide="${item.icon}" style="width: 22px;"></i></span>
                    <span class="mt-1">${item.label}</span>
                </a>
            `).join('');

            lucide.createIcons();
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (isSidebarCollapsed) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.add('wide');
                mainContent.classList.remove('collapsed-space');
                mainContent.classList.add('wide-space');
            } else {
                sidebar.classList.remove('wide');
                sidebar.classList.add('collapsed');
                mainContent.classList.remove('wide-space');
                mainContent.classList.add('collapsed-space');
            }
            isSidebarCollapsed = !isSidebarCollapsed;
        }

        function switchRole() {
            currentRole = currentRole === 'donatur' ? 'fundraiser' : 'donatur';
            renderNav();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderNav();
            
            // ── Live Search Logic ──
            const searchInput = document.getElementById('main-search-input');
            const searchResults = document.getElementById('search-results');
            let searchTimeout = null;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();
                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        searchResults.style.display = 'none';
                        return;
                    }

                    searchTimeout = setTimeout(() => {
                        fetch(`{{ route('api.campaigns.search') }}?query=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.length > 0) {
                                    searchResults.innerHTML = data.map(item => `
                                        <a href="/my-campaigns/${item.id}" class="search-result-item">
                                            <div class="icon-box">
                                                <i data-lucide="layout-grid" style="width:16px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold small">${item.title}</div>
                                                <div class="text-muted" style="font-size:10px;">${item.tag || 'No Category'}</div>
                                            </div>
                                        </a>
                                    `).join('');
                                    searchResults.style.display = 'block';
                                    lucide.createIcons();
                                } else {
                                    searchResults.innerHTML = '<div class="p-3 text-center text-muted small">Tidak ada kampanye ditemukan.</div>';
                                    searchResults.style.display = 'block';
                                }
                            });
                    }, 300);
                });

                // Hide results on click away
                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.style.display = 'none';
                    }
                });
            }

            // ── Real-time Status Sync Polling ──
            @auth
            const startSync = () => {
                setInterval(() => {
                    fetch('{{ route('api.sync') }}')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            // Sync role if changed (e.g. admin approved fundraiser)
                            if (data.role !== currentRole) {
                                window.location.reload(); 
                            }
                            // Store last known donation ID to detect NEW ones
                            const lastDonationId = localStorage.getItem('last_donation_id');
                            if (data.donation && data.donation.id != lastDonationId) {
                                localStorage.setItem('last_donation_id', data.donation.id);
                                // If they are on archive page, refresh it
                                if (window.location.pathname.includes('archive')) {
                                    window.location.reload();
                                }
                            }
                        }
                    }).catch(() => {});
                }, 15000); // Every 15s for optimization
            };
            startSync();
            @endauth

        // ── Ripple Effect Logic ──
        function addRipple(e) {
            const btn = e.currentTarget;
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            const rect = btn.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        }

        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-primary-custom, .btn-accent-custom, .btn-secondary-custom')) {
                addRipple(e);
            }
        });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('modals')
    @stack('scripts')
</body>
</html>
