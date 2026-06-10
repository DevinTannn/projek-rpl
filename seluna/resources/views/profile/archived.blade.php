@extends('layouts.app')

@section('content')
<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="d-flex align-items-center gap-3 mb-4 px-2 px-lg-0">
                <a href="{{ route('profile.show') }}" class="btn btn-light rounded-circle shadow-sm p-2">
                    <i data-lucide="arrow-left" style="width: 24px; height: 24px;"></i>
                </a>
                <h3 class="fw-bold m-0">Riwayat Donasi</h3>
            </div>

            {{-- Section: Unpaid Donations (Pending Midtrans) --}}
            @if($unpaidDonations->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-2 mb-3 px-2 px-lg-0">
                        <div class="bg-warning rounded-pill" style="width: 8px; height: 8px;"></div>
                        <h6 class="fw-bold m-0 text-muted uppercase tracking-wider small">Pembayaran Tertunda</h6>
                    </div>
                    
                    @foreach($unpaidDonations as $donation)
                        <div class="card border-0 shadow-sm mb-3 unpaid-card" style="border-radius: 24px; border-left: 5px solid #FFC107 !important;">
                            <div class="card-body p-3 p-lg-4">
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 gap-lg-4">
                                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10" 
                                         style="width: 54px; height: 54px; flex-shrink: 0;">
                                        <i data-lucide="credit-card" class="text-warning" style="width: 28px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold mb-1 text-primary-custom text-truncate" style="font-size: 1rem;">
                                                    {{ $donation->campaign->title }}
                                                </h6>
                                                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                                    <span class="text-muted" style="font-size: 10px;">#{{ $donation->order_id }}</span>
                                                    <span class="badge bg-warning bg-opacity-25 text-warning-emphasis rounded-pill px-2" style="font-size: 9px;">Menunggu Pembayaran</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-dark h6 m-0">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <button class="btn btn-primary rounded-pill fw-bold px-4 py-2 w-100 w-sm-auto shadow-sm" 
                                                    style="font-size: 12px;"
                                                    onclick="payDonation('{{ $donation->snap_token }}')">
                                                Selesaikan Pembayaran
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Section: Paid Donations --}}
            @forelse($paidDonations as $date => $dayDonations)
                <div class="date-group mb-4">
                    <h6 class="fw-bold text-muted mb-3 px-2 px-lg-0 small">{{ $date }}</h6>
                    
                    @foreach($dayDonations as $donation)
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 20px;">
                            <div class="card-body p-3 p-lg-4">
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 gap-lg-4">
                                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 54px; height: 54px; flex-shrink: 0;">
                                        <i data-lucide="award" class="text-secondary-color" style="width: 28px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold mb-1 text-primary-custom text-truncate" style="font-size: 1rem;">
                                                    {{ $donation->campaign->title }}
                                                </h6>
                                                <p class="text-muted small mb-0 d-none d-sm-block text-truncate" style="font-style: italic; max-width: 300px;">
                                                    "Terima kasih atas kebaikan Anda."
                                                </p>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="text-muted" style="font-size: 10px;">#{{ $donation->order_id }}</span>
                                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2" style="font-size: 9px;">Berhasil Terverifikasi</span>
                                                </div>
                                            </div>
                                            <div class="text-end d-flex flex-column align-items-end gap-2">
                                                <div class="fw-bold text-accent-custom h6 m-0">-Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                                <a href="{{ route('donations.certificate', $donation->id) }}" 
                                                   class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3 py-1 mt-1"
                                                   style="font-size: 10px;">
                                                    <i data-lucide="download" style="width: 12px;" class="me-1"></i> Sertifikat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                @if($unpaidDonations->count() === 0)
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i data-lucide="history" style="width: 64px; height: 64px; opacity: 0.1;"></i>
                        </div>
                        <h5 class="fw-bold">Pelacak Kebaikan Anda Kosong</h5>
                        <p class="text-muted small">Donasi yang Anda lakukan akan tersimpan manis di sini.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold mt-2 shadow-sm">Mulai Berbagi</a>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    function payDonation(token) {
        if (!token) {
            alert('Maaf, token pembayaran tidak ditemukan.');
            return;
        }
        window.snap.pay(token, {
            onSuccess: function(result){ location.reload(); },
            onPending: function(result){ location.reload(); },
            onError: function(result){ location.reload(); },
            onClose: function(){ alert('Silakan selesaikan pembayaran nanti di halaman ini.'); }
        });
    }
</script>
@endsection

@push('styles')
<style>
    .date-group h6 {
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .unpaid-card {
        background: linear-gradient(to right, #ffffff, #fffdf8);
    }
    @media (max-width: 576px) {
        .icon-wrapper {
            width: 44px !important;
            height: 44px !important;
        }
        .icon-wrapper i {
            width: 20px !important;
        }
        h3 { font-size: 1.5rem; }
    }
</style>
@endpush
