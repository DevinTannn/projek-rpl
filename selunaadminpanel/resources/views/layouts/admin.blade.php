<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SELUNA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-peduli { background-color: #243E36; }
        .text-accent { color: #C2A83E; }
        .sidebar-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }
        .sidebar-logo-container {
            width: 40px;
            height: 40px;
            background: #7CA982;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        /* ── Sidebar ── */
        #admin-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 256px;
            z-index: 50;
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }
        #admin-sidebar.collapsed {
            transform: translateX(-100%);
        }
        #admin-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
        }
        #admin-overlay.active { display: block; }

        #admin-main {
            margin-left: 256px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (max-width: 1023px) {
            #admin-sidebar {
                transform: translateX(-100%);
            }
            #admin-sidebar.mobile-open {
                transform: translateX(0);
            }
            #admin-main {
                margin-left: 0;
            }
        }

        /* ── Ripples ── */
        .ripple {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
        }
        @keyframes ripple-animation {
            to { transform: scale(4); opacity: 0; }
        }
        button, .btn, .nav-item { position: relative; overflow: hidden; }

        .hover-scale { transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .hover-scale:hover { transform: scale(1.02); }
        .hover-scale:active { transform: scale(0.98); }

        /* ── Mobile topbar ── */
        #admin-topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 30;
            background: #243E36;
            padding: 12px 16px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        @media (max-width: 1023px) {
            #admin-topbar { display: flex; }
        }
    </style>
</head>
<body class="bg-[#F0F4EF] min-h-screen">

<!-- Mobile Topbar -->
<div id="admin-topbar">
    <button id="sidebar-toggle" class="text-white p-1 rounded-lg hover:bg-white/10 transition" onclick="toggleSidebar()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <a href="/admin" class="flex items-center gap-2">
        <div class="sidebar-logo-container" style="width:32px;height:32px;border-radius:8px;">
            <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" class="sidebar-logo" style="width:22px;height:22px;">
        </div>
        <span class="text-accent font-black tracking-widest text-sm">SELUNA</span>
    </a>
    <div style="width:32px;"></div>
</div>

<!-- Sidebar Overlay (mobile) -->
<div id="admin-overlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="bg-peduli text-white flex flex-col py-8 px-6">
    <a href="/admin" class="flex items-center mb-12 px-2 overflow-hidden gap-3 text-decoration-none hover:opacity-80 transition-opacity">
        <div class="sidebar-logo-container">
            <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" class="sidebar-logo">
        </div>
        <div class="overflow-hidden">
            <h1 class="text-xl font-black tracking-widest text-accent whitespace-nowrap leading-none mb-1">SELUNA</h1>
            <p class="text-gray-400 uppercase tracking-widest whitespace-nowrap opacity-60 leading-none" style="font-size: 8px; font-weight: 700;">Admin Panel</p>
        </div>
    </a>

    <nav class="space-y-4 flex-1">
        <a href="/admin" class="flex items-center gap-4 py-3 px-4 rounded-lg transition {{ request()->is('admin') ? 'bg-white/10 text-white' : 'text-gray-200 hover:bg-white/10' }}" onclick="closeSidebar()">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            {{ __('Dashboard') }}
        </a>

        <div class="space-y-1">
            <button onclick="toggleDropdown()" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-white/10 transition text-gray-200">
                <span class="flex items-center gap-4">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Verifikasi') }}
                </span>
                <svg id="chevron" class="w-4 h-4 transition-transform duration-300 {{ request()->is('admin/verifikasi/*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div id="dropdownMenu" class="{{ request()->is('admin/verifikasi/*') ? '' : 'hidden' }} flex flex-col space-y-1 ml-4 border-l border-white/20 pl-4">
                <a href="/admin/verifikasi/kampanye" class="py-2 px-4 rounded-lg text-sm transition {{ request()->is('admin/verifikasi/kampanye') ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white' }}" onclick="closeSidebar()">{{ __('Kampanye') }}</a>
                <a href="/admin/verifikasi/akun" class="py-2 px-4 rounded-lg text-sm transition {{ request()->is('admin/verifikasi/akun') ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white' }}" onclick="closeSidebar()">{{ __('Akun') }}</a>
                <a href="/admin/verifikasi/donasi" class="py-2 px-4 rounded-lg text-sm transition {{ request()->is('admin/verifikasi/donasi') ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white' }}" onclick="closeSidebar()">{{ __('Donasi') }}</a>
            </div>
        </div>

        <a href="{{ route('admin.verify.report.index') }}" class="flex items-center gap-4 py-3 px-4 rounded-lg transition {{ request()->routeIs('admin.verify.report.*') ? 'bg-white/10 text-white' : 'text-gray-200 hover:bg-white/10' }}" onclick="closeSidebar()">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Laporan Kampanye') }}
        </a>
    </nav>

    <!-- Logout Form -->
    <div class="mt-auto pt-6 border-t border-white/10">
        <form action="{{ route('admin.logout') }}" method="POST" data-confirm data-confirm-title="{{ __('Logout?') }}" data-confirm-body="{{ __('Apakah Anda yakin ingin logout?') }}" data-confirm-icon="🚪" data-confirm-ok="{{ __('Logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-4 py-3 px-4 rounded-lg text-red-200 hover:text-white hover:bg-red-900/30 transition text-left">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>

<!-- Main content -->
<div id="admin-main">
    @yield('header')
    @yield('content')
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');
        const isOpen = sidebar.classList.contains('mobile-open');
        if (isOpen) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        } else {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('active');
        }
    }

    function closeSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    }

    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        const chevron = document.getElementById('chevron');
        menu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    // ── Real-time Admin Sync Polling ──
    let lastTotal = null;
    function syncAdminData() {
        fetch('{{ route('admin.api.sync') }}')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const currentTotal = data.summary.total;
                if (lastTotal !== null && currentTotal > lastTotal) {
                    console.log('New pending tasks detected, refreshing...');
                    window.location.reload();
                }
                lastTotal = currentTotal;
            }
        }).catch(() => {});
    }

    // Check every 15 seconds
    setInterval(syncAdminData, 15000);

    // ── Ripple Effect ──
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

    document.querySelectorAll('button, a.rounded-lg, .hover-scale').forEach(el => {
        el.addEventListener('click', addRipple);
    });
</script>

    <script src="{{ asset('js/seluna_action_loading.js') }}"></script>
</body>
</html>