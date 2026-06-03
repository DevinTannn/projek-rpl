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
        :root {
            --primary-color: #1A2F28;
            --secondary-color: #6B8F71;
            --accent-color: #D4AF37;
            --bg-color: #F8FAF7;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --sidebar-width-wide: 260px;
            --sidebar-width-collapsed: 80px;
            --bottom-nav-height: 75px;
        }

        .required::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        .sidebar-logo {
            max-height: 32px;
            object-fit: contain;
        }

        .sidebar.collapsed .sidebar-logo {
            max-height: 24px;
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
            height: 90px;
            background-color: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        .search-container {
            width: 450px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            margin-top: 10px;
            display: none;
            overflow: hidden;
            z-index: 2000;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .search-result-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--primary-color);
            transition: background 0.2s;
            border-bottom: 1px solid rgba(0,0,0,0.03);
        }

        .search-result-item:hover {
            background-color: #f7faf8;
            color: #1b4d3e;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item .icon-box {
            width: 32px;
            height: 32px;
            background: #E0EEC6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
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

        /* Floating Event Button */
        .floating-event-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1b4d3e 0%, #243e36 100%);
            border: 4px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c2a83e;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 2100;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }
        .floating-event-btn:hover {
            transform: scale(1.15) rotate(15deg);
            box-shadow: 0 15px 40px rgba(27, 77, 62, 0.4);
            color: white;
        }
        .floating-event-btn i {
            width: 35px;
            height: 35px;
        }
        .event-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4757;
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 20px;
            border: 2px solid white;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <h5 class="m-0 fw-bold">SELUNA</h5>
        <div class="profile-area">
            <i data-lucide="search" style="width: 22px;"></i>
            <div class="avatar"></div>
        </div>
    </div>

    <!-- Sidebar (Desktop Only) -->
    <aside id="sidebar" class="sidebar wide d-none d-lg-flex">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/images/seluna-logo.png') }}" alt="Logo" class="sidebar-logo">
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

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm animate__animated animate__fadeInDown">
                    <ul class="mb-0 small fw-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Floating Event Button -->
    <a href="{{ route('events.show', 'ramadan') }}" class="floating-event-btn" id="event-btn">
        <i data-lucide="moon"></i>
        <div class="event-badge">LIVE</div>
    </a>

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

        const campaignUrl = '{{ route('campaigns.index') }}';
        const isCampaignActive = {{ request()->routeIs('campaigns.*') ? 'true' : 'false' }};

        const navItems = [
            { id: 'home', label: 'Home', icon: 'home', url: '{{ route('home') }}', active: {{ request()->routeIs('home') ? 'true' : 'false' }} },
            { id: 'follow', label: 'Follow', icon: 'heart', url: '{{ route('campaigns.followed') }}', active: {{ request()->routeIs('campaigns.followed') ? 'true' : 'false' }} },
            { id: 'campaign', label: 'Your Campaign', icon: 'layout-grid', url: '{{ route('campaigns.index') }}', active: {{ request()->routeIs('campaigns.index') ? 'true' : 'false' }} },
            { id: 'archive', label: 'Archive', icon: 'archive', url: '{{ route('profile.archived') }}', active: {{ request()->routeIs('profile.archived') ? 'true' : 'false' }} }
        ];

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
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('modals')
    @stack('scripts')
</body>
</html>
