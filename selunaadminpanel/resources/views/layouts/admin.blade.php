<!DOCTYPE html>
<html lang="id">
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
        
        /* ── Transitions & Ripples ── */
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
    </style>
</head>
<body class="bg-[#F0F4EF] min-h-screen">

<div class="flex min-h-screen">
    <aside class="w-64 bg-peduli text-white flex flex-col py-8 px-6">
        <a href="/admin" class="flex items-center mb-12 px-2 overflow-hidden gap-3 text-decoration-none hover:opacity-80 transition-opacity">
            <div class="sidebar-logo-container">
                <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" class="sidebar-logo">
            </div>
            <div class="overflow-hidden">
                <h1 class="text-xl font-black tracking-widest text-accent whitespace-nowrap leading-none mb-1">SELUNA</h1>
                <p class="text-gray-400 uppercase tracking-widest whitespace-nowrap opacity-60 leading-none" style="font-size: 8px; font-weight: 700;">Admin Panel</p>
            </div>
        </a>

        <nav class="space-y-4">
            <a href="/admin" class="flex items-center gap-4 py-3 px-4 rounded-lg transition {{ request()->is('admin') ? 'bg-white/10 text-white' : 'text-gray-200 hover:bg-white/10' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="space-y-1">
                <button onclick="toggleDropdown()" class="w-full flex items-center justify-between py-3 px-4 rounded-lg hover:bg-white/10 transition text-gray-200">
                    <span class="flex items-center gap-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verifikasi
                    </span>
                    <svg id="chevron" class="w-4 h-4 transition-transform duration-300 {{ request()->is('admin/verifikasi/*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                
                <div id="dropdownMenu" class="{{ request()->is('admin/verifikasi/*') ? '' : 'hidden' }} flex flex-col space-y-1 ml-4 border-l border-white/20 pl-4">
                    <a href="/admin/verifikasi/kampanye" class="py-2 px-4 rounded-lg text-sm transition {{ request()->is('admin/verifikasi/kampanye') ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white' }}">Kampanye</a>
                    <a href="/admin/verifikasi/akun" class="py-2 px-4 rounded-lg text-sm transition {{ request()->is('admin/verifikasi/akun') ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white' }}">Akun</a>
                    <a href="/admin/verifikasi/donasi" class="py-2 px-4 rounded-lg text-sm transition {{ request()->is('admin/verifikasi/donasi') ? 'bg-white/10 text-white font-medium' : 'text-gray-400 hover:text-white' }}">Donasi</a>
                </div>

            </div>

            
            <a href="{{ route('admin.verify.report.index') }}" class="flex items-center gap-4 py-3 px-4 rounded-lg transition {{ request()->routeIs('admin.verify.report.*') ? 'bg-white/10 text-white' : 'text-gray-200 hover:bg-white/10' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan Kampanye
            </a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto">
        @yield('header')
        @yield('content')
    </main>
</div>

<script>
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
                    // Alert or simple refresh
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