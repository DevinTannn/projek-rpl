@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 py-lg-4">
    <div class="row g-4 justify-content-center">
        <!-- Left Sidebar: Profile Info -->
        <div class="col-12 col-lg-3">
            <div class="text-center text-lg-start bg-white p-4 rounded-4 shadow-sm border border-light">
                <div class="mb-4 position-relative d-inline-block">
                    <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name='.$user->username.'&background=7CA982&color=fff&size=300' }}" 
                         class="rounded-circle border border-4 border-white shadow-sm profile-img-main" 
                         style="width: 180px; height: 180px; object-fit: cover; background-color: var(--surface-color);" loading="lazy">
                </div>
                
                <h4 class="fw-bold m-0 text-primary-custom">{{ $user->name ?? $user->username }}</h4>
                <p class="text-muted small mb-3">@ {{ $user->username }}</p>

                @if($user->description)
                    <p class="mb-4 small text-secondary">{{ $user->description }}</p>
                @endif

                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold mb-4 py-2 small">{{ __('Edit Profile') }}</a>

                <div class="d-flex flex-column gap-2 text-muted small mt-2">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="mail" style="width: 14px;"></i>
                        <span class="text-truncate">{{ $user->email }}</span>
                    </div>
                    @if($user->date_of_birth)
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="calendar" style="width: 14px;"></i>
                        <span>Lahir pada {{ \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-top">
                    @if($user->isVerified())
                        <div class="badge bg-success bg-opacity-10 text-success p-2 w-100 rounded-3 mb-2 small d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="check-circle" style="width: 14px;"></i> {{ __('Identitas Terverifikasi') }}
                        </div>
                    @else
                        <a href="{{ route('profile.verification') }}" class="btn btn-warning btn-sm w-100 rounded-pill mb-2 fw-bold shadow-sm py-2">
                            <i data-lucide="alert-triangle" class="me-1" style="width: 14px;"></i> {{ __('Verifikasi Sekarang') }}
                        </a>
                    @endif

                    @if($user->role === 'fundraiser')
                        <div class="badge bg-primary-custom p-2 w-100 rounded-3 small d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="award" style="width: 14px;"></i> {{ __('Official Fundraiser') }}
                        </div>
                    @else
                        <div class="badge bg-secondary bg-opacity-10 text-secondary p-2 w-100 rounded-3 small d-flex align-items-center justify-content-center gap-2">
                            <i data-lucide="user" style="width: 14px;"></i> {{ __('Official Donatur') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Content: Tabs & Activity -->
        <div class="col-12 col-lg-9">
            <div class="bg-white p-3 p-lg-4 rounded-4 shadow-sm border border-light">
                <ul class="nav nav-tabs border-0 flex-nowrap overflow-auto mb-3" id="profileTabs" role="tablist" style="scrollbar-width: none;">
                    <li class="nav-item">
                        <a class="nav-link active border-0 bg-transparent fw-bold px-3 pb-2 text-nowrap" id="overview-tab" data-bs-toggle="tab" href="#overview" style="color: var(--primary-color);">
                            Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0 bg-transparent fw-bold px-3 pb-2 text-muted text-nowrap" id="followed-tab" data-bs-toggle="tab" href="#followed">
                            {{ __('Diikuti') }} <span class="ms-1 opacity-50">{{ $user->follows->count() }}</span>
                        </a>
                    </li>
                    @if($user->role === 'fundraiser')
                    <li class="nav-item">
                        <a class="nav-link border-0 bg-transparent fw-bold px-3 pb-2 text-muted text-nowrap" id="campaigns-tab" data-bs-toggle="tab" href="#campaigns">
                            {{ __('Kampanye') }} <span class="ms-1 opacity-50">{{ $user->campaigns->count() }}</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link border-0 bg-transparent fw-bold px-3 pb-2 text-muted text-nowrap" id="donations-tab" data-bs-toggle="tab" href="#donations">
                            {{ __('Riwayat') }} <span class="ms-1 opacity-50">{{ $user->donations->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Tab 1: Overview --}}
                    <div class="tab-pane fade show active" id="overview">
                        <h6 class="fw-bold mb-4 small uppercase tracking-widest text-muted">Aktivitas Terakhir</h6>
                        <div class="row g-3">
                            @forelse($user->follows->take(4) as $follow)
                                <div class="col-12 col-md-6">
                                    <a href="{{ route('campaigns.show', $follow->campaign->id) }}" class="text-decoration-none card border-0 bg-light p-3 rounded-4 h-100 activity-card transition-all">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-white p-2 rounded-3 shadow-sm text-accent-custom">
                                                <i data-lucide="heart" style="width: 18px;"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold m-0 text-primary-custom text-truncate">{{ $follow->campaign->title }}</h6>
                                                <small class="text-muted" style="font-size: 10px;">{{ __('Mengikuti sejak') }} {{ $follow->created_at->format('M Y') }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12 py-5 text-center bg-light rounded-4">
                                    <i data-lucide="activity" class="text-muted opacity-25 mb-2" style="width: 40px; height: 40px;"></i>
                                    <p class="text-muted small m-0">{{ __('Belum ada aktivitas untuk ditampilkan.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- Tab 2: Followed --}}
                    <div class="tab-pane fade" id="followed">
                        <div class="row g-3">
                            @foreach($user->follows as $follow)
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                        <img src="https://picsum.photos/seed/{{ $follow->campaign->id }}/400/200" class="w-100" style="height: 120px; object-fit: cover;" loading="lazy">
                                        <div class="p-3">
                                            <h6 class="fw-bold small text-truncate mb-2">{{ $follow->campaign->title }}</h6>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" style="width: {{ $follow->campaign->percentage }}%; background-color: var(--secondary-color);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab 3: Campaigns --}}
                    @if($user->role === 'fundraiser')
                    <div class="tab-pane fade" id="campaigns">
                        <div class="row g-3">
                            @foreach($user->campaigns as $campaign)
                                <div class="col-12 col-sm-6 col-xl-4">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                                        <div class="p-3">
                                            <h6 class="fw-bold mb-1 small text-truncate">{{ $campaign->title }}</h6>
                                            <div class="text-muted mb-3" style="font-size: 10px;">Target: Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</div>
                                            <div class="progress" style="height: 6px; border-radius: 10px;">
                                                <div class="progress-bar" style="width: {{ $campaign->percentage }}%; background-color: var(--accent-color);"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Tab 4: Donations (Riwayat) --}}
                    <div class="tab-pane fade" id="donations">
                        <div class="d-flex flex-column gap-3">
                            @forelse($user->donations as $donation)
                                @php
                                    $isPaid = in_array($donation->status, ['paid', 'success', 'settlement', 'approved']);
                                    $isPending = $donation->status === 'pending';
                                    $isFailed = in_array($donation->status, ['failed', 'rejected', 'expire', 'cancel']);
                                @endphp
                                <div class="card border-0 shadow-sm p-3 p-lg-4 rounded-4 position-relative overflow-hidden transition-all"
                                     style="border-left: 5px solid {{ $isPaid ? '#2D5A27' : ($isPending ? '#FFC107' : '#DC3545') }} !important;">
                                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-opacity-10 p-2"
                                                 style="width: 48px; height: 48px; flex-shrink: 0; background-color: {{ $isPaid ? '#2D5A27' : ($isPending ? '#FFC107' : '#DC3545') }}; color: {{ $isPaid ? '#2D5A27' : ($isPending ? '#B8860B' : '#DC3545') }};">
                                                @if($isPaid)
                                                    <i data-lucide="check-circle" style="width: 24px; height: 24px;"></i>
                                                @elseif($isPending)
                                                    <i data-lucide="clock" style="width: 24px; height: 24px;"></i>
                                                @else
                                                    <i data-lucide="x-circle" style="width: 24px; height: 24px;"></i>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold mb-1 text-primary-custom text-truncate" style="font-size: 0.95rem;" title="{{ $donation->campaign->title }}">
                                                    {{ $donation->campaign->title }}
                                                </h6>
                                                <div class="d-flex flex-wrap align-items-center gap-2 small mt-1">
                                                    <span class="text-muted" style="font-size: 10px;">#{{ $donation->order_id }}</span>
                                                    <span class="text-muted">•</span>
                                                    <span class="text-muted" style="font-size: 10px;">{{ $donation->created_at->format('d M Y, H:i') }}</span>
                                                    <span class="text-muted">•</span>
                                                    <span class="badge bg-light text-secondary rounded-pill px-2 border" style="font-size: 9px;">{{ $donation->payment_method }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row flex-md-column align-items-center align-items-md-end justify-content-between justify-content-md-center gap-2">
                                            <div class="fw-bold h5 m-0 {{ $isPaid ? 'text-success' : ($isPending ? 'text-warning-emphasis' : 'text-danger') }}" style="color: {{ $isPaid ? '#2D5A27 !important' : '' }}">
                                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($isPaid)
                                                     <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1" style="font-size: 10px; font-weight: 700; color: #2D5A27 !important; background-color: rgba(45, 90, 39, 0.1) !important;">
                                                         {{ __('Berhasil') }}
                                                     </span>
                                                     <a href="{{ route('donations.certificate', $donation->id) }}" 
                                                        class="btn btn-outline-success btn-sm rounded-pill fw-bold px-3 py-1 ms-2"
                                                        style="font-size: 10px; border-color: #2D5A27; color: #2D5A27;">
                                                         <i data-lucide="download" style="width: 12px;" class="me-1"></i> {{ __('Sertifikat') }}
                                                     </a>
                                                @elseif($isPending)
                                                    @if($donation->payment_method === 'Manual')
                                                        <span class="badge bg-warning bg-opacity-10 text-warning-emphasis rounded-pill px-2.5 py-1" style="font-size: 10px; font-weight: 700;">
                                                            {{ __('Verifikasi Admin') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning bg-opacity-10 text-warning-emphasis rounded-pill px-2.5 py-1" style="font-size: 10px; font-weight: 700;">
                                                            {{ __('Pending') }}
                                                        </span>
                                                        <button class="btn btn-primary btn-sm rounded-pill fw-bold px-3 py-1 shadow-sm ms-2"
                                                                style="font-size: 10px;"
                                                                onclick="payDonation('{{ $donation->snap_token }}')">
                                                            {{ __('Bayar') }}
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1" style="font-size: 10px; font-weight: 700;">
                                                        {{ __('Gagal') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-5 text-center bg-light rounded-4">
                                    <i data-lucide="history" class="text-muted opacity-25 mb-2" style="width: 40px; height: 40px;"></i>
                                    <p class="text-muted small m-0">{{ __('Belum ada riwayat donasi.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        
        // Handle tab styles manually if needed for better mobile feel
        document.querySelectorAll('#profileTabs .nav-link').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                document.querySelectorAll('#profileTabs .nav-link').forEach(l => {
                    l.classList.add('text-muted');
                    l.style.borderBottom = 'none';
                });
                e.target.classList.remove('text-muted');
                e.target.style.borderBottom = '2px solid var(--accent-color)';
            });
        });
    });
</script>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link.active {
        border-bottom: 2px solid var(--accent-color) !important;
    }
    .activity-card:hover {
        background-color: #f8fcf9 !important;
        transform: translateX(5px);
    }
    @media (max-width: 991px) {
        .profile-img-main {
            width: 140px !important;
            height: 140px !important;
        }
        .container-fluid { padding-bottom: 80px !important; }
    }
</style>
@endpush

@push('scripts')
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
@endpush
