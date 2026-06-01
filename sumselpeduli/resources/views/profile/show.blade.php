@extends('layouts.app')

@section('content')
<div class="row g-4 mt-2">
    <!-- Left Sidebar: Profile Info -->
    <div class="col-lg-3">
        <div class="text-center text-lg-start">
            <div class="mb-4 position-relative d-inline-block">
                <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name='.$user->username.'&background=7CA982&color=fff&size=300' }}" 
                     class="rounded-circle border border-4 border-white shadow-sm" 
                     style="width: 260px; height: 260px; object-fit: cover; background-color: var(--surface-color);">
            </div>
            
            <h3 class="fw-bold m-0 text-primary-custom">{{ $user->name ?? $user->username }}</h3>
            <p class="text-muted fs-5 mb-3">{{ $user->username }}</p>

            @if($user->description)
                <p class="mb-4">{{ $user->description }}</p>
            @endif

            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 rounded-3 fw-bold mb-4">Edit Profile</a>

            <div class="d-flex flex-column gap-2 text-muted small">
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="mail" style="width: 16px;"></i>
                    <span>{{ $user->email }}</span>
                </div>
                @if($user->date_of_birth)
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="calendar" style="width: 16px;"></i>
                    <span>Born on {{ \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') }}</span>
                </div>
                @endif
                <div class="d-flex align-items-center gap-2">
                    <i data-lucide="user" style="width: 16px;"></i>
                    <span>{{ $user->gender ?? 'Not specified' }}</span>
                </div>
            </div>

                <div class="mt-5 pt-4 border-top">
                    @if($user->isVerified())
                        <div class="badge bg-success p-2 w-100 rounded-3 mb-2 shadow-sm">
                            <i data-lucide="check-circle" class="me-1" style="width: 14px;"></i> Identitas Terverifikasi
                        </div>
                    @else
                        <a href="{{ route('profile.verification') }}" class="btn btn-warning btn-sm w-100 rounded-3 mb-2 fw-bold shadow-sm">
                            <i data-lucide="alert-triangle" class="me-1" style="width: 14px;"></i> Verifikasi Sekarang
                        </a>
                    @endif

                    @if($user->role === 'fundraiser')
                        <div class="badge bg-primary-custom p-2 w-100 rounded-3">
                            <i data-lucide="award" class="me-1"></i> Official Fundraiser
                        </div>
                    @else
                        <div class="badge bg-secondary p-2 w-100 rounded-3 text-white opacity-75">
                            <i data-lucide="user" class="me-1"></i> Official Donatur
                        </div>
                    @endif
                </div>
        </div>
    </div>

    <!-- Right Content: Tabs & Activity -->
    <div class="col-lg-9">
        <ul class="nav nav-tabs border-bottom gap-4" id="profileTabs">
            <li class="nav-item">
                <a class="nav-link active border-0 px-0 pb-3" style="border-bottom: 2px solid var(--accent-color) !important;" data-bs-toggle="tab" href="#overview">
                    <i data-lucide="layout" class="me-2" style="width: 18px;"></i> Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-0 px-0 pb-3" data-bs-toggle="tab" href="#followed">
                    <i data-lucide="heart" class="me-2" style="width: 18px;"></i> Diikuti <span class="badge bg-light text-dark rounded-pill ms-1">{{ $user->follows->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-0 px-0 pb-3" href="{{ route('profile.archived') }}">
                    <i data-lucide="history" class="me-2" style="width: 18px;"></i> Riwayat Donasi
                </a>
            </li>
            @if($user->role === 'fundraiser')
            <li class="nav-item">
                <a class="nav-link border-0 px-0 pb-3" data-bs-toggle="tab" href="#campaigns">
                    <i data-lucide="layers" class="me-2" style="width: 18px;"></i> Campaigns <span class="badge bg-light text-dark rounded-pill ms-1">{{ $user->campaigns->count() }}</span>
                </a>
            </li>
            @endif
        </ul>

        <div class="tab-content pt-4">
            <div class="tab-pane fade show active" id="overview">
                <h6 class="fw-bold mb-3">Popular Activity</h6>
                <div class="row g-3">
                    @forelse($user->follows->take(4) as $follow)
                        <div class="col-md-6">
                            <div class="card border p-3 rounded-4 h-100">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-surface p-2 rounded-3">
                                        <i data-lucide="heart" class="text-accent-custom"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold m-0">{{ $follow->campaign->title }}</h6>
                                        <small class="text-muted">Following since {{ $follow->created_at->format('M Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center bg-light rounded-4">
                            <p class="text-muted m-0">No recent activity to show.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="tab-pane fade" id="followed">
                <div class="row g-4">
                    @foreach($user->follows as $follow)
                        <!-- Campaign Cards (Simplified) -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <img src="https://picsum.photos/seed/{{ $follow->campaign->id }}/400/200" class="w-100" style="height: 150px; object-fit: cover;">
                                <div class="p-3">
                                    <h6 class="fw-bold">{{ $follow->campaign->title }}</h6>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar" style="width: {{ $follow->campaign->percentage }}%; background-color: var(--secondary-color);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($user->role === 'fundraiser')
            <div class="tab-pane fade" id="campaigns">
                <div class="row g-4">
                    @foreach($user->campaigns as $campaign)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="p-3">
                                    <h6 class="fw-bold mb-1">{{ $campaign->title }}</h6>
                                    <div class="text-muted small mb-3">Target: Rp {{ number_format($campaign->goal_amount) }}</div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" style="width: {{ $campaign->percentage }}%; background-color: var(--accent-color);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
