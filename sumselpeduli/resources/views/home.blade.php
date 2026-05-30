@extends('layouts.app')

@section('content')
<style>
    .section-title h4 { font-weight: 800; letter-spacing: -0.5px; }
    .dummy-card { 
        background-color: white; 
        border: 1px solid rgba(0,0,0,0.03); 
        border-radius: 24px; 
        padding: 10px;
        transition: all 0.3s ease;
    }
    .dummy-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 15px 35px rgba(36, 62, 54, 0.1);
        border-color: var(--secondary-color);
    }
    .dummy-card img { border-radius: 18px; height: 200px; object-fit: cover; }
    .btn-donasi { 
        background-color: var(--accent-color); 
        color: white; 
        border-radius: 12px; 
        font-weight: 700;
        padding: 8px 20px;
        border: none;
        transition: all 0.2s;
    }
    .btn-donasi:hover { background-color: #a89235; transform: scale(1.05); }
</style>

<!-- Recently Accessed -->
<div class="mb-5">
    <div class="section-title mb-4">
        <h4 class="m-0 text-primary-custom">Akses Terakhir</h4>
        <a href="#" class="text-secondary-custom text-decoration-none small fw-bold">Lihat Semua <i data-lucide="chevron-right" style="width: 14px;"></i></a>
    </div>
    <div class="row g-4">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="dummy-card">
                <img src="https://picsum.photos/seed/recent1/600/400" class="w-100" alt="Campaign">
                <div class="p-3">
                    <span class="badge mb-2" style="background-color: var(--bg-color); color: var(--secondary-color);">Pendidikan</span>
                    <h5 class="fw-bold text-primary-custom mb-3">Bantu Renovasi Sekolah di Pelosok Sumsel</h5>
                    <div class="progress mb-2" style="height: 8px; border-radius: 10px; background-color: #eee;">
                        <div class="progress-bar" style="width: 75%; background-color: var(--secondary-color); border-radius: 10px;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small">
                            <span class="fw-bold text-accent-custom">Rp 15.000.000</span> <span class="text-muted">/ Rp 20jt</span>
                        </div>
                        <span class="text-muted small fw-semibold">12 Hari Lagi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="dummy-card">
                <img src="https://picsum.photos/seed/recent2/600/400" class="w-100" alt="Campaign">
                <div class="p-3">
                    <span class="badge mb-2" style="background-color: var(--bg-color); color: var(--secondary-color);">Sosial</span>
                    <h5 class="fw-bold text-primary-custom mb-3">Sembako untuk Lansia Dhuafa di Palembang</h5>
                    <div class="progress mb-2" style="height: 8px; border-radius: 10px; background-color: #eee;">
                        <div class="progress-bar" style="width: 40%; background-color: var(--secondary-color); border-radius: 10px;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small">
                            <span class="fw-bold text-accent-custom">Rp 4.000.000</span> <span class="text-muted">/ Rp 10jt</span>
                        </div>
                        <span class="text-muted small fw-semibold">5 Hari Lagi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4 d-none d-xl-block">
            <div class="dummy-card">
                <img src="https://picsum.photos/seed/recent3/600/400" class="w-100" alt="Campaign">
                <div class="p-3">
                    <span class="badge mb-2" style="background-color: var(--bg-color); color: var(--secondary-color);">Kesehatan</span>
                    <h5 class="fw-bold text-primary-custom mb-3">Operasi Bibir Sumbing Anak Kurang Mampu</h5>
                    <div class="progress mb-2" style="height: 8px; border-radius: 10px; background-color: #eee;">
                        <div class="progress-bar" style="width: 92%; background-color: var(--accent-color); border-radius: 10px;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small">
                            <span class="fw-bold text-accent-custom">Rp 46.000.000</span> <span class="text-muted">/ Rp 50jt</span>
                        </div>
                        <span class="text-muted small fw-semibold">2 Hari Lagi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Now -->
<div class="mb-5">
    <div class="section-title mb-4">
        <h4 class="m-0 text-primary-custom">Populer Sekarang</h4>
    </div>
    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-2" style="background-color: var(--primary-color); border-radius: 28px; color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i data-lucide="flame" class="text-accent-custom"></i>
                        </div>
                        <span class="badge" style="background-color: rgba(255,255,255,0.1); height: fit-content; padding: 8px 15px;">Trending #1</span>
                    </div>
                    <h5 class="fw-bold mb-3">Emergency: Kebakaran Lahan Gambut</h5>
                    <p class="small opacity-75 mb-4">Bantu tim relawan memadamkan api dan memberikan bantuan masker serta obat-obatan.</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div class="small fw-bold">12,5k Donasi</div>
                        <button class="btn-donasi">Donasi Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-2" style="background-color: var(--secondary-color); border-radius: 28px; color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i data-lucide="droplets" class="text-primary-custom"></i>
                        </div>
                        <span class="badge" style="background-color: rgba(255,255,255,0.1); height: fit-content; padding: 8px 15px;">Trending #2</span>
                    </div>
                    <h5 class="fw-bold mb-3">Air Bersih untuk Desa Kekeringan</h5>
                    <p class="small opacity-75 mb-4">Sumur bor untuk 3 desa di Ogan Ilir yang kesulitan air bersih saat musim kemarau tiba.</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div class="small fw-bold">8,2k Donasi</div>
                        <button class="btn btn-light text-primary-custom fw-bold rounded-3 px-4">Donasi</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-2" style="background-color: var(--accent-color); border-radius: 28px; color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i data-lucide="book-open" class="text-primary-custom"></i>
                        </div>
                        <span class="badge" style="background-color: rgba(255,255,255,0.1); height: fit-content; padding: 8px 15px;">Trending #3</span>
                    </div>
                    <h5 class="fw-bold mb-3">Beasiswa Anak Marbot Masjid</h5>
                    <p class="small opacity-75 mb-4">Membantu biaya kuliah 10 anak marbot masjid berprestasi agar bisa meraih cita-cita.</p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div class="small fw-bold">5,4k Donasi</div>
                        <button class="btn btn-light text-primary-custom fw-bold rounded-3 px-4">Donasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
