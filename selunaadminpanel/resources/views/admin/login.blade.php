<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SELUNA</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f0f4f1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-gray-100 p-8 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#2D5A27]/5 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#2D5A27]/5 rounded-full -ml-16 -mb-16"></div>

        <!-- Header -->
        <div class="text-center mb-8 relative z-10">
            <div class="inline-flex p-4 bg-[#2D5A27]/10 rounded-2xl mb-4">
                <!-- Logo Icon -->
                <svg class="w-10 h-10 text-[#2D5A27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-[#1B3022]">Login Administrator</h2>
            <p class="text-xs text-gray-500 mt-1.5 uppercase tracking-widest font-black">Admin Panel SELUNA</p>
        </div>

        <!-- Session Status / Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-4 rounded-2xl mb-6">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            <div>
                <label for="username" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Username / Email</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#2D5A27] focus:border-transparent transition-all text-sm font-semibold text-gray-800 placeholder-gray-400 bg-gray-50/50"
                       placeholder="Masukkan username atau email">
            </div>

            <div>
                <label for="password" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="admin-password" required
                           class="w-full px-4 py-3 pr-12 rounded-2xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#2D5A27] focus:border-transparent transition-all text-sm font-semibold text-gray-800 placeholder-gray-400 bg-gray-50/50"
                           placeholder="••••••••">
                    <button type="button" onclick="toggleAdminPassword()"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2D5A27] transition-colors p-1"
                            aria-label="Tampilkan password">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full bg-[#2D5A27] text-white py-3.5 rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-[#1B3022] active:scale-[0.98] transition-all shadow-md shadow-[#2D5A27]/10">
                    Masuk
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleAdminPassword() {
            const input = document.getElementById('admin-password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', isHidden);
            eyeOffIcon.classList.toggle('hidden', !isHidden);
        }
    </script>
</body>
</html>
