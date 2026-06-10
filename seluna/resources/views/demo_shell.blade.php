@extends('layouts.shell')

@section('content')
<!-- Recently Accessed -->
<div class="mb-5">
    <div class="section-title">
        <h4 class="m-0 text-primary-custom">Akses Terakhir</h4>
        <a href="#" class="text-accent-custom text-decoration-none small fw-bold">Lihat Semua</a>
    </div>
    <div class="row g-4">
        <div class="col-6 col-lg-4">
            <div class="dummy-card shadow-sm">
                <img src="https://picsum.photos/seed/recent1/600/400" alt="Campaign">
                <div class="dummy-card-body">
                    <h6 class="fw-bold text-truncate">Bantu Renovasi Sekolah di Pelosok</h6>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar" style="width: 75%; background-color: var(--accent-color);"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2" style="font-size: 11px;">
                        <span>75% Terkumpul</span>
                        <span class="text-muted">12 Hari Lagi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-4">
            <div class="dummy-card shadow-sm">
                <img src="https://picsum.photos/seed/recent2/600/400" alt="Campaign">
                <div class="dummy-card-body">
                    <h6 class="fw-bold text-truncate">Sembako untuk Lansia Dhuafa</h6>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar" style="width: 40%; background-color: var(--accent-color);"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2" style="font-size: 11px;">
                        <span>40% Terkumpul</span>
                        <span class="text-muted">5 Hari Lagi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block">
            <div class="dummy-card shadow-sm">
                <img src="https://picsum.photos/seed/recent3/600/400" alt="Campaign">
                <div class="dummy-card-body">
                    <h6 class="fw-bold text-truncate">Operasi Bibir Sumbing Anak</h6>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar" style="width: 90%; background-color: var(--accent-color);"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2" style="font-size: 11px;">
                        <span>90% Terkumpul</span>
                        <span class="text-muted">2 Hari Lagi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Now -->
<div class="mb-5">
    <div class="section-title">
        <h4 class="m-0 text-primary-custom">Populer Sekarang</h4>
        <i data-lucide="trending-up" class="text-accent-custom"></i>
    </div>
    <div class="row g-4">
        @for($i=1; $i<=3; $i++)
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background-color: var(--secondary-color); color: white;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i data-lucide="zap" class="text-accent-custom"></i>
                        </div>
                        <span class="badge bg-light text-dark">Trending #{{$i}}</span>
                    </div>
                    <h5 class="fw-bold">Darurat Banjir Sumatera Selatan</h5>
                    <p class="small opacity-75">Ribuan rumah terendam, butuh bantuan logistik dan obat-obatan segera.</p>
                    <hr class="bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small">8,4k Donasi</div>
                        <button class="btn btn-sm btn-light fw-bold text-primary-custom px-3" style="border-radius: 10px;">Donasi</button>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

<!-- Events -->
<div class="mb-5">
    <div class="section-title">
        <h4 class="m-0 text-primary-custom">Event Mendatang</h4>
    </div>
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 p-4" style="background: linear-gradient(135deg, var(--primary-color), #3a6351); border-radius: 20px; color: white;">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <h1 class="display-4 fw-bold m-0 text-accent-custom">12</h1>
                        <p class="m-0 text-uppercase tracking-wider">Juni 2026</p>
                    </div>
                    <div class="col-md-7 ps-md-4">
                        <h4 class="fw-bold">Gala Dinner Amal: Peduli Pendidikan Sumsel</h4>
                        <p class="m-0 opacity-75">Hotel Aryaduta Palembang • 19:00 WIB</p>
                        <div class="mt-2 d-flex gap-2">
                            <span class="badge" style="background-color: var(--secondary-color);">Offline</span>
                            <span class="badge" style="background-color: rgba(255,255,255,0.1);">Pendidikan</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end mt-3 mt-md-0">
                        <button class="btn btn-accent px-4 py-2 fw-bold">Daftar Event</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
