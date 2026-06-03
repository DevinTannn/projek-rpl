<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SELUNA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-peduli { background-color: #1B3022; }
        .heart-shape {
            position: relative; width: 20px; height: 20px;
            background-color: #D4AF37; transform: rotate(-45deg);
            margin-right: 15px; margin-left: 5px; display: inline-block;
        }
        .heart-shape::before, .heart-shape::after {
            content: ""; position: absolute; width: 20px; height: 20px;
            background-color: #D4AF37; border-radius: 50%;
        }
        .heart-shape::before { top: -10px; left: 0; }
        .heart-shape::after { left: 10px; top: 0; }
        .sidebar-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-right: 15px;
        }
    </style>
</head>
<body class="bg-[#F0F4EF] min-h-screen">

<div class="flex min-h-screen">
    <aside class="w-64 bg-peduli text-white flex flex-col py-8 px-6">
        <a href="/admin" class="flex items-center mb-12 px-2 overflow-hidden gap-2 text-decoration-none hover:opacity-80 transition-opacity">
            <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" class="sidebar-logo">
            <div class="overflow-hidden">
                <h1 class="text-lg font-black tracking-widest text-[#D4AF37] whitespace-nowrap leading-none mb-1">SELUNA</h1>
                <p class="text-gray-400 uppercase tracking-widest whitespace-nowrap opacity-60 leading-none" style="font-size: 8px;">Admin Panel</p>
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

            <a href="/admin/laporan" class="flex items-center gap-4 py-3 px-4 rounded-lg transition {{ request()->is('admin/laporan') ? 'bg-white/10 text-white' : 'text-gray-200 hover:bg-white/10' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Laporan
            </a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto">
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
</script>

</body>
</html> 