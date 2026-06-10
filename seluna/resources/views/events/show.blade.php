@extends('layouts.app')

@section('content')
<style>
    :root {
        --event-bg: {{ $event['bg_color'] }};
        --event-accent: {{ $event['accent_color'] }};
    }
    .event-header {
        background: linear-gradient(135deg, var(--event-bg) 0%, #000 150%);
        color: white;
        padding: 60px 40px;
        border-radius: 40px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    .event-header::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: var(--event-accent);
        filter: blur(80px);
        opacity: 0.3;
    }
    .event-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    .event-card:hover {
        transform: translateY(-5px);
        border-color: var(--event-accent);
    }
    .btn-event {
        background-color: var(--event-accent);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.2s;
    }
    .btn-event:hover {
        background-color: white;
        color: var(--event-bg);
        transform: scale(1.05);
    }
</style>

<div class="event-header shadow-lg">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <span class="badge bg-white text-dark mb-3 px-3 py-2 rounded-pill fw-bold">Special Event</span>
            <h1 class="display-4 fw-bold mb-3">{{ $event['title'] }}</h1>
            <p class="lead opacity-75 mb-4">{{ $event['description'] }}</p>
            <div class="d-flex gap-3">
                <div class="bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                    <div class="small opacity-75">Terkumpul Pasca Event</div>
                    <div class="h4 fw-bold m-0">Rp 128.500.000</div>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-4 backdrop-blur">
                    <div class="small opacity-75">Donatur Terlibat</div>
                    <div class="h4 fw-bold m-0">1.240</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <i data-lucide="{{ $event['icon'] }}" style="width: 120px; height: 120px; color: var(--event-accent); filter: drop-shadow(0 0 20px rgba(255,255,255,0.2));"></i>
        </div>
    </div>
</div>

<div class="section-title mb-4">
    <h4 class="fw-bold text-primary-custom">Program Donasi {{ $event['title'] }}</h4>
</div>

<div class="row g-4">
    <div class="col-md-6 col-xl-4">
        <div class="event-card shadow-sm">
            <img src="https://picsum.photos/seed/event1/600/400" class="w-100 rounded-4 mb-4" style="height: 200px; object-fit: cover;">
            <h5 class="fw-bold mb-2">Sedekah Pangan {{ $event['theme'] === 'ramadan' ? 'Iftar' : 'Keluarga' }}</h5>
            <p class="text-muted small mb-4">Membantu menyediakan kebutuhan makanan pokok bagi masyarakat prasejahtera.</p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-bold text-primary-custom">Rp 50.000 / Paket</div>
                <button class="btn-event">Donasi</button>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="event-card shadow-sm">
            <img src="https://picsum.photos/seed/event2/600/400" class="w-100 rounded-4 mb-4" style="height: 200px; object-fit: cover;">
            <h5 class="fw-bold mb-2">Bantuan {{ $event['theme'] === 'ramadan' ? 'Zakat & Infaq' : 'Kemanusiaan' }}</h5>
            <p class="text-muted small mb-4">Penyaluran bantuan dana pendidikan dan kesehatan untuk anak yatim piatu.</p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-bold text-primary-custom">Nominal Bebas</div>
                <button class="btn-event">Donasi</button>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="event-card shadow-sm">
            <img src="https://picsum.photos/seed/event3/600/400" class="w-100 rounded-4 mb-4" style="height: 200px; object-fit: cover;">
            <h5 class="fw-bold mb-2">Renovasi {{ $event['theme'] === 'ramadan' ? 'Masjid' : 'Fasilitas Umum' }}</h5>
            <p class="text-muted small mb-4">Perbaikan sarana ibadah dan fasilitas publik untuk kenyamanan bersama.</p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-bold text-primary-custom">Target Rp 50jt</div>
                <button class="btn-event">Donasi</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endpush
@endsection
