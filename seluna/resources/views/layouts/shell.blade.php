<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SumselPeduli - Shell</title>
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
            --sidebar-width-wide: 240px;
            --sidebar-width-collapsed: 80px;
            --bottom-nav-height: 65px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-color);
            margin: 0;
            overflow-x: hidden;
            transition: padding 0.3s ease;
        }

        /* Desktop Sidebar */
        .sidebar {
            background-color: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: width 0.3s ease;
            z-index: 1000;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar.wide { width: var(--sidebar-width-wide); }
        .sidebar.collapsed { width: var(--sidebar-width-collapsed); }

        .sidebar-brand {
            padding: 15px 0;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center; /* Center for collapsed mode */
            overflow: hidden;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .sidebar.wide .sidebar-brand {
            justify-content: flex-start;
            padding-left: 24px;
        }

        .sidebar-brand h4 {
            margin: 0 0 0 12px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: opacity 0.2s, width 0.2s;
            display: inline-block;
        }

        .sidebar.collapsed .sidebar-brand h4 { 
            opacity: 0; 
            width: 0;
            margin: 0;
            pointer-events: none;
        }

        .nav-items { flex-grow: 1; padding: 0 12px; }

        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 14px 16px; /* Increased horizontal padding */
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 14px;
            margin-bottom: 8px;
            transition: all 0.2s;
            overflow: hidden;
            white-space: nowrap;
        }

        .nav-item-link i { 
            min-width: 24px; 
            margin-right: 18px; /* Increased gap between icon and text */
            transition: margin 0.3s;
        }

        .nav-item-link.active {
            background-color: var(--secondary-color);
            color: white;
        }

        .nav-item-link:hover {
            background-color: rgba(124, 169, 130, 0.2);
            color: white;
        }

        .sidebar.collapsed .nav-item-link span { display: none; }
        .sidebar.collapsed .nav-item-link i { margin-right: 0; margin-left: 6px; }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            padding: 10px;
            margin: 0 12px 10px;
            text-align: left;
            cursor: pointer;
        }

        /* Top Bar Desktop */
        .main-content {
            flex-grow: 1;
            transition: margin-left 0.3s ease;
        }

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
            z-index: 900;
        }

        .search-container {
            width: 450px;
            position: relative;
        }

        .search-container input {
            background-color: #f0f0f0;
            border: 1px solid transparent;
            border-radius: 14px;
            padding: 12px 15px 12px 45px; /* Added left padding for icon */
            width: 100%;
            transition: all 0.2s;
        }

        .search-container input:focus {
            background-color: white;
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%); /* Perfectly centered vertically */
            color: #777;
            pointer-events: none;
            width: 20px;
            height: 20px;
        }

        .profile-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--secondary-color);
        }

        /* Bottom Nav Mobile/Tablet */
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
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 2000;
            padding-bottom: env(safe-area-inset-bottom);
        }

        .bottom-nav-item {
            text-decoration: none;
            color: #777;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 10px;
            transition: color 0.2s;
        }

        .bottom-nav-item.active {
            color: var(--primary-color);
        }

        /* Responsiveness */
        @media (min-width: 992px) {
            .bottom-nav { display: none; }
            .sidebar { display: flex; }
            .main-content.wide-space { margin-left: var(--sidebar-width-wide); }
            .main-content.collapsed-space { margin-left: var(--sidebar-width-collapsed); }
        }

        @media (max-width: 991px) {
            .sidebar { display: none; }
            .top-bar { display: none; } /* User wants hidden or minimal top bar */
            .main-content { margin-left: 0 !important; padding-bottom: calc(var(--bottom-nav-height) + 20px); }
            body { padding-bottom: var(--bottom-nav-height); }
            .mobile-header {
                height: 60px;
                background-color: var(--primary-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 20px;
                color: white;
            }
        }

        /* Content Sections */
        .section-title {
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dummy-card {
            background-color: var(--surface-color);
            border-radius: 20px;
            overflow: hidden;
            border: none;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .dummy-card:hover { transform: translateY(-5px); }

        .dummy-card img { height: 180px; object-fit: cover; width: 100%; }

        .dummy-card-body { padding: 20px; }

        .role-badge {
            background-color: var(--accent-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .role-switcher {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 3000;
        }

        .btn-role-toggle {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--accent-color);
            border-radius: 50px;
            padding: 10px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <h5 class="m-0 fw-bold">SumselPeduli</h5>
        <div class="profile-area">
            <i data-lucide="search"></i>
            <div class="avatar"></div>
        </div>
    </div>

    <!-- Sidebar (Desktop Only) -->
    <aside id="sidebar" class="sidebar wide d-none d-lg-flex">
        <div class="sidebar-brand">
            <i data-lucide="heart" class="text-white" style="width: 32px; height: 32px;"></i>
            <h4>PEDULI</h4>
        </div>

        <button onclick="toggleSidebar()" class="sidebar-toggle">
            <i data-lucide="menu"></i>
        </button>

        <div class="nav-items" id="sidebar-nav-items">
            <!-- Items injected by JS -->
        </div>

        <div class="p-3">
            <div class="nav-item-link" style="background: rgba(255,255,255,0.05);">
                <i data-lucide="settings"></i>
                <span>Settings</span>
            </div>
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
                <span id="role-display" class="role-badge">Donatur</span>
                <div class="d-flex flex-column align-items-end me-2">
                    <span class="fw-bold small">Joko Susilo</span>
                    <span class="text-muted" style="font-size: 10px;">donatur@gmail.com</span>
                </div>
                <div class="avatar"></div>
            </div>
        </header>

        <div class="container py-4">
            @yield('content')
        </div>
    </div>

    <!-- Bottom Nav (Mobile/Tablet Only) -->
    <nav class="bottom-nav d-lg-none">
        <!-- Items injected by JS -->
    </nav>

    <!-- Role Switcher Floater -->
    <div class="role-switcher">
        <button class="btn btn-role-toggle d-flex align-items-center gap-2" onclick="switchRole()">
            <i data-lucide="refresh-cw" style="width: 18px;"></i>
            <span id="next-role-label">Switch to Fundraiser</span>
        </button>
    </div>

    <script>
        let currentRole = 'donatur'; // Default role
        let isSidebarCollapsed = false;

        const navConfig = {
            donatur: [
                { id: 'home', label: 'Home', icon: 'home', active: true },
                { id: 'follow', label: 'Follow', icon: 'heart', active: false },
                { id: 'archive', label: 'Archive', icon: 'archive', active: false }
            ],
            fundraiser: [
                { id: 'home', label: 'Home', icon: 'home', active: true },
                { id: 'follow', label: 'Follow', icon: 'heart', active: false },
                { id: 'campaign', label: 'Your Campaign', icon: 'layers', active: false },
                { id: 'archive', label: 'Archive', icon: 'archive', active: false }
            ]
        };

        function renderNav() {
            const sidebar = document.getElementById('sidebar-nav-items');
            const bottomNav = document.querySelector('.bottom-nav');
            const items = navConfig[currentRole];

            // Render Sidebar
            sidebar.innerHTML = items.map(item => `
                <a href="#" class="nav-item-link ${item.active ? 'active' : ''}">
                    <i data-lucide="${item.icon}"></i>
                    <span>${item.label}</span>
                </a>
            `).join('');

            // Render Bottom Nav
            bottomNav.innerHTML = items.map(item => `
                <a href="#" class="bottom-nav-item ${item.active ? 'active' : ''}">
                    <i data-lucide="${item.icon}" style="width: 20px;"></i>
                    <span>${item.label}</span>
                </a>
            `).join('');

            // Update Icons
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
            
            // Update UI Labels
            const roleDisplay = document.getElementById('role-display');
            const nextRoleLabel = document.getElementById('next-role-label');
            
            roleDisplay.innerText = currentRole.charAt(0).toUpperCase() + currentRole.slice(1);
            nextRoleLabel.innerText = currentRole === 'donatur' ? 'Switch to Fundraiser' : 'Switch to Donatur';
            
            renderNav();
        }

        // Initialize Lucide and Nav
        document.addEventListener('DOMContentLoaded', () => {
            renderNav();
        });
    </script>
</body>
</html>
