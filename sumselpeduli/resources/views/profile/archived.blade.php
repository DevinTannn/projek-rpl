@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('profile.show') }}" class="btn btn-light rounded-circle shadow-sm p-2">
                    <i data-lucide="arrow-left" style="width: 24px; height: 24px;"></i>
                </a>
                <h3 class="fw-bold m-0">Riwayat Donasi</h3>
            </div>

            @forelse($donations as $date => $dayDonations)
                <div class="date-group mb-4">
                    <h6 class="fw-bold text-muted mb-3">{{ $date }}</h6>
                    
                    @foreach($dayDonations as $donation)
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 20px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="icon-wrapper rounded-circle d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 60px; height: 60px; flex-shrink: 0;">
                                        <i data-lucide="award" class="text-secondary-color" style="width: 32px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-primary-custom" style="font-size: 1.1rem;">
                                                    {{ $donation->campaign->title }}
                                                </h6>
                                                <p class="text-muted small mb-0" style="font-style: italic;">
                                                    "Terima kasih atas kebaikan Anda, semoga bermanfaat bagi sesama."
                                                </p>
                                                <div class="d-flex align-items-center gap-2 mt-2">
                                                    <span class="text-muted" style="font-size: 11px;">#{{ $donation->order_id }}</span>
                                                    @if($donation->status === 'paid')
                                                        <span class="badge bg-success rounded-pill px-3" style="font-size: 10px;">Berhasil Terverifikasi</span>
                                                    @elseif($donation->status === 'pending')
                                                        <span class="badge bg-warning text-dark rounded-pill px-3" style="font-size: 10px;">Menunggu Verifikasi</span>
                                                    @else
                                                        <span class="badge bg-danger rounded-pill px-3" style="font-size: 10px;">Transaksi Gagal</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end d-flex flex-column align-items-end gap-3">
                                                <div class="fw-bold text-accent-custom h5 m-0">-Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                                
                                                @if($donation->status === 'paid')
                                                    <a href="{{ route('donations.certificate', $donation->id) }}" 
                                                       class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3"
                                                       style="font-size: 11px;">
                                                        <i data-lucide="download" style="width: 14px;" class="me-1"></i> Sertifikat
                                                    </a>
                                                @elseif($donation->status === 'pending' && $donation->snap_token && $donation->midtrans_status === 'pending')
                                                    <button class="btn btn-primary btn-sm rounded-pill fw-bold px-4" 
                                                            style="font-size: 11px;"
                                                            onclick="payDonation('{{ $donation->snap_token }}')">
                                                        Beri Donasi
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i data-lucide="history" style="width: 64px; height: 64px; opacity: 0.2;"></i>
                    </div>
                    <h5 class="fw-bold">Belum Ada Transaksi</h5>
                    <p class="text-muted">Donasi yang Anda lakukan akan muncul di sini.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 fw-bold">Mulai Berbagi</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function payDonation(token) {
        window.snap.pay(token, {
            onSuccess: function(result){ location.reload(); },
            onPending: function(result){ location.reload(); },
            onError: function(result){ location.reload(); },
            onClose: function(){ alert('Anda belum menyelesaikan pembayaran.'); }
        });
    }
</script>
@endsection

@push('styles')
<style>
    .date-group h6 {
        font-size: 14px;
        letter-spacing: -0.2px;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:active {
        transform: scale(0.98);
    }
    .bg-info {
        background-color: #0081a0 !important;
    }
</style>
@endpush
