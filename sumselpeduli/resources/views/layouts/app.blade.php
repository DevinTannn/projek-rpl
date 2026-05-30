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
    <style>
        :root {
            --primary-color: #243E36;
            --secondary-color: #7CA982;
            --accent-color: #C2A83E;
            --bg-color: #F1F7ED;
            --surface-color: #E0EEC6;
            --sidebar-width-wide: 260px;
            --sidebar-width-collapsed: 80px;
            --bottom-nav-height: 70px;
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
            background-color: #1f3328; /* Polished Dark Green */
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: width 0.25s ease;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar.wide { width: 240px; }
        .sidebar.collapsed { width: 64px; }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 24px 0 20px;
        }

        .sidebar-brand-text {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 0;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .sidebar-brand-text {
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
            background: rgba(255,255,255,0.13);
            color: #fff;
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
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-item-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .nav-item-link.active {
            background: #4a7c59;
            color: #fff;
        }

        .nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .nav-icon i, .nav-icon svg {
            width: 18px;
            height: 18px;
        }

        .nav-label {
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .nav-label {
            display: none;
        }

        .sidebar.collapsed .nav-item-link {
            justify-content: center;
            padding: 10px;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .logout-item {
            background: rgba(255,255,255,0.05);
        }

        .logout-item:hover {
            background: rgba(220, 80, 80, 0.15);
            color: #ff8a8a;
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .top-bar {
            height: 80px;
            background-color: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .search-container {
            width: 450px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-container input {
            background-color: #f0f2f1;
            border: 1px solid transparent;
            border-radius: 16px;
            padding: 12px 15px 12px 48px; /* Room for icon */
            width: 100%;
            transition: all 0.2s;
            color: var(--primary-color);
        }

        .search-container input:focus {
            background-color: white;
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
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

        .bottom-nav-item.active {
            color: var(--primary-color);
        }

        /* Desktop specific Margin */
        @media (min-width: 992px) {
            .bottom-nav { display: none; }
            .sidebar { display: flex; }
            .main-content.wide-space { margin-left: var(--sidebar-width-wide); }
            .main-content.collapsed-space { margin-left: var(--sidebar-width-collapsed); }
        }

        @media (max-width: 991px) {
            .sidebar { display: none; }
            .top-bar { display: none; }
            .main-content { margin-left: 0 !important; padding-bottom: calc(var(--bottom-nav-height) + 30px); }
            body { padding-bottom: var(--bottom-nav-height); }
            .mobile-header {
                height: 70px;
                background-color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 25px;
                color: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
        }

        .role-badge {
            background-color: var(--accent-color);
            color: white;
            padding: 5px 14px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-switcher {
            position: fixed;
            bottom: 90px;
            right: 25px;
            z-index: 2000;
        }

        .btn-role-toggle {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--accent-color);
            border-radius: 16px;
            padding: 12px 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-role-toggle:hover {
            transform: scale(1.05);
            background-color: #2c4a40;
        }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <h5 class="m-0 fw-bold">SumselPeduli</h5>
        <div class="profile-area">
            <i data-lucide="search" style="width: 22px;"></i>
            <div class="avatar"></div>
        </div>
    </div>

    <!-- Sidebar (Desktop Only) -->
    <aside id="sidebar" class="sidebar wide d-none d-lg-flex">
        <div class="sidebar-brand">
            <i data-lucide="heart" style="width:28px;height:28px;fill:#f5a623;color:#f5a623;flex-shrink:0;"></i>
            <h4 class="sidebar-brand-text">PEDULI</h4>
        </div>

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
        </div>
    </aside>

    <!-- Main Content -->
    <div id="main-content" class="main-content wide-space">
        <!-- Top Bar (Desktop Only) -->
        <header class="top-bar d-none d-lg-flex">
            <div class="search-container">
                <span class="search-icon">
                    <i data-lucide="search"></i>
                </span>
                <input type="text" placeholder="Cari kampanye donasi...">
            </div>

            <div class="profile-area">
                @auth
                <span id="role-display" class="role-badge">{{ Auth::user()->role ?? 'Donatur' }}</span>
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
            @yield('content')
        </div>
    </div>

    <!-- Bottom Nav (Mobile/Tablet Only) -->
    <nav class="bottom-nav d-lg-none">
        <!-- Items via JS -->
    </nav>

    {{-- <!-- Role Switcher -->
    <div class="role-switcher">
        <button class="btn btn-role-toggle d-flex align-items-center gap-2" onclick="switchRole()">
            <i data-lucide="refresh-cw" style="width: 18px;"></i>
            <span id="next-role-label">Switch to Fundraiser</span>
        </button>
    </div> --}}

    <script>
        let currentRole = '{{ Auth::check() ? Auth::user()->role : 'donatur' }}';
        let isSidebarCollapsed = false;

        const navConfig = {
            donatur: [
                { id: 'home', label: 'Home', icon: 'home', url: '{{ route('home') }}', active: {{ request()->routeIs('home') ? 'true' : 'false' }} },
                { id: 'follow', label: 'Follow', icon: 'heart', url: '#', active: false },
                { id: 'archive', label: 'Archive', icon: 'archive', url: '#', active: false }
            ],
            fundraiser: [
                { id: 'home', label: 'Home', icon: 'home', url: '{{ route('home') }}', active: {{ request()->routeIs('home') ? 'true' : 'false' }} },
                { id: 'follow', label: 'Follow', icon: 'heart', url: '#', active: false },
                { id: 'campaign', label: 'Your Campaign', icon: 'layout-grid', url: '{{ route('campaigns.index') }}', active: {{ request()->routeIs('campaigns.*') ? 'true' : 'false' }} },
                { id: 'archive', label: 'Archive', icon: 'archive', url: '#', active: false }
            ]
        };

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
            const roleDisplay = document.getElementById('role-display');
            const nextRoleLabel = document.getElementById('next-role-label');
            roleDisplay.innerText = currentRole.charAt(0).toUpperCase() + currentRole.slice(1);
            nextRoleLabel.innerText = currentRole === 'donatur' ? 'Switch to Fundraiser' : 'Switch to Donatur';
            renderNav();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderNav();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
