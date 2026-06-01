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

<!-- Recently Updated -->
<div class="mb-5">
    <div class="section-title mb-4">
        <h4 class="m-0 text-primary-custom">Akses Terakhir</h4>
        @if($lastUpdatedCampaigns->count() > 0)
            <a href="{{ route('campaigns.last-accessed') }}" class="text-secondary-custom text-decoration-none small fw-bold">Lihat Semua <i data-lucide="chevron-right" style="width: 14px;"></i></a>
        @endif
    </div>
    <div class="row g-4">
        @forelse($lastUpdatedCampaigns as $campaign)
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-decoration-none">
                    <div class="dummy-card h-100">
                        <img src="https://picsum.photos/seed/recent-{{ $campaign->id }}/600/400" class="w-100" alt="Campaign">
                        <div class="p-3">
                            <span class="badge mb-2" style="background-color: var(--bg-color); color: var(--secondary-color);">{{ $campaign->tag }}</span>
                            <h5 class="fw-bold text-primary-custom mb-3">{{ $campaign->title }}</h5>
                            <div class="progress mb-2" style="height: 8px; border-radius: 10px; background-color: #eee;">
                                <div class="progress-bar" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color); border-radius: 10px;"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small">
                                    <span class="fw-bold text-accent-custom">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span> <span class="text-muted">/ Rp {{ number_format($campaign->goal_amount / 1000000, 1) }}jt</span>
                                </div>
                                <span class="text-muted small fw-semibold">{{ $campaign->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Belum ada riwayat akses campaign.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Popular Now -->
<div class="mb-5">
    <div class="section-title mb-4">
        <h4 class="m-0 text-primary-custom">Populer Sekarang</h4>
    </div>
    <div class="row g-4">
        @forelse($popularCampaigns as $index => $campaign)
            <div class="col-12 col-lg-4">
                @php
                    $colors = [
                        'var(--primary-color)',
                        'var(--secondary-color)',
                        'var(--accent-color)'
                    ];
                    $bg = $colors[$index % 3];
                @endphp
                <div class="card border-0 shadow-sm p-2" style="background-color: {{ $bg }}; border-radius: 28px; color: white;">
                    <div class="card-body p-4 d-flex flex-column h-100">
                        <div class="d-flex justify-content-between mb-4">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i data-lucide="{{ $index === 0 ? 'flame' : ($index === 1 ? 'droplets' : 'book-open') }}" class="text-primary-custom"></i>
                            </div>
                            <span class="badge" style="background-color: rgba(255,255,255,0.1); height: fit-content; padding: 8px 15px;">Trending #{{ $index + 1 }}</span>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $campaign->title }}</h5>
                        <p class="small opacity-75 mb-4 line-clamp-2">{{ Str::limit($campaign->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="small fw-bold">{{ number_format($campaign->collected_amount / 1000, 1) }}k Donasi</div>
                            <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn-donasi text-decoration-none">Donasi Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Belum ada kampanye populer.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Exploration Section -->
<div class="mb-5">
    <div class="section-title mb-4">
        <h4 class="m-0 text-primary-custom">Eksplorasi</h4>
        <p class="text-muted small m-0">Kampanye terbaru yang menunggumu</p>
    </div>
    <div class="row g-4">
        @forelse($explorationCampaigns as $campaign)
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-decoration-none">
                    <div class="dummy-card h-100">
                        <img src="https://picsum.photos/seed/exp-{{ $campaign->id }}/600/400" class="w-100" alt="Campaign">
                        <div class="p-3">
                            <span class="badge mb-2" style="background-color: var(--bg-color); color: var(--secondary-color);">{{ $campaign->tag }}</span>
                            <h5 class="fw-bold text-primary-custom mb-3">{{ $campaign->title }}</h5>
                            <div class="progress mb-2" style="height: 8px; border-radius: 10px; background-color: #eee;">
                                <div class="progress-bar" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color); border-radius: 10px;"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small">
                                    <span class="fw-bold text-accent-custom">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-muted small fw-semibold">Aktif</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i data-lucide="layout-grid" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                <p class="text-muted">Mulai eksplorasi pertamamu.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
