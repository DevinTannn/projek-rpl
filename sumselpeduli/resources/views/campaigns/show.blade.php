@extends('layouts.app')

@push('styles')
<style>
    .timeline { position: relative; padding-left: 0; }
    .update-card { transition: transform 0.2s; border: 1px solid #eee !important; }
    .update-card:hover { transform: translateY(-3px); }
    .update-media img, .update-media video { border-bottom: 1px solid #eee; }
    .fill-danger { fill: #dc3545 !important; }
    
    #follow-btn {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    #follow-btn:hover {
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    #follow-btn:active {
        transform: scale(0.9);
    }
    .heart-pulse {
        animation: heartPulse 0.4s ease;
    }
    @keyframes heartPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@section('content')

{{-- Real-time Alerts Container --}}
<div id="alert-container">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm">
            <i data-lucide="check-circle" style="width:16px" class="me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm">
            <i data-lucide="alert-circle" style="width:16px" class="me-2"></i> {{ session('error') }}
        </div>
    @endif
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">

        {{-- ── Gallery Wrapper ── --}}
        <div id="gallery-wrapper">
            @if($campaign->media->count() > 0)
            <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 24px; background: #1a1a2e;">
                {{-- Main Viewer --}}
                <div id="main-viewer" style="width:100%; aspect-ratio:16/9; position:relative; background:#000; overflow:hidden;">
                    @php $first = $campaign->media->first(); @endphp
                    @if($first->isVideo())
                        <video id="main-video" src="{{ $first->url }}" controls class="w-100 h-100" style="object-fit:contain; display:block;"></video>
                        <div id="main-image" style="display:none; width:100%; height:100%;"></div>
                    @else
                        <video id="main-video" style="display:none;" controls class="w-100 h-100" style="object-fit:contain;"></video>
                        <div id="main-image" style="width:100%; height:100%; background-size:contain; background-position:center; background-repeat:no-repeat; background-image:url('{{ $first->url }}');"></div>
                    @endif
                </div>

                {{-- Thumbnail Strip --}}
                <div style="display:flex; gap:8px; padding:12px 16px; overflow-x:auto; background:#111827;" id="thumb-strip">
                    @foreach($campaign->media as $index => $media)
                        <div class="thumb-item {{ $index === 0 ? 'active' : '' }}"
                             id="thumb-{{ $media->id }}"
                             data-id="{{ $media->id }}"
                             data-url="{{ $media->url }}"
                             data-type="{{ $media->file_type }}"
                             onclick="selectMedia(this)"
                             style="flex-shrink:0; width:120px; height:68px; border-radius:8px; overflow:hidden; cursor:pointer;
                                    border: 2px solid {{ $index === 0 ? '#C2A83E' : 'transparent' }};
                                    position:relative; transition: border-color .2s;">
                            @if($media->isVideo())
                                <video src="{{ $media->url }}" muted preload="metadata"
                                       style="width:100%; height:100%; object-fit:cover; pointer-events:none;"></video>
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            @else
                                <img src="{{ $media->url }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Campaign Info Card --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 24px;">
            <div id="header-placeholder" class="{{ $campaign->media->count() > 0 ? 'd-none' : '' }}">
                <img src="https://picsum.photos/seed/header-{{ $campaign->id }}/1200/500" class="w-100" style="height: 350px; object-fit: cover;">
            </div>
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-primary-custom m-0">{{ $campaign->title }}</h2>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-bold text-primary-custom" style="font-size: 14px;"><span id="follow-count">{{ $campaign->follows->count() }}</span></div>
                            <div class="text-muted" style="font-size: 10px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">Followers</div>
                        </div>
                        @auth
                            <button id="follow-btn" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                                    style="width: 50px; height: 50px;"
                                    onclick="toggleFollow({{ $campaign->id }})">
                                <i id="follow-icon" data-lucide="heart" 
                                   class="{{ $campaign->follows->where('user_id', Auth::id())->count() > 0 ? 'fill-danger text-danger' : 'text-muted' }}"
                                   style="width: 26px;"></i>
                            </button>
                        @endauth
                    </div>
                </div>
                <div id="tag-container">
                    @if($campaign->tag)
                        <span class="badge bg-primary-custom rounded-pill px-3 py-2 fw-semibold">{{ $campaign->tag }}</span>
                    @else
                        <span class="badge bg-secondary-custom rounded-pill px-3 py-2 fw-semibold opacity-75 text-white">No Category</span>
                    @endif
                </div>

                <div class="text-muted mb-5 lead">{!! $campaign->description !!}</div>

                <h5 class="fw-bold mb-4">Milestone Tracker</h5>
                <div id="milestone-grid" class="row g-3 mb-5">
                    @foreach($campaign->milestones as $milestone)
                        <div class="col-md-6 col-xl-3">
                            <div class="p-3 rounded-4 border {{ $milestone->reached ? 'border-secondary-color bg-light' : 'bg-white opacity-75' }}" style="border-style: dashed !important;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small">{{ $milestone->percentage }}%</span>
                                    @if($milestone->reached)
                                        <i data-lucide="check-circle" class="text-secondary-color" style="width: 16px;"></i>
                                    @endif
                                </div>
                                <div class="fw-bold mb-1 {{ $milestone->reached ? 'text-primary-custom' : 'text-muted' }}">{{ $milestone->badge_label }}</div>
                                <div class="small text-muted">Rp {{ number_format($milestone->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Content Tabs -->
                <div class="mb-5">
                    <ul class="nav nav-tabs border-0 gap-3 mb-4" id="campaignTabs">
                        <li class="nav-item">
                            <a class="nav-link active rounded-pill px-4" data-bs-toggle="tab" href="#updates">Updates</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-4" data-bs-toggle="tab" href="#gallery">Gallery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-4" data-bs-toggle="tab" href="#reports">Report</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="updates">
                            @if(Auth::id() === $campaign->user_id)
                                <div class="text-end mb-4">
                                    <button class="btn btn-accent-custom rounded-pill fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
                                        <i data-lucide="plus-circle" class="me-2" style="width:18px;"></i> Buat Update Berita
                                    </button>
                                </div>
                            @endif

                            <div class="timeline">
                                @forelse($campaign->updates as $update)
                                    <div class="update-card border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-white text-start">
                                        @if($update->media_path)
                                            <div class="update-media">
                                                @if($update->media_type === 'video')
                                                    <video src="{{ $update->media_url }}" controls class="w-100" style="max-height: 400px; object-fit: contain; background: #000;"></video>
                                                @else
                                                    <img src="{{ $update->media_url }}" class="w-100" style="max-height: 400px; object-fit: cover;">
                                                @endif
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="fw-bold m-0 text-primary-custom">{{ $update->title }}</h5>
                                                <small class="text-muted">{{ $update->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-muted m-0" style="white-space: pre-line;">{{ $update->content }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 bg-light rounded-4">
                                        <i data-lucide="clock" class="text-muted mb-3" style="width: 40px; height: 40px;"></i>
                                        <p class="text-muted">Belum ada update berita untuk kampanye ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade text-center py-5 bg-light rounded-4" id="donors">
                            <i data-lucide="users" class="text-muted mb-3" style="width: 40px; height: 40px;"></i>
                            <p class="text-muted">List of generous donors will be shown here.</p>
                        </div>
                        <div class="tab-pane fade text-center py-5 bg-light rounded-4" id="gallery">
                            <div class="gallery-grid" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap:12px; padding:20px;">
                                {{-- User can see all media here too --}}
                                @foreach($campaign->media as $m)
                                    <div class="gallery-item rounded-3 overflow-hidden shadow-sm" style="aspect-ratio:1;">
                                        @if($m->isImage())
                                            <img src="{{ $m->url }}" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div style="width:100%; height:100%; background:#1a1a2e; display:flex; align-items:center; justify-content:center;">
                                                <i data-lucide="film" style="color:white; width:40px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reports">
                            @if(Auth::id() === $campaign->user_id)
                                {{-- Fundraiser View: Manage Reports --}}
                                <div class="bg-light rounded-4 p-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold m-0 text-primary-custom">Manage Reports</h6>
                                        <button class="btn btn-sm btn-accent-custom rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#uploadReportModal">
                                            <i data-lucide="plus" style="width:14px;" class="me-1"></i> Add New
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="small py-2 border-0">File</th>
                                                    <th class="small py-2 border-0">Size</th>
                                                    <th class="small py-2 border-0">Status</th>
                                                    <th class="small py-2 border-0 text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($campaign->reports as $report)
                                                    <tr>
                                                        <td class="small border-0">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i data-lucide="file-text" class="text-danger" style="width:16px;"></i>
                                                                <span class="text-truncate" style="max-width: 150px;">{{ $report->original_name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="small border-0">{{ $report->file_size_formatted }}</td>
                                                        <td class="small border-0">
                                                            @if($report->status === 'verified')
                                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill" style="font-size: 9px;">Verified</span>
                                                            @elseif($report->status === 'rejected')
                                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill" style="font-size: 9px;">Rejected</span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill" style="font-size: 9px;">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td class="small border-0 text-end">
                                                            <div class="d-flex justify-content-end gap-1">
                                                                <a href="{{ $report->url }}" target="_blank" class="btn btn-sm btn-light p-1" title="View Source">
                                                                    <i data-lucide="external-link" style="width:14px;"></i>
                                                                </a>
                                                                <form action="{{ route('campaigns.reports.delete', $report->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger p-1" title="Delete">
                                                                        <i data-lucide="trash-2" style="width:14px;"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3 small">Belum ada laporan diupload.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Public View: List Verified Reports --}}
                            <div class="verified-reports">
                                @forelse($campaign->verified_reports as $report)
                                    <div class="report-viewer-card mb-5 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                        <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-danger bg-opacity-10 p-3 rounded-4">
                                                    <i data-lucide="file-text" class="text-danger" style="width:24px; height:24px;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold m-0">{{ $report->original_name }}</h6>
                                                    <small class="text-muted">{{ $report->file_size_formatted }} • {{ $report->created_at->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ $report->url }}" download class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i data-lucide="download" style="width:14px;" class="me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden border shadow-sm" style="height: 700px;">
                                                @if(str_contains(request()->getHttpHost(), '127.0.0.1') || str_contains(request()->getHttpHost(), 'localhost'))
                                                    <div class="d-flex flex-column align-items-center justify-content-center bg-light h-100 p-4 text-center">
                                                        <i data-lucide="monitor-off" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                                                        <h5 class="fw-bold text-primary-custom">Preview tidak tersedia di Localhost</h5>
                                                        <p class="text-muted small mb-4">Google Docs Viewer memerlukan URL publik untuk menampilkan file.<br>Gunakan tombol download atau akses secara langsung di bawah ini:</p>
                                                        <div class="d-flex gap-2">
                                                            <a href="{{ $report->url }}" download class="btn btn-primary rounded-pill px-4">Download File</a>
                                                            <a href="{{ $report->url }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">View Direct</a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <iframe src="{{ $report->google_viewer_url }}" frameborder="0"></iframe>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    @if(Auth::id() !== $campaign->user_id)
                                        <div class="text-center py-5 bg-light rounded-4">
                                            <i data-lucide="file-x" class="text-muted mb-3" style="width: 40px; height: 40px;"></i>
                                            <p class="text-muted">Laporan pertanggungjawaban belum tersedia.</p>
                                        </div>
                                    @endif
                                @endforelse
                            </div>
                        </div>
                    </div> {{-- tab-content --}}
                </div> {{-- tabs wrapper --}}
            </div> {{-- card-body --}}
        </div> {{-- card --}}
    </div> {{-- col-lg-8 --}}

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 100px;">
            <h5 class="fw-bold mb-4">Donation Progress</h5>
            <div class="d-flex justify-content-between mb-2">
                <span id="collected-label" class="h4 fw-bold m-0 text-accent-custom">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                <span class="text-muted">Goal: Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</span>
            </div>
            <div class="progress mb-4" style="height: 12px; border-radius: 12px;">
                <div id="progress-bar-el" class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color);"></div>
            </div>
            
            <div class="d-grid gap-2">
                {{-- Share Button --}}
                <button class="btn btn-light py-3 fw-bold rounded-pill shadow-sm mb-1 d-flex align-items-center justify-content-center gap-2 border border-light-custom" 
                        onclick="openShareModal()">
                    <i data-lucide="share-2" style="width: 20px;"></i>
                    Bagikan Campaign
                </button>

                @if(Auth::id() != $campaign->user_id)
                    <button class="btn btn-secondary-color py-3 fw-bold rounded-pill shadow-sm text-white mb-1" 
                            style="background-color: var(--secondary-color);"
                            data-bs-toggle="modal" data-bs-target="#donationModal"
                            onclick="setTimeout(() => updateFee(document.getElementById('donation-amount')?.value || 0), 200)">
                        Donasi Sekarang
                    </button>
                @endif
                @if(Auth::id() == $campaign->user_id)
                <button class="btn btn-outline-secondary py-2 rounded-pill small fw-bold"
                        data-bs-toggle="modal" data-bs-target="#campaignSettingsModal">
                    <i data-lucide="settings" style="width: 16px;" class="me-2"></i> Campaign Settings
                </button>
                @endif
            </div>

            @if(Auth::id() === $campaign->user_id)
            <!-- Upload Media Section -->
            <div class="mt-4 pt-4 border-top">
                <h6 class="fw-bold mb-1">Upload Bukti Lapangan</h6>
                <p class="text-muted small mb-3">Tambahkan foto atau video dokumentasi terbaru.</p>
                
                <form id="media-upload-form" action="{{ route('campaigns.media.upload', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="real-file-input" name="media[]" class="d-none" accept="image/*,video/*" multiple>
                    
                    <div id="drop-zone" class="rounded-4 p-4 text-center mb-3"
                         style="border: 2px dashed var(--secondary-color); cursor:pointer; background: #fbfdfb; transition: all 0.2s;">
                        <i data-lucide="plus-square" style="width:32px; height:32px; color:var(--secondary-color);" class="mb-2"></i>
                        <div class="fw-bold small">Pilih File</div>
                        <div class="text-muted" style="font-size:10px;">Bisa pilih per satu • Maks 10 file • 7MB/file</div>
                    </div>

                    <div id="preview-container" class="mb-3" style="display:grid; grid-template-columns: repeat(auto-fill, 64px); gap: 8px;"></div>
                    <div id="upload-error" class="alert alert-danger p-2 small d-none mb-3"></div>

                    <button type="submit" id="submit-media-btn" class="btn btn-primary w-100 rounded-pill fw-bold d-none">
                        <span id="btn-text"><i data-lucide="upload" style="width:16px;" class="me-2"></i> Upload (<span id="total-selected">0</span>)</span>
                        <div id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></div>
                    </button>
                </form>

                <div id="uploaded-media-container" class="mt-4 {{ $campaign->media->count() === 0 ? 'd-none' : '' }}">
                    <h6 class="fw-bold small text-muted mb-3">Media Terunggah (<span id="media-count">{{ $campaign->media->count() }}</span>)</h6>
                    <div id="media-grid-sidebar" style="display:grid; grid-template-columns: repeat(auto-fill, 60px); gap: 8px;">
                        @foreach($campaign->media as $m)
                            <div class="position-relative media-item-sidebar" id="sidebar-media-{{ $m->id }}" style="width:60px; height:60px;">
                                @if($m->isImage())
                                    <img src="{{ $m->url }}" style="width:60px;height:60px;object-fit:cover;border-radius:10px;">
                                @else
                                    <div style="width:60px;height:60px;border-radius:10px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;">
                                        <i data-lucide="film" style="width:24px;color:white;"></i>
                                    </div>
                                @endif
                                <button onclick="confirmDeleteMedia({{ $m->id }})" 
                                        class="btn btn-danger btn-sm p-0 position-absolute top-0 end-0 p-0 shadow-sm"
                                        style="width:20px;height:20px;border-radius:50%;font-size:10px;line-height:1;transform: translate(30%, -30%);">×</button>
                            </div>
                        @endforeach
                    </div> {{-- media-grid-sidebar --}}
                </div> {{-- uploaded-media-container --}}
            </div> {{-- border-top section --}}
            @endif
        </div> {{-- sticky card --}}
    </div> {{-- col-lg-4 --}}
</div> {{-- row --}}



@push('scripts')


<script>
document.addEventListener('DOMContentLoaded', function() {
    // ── Global State ──
    const MAX_MEDIA = 10;
    const MAX_FILE_SIZE = 7 * 1024 * 1024; 
    let pendingFiles = new DataTransfer();
    let deleteTargetId = null;

    const dz = document.getElementById('drop-zone');
    const realInput = document.getElementById('real-file-input');
    const previewGrid = document.getElementById('preview-container');
    const submitBtn = document.getElementById('submit-media-btn');
    const totalLabel = document.getElementById('total-selected');
    const errorLabel = document.getElementById('upload-error');
    const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

    // ── Drop Zone Logic ──
    if (dz) {
        dz.addEventListener('click', () => realInput.click());
        realInput.addEventListener('change', (e) => { addFiles(e.target.files); realInput.value = ''; });
        dz.addEventListener('dragover', (e) => { e.preventDefault(); dz.style.background = '#edf5ef'; });
        dz.addEventListener('dragenter', (e) => { e.preventDefault(); dz.style.background = '#edf5ef'; });
        dz.addEventListener('dragleave', (e) => { dz.style.background = '#fbfdfb'; });
        dz.addEventListener('drop', (e) => { e.preventDefault(); dz.style.background = '#fbfdfb'; addFiles(e.dataTransfer.files); });
    }

    function addFiles(fileList) {
        errorLabel.classList.add('d-none');
        let addedCount = 0;
        Array.from(fileList).forEach(file => {
            if (pendingFiles.items.length >= MAX_MEDIA) {
                errorLabel.innerText = "Maksimum 10 file.";
                errorLabel.classList.remove('d-none');
                return;
            }
            if (file.size > MAX_FILE_SIZE) {
                errorLabel.innerText = `${file.name} > 7MB.`;
                errorLabel.classList.remove('d-none');
                return;
            }
            pendingFiles.items.add(file);
            addedCount++;
        });
        if (addedCount > 0) renderPreviews();
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';
        const files = pendingFiles.files;
        submitBtn.classList.toggle('d-none', files.length === 0);
        totalLabel.innerText = files.length;
        Array.from(files).forEach((file, index) => {
            const div = document.createElement('div');
            div.className = 'position-relative';
            div.style.width = '64px'; div.style.height = '64px';
            const content = document.createElement('div');
            content.style.width = '64px'; content.style.height = '64px'; content.style.borderRadius = '10px'; content.style.overflow = 'hidden';
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img'); img.src = URL.createObjectURL(file); img.style.width = '100%'; img.style.height = '100%'; img.style.objectFit = 'cover';
                content.appendChild(img);
            } else {
                content.style.background = '#1a1a2e'; content.style.display = 'flex'; content.style.alignItems = 'center'; content.style.justifyContent = 'center';
                content.innerHTML = '<i data-lucide="video" style="color:white; width:24px;"></i>';
            }
            const rmBtn = document.createElement('button');
            rmBtn.className = 'btn btn-danger btn-sm p-0 position-absolute top-0 end-0';
            rmBtn.style.width = '18px'; rmBtn.style.height = '18px'; rmBtn.style.borderRadius = '50%'; rmBtn.style.transform = 'translate(30%, -30%)';
            rmBtn.innerText = '×';
            rmBtn.type = 'button';
            rmBtn.onclick = () => removePendingFile(index);
            div.appendChild(content); div.appendChild(rmBtn);
            previewGrid.appendChild(div);
        });
        lucide.createIcons();
    }

    window.removePendingFile = function(index) {
        const nextDT = new DataTransfer();
        Array.from(pendingFiles.files).forEach((file, i) => { if (i !== index) nextDT.items.add(file); });
        pendingFiles = nextDT;
        renderPreviews();
    };

    // ── Real-time AJAX Upload ──
    const uploadForm = document.getElementById('media-upload-form');
    if (uploadForm) {
        uploadForm.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.delete('media[]');
            Array.from(pendingFiles.files).forEach(file => formData.append('media[]', file));

            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    data.media.forEach(m => appendMediaToUI(m));
                    pendingFiles = new DataTransfer();
                    renderPreviews();
                    
                    // ── Real-time Sync ──
                    const firstMedia = document.querySelector('.thumb-item');
                    if (firstMedia) {
                        const syncData = {
                            id: {{ $campaign->id }},
                            banner_url: firstMedia.dataset.url,
                            timestamp: Date.now()
                        };
                        // Use BroadcastChannel (Modern)
                        try {
                            new BroadcastChannel('campaign_sync').postMessage(syncData);
                        } catch(e) {}
                        // Fallback to LocalStorage (Legacy)
                        localStorage.setItem('campaign_update', JSON.stringify(syncData));
                    }
                    
                    showAlert('success', 'Media berhasil diupload secara real-time!');
                } else {
                    showAlert('danger', data.message || 'Upload gagal.');
                }
            })
            .catch(() => showAlert('danger', 'Terjadi kesalahan sistem.'))
            .finally(() => {
                btnText.classList.remove('d-none');
                btnSpinner.classList.add('d-none');
                submitBtn.disabled = false;
            });
        };
    }

    function appendMediaToUI(media) {
        const wrapper = document.getElementById('gallery-wrapper');
        const header = document.getElementById('header-placeholder');
        if (header) header.classList.add('d-none');
        
        if (wrapper.children.length === 0) {
            wrapper.innerHTML = `
                <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 24px; background: #1a1a2e;">
                    <div id="main-viewer" style="width:100%; aspect-ratio:16/9; position:relative; background:#000; overflow:hidden;">
                        <video id="main-video" style="display:none;" controls class="w-100 h-100"></video>
                        <div id="main-image" style="width:100%; height:100%; background-size:contain; background-position:center; background-repeat:no-repeat;"></div>
                    </div>
                    <div style="display:flex; gap:8px; padding:12px 16px; overflow-x:auto; background:#111827;" id="thumb-strip"></div>
                </div>`;
        }

        const strip = document.getElementById('thumb-strip');
        const thumb = document.createElement('div');
        thumb.className = `thumb-item ${strip.children.length === 0 ? 'active' : ''}`;
        thumb.id = `thumb-${media.id}`;
        thumb.dataset.id = media.id; thumb.dataset.url = media.url; thumb.dataset.type = media.type;
        thumb.onclick = function() { selectMedia(this); };
        thumb.style.cssText = `flex-shrink:0; width:120px; height:68px; border-radius:8px; overflow:hidden; cursor:pointer; border: 2px solid ${strip.children.length === 0 ? '#C2A83E' : 'transparent'}; position:relative;`;
        
        if (media.type === 'video') {
            thumb.innerHTML = `<video src="${media.url}" muted style="width:100%; height:100%; object-fit:cover;"></video><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>`;
        } else {
            thumb.innerHTML = `<img src="${media.url}" style="width:100%; height:100%; object-fit:cover;">`;
        }
        strip.appendChild(thumb);

        const sidebarGrid = document.getElementById('media-grid-sidebar');
        const container = document.getElementById('uploaded-media-container');
        container.classList.remove('d-none');
        const sideItem = document.createElement('div');
        sideItem.className = 'position-relative'; sideItem.id = `sidebar-media-${media.id}`; sideItem.style.width = '60px'; sideItem.style.height = '60px';
        if (media.type === 'image') {
            sideItem.innerHTML = `<img src="${media.url}" style="width:60px;height:60px;object-fit:cover;border-radius:10px;">`;
        } else {
            sideItem.innerHTML = `<div style="width:60px;height:60px;border-radius:10px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;"><i data-lucide="film" style="width:24px;color:white;"></i></div>`;
        }
        sideItem.innerHTML += `<button onclick="confirmDeleteMedia(${media.id})" class="btn btn-danger btn-sm p-0 position-absolute top-0 end-0 shadow-sm" style="width:20px;height:20px;border-radius:50%;font-size:10px;line-height:1;transform: translate(30%, -30%);">×</button>`;
        sidebarGrid.appendChild(sideItem);

        const countEl = document.getElementById('media-count');
        countEl.innerText = parseInt(countEl.innerText) + 1;
        if (strip.children.length === 1) selectMedia(thumb);
        lucide.createIcons();
    }

    window.confirmDeleteMedia = function(id) {
        deleteTargetId = id;
        deleteModal.show();
    };

    document.getElementById('confirm-delete-btn').onclick = function() {
        if (!deleteTargetId) return;
        const btn = this;
        btn.disabled = true;
        btn.innerText = 'Menghapus...';

        fetch(`{{ url('/my-campaigns/' . $campaign->id . '/media') }}/${deleteTargetId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`sidebar-media-${deleteTargetId}`)?.remove();
                const thumb = document.getElementById(`thumb-${deleteTargetId}`);
                if (thumb) {
                    const isWasActive = thumb.classList.contains('active');
                    thumb.remove();
                    const remainingThm = document.querySelector('.thumb-item');
                    if (isWasActive && remainingThm) selectMedia(remainingThm);
                }
                const countEl = document.getElementById('media-count');
                const total = parseInt(countEl.innerText) - 1;
                countEl.innerText = total;
                if (total === 0) {
                    document.getElementById('gallery-wrapper').innerHTML = '';
                    document.getElementById('header-placeholder').classList.remove('d-none');
                    document.getElementById('uploaded-media-container').classList.add('d-none');
                }
                deleteModal.hide();
                showAlert('success', 'Media berhasil dihapus.');

                // ── Real-time Sync ──
                const firstMedia = document.querySelector('.thumb-item');
                const syncData = {
                    id: {{ $campaign->id }},
                    banner_url: firstMedia ? firstMedia.dataset.url : 'https://picsum.photos/seed/campaign-{{ $campaign->id }}/600/400',
                    timestamp: Date.now()
                };
                try {
                    new BroadcastChannel('campaign_sync').postMessage(syncData);
                } catch(e) {}
                localStorage.setItem('campaign_update', JSON.stringify(syncData));
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerText = 'Ya, Hapus';
            deleteTargetId = null;
        });
    };

    window.selectMedia = function(el) {
        document.querySelectorAll('.thumb-item').forEach(t => {
            t.style.borderColor = 'transparent';
            t.classList.remove('active');
        });
        el.style.borderColor = '#C2A83E';
        el.classList.add('active');
        const url = el.dataset.url;
        const type = el.dataset.type;
        const v = document.getElementById('main-video');
        const i = document.getElementById('main-image');
        if (type === 'video') {
            i.style.display = 'none'; v.style.display = 'block'; v.src = url; v.play();
        } else {
            if (v && v.tagName === 'VIDEO') { v.pause(); v.style.display = 'none'; }
            i.style.backgroundImage = `url('${url}')`; i.style.display = 'block';
        }
    };

    window.copyShareLink = function() {
        // ... (existing)
    };

    // ── Donation Modal Logic (Improved) ──
    const donationModalEl = document.getElementById('donationModal');
    const donationInput = document.getElementById('donation-amount');
    const feeDisplay = document.getElementById('fee-display');
    const manualInfo = document.getElementById('manual-payment-info');
    const submitDonationBtn = document.getElementById('submit-donation-btn');
    const donationForm = document.getElementById('donation-form');

    function updateFee(amount) {
        if (!feeDisplay) return;
        const num = parseFloat(amount) || 0;
        const fee = Math.floor(num * 0.05);
        feeDisplay.textContent = fee.toLocaleString('id-ID');
    }

    function selectPaymentOption(optEl) {
        // Reset all
        document.querySelectorAll('.payment-option').forEach(o => {
            o.classList.remove('border-primary', 'bg-light');
            const checkSpan = o.querySelector('.pay-check-icon');
            if (checkSpan) checkSpan.style.display = 'none';
        });

        // Activate selected
        optEl.classList.add('border-primary', 'bg-light');
        const radio = optEl.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
        const checkSpan = optEl.querySelector('.pay-check-icon');
        if (checkSpan) checkSpan.style.display = '';

        // Toggle manual info section
        if (optEl.id === 'opt-manual') {
            manualInfo?.classList.remove('d-none');
            const proofInput = document.getElementById('proof-input');
            if (proofInput) proofInput.required = true;
            if (submitDonationBtn) submitDonationBtn.innerText = 'Konfirmasi Transfer';
        } else {
            manualInfo?.classList.add('d-none');
            const proofInput = document.getElementById('proof-input');
            if (proofInput) proofInput.required = false;
            if (submitDonationBtn) submitDonationBtn.innerText = 'Lanjut Bayar';
        }
    }

    if (donationModalEl) {
        // Handle Manual Input — real-time fee calculation
        if (donationInput) {
            donationInput.addEventListener('input', (e) => {
                updateFee(e.target.value);
                // Deselect chips when user types manually
                document.querySelectorAll('.amount-chip').forEach(c => {
                    c.classList.remove('btn-primary', 'text-white');
                    c.classList.add('btn-outline-primary');
                });
            });
        }

        // Event Delegation — handles both chips and payment options
        donationModalEl.addEventListener('click', (e) => {
            // 1. Amount Chips
            const chip = e.target.closest('.amount-chip');
            if (chip) {
                const amount = chip.getAttribute('data-amount');
                if (donationInput) {
                    donationInput.value = amount;
                    updateFee(amount);
                }
                // UI: highlight selected chip
                document.querySelectorAll('.amount-chip').forEach(c => {
                    c.classList.remove('btn-primary', 'text-white');
                    c.classList.add('btn-outline-primary');
                });
                chip.classList.remove('btn-outline-primary');
                chip.classList.add('btn-primary', 'text-white');
                return; // stop propagation handling
            }

            // 2. Payment Options — use closest to handle clicks on child elements too
            const opt = e.target.closest('.payment-option');
            if (opt) {
                selectPaymentOption(opt);
            }
        });
    }

    if (donationForm) {
        donationForm.onsubmit = function(e) {
            const methodInput = document.querySelector('input[name="payment_method"]:checked');
            const method = methodInput ? methodInput.value : 'Midtrans';
            
            // Manual flow is handled by controller redirect normally, 
            // but if AJAX is used for everything, we handle it here.
            if (method === 'Manual') return true; 

            e.preventDefault();
            const originalText = submitDonationBtn.innerText;
            submitDonationBtn.disabled = true;
            submitDonationBtn.innerText = 'Memproses...';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.snap_token) {
                    bootstrap.Modal.getInstance(donationModalEl).hide();
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){ location.reload(); },
                        onPending: function(result){ location.href = "{{ route('profile.archived') }}"; },
                        onError: function(result){ showAlert('danger', 'Pembayaran gagal!'); },
                        onClose: function(){ showAlert('warning', 'Anda belum menyelesaikan pembayaran.'); }
                    });
                } else {
                    showAlert('danger', data.message || 'Gagal membuat token pembayaran.');
                }
            })
            .catch(() => showAlert('danger', 'Terjadi kesalahan sistem.'))
            .finally(() => {
                submitDonationBtn.disabled = false;
                submitDonationBtn.innerText = originalText;
            });
        };
    }

    // ── Milestone Calculation Logic ──
    const goalInput = document.getElementById('settings_goal_amount');
    if (goalInput) {
        goalInput.addEventListener('input', function(e) {
            const goal = parseFloat(e.target.value) || 0;
            [25, 50, 75, 100].forEach(perc => {
                const amount = Math.round(goal * (perc / 100));
                const label = document.getElementById('settings-milestone-' + perc);
                if (label) label.innerText = amount.toLocaleString('id-ID');
            });
        });
    }

    // ── TinyMCE Initialization ──
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#settings_description',
            plugins: 'lists link code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | help',
            menubar: false,
            height: 300,
            branding: false,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    }

    window.toggleFollow = function(id) {
        const btn = document.getElementById('follow-btn');
        const icon = document.getElementById('follow-icon');
        const countEl = document.getElementById('follow-count');
        
        // Visual feedback immediate
        icon.classList.add('heart-pulse');
        setTimeout(() => icon.classList.remove('heart-pulse'), 400);

        fetch(`/campaigns/${id}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error();
            return res.json();
        })
        .then(data => {
            if (data.success) {
                const currentCount = parseInt(countEl.innerText);
                if (data.status === 'followed') {
                    icon.classList.remove('text-muted');
                    icon.classList.add('fill-danger', 'text-danger');
                    countEl.innerText = currentCount + 1;
                    showAlert('success', 'Berhasil mengikuti campaign!');
                } else {
                    icon.classList.remove('fill-danger', 'text-danger');
                    icon.classList.add('text-muted');
                    countEl.innerText = Math.max(0, currentCount - 1);
                    showAlert('info', 'Batal mengikuti campaign.');
                }
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        })
        .catch(() => {
            showAlert('danger', 'Harap login ulang atau periksa koneksi Anda.');
            // Revert icon state if failed? Maybe not needed for simple toggle
        });
    };

    window.showAlert = function(type, msg) {
        const container = document.getElementById('alert-container');
        if (!container) return;
        const div = document.createElement('div');
        div.className = `alert alert-${type} border-0 rounded-4 mb-4 shadow-sm animate__animated animate__fadeInDown`;
        div.innerHTML = `<i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" style="width:16px" class="me-2"></i> ${msg}`;
        container.prepend(div);
        setTimeout(() => {
            div.classList.replace('animate__fadeInDown', 'animate__fadeOutUp');
            setTimeout(() => div.remove(), 500);
        }, 4000);
        if (typeof lucide !== 'undefined') lucide.createIcons();
    };
});
</script>
@endpush
{{-- ── Share Modal ── --}}
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Bagikan ke Sosial Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-muted small mb-4">Sebarkan kebaikan dengan membagikan kampanye ini ke teman dan keluarga Anda.</p>
                
                <div class="d-flex justify-content-between mb-5 overflow-auto pb-2 gap-3 no-scrollbar" style="scroll-snap-type: x mandatory;">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . url()->current()) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i data-lucide="message-circle" class="text-success" style="width: 28px; height: 28px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">WhatsApp</span>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i data-lucide="facebook" class="text-primary" style="width: 28px; height: 28px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">Facebook</span>
                    </a>

                    {{-- Twitter / X --}}
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($campaign->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i data-lucide="twitter" class="text-dark" style="width: 28px; height: 28px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">X / Twitter</span>
                    </a>

                    {{-- Telegram --}}
                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($campaign->title) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i data-lucide="send" class="text-info" style="width: 28px; height: 28px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">Telegram</span>
                    </a>
                </div>

                <div class="bg-light p-2 rounded-4 d-flex align-items-center gap-2 border border-dashed">
                    <input type="text" readonly value="{{ url()->current() }}" id="copyLinkInput" 
                           class="form-control border-0 bg-transparent text-muted small py-2 px-3 fw-medium">
                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" onclick="copyCampaignLink()">
                        Salin
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .share-icon-btn { transition: all 0.2s ease; }
    .share-icon-btn:hover { transform: translateY(-5px); }
    .share-icon-btn:hover div { transform: scale(1.1); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
</style>

<script>
    function openShareModal() {
        const modal = new bootstrap.Modal(document.getElementById('shareModal'));
        modal.show();
    }

    function copyCampaignLink() {
        const input = document.getElementById('copyLinkInput');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value);
        
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Berhasil!';
        btn.classList.replace('btn-primary', 'btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.replace('btn-success', 'btn-primary');
        }, 2000);
        
        if(typeof showAlert === 'function') showAlert('success', 'Link berhasil disalin ke clipboard!');
    }
</script>
@endsection

@push('modals')
{{-- Add Update Modal --}}
<div class="modal fade" id="addUpdateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; background-color: var(--bg-color);">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Buat Update Berita</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('campaigns.updates.store', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Judul Update</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Penyaluran Tahap 1 Selesai!" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Isi Berita</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Ceritakan progres atau penggunaan dana..." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Media / Laporan (Opsional)</label>
                        <input type="file" name="media" class="form-control" accept="image/*,video/*,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <p class="text-muted small mt-1">Foto, Video, GIF, atau Laporan (PDF, DOCX, XLSX).</p>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow">Posting Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Donation Modal --}}
<div class="modal fade" id="donationModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; background-color: var(--bg-color);">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Donasi Sekarang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="donation-form" action="{{ route('campaigns.donate', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    {{-- Amount Chips --}}
                    <label class="form-label fw-bold required">Pilih Nominal</label>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach([50000, 100000, 200000, 500000] as $nominal)
                            <button type="button" class="btn btn-outline-primary rounded-pill amount-chip" data-amount="{{ $nominal }}">
                                Rp {{ number_format($nominal, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Manual Input --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Atau masukkan nominal lain</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">Rp</span>
                            <input type="number" id="donation-amount" name="amount"
                                   class="form-control border-start-0" placeholder="Contoh: 150000" min="10000" required>
                        </div>
                    </div>

                    {{-- Fee Info --}}
                    <div class="alert alert-light border rounded-3 small text-muted mb-3">
                        Biaya layanan (5%): <strong>Rp <span id="fee-display">0</span></strong>
                    </div>

                    {{-- Payment Method --}}
                    <label class="form-label fw-bold required">Metode Pembayaran</label>
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="payment-option border rounded-3 p-3 d-flex align-items-center gap-3 border-primary bg-light" id="opt-midtrans" style="cursor:pointer;">
                            <input type="radio" name="payment_method" value="Midtrans" class="d-none" checked>
                            <i data-lucide="credit-card" style="width:20px;"></i>
                            <span class="fw-semibold">Transfer / E-Wallet (Midtrans)</span>
                            <span class="ms-auto pay-check-icon" style="color:var(--primary-color); font-size:18px;">&#10003;</span>
                        </div>
                        <div class="payment-option border rounded-3 p-3 d-flex align-items-center gap-3" id="opt-manual" style="cursor:pointer;">
                            <input type="radio" name="payment_method" value="Manual" class="d-none">
                            <i data-lucide="landmark" style="width:20px;"></i>
                            <span class="fw-semibold">Transfer Manual</span>
                            <span class="ms-auto pay-check-icon" style="color:var(--primary-color); font-size:18px; display:none;">&#10003;</span>
                        </div>
                    </div>

                    {{-- Manual Payment Info --}}
                    <div id="manual-payment-info" class="alert alert-info rounded-3 small d-none">
                        <div class="mb-2">
                            Transfer ke: <strong>BCA 1234567890 a.n. Yayasan Peduli</strong>
                        </div>
                        <label class="form-label fw-bold mb-1">Unggah Bukti Transfer</label>
                        <input type="file" name="proof" id="proof-input" class="form-control form-control-sm" accept="image/*">
                        <div class="text-xs mt-1 text-muted">Format: JPG, PNG (Maks 5MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="submit-donation-btn" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow">
                        Lanjut Bayar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush

@push('modals')

{{-- Share Modal --}}

<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; background-color: #f8f9fa;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold m-0">Bagikan Kampanye</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-between text-center mb-4 overflow-auto pb-2 gap-3" id="social-share-list">
                    @php 
                        $shareUrl = urlencode(request()->fullUrl()); 
                        $shareTitle = urlencode($campaign->title);
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" class="text-decoration-none">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto" style="width:50px; height:50px; background:#25D366; color:white;">
                            <i data-lucide="message-circle" style="width:24px;"></i>
                        </div>
                        <span class="small text-muted">WhatsApp</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="text-decoration-none">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto" style="width:50px; height:50px; background:#1877F2; color:white;">
                            <i data-lucide="facebook" style="width:24px;"></i>
                        </div>
                        <span class="small text-muted">Facebook</span>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}" target="_blank" class="text-decoration-none">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto" style="width:50px; height:50px; background:#000000; color:white;">
                            <i data-lucide="twitter" style="width:24px;"></i>
                        </div>
                        <span class="small text-muted">X</span>
                    </a>
                    <a href="mailto:?subject={{ $shareTitle }}&body=Check out this campaign: {{ $shareUrl }}" class="text-decoration-none">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto" style="width:50px; height:50px; background:#EA4335; color:white;">
                            <i data-lucide="mail" style="width:24px;"></i>
                        </div>
                        <span class="small text-muted">Email</span>
                    </a>
                </div>

                <div class="p-3 bg-white rounded-4 border">
                    <div class="small fw-bold text-muted mb-2">Salin Tautan</div>
                    <div class="input-group">
                        <input type="text" id="share-link-input" class="form-control border-0 bg-light rounded-start-pill ps-3" value="{{ request()->fullUrl() }}" readonly>
                        <button class="btn btn-primary rounded-end-pill px-4 fw-bold" onclick="copyShareLink()">Copy</button>
                    </div>
                </div> {{-- End copy link div --}}
            </div> {{-- End modal-body --}}
        </div> {{-- End modal-content --}}
    </div> {{-- End modal-dialog --}}
</div> {{-- End shareModal --}}

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-body p-4 text-center">
                <i data-lucide="info" class="text-danger mb-3" style="width:48px;height:48px;"></i>
                <h5 class="fw-bold mb-2">Hapus Media?</h5>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus media ini secara permanen?</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirm-delete-btn" class="btn btn-danger w-100 rounded-pill fw-bold">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Upload Report Modal --}}
<div class="modal fade" id="uploadReportModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; background-color: var(--bg-color);">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Upload Laporan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('campaigns.reports.store', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-4 small mb-4" style="background-color: #eef3f0; color: #243e36;">
                        <i data-lucide="info" class="me-2" style="width:16px;"></i>
                        Unggah laporan pertanggungjawaban berupa file PDF, DOCX, XLSX, atau ZIP. Maksimal 50MB per file.
                    </div>
                    
                    <div id="report-drop-zone" class="rounded-4 p-5 text-center mb-0"
                         style="border: 2px dashed var(--secondary-color); cursor:pointer; background: white; transition: all 0.2s;"
                         onclick="document.getElementById('report-file-input').click()">
                        <div class="bg-secondary-color bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i data-lucide="upload-cloud" style="width:32px; height:32px; color:var(--secondary-color);"></i>
                        </div>
                        <h6 class="fw-bold text-primary-custom mb-1">Klik atau seret file ke sini</h6>
                        <p class="text-muted small mb-0">PDF, DOCX, XLSX, ZIP (Max. 50MB)</p>
                        <input type="file" id="report-file-input" name="files[]" class="d-none" accept=".pdf,.docx,.xlsx,.zip" multiple onchange="updateReportFilename(this)">
                    </div>
                    <div id="report-filename-preview" class="mt-3 small text-primary-custom fw-bold text-center"></div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">Upload Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush

@push('scripts')

<script>
    function updateReportFilename(input) {
        const preview = document.getElementById('report-filename-preview');
        if (input.files && input.files.length > 0) {
            if (input.files.length === 1) {
                preview.textContent = "Selected: " + input.files[0].name;
            } else {
                preview.textContent = "Selected: " + input.files.length + " files";
            }
        } else {
            preview.textContent = "";
        }
    }
</script>

@endpush

@push('modals')

{{-- Campaign Settings Modal --}}

<div class="modal fade" id="campaignSettingsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; background-color: var(--bg-color);">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Campaign Settings</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('campaigns.update', $campaign->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Judul Kampanye</label>
                        <input type="text" name="title" class="form-control" value="{{ $campaign->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Bio / Deskripsi</label>
                        <textarea name="description" id="settings_description" class="form-control" rows="4" required>{{ $campaign->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Kampanye</label>
                        <select name="tag" class="form-select">
                            <option value="">Pilih Kategori...</option>
                            <option value="Sosial & Kemanusiaan" {{ $campaign->tag == 'Sosial & Kemanusiaan' ? 'selected' : '' }}>Sosial & Kemanusiaan</option>
                            <option value="Pendidikan" {{ $campaign->tag == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Kesehatan" {{ $campaign->tag == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="Bencana Alam" {{ $campaign->tag == 'Bencana Alam' ? 'selected' : '' }}>Bencana Alam</option>
                            <option value="Lingkungan" {{ $campaign->tag == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                            <option value="Keagamaan" {{ $campaign->tag == 'Keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                            <option value="Pembangunan & Infrastruktur" {{ $campaign->tag == 'Pembangunan & Infrastruktur' ? 'selected' : '' }}>Pembangunan & Infrastruktur</option>
                            <option value="Pemberdayaan Ekonomi Komunitas" {{ $campaign->tag == 'Pemberdayaan Ekonomi Komunitas' ? 'selected' : '' }}>Pemberdayaan Ekonomi Komunitas</option>
                            <option value="Seni & Budaya" {{ $campaign->tag == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                            <option value="Penelitian & Inovasi" {{ $campaign->tag == 'Penelitian & Inovasi' ? 'selected' : '' }}>Penelitian & Inovasi</option>
                            <option value="Animal Safety and Care" {{ $campaign->tag == 'Animal Safety and Care' ? 'selected' : '' }}>Animal Safety and Care</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold required">Target Dana</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">Rp</span>
                            <input type="number" name="goal_amount" id="settings_goal_amount"
                                   class="form-control border-start-0" value="{{ $campaign->goal_amount }}" required>
                        </div>
                    </div>
                    <h6 class="fw-bold text-primary-custom mb-3 mt-4">Milestone Rewards</h6>
                    <div class="row g-3">
                        @foreach($campaign->milestones->sortBy('percentage') as $milestone)
                            <div class="col-12">
                                <div class="p-3 bg-white rounded-3 d-flex align-items-center gap-3 shadow-sm border border-light">
                                    <div class="fw-bold text-accent-custom" style="width: 50px;">{{ $milestone->percentage }}%</div>
                                    <div class="text-muted small flex-grow-1" style="min-width: 120px;">
                                        Rp <span id="settings-milestone-{{ $milestone->percentage }}">{{ number_format($milestone->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <input type="text" name="milestones[{{ $milestone->percentage }}]"
                                           class="form-control flex-grow-1 border-0 bg-light" value="{{ $milestone->badge_label }}" placeholder="Badge label for {{ $milestone->percentage }}% milestone" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="https://cdn.tiny.cloud/1/uzyi3qni0rl59wmj5i3t38v3cebtp184ygnuw2vto9ugxut5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
@endpush