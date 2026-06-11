@extends('layouts.landing')

@section('content')
<style>
    /* ── Hero Section ── */
    .hero-container {
        padding: 60px 0;
        background-color: #ffffff;
    }
    .hero-title {
        font-size: 52px;
        font-weight: 800;
        line-height: 1.15;
        color: var(--primary-color);
        letter-spacing: -1px;
    }
    .hero-subtitle {
        font-size: 18px;
        color: #555;
        font-weight: 500;
        line-height: 1.6;
        margin-top: 20px;
        margin-bottom: 35px;
    }
    .hero-image-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .hero-image {
        max-width: 100%;
        border-radius: 30px;
        box-shadow: 0 20px 40px rgba(36, 62, 54, 0.08);
    }
    .btn-hero-outline {
        border: 2px solid var(--primary-color);
        background: transparent;
        color: var(--primary-color);
        font-weight: 700;
        border-radius: 24px;
        padding: 10px 24px;
        transition: all 0.2s;
        text-decoration: none !important;
    }
    .btn-hero-outline:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* ── Featured Topics ── */
    .section-header-custom {
        margin-top: 80px;
        margin-bottom: 40px;
    }
    .section-title-custom {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--primary-color);
    }
    .card-featured-horizontal {
        border-radius: 24px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        background-color: #ffffff;
        display: flex;
        flex-direction: row;
        margin-bottom: 30px;
        min-height: 280px;
    }
    .card-featured-left {
        width: 50%;
        background: linear-gradient(135deg, #243E36, #7CA982);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
        overflow: hidden;
    }
    .card-featured-left::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        top: -30px;
        right: -30px;
    }
    .card-featured-right {
        width: 50%;
        padding: 45px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .card-topic-pill {
        background-color: #fbf0d3;
        color: #b58d19;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 6px 14px;
        border-radius: 20px;
        width: fit-content;
        margin-bottom: 15px;
    }
    .card-topic-col {
        border-radius: 20px;
        border: none;
        padding: 35px;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
    }
    .card-topic-col:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }

    /* ── Campaign Discovery Section ── */
    .discovery-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 40px;
        margin-top: 30px;
    }
    
    /* Left featured card */
    .featured-campaign-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: none;
        box-shadow: 0 15px 35px rgba(0,0,0,0.04);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .featured-campaign-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.08);
    }
    .featured-card-img {
        width: 100%;
        aspect-ratio: 16/10;
        object-fit: cover;
    }
    .featured-card-body {
        padding: 35px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Right grid of 4 smaller cards */
    .compact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .compact-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none !important;
        color: inherit;
    }
    .compact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }
    .compact-card-img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }
    .compact-card-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* ── Progress bar ── */
    .progress-thick {
        height: 8px;
        background-color: #e8f5ed;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .progress-bar-thick {
        height: 100%;
        background-color: var(--gofundme-green);
        border-radius: 10px;
    }

    /* ── How it works ── */
    .how-it-works-card {
        text-align: center;
        padding: 30px;
        background: transparent;
        border: none;
    }
    .how-it-works-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: var(--light-color);
        color: var(--secondary-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    /* ── Bottom Banner ── */
    .bottom-banner-landing {
        background-color: var(--light-color);
        border-radius: 30px;
        padding: 60px 40px;
        text-align: center;
        margin-top: 80px;
        position: relative;
        overflow: hidden;
    }
    .bottom-banner-landing::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(124, 169, 130, 0.08);
        border-radius: 50%;
        top: -100px;
        left: -100px;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .hero-title {
            font-size: 38px;
        }
        .card-featured-horizontal {
            flex-direction: column;
        }
        .card-featured-left, .card-featured-right {
            width: 100%;
            padding: 30px;
        }
        .discovery-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .compact-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- 1. Hero Section -->
<section class="hero-container">
    <div class="container px-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-6">
                <h1 class="hero-title">{{ __('Dukung Aksi Kebaikan Nyata di Indonesia') }}</h1>
                <p class="hero-subtitle">
                    {{ __('Seluna membantu Anda mengumpulkan donasi secara transparan dan tepercaya untuk penanganan medis, dana darurat, bantuan bencana, serta inisiatif komunitas lokal di Indonesia.') }}
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('campaigns.index') }}" class="btn-gofundme-green py-3 px-4 fw-bold">{{ __('Mulai Galang Dana') }}</a>
                    <a href="#explore-campaigns" class="btn-hero-outline py-3 px-4">{{ __('Cari Kampanye') }}</a>
                </div>
            </div>
            <div class="col-12 col-lg-6 hero-image-wrapper">
                <img src="{{ asset('assets/images/seluna_hero_banner.png') }}" alt="Seluna Illustration" class="hero-image" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- 2. Featured Topics -->
<section class="container px-lg-5">
    <div class="section-header-custom">
        <h2 class="section-title-custom">{{ __('Topik Pilihan') }}</h2>
    </div>

    <!-- Main horizontal banner -->
    <div class="card-featured-horizontal">
        <div class="card-featured-left text-white">
            <div class="text-center">
                <i data-lucide="shield-check" style="width: 72px; height: 72px;" class="mb-3"></i>
                <h3 class="fw-bold m-0">Aman & Terverifikasi</h3>
                <p class="small opacity-75 mt-2 mb-0">Setiap kampanye dipantau langsung untuk menjaga transparansi</p>
            </div>
        </div>
        <div class="card-featured-right">
            <span class="card-topic-pill">FITUR TERPERCAYA</span>
            <h4 class="fw-bold text-primary-custom mb-3">Galang Dana Mandiri dengan Pendampingan Laporan Lanjutan</h4>
            <p class="text-muted small mb-4" style="line-height: 1.6;">
                Seluna menyediakan sistem milestone dan dokumentasi berkala. Donatur dapat memantau penggunaan dana secara real-time melalui bukti unggahan foto/video yang diunggah langsung oleh penggalang dana.
            </p>
            <a href="{{ route('campaigns.index') }}" class="text-success fw-bold text-decoration-none d-flex align-items-center gap-1">
                Mulai Buat Kampanye <i data-lucide="chevron-right" style="width: 16px;"></i>
            </a>
        </div>
    </div>

    <!-- 3 secondary topics -->
    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card-topic-col" style="background-color: #fcf6e8;">
                <div>
                    <span class="card-topic-pill" style="background-color: #fcebc7; color: #9c6c0e;">KOLABORASI</span>
                    <h5 class="fw-bold text-primary-custom mb-3">Tantangan #KitaPeduli</h5>
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        Ikut gotong royong komunitas bersama relawan lokal dalam mempercepat penggalangan dana bagi sekolah pelosok dan renovasi fasilitas publik.
                    </p>
                </div>
                <a href="{{ route('campaigns.search', ['query' => 'Pendidikan']) }}" class="text-warning fw-bold text-decoration-none small d-flex align-items-center gap-1">
                    Cari Kegiatan <i data-lucide="chevron-right" style="width: 14px;"></i>
                </a>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card-topic-col text-white" style="background-color: var(--primary-color);">
                <div>
                    <span class="card-topic-pill" style="background-color: rgba(255,255,255,0.15); color: #fff;">DANA BERSAMA</span>
                    <h5 class="fw-bold mb-3">Seluna Giving Funds</h5>
                    <p class="opacity-75 small mb-4" style="line-height: 1.6;">
                        Salurkan bantuan instan untuk kasus medis mendesak atau tanggap darurat bencana alam. Seluna mengalokasikan donasi ke korban secara adil dan teratur.
                    </p>
                </div>
                <a href="{{ route('campaigns.search', ['query' => 'Bencana']) }}" class="text-white fw-bold text-decoration-none small d-flex align-items-center gap-1 opacity-90">
                    Pelajari Selengkapnya <i data-lucide="chevron-right" style="width: 14px;"></i>
                </a>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card-topic-col" style="background-color: #eef5f0;">
                <div>
                    <span class="card-topic-pill" style="background-color: #daebd9; color: #2d6b35;">OPERASIONAL</span>
                    <h5 class="fw-bold text-primary-custom mb-3">Relawan Bencana Alam</h5>
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        Dukung operasional para relawan kemanusiaan yang turun langsung ke lapangan. Kebutuhan logistik, tenda, obat-obatan, dan makan.
                    </p>
                </div>
                <a href="{{ route('campaigns.search', ['query' => 'Sosial']) }}" class="text-success fw-bold text-decoration-none small d-flex align-items-center gap-1">
                    Donasi Sekarang <i data-lucide="chevron-right" style="width: 14px;"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 3. Discover Campaigns Section -->
<section class="container px-lg-5" id="explore-campaigns">
    <div class="section-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h2 class="section-title-custom m-0">{{ __('Temukan kampanye yang menginspirasi') }}</h2>
            <p class="text-muted small m-0 mt-1">{{ __('Salurkan kebaikan Anda ke kampanye donasi terverifikasi di bawah ini') }}</p>
        </div>
        
        <!-- Category Pill selector -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('campaigns.search', ['query' => 'Medis']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">Medis</a>
            <a href="{{ route('campaigns.search', ['query' => 'Pendidikan']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">Pendidikan</a>
            <a href="{{ route('campaigns.search', ['query' => 'Bencana']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">Bencana</a>
            <a href="{{ route('campaigns.search', ['query' => 'Keagamaan']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">Keagamaan</a>
        </div>
    </div>

    @php
        $featured = $popularCampaigns->first() ?? $explorationCampaigns->first();
        $featuredId = $featured ? $featured->id : null;
        $rightCampaigns = collect();
        
        foreach($popularCampaigns as $c) {
            if ($c->id !== $featuredId && $rightCampaigns->count() < 4) {
                $rightCampaigns->push($c);
            }
        }
        foreach($explorationCampaigns as $c) {
            if ($c->id !== $featuredId && !$rightCampaigns->contains('id', $c->id) && $rightCampaigns->count() < 4) {
                $rightCampaigns->push($c);
            }
        }
    @endphp

    @if(!$featured)
        <div class="text-center py-5 bg-light rounded-5 border">
            <i data-lucide="heart-off" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
            <h5 class="fw-bold text-primary-custom">{{ __('Belum Ada Kampanye Aktif') }}</h5>
            <p class="text-muted small mb-0">{{ __('Silakan kembali lagi nanti atau buat kampanye donasi pertama Anda!') }}</p>
        </div>
    @else
        <div class="discovery-grid">
            <!-- Left Column: Large Featured Card -->
            <div>
                <a href="{{ route('campaigns.show', $featured->id) }}" class="text-decoration-none color-inherit">
                    <div class="featured-campaign-card">
                        @php
                            $fBanner = 'https://picsum.photos/seed/featured-' . $featured->id . '/800/500';
                            $fFirstMedia = $featured->media->first();
                            if ($fFirstMedia) {
                                $fBanner = $fFirstMedia->url;
                            }
                        @endphp
                        <img src="{{ $fBanner }}" alt="{{ $featured->title }}" class="featured-card-img" loading="lazy">
                        <div class="featured-card-body">
                            <div>
                                <span class="badge mb-3 px-3 py-2 rounded-pill" style="background-color: var(--light-color); color: var(--secondary-color); font-weight: 700; font-size: 11px;">
                                    {{ strtoupper($featured->tag ?? 'KATEGORI UMUM') }}
                                </span>
                                <h3 class="fw-bold text-primary-custom mb-3" style="line-height: 1.3;">{{ $featured->title }}</h3>
                                <p class="text-muted small mb-4 line-clamp-3">
                                    {{ Str::limit(strip_tags($featured->description), 180) }}
                                </p>
                            </div>
                            <div>
                                <div class="progress-thick">
                                    <div class="progress-bar-thick" style="width: {{ $featured->percentage }}%;"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small">
                                        <span class="fw-bold text-success" style="font-size: 18px;">Rp {{ number_format($featured->collected_amount, 0, ',', '.') }}</span>
                                        <span class="text-muted" style="font-size: 13px;"> {{ __('terkumpul dari') }} Rp {{ number_format($featured->goal_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold px-3 py-2" style="font-size: 10px;">
                                        {{ $featured->percentage }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Right Column: 2x2 Grid -->
            <div>
                <div class="compact-grid">
                    @forelse($rightCampaigns as $c)
                        @php
                            $cBanner = 'https://picsum.photos/seed/comp-' . $c->id . '/600/400';
                            $cFirstMedia = $c->media->first();
                            if ($cFirstMedia) {
                                $cBanner = $cFirstMedia->url;
                            }
                        @endphp
                        <a href="{{ route('campaigns.show', $c->id) }}" class="compact-card">
                            <img src="{{ $cBanner }}" alt="{{ $c->title }}" class="compact-card-img" loading="lazy">
                            <div class="compact-card-body">
                                <div>
                                    <span class="text-muted fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">
                                        {{ strtoupper($c->tag ?? 'KATEGORI UMUM') }}
                                    </span>
                                    <h6 class="fw-bold text-primary-custom line-clamp-2 mb-3" style="min-height: 38px; line-height: 1.4;">{{ $c->title }}</h6>
                                </div>
                                <div>
                                    <div class="progress-thick" style="height: 6px; margin-bottom: 10px;">
                                        <div class="progress-bar-thick" style="width: {{ $c->percentage }}%;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary-custom small">Rp {{ number_format($c->collected_amount, 0, ',', '.') }}</span>
                                        <span class="text-muted fw-bold" style="font-size: 10px;">{{ $c->percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            {{ __('Tidak ada kampanye tambahan lainnya.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</section>

<!-- 4. How Seluna Works Section -->
<section class="container px-lg-5 mt-5">
    <div class="section-header-custom text-center">
        <h2 class="section-title-custom mb-2">{{ __('Bagaimana Seluna Bekerja?') }}</h2>
        <p class="text-muted small mx-auto" style="max-width: 500px;">{{ __('Hanya butuh beberapa menit untuk mulai berbagi harapan dengan jutaan donatur') }}</p>
    </div>
    
    <div class="row g-4">
        <div class="col-12 col-md-3">
            <div class="how-it-works-card">
                <div class="how-it-works-icon">
                    <i data-lucide="edit-3" style="width: 28px; height: 28px;"></i>
                </div>
                <h5 class="fw-bold mb-2">1. Buat Kampanye</h5>
                <p class="text-muted small mb-0" style="line-height: 1.5;">Tulis kisah Anda secara jujur, atur target dana, dan tentukan label reward milestone.</p>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="how-it-works-card">
                <div class="how-it-works-icon">
                    <i data-lucide="share-2" style="width: 28px; height: 28px;"></i>
                </div>
                <h5 class="fw-bold mb-2">2. Bagikan Tautan</h5>
                <p class="text-muted small mb-0" style="line-height: 1.5;">Kirimkan tautan kampanye donasi Anda kepada teman, keluarga, atau komunitas via sosial media.</p>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="how-it-works-card">
                <div class="how-it-works-icon">
                    <i data-lucide="hand-coins" style="width: 28px; height: 28px;"></i>
                </div>
                <h5 class="fw-bold mb-2">3. Terima Donasi</h5>
                <p class="text-muted small mb-0" style="line-height: 1.5;">Donatur dapat mentransfer bantuan langsung menggunakan opsi e-wallet atau transfer bank Midtrans.</p>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="how-it-works-card">
                <div class="how-it-works-icon">
                    <i data-lucide="receipt" style="width: 28px; height: 28px;"></i>
                </div>
                <h5 class="fw-bold mb-2">4. Laporan Berkala</h5>
                <p class="text-muted small mb-0" style="line-height: 1.5;">Unggah bukti penggunaan dana lapangan di tab update agar donatur tetap percaya.</p>
            </div>
        </div>
    </div>
</section>

<!-- 5. Call to Action Banner -->
<section class="container px-lg-5">
    <div class="bottom-banner-landing">
        <h2 class="fw-bold text-primary-custom mb-3" style="font-size: 36px; letter-spacing: -0.5px;">{{ __('Siap Menyalakan Cahaya Harapan?') }}</h2>
        <p class="text-muted small mx-auto mb-4" style="max-width: 550px; font-weight: 500;">
            {{ __('Buat kampanye donasi online pertamamu sekarang secara gratis. Bersama Seluna, setiap kontribusi kecil membawa perubahan besar.') }}
        </p>
        <div class="d-flex justify-content-center">
            <a href="{{ route('campaigns.index') }}" class="btn-gofundme-green py-3 px-5 fw-bold shadow-lg">{{ __('Mulai Seluna Hari Ini') }}</a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
