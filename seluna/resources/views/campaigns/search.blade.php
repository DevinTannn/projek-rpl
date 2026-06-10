@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-5">
        <h2 class="fw-bold text-primary-custom mb-2">Hasil Pencarian</h2>
        <p class="text-muted">Menampilkan hasil untuk: <span class="fw-bold text-secondary-custom">"{{ $query }}"</span></p>
    </div>

    @if($campaigns->count() > 0)
        <div class="row g-4">
            @foreach($campaigns as $campaign)
                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 24px; transition: transform 0.2s;">
                            <img src="https://picsum.photos/seed/campaign-{{ $campaign->id }}/600/400" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-4">
                                <span class="badge mb-3" style="background-color: var(--bg-color); color: var(--secondary-color);">{{ $campaign->tag }}</span>
                                <h5 class="fw-bold text-primary-custom mb-3">{{ $campaign->title }}</h5>
                                
                                <div class="progress mb-3" style="height: 8px; border-radius: 10px; background-color: #eee;">
                                    <div class="progress-bar" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color); border-radius: 10px;"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-muted">Terkumpul</div>
                                        <div class="fw-bold text-accent-custom">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted">Target</div>
                                        <div class="fw-bold text-primary-custom">Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $campaigns->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i data-lucide="search-x" class="text-muted mb-4" style="width: 64px; height: 64px;"></i>
            <h4 class="fw-bold text-muted">Tidak ada kampanye ditemukan</h4>
            <p class="text-muted">Gunakan kata kunci lain atau cari berdasarkan kategori.</p>
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 mt-3">Kembali ke Home</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endpush

<style>
    .card:hover { transform: translateY(-5px); }
</style>
@endsection
