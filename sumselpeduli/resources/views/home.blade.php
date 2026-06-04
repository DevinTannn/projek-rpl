@extends('layouts.app')

@section('content')
<style>
    .section-title h4 { font-weight: 800; letter-spacing: -0.5px; }
    .trending-card { 
        border-radius: 30px; 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
    }
    .trending-card:hover { 
        transform: translateY(-10px); 
        box-shadow: 0 25px 50px rgba(36, 62, 54, 0.15);
    }
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
                    <div class="campaign-card h-100">
                        <div class="card-img-container">
                            <img src="https://picsum.photos/seed/recent-{{ $campaign->id }}/600/400" alt="Campaign">
                        </div>
                        <div class="p-4">
                            <span class="badge mb-3 px-3 py-2 rounded-pill" style="background-color: var(--bg-color); color: var(--secondary-color); font-weight: 700; font-size: 10px;">{{ strtoupper($campaign->tag) }}</span>
                            <h5 class="fw-bold text-primary-custom mb-3 line-clamp-2 h-auto" style="min-height: 48px;">{{ $campaign->title }}</h5>
                            
                            <div class="progress-custom mb-3">
                                <div class="progress-bar-custom" style="width: {{ $campaign->percentage }}%;"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small">
                                    <span class="fw-bold text-accent-custom" style="font-size: 14px;">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                    <span class="text-muted" style="font-size: 11px;"> / Rp {{ number_format($campaign->goal_amount / 1000000, 1) }}jt</span>
                                </div>
                                <span class="text-muted small fw-semibold" style="font-size: 10px;">{{ $campaign->updated_at->diffForHumans() }}</span>
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
                <div class="trending-card h-100 p-2 shadow-sm" style="background-color: {{ $bg }}; color: white;">
                    <div class="card-body p-4 d-flex flex-column h-100">
                        <div class="d-flex justify-content-between mb-4">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                                <i data-lucide="{{ $index === 0 ? 'flame' : ($index === 1 ? 'droplets' : 'book-open') }}" style="color: {{ $bg }}; width: 24px; height: 24px;"></i>
                            </div>
                            <span class="badge px-3 py-2 rounded-pill" style="background-color: rgba(255,255,255,0.15); font-weight: 800; font-size: 10px; letter-spacing: 1px;">TRENDING #{{ $index + 1 }}</span>
                        </div>
                        <h5 class="fw-bold mb-3" style="font-size: 18px; line-height: 1.4;">{{ $campaign->title }}</h5>
                        <p class="small opacity-75 mb-4 line-clamp-2">{{ Str::limit($campaign->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="small fw-bold">{{ number_format($campaign->collected_amount / 1000, 1) }}k Donasi</div>
                            <a href="{{ route('campaigns.show', $campaign->id) }}" class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm" style="font-size: 12px; color: {{ $bg }};">Lihat Detail</a>
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
                    <div class="campaign-card h-100">
                        <div class="card-img-container">
                            <img src="https://picsum.photos/seed/exp-{{ $campaign->id }}/600/400" alt="Campaign">
                        </div>
                        <div class="p-4">
                            <span class="badge mb-3 px-3 py-2 rounded-pill" style="background-color: var(--bg-color); color: var(--secondary-color); font-weight: 700; font-size: 10px;">{{ strtoupper($campaign->tag) }}</span>
                            <h5 class="fw-bold text-primary-custom mb-3 line-clamp-2 h-auto" style="min-height: 48px;">{{ $campaign->title }}</h5>
                            
                            <div class="progress-custom mb-3">
                                <div class="progress-bar-custom" style="width: {{ $campaign->percentage }}%;"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small">
                                    <span class="fw-bold text-accent-custom" style="font-size: 14px;">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold" style="font-size: 9px; padding: 4px 10px; letter-spacing: 1px;">AKTIF</span>
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
