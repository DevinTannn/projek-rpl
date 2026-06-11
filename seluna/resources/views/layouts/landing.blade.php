<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seluna - Cahaya Untuk Setiap Harapan</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary-color: #243E36;
            --secondary-color: #7CA982;
            --accent-color: #C2A83E;
            --light-color: #F1F7ED;
            --muted-color: #E0EEC6;
            --bg-color: #ffffff;
            --gofundme-green: #02a95c;
            --gofundme-hover: #028e4e;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-color);
            margin: 0;
            overflow-x: hidden;
        }

        /* ── Top Navbar ── */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            height: 80px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1050;
            padding: 0 40px;
        }

        .navbar-brand-center {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none !important;
            transition: opacity 0.2s;
        }

        .navbar-brand-center:hover {
            opacity: 0.9;
        }

        .navbar-brand-logo {
            height: 38px;
            object-fit: contain;
        }

        .navbar-brand-text {
            color: var(--primary-color);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .nav-links-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-links-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-item-custom {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .nav-item-custom:hover {
            color: var(--secondary-color);
        }

        /* Search Box in Navbar */
        .nav-search-container {
            position: relative;
            width: 220px;
            transition: width 0.3s;
        }

        .nav-search-container:focus-within {
            width: 280px;
        }

        .nav-search-container input {
            width: 100%;
            padding: 8px 12px 8px 38px;
            background-color: #f6f8f6;
            border: 1px solid transparent;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-search-container input:focus {
            outline: none;
            background-color: white;
            border-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(124, 169, 130, 0.15);
        }

        .nav-search-container .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #728a75;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        /* Search dropdown results */
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.08);
            margin-top: 8px;
            display: none;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1100;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none !important;
            color: var(--primary-color);
            transition: background 0.2s;
            border-bottom: 1px solid rgba(0,0,0,0.03);
        }

        .search-result-item:hover {
            background-color: #f8faf8;
        }

        .search-result-item .icon-box {
            width: 32px;
            height: 32px;
            background-color: var(--light-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            flex-shrink: 0;
        }

        /* Button Mulai Seluna */
        .btn-gofundme-green {
            background-color: var(--gofundme-green);
            color: white !important;
            border: none;
            border-radius: 24px;
            padding: 10px 24px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none !important;
            transition: background-color 0.2s, transform 0.1s;
            box-shadow: 0 4px 10px rgba(2, 169, 92, 0.15);
        }

        .btn-gofundme-green:hover {
            background-color: var(--gofundme-hover);
            transform: translateY(-1px);
        }

        .btn-gofundme-green:active {
            transform: translateY(1px);
        }

        /* Dropdown custom */
        .dropdown-menu-custom {
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 10px;
        }

        .dropdown-item-custom {
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 14px;
            color: var(--primary-color);
        }

        .dropdown-item-custom:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }

        /* Footer styling */
        .footer-landing {
            background-color: #f8faf8;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 80px 0 40px;
            margin-top: 80px;
        }

        .footer-logo-text {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .footer-link-col h6 {
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: var(--primary-color);
            opacity: 0.8;
        }

        .footer-link-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-link-col ul li {
            margin-bottom: 12px;
        }

        .footer-link-col ul li a {
            color: #555;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
            font-weight: 500;
        }

        .footer-link-col ul li a:hover {
            color: var(--secondary-color);
        }

        .footer-divider {
            height: 1px;
            background-color: rgba(0,0,0,0.06);
            margin: 40px 0 30px;
        }

        .footer-bottom-text {
            font-size: 13px;
            color: #777;
            font-weight: 500;
        }

        /* Responsive Navbar */
        @media (max-width: 991px) {
            .navbar-custom {
                padding: 0 20px;
                height: 70px;
                justify-content: space-between;
            }
            .nav-links-left {
                display: none !important;
            }
            .nav-links-right .btn-gofundme-green {
                padding: 8px 16px;
                font-size: 13px;
            }
            .nav-links-right .nav-item-custom span {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <header class="navbar-custom">
        <div class="d-flex align-items-center w-100 justify-content-between">
            <!-- Left Links -->
            <div class="nav-links-left">
                <!-- Live Search Box -->
                <form action="{{ route('campaigns.search') }}" method="GET" class="nav-search-container">
                    <span class="search-icon">
                        <i data-lucide="search" style="width: 18px; height: 18px;"></i>
                    </span>
                    <input type="text" id="landing-search-input" name="query" placeholder="{{ __('Search donation campaigns...') }}" value="{{ request('query') }}" autocomplete="off">
                    <div id="landing-search-results" class="search-results-dropdown">
                        <!-- Autocomplete items -->
                    </div>
                </form>

                <!-- Kategori Dropdown -->
                <div class="dropdown">
                    <button class="nav-item-custom dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>{{ __('Kategori') }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.search', ['query' => 'Kesehatan']) }}">Kesehatan</a></li>
                        <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.search', ['query' => 'Pendidikan']) }}">Pendidikan</a></li>
                        <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.search', ['query' => 'Bencana Alam']) }}">Bencana Alam</a></li>
                        <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.search', ['query' => 'Sosial']) }}">{{ __('Sosial & Kemanusiaan') }}</a></li>
                        <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.search', ['query' => 'Lingkungan']) }}">Lingkungan</a></li>
                        <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.search', ['query' => 'Keagamaan']) }}">Keagamaan</a></li>
                    </ul>
                </div>

                <a href="{{ route('campaigns.index') }}" class="nav-item-custom">
                    <span>{{ __('Galang Dana') }}</span>
                </a>
            </div>

            <!-- Brand Logo (Centered) -->
            <a href="{{ route('home') }}" class="navbar-brand-center">
                <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Seluna Logo" class="navbar-brand-logo">
                <span class="navbar-brand-text">SELUNA</span>
            </a>

            <!-- Right Links -->
            <div class="nav-links-right">
                <button class="nav-item-custom d-none d-md-flex" data-bs-toggle="modal" data-bs-target="#infoModal">
                    <i data-lucide="info" style="width: 18px; height: 18px;"></i>
                    <span>{{ __('Tentang') }}</span>
                </button>

                @auth
                    <!-- Authenticated User Dropdown -->
                    <div class="dropdown">
                        <button class="nav-item-custom dropdown-toggle border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.Auth::user()->username.'&background=7CA982' }}" 
                                 class="rounded-circle shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                            <span class="ms-1 d-none d-sm-inline">{{ Auth::user()->username }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                            <li><h6 class="dropdown-header text-muted fw-bold" style="font-size: 11px;">{{ Auth::user()->email }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="{{ route('profile.show') }}"><i data-lucide="user" class="me-2" style="width: 16px;"></i> {{ __('Profil Saya') }}</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="{{ route('campaigns.index') }}"><i data-lucide="layers" class="me-2" style="width: 16px;"></i> {{ __('Your Campaign') }}</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item dropdown-item-custom text-danger"><i data-lucide="log-out" class="me-2" style="width: 16px;"></i> {{ __('Keluar') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('campaigns.index') }}" class="btn-gofundme-green">{{ __('Mulai Seluna') }}</a>
                @else
                    <a href="{{ route('login') }}" class="nav-item-custom">
                        <span>{{ __('Masuk') }}</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn-gofundme-green">{{ __('Mulai Seluna') }}</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Alerts -->
    <div class="container mt-4 px-lg-5">
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm d-flex align-items-center gap-2" style="background-color: #e8f5ed; color: #028e4e;">
                <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-4 shadow-sm d-flex align-items-center gap-2" style="background-color: #fdf2f2; color: #de3a3a;">
                <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
                <span class="fw-semibold">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-landing">
        <div class="container px-lg-5">
            <div class="row g-5">
                <div class="col-12 col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Seluna Logo" style="height: 38px;">
                        <span class="footer-logo-text">SELUNA</span>
                    </div>
                    <p class="text-muted small fw-medium" style="line-height: 1.6;">
                        {{ __('Seluna adalah platform galang dana dan donasi online tepercaya di Indonesia. Kami mempertemukan orang-orang baik untuk berbagi kebahagiaan dan menyalakan cahaya harapan bagi sesama.') }}
                    </p>
                </div>
                <div class="col-6 col-md-4 col-lg-2 offset-lg-1 footer-link-col">
                    <h6>{{ __('Galang Dana') }}</h6>
                    <ul>
                        <li><a href="{{ route('campaigns.search', ['query' => 'Medis']) }}">{{ __('Medis & Kesehatan') }}</a></li>
                        <li><a href="{{ route('campaigns.search', ['query' => 'Pendidikan']) }}">{{ __('Pendidikan') }}</a></li>
                        <li><a href="{{ route('campaigns.search', ['query' => 'Bencana']) }}">{{ __('Bencana Alam') }}</a></li>
                        <li><a href="{{ route('campaigns.search', ['query' => 'Lingkungan']) }}">{{ __('Lingkungan') }}</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-4 col-lg-2 footer-link-col">
                    <h6>{{ __('Tentang') }}</h6>
                    <ul>
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#infoModal">{{ __('Tentang Seluna') }}</a></li>
                        <li><a href="https://doc-hosting.flycricket.io/seluna-privacy-policy/26c20c6d-945f-4cfb-a752-a4e69c511147/privacy" target="_blank">{{ __('Kebijakan Privasi') }}</a></li>
                        <li><a href="https://doc-hosting.flycricket.io/seluna-terms-of-use/01968fad-012c-4ea3-af57-ba843e46d7e5/terms" target="_blank">{{ __('Syarat & Ketentuan') }}</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 col-lg-3 footer-link-col">
                    <h6>{{ __('Hubungi Kami') }}</h6>
                    <ul class="text-muted small fw-medium" style="line-height: 1.6;">
                        <li>Email: support@seluna.org</li>
                        <li>{{ __('Jam Layanan: 09:00 - 17:00 WIB') }}</li>
                        <li>Palembang, Sumatera Selatan</li>
                    </ul>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <span class="footer-bottom-text">&copy; 2026 SELUNA. Hak Cipta Dilindungi Undang-Undang.</span>
                <div class="d-flex gap-3">
                    <a href="https://doc-hosting.flycricket.io/seluna-privacy-policy/26c20c6d-945f-4cfb-a752-a4e69c511147/privacy" target="_blank" class="text-muted text-decoration-none small fw-semibold hover-opacity">Privacy Policy</a>
                    <span class="text-muted">•</span>
                    <a href="https://doc-hosting.flycricket.io/seluna-terms-of-use/01968fad-012c-4ea3-af57-ba843e46d7e5/terms" target="_blank" class="text-muted text-decoration-none small fw-semibold hover-opacity">Terms of Use</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Info Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true" style="z-index: 2050;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-5 shadow-lg">
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <img src="{{ asset('assets/images/seluna_logo_fit.png') }}" alt="Logo" style="height: 45px;">
                        </div>
                        <h3 class="fw-bold text-primary-custom">Tentang SELUNA</h3>
                        <p class="text-muted">Versi 1.0.4 - 2026</p>
                    </div>
                    
                    <p class="text-muted text-center small mb-4">
                        Seluna adalah platform urun dana (crowdfunding) sosial yang dirancang untuk memudahkan penggalangan dana bagi kebutuhan mendesak di Indonesia. Kami mengedepankan keamanan, transparansi laporan berkala, dan aksesibilitas untuk seluruh lapisan masyarakat.
                    </p>

                    <div class="d-grid gap-3 mb-4">
                        <a href="https://doc-hosting.flycricket.io/seluna-privacy-policy/26c20c6d-945f-4cfb-a752-a4e69c511147/privacy" target="_blank" 
                           class="btn btn-light py-3 rounded-4 fw-bold border-0 d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="lock" style="width: 18px;"></i> Kebijakan Privasi
                        </a>
                        <a href="https://doc-hosting.flycricket.io/seluna-terms-of-use/01968fad-012c-4ea3-af57-ba843e46d7e5/terms" target="_blank" 
                           class="btn btn-light py-3 rounded-4 fw-bold border-0 d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="file-text" style="width: 18px;"></i> Syarat & Ketentuan
                        </a>
                    </div>
                    
                    <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" style="background-color: var(--primary-color); border: none;" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/seluna_action_loading.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Lucide Icons
            lucide.createIcons();

            // ── Live Search Autocomplete Logic ──
            const searchInput = document.getElementById('landing-search-input');
            const searchResults = document.getElementById('landing-search-results');
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
                                                <i data-lucide="heart" style="width:16px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold small">${item.title}</div>
                                                <div class="text-muted" style="font-size:10px;">${item.tag || 'Kategori Umum'}</div>
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
        });
    </script>
    @stack('scripts')
</body>
</html>
