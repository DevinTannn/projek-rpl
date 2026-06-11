<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SELUNA - Cahaya untuk Setiap Harapan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #1A2F28;
            --secondary: #6B8F71;
            --accent: #D4AF37;
            --bg: #F8FAF7;
            --glass: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--primary);
            overflow-x: hidden;
        }

        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(26, 47, 40, 0.95) 0%, rgba(107, 143, 113, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 50px;
            color: white;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-accent {
            background-color: var(--accent);
            color: var(--primary);
            font-weight: 700;
            border-radius: 50px;
            padding: 15px 40px;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }

        .btn-accent:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.5);
            background-color: #e5be3e;
            color: var(--primary);
        }

        .navbar {
            padding: 20px 0;
            transition: all 0.3s;
        }

        .navbar-brand img {
            height: 40px;
        }

        .nav-link {
            color: white !important;
            font-weight: 600;
            margin-left: 30px;
            opacity: 0.8;
        }

        .nav-link:hover {
            opacity: 1;
            color: var(--accent) !important;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
            color: white;
            margin: 0;
        }

        .tagline {
            font-weight: 300;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 20px;
            display: block;
        }

        .features {
            padding: 100px 0;
        }

        .feature-card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(26, 47, 40, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: #f0f4f1;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            margin-bottom: 25px;
        }

        footer {
            background-color: var(--primary);
            color: rgba(255,255,255,0.6);
            padding: 60px 0;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('assets/images/seluna-logo.png') }}" alt="SELUNA" loading="lazy">
                <span class="logo-text">SELUNA</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Donatur</a></li>
                    @auth
                        <li class="nav-item">
                            <a class="btn btn-accent ms-lg-4" href="{{ route('home') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-accent ms-lg-4" href="{{ route('register') }}">Mulai Berbagi</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-glass">
                        <span class="tagline">Cahaya untuk Setiap Harapan</span>
                        <h1 class="display-3 fw-bold mb-4">Mari Menjadi Bagian dari <span style="color: var(--accent);">Perubahan.</span></h1>
                        <p class="lead mb-5 opacity-75">Platform crowdfunding terpercaya untuk membantu sesama. Setiap rupiah yang Anda berikan adalah cahaya harapan bagi mereka yang membutuhkan.</p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('register') }}" class="btn btn-accent">Donasi Sekarang</a>
                            <a href="#features" class="btn btn-outline-light rounded-pill px-4 py-3 fw-bold border-2">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-primary">Kenapa Memilih SELUNA?</h2>
                <div class="mx-auto" style="width: 80px; height: 5px; background: var(--accent); border-radius: 10px;"></div>
            </div>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto"><i data-lucide="shield-check" style="width: 30px;"></i></div>
                        <h4 class="fw-bold mb-3">Transparansi Penuh</h4>
                        <p class="text-muted">Setiap donasi dapat dipantau secara real-time dengan laporan penggunaan dana yang mendetail dan terdokumentasi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto"><i data-lucide="zap" style="width: 30px;"></i></div>
                        <h4 class="fw-bold mb-3">Mudah & Cepat</h4>
                        <p class="text-muted">Proses donasi yang instan dengan berbagai metode pembayaran otomatis maupun manual yang aman.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon mx-auto"><i data-lucide="heart" style="width: 30px;"></i></div>
                        <h4 class="fw-bold mb-3">Berdampak Nyata</h4>
                        <p class="text-muted">Kami menjamin setiap kampanye telah melalui verifikasi ketat sehingga bantuan Anda tepat sasaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container text-center">
            <h3 class="logo-text mb-4">SELUNA</h3>
            <p class="mb-4">© {{ date('Y') }} SELUNA. Cahaya untuk Setiap Harapan. Seluruh hak cipta dilindungi.</p>
            <div class="d-flex justify-content-center gap-4">
                <a href="#" class="text-white opacity-50 text-decoration-none hover-opacity-100">Kebijakan Privasi</a>
                <a href="#" class="text-white opacity-50 text-decoration-none hover-opacity-100">Syarat & Ketentuan</a>
                <a href="#" class="text-white opacity-50 text-decoration-none hover-opacity-100">Hubungi Kami</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        lucide.createIcons();
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(26, 47, 40, 0.95)';
                nav.style.backdropFilter = 'blur(10px)';
                nav.style.padding = '10px 0';
            } else {
                nav.style.background = 'transparent';
                nav.style.backdropFilter = 'none';
                nav.style.padding = '20px 0';
            }
        });
    </script>
</body>
</html>
