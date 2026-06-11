@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" />
<style>
    /* Custom Dropzone Styling */
    .drop-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 1.5rem;
      width: 100%;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      position: relative;
    }

    .seluna-dropzone {
      border: 2px dashed #cbd5e1;
      background: #f8fafc;
      border-radius: 12px;
      min-height: 130px;
      display: flex;
      flex-direction: column;
      align-items: stretch;
      justify-content: flex-start;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: visible;
    }
    .seluna-dropzone:hover, .seluna-dropzone.dz-drag-hover {
      border-color: var(--secondary-color) !important;
      background: rgba(124, 169, 130, 0.05) !important;
    }

    .seluna-dropzone .dz-message {
      text-align: center;
      margin: 0 !important;
      padding: 1.5rem;
      align-self: center;
      width: 100%;
      display: block !important;
    }
    .seluna-dropzone .dz-message .icon-wrap {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: rgba(0,0,0,0.03);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      color: #64748b;
      transition: all 0.3s ease;
    }
    .seluna-dropzone:hover .icon-wrap {
      background: var(--secondary-color);
      color: #ffffff;
      transform: scale(1.05);
    }
    .seluna-dropzone .dz-message svg {
      width: 24px;
      height: 24px;
    }
    .seluna-dropzone .dz-message h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 0.25rem;
    }
    .seluna-dropzone .dz-message p {
      font-size: 0.85rem;
      color: #64748b;
      margin-bottom: 1rem;
    }
    .seluna-btn-browse {
      background: transparent;
      border: 1px solid #cbd5e1;
      color: #1e293b;
      padding: 0.5rem 1.25rem;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .seluna-btn-browse:hover {
      background: var(--primary-color);
      color: #ffffff;
      border-color: var(--primary-color);
    }

    /* Stats Panel */
    .upload-stats {
      display: flex;
      justify-content: space-between;
      margin-top: 1.25rem;
      padding-top: 1rem;
      border-top: 1px solid #f1f5f9;
      font-size: 0.8rem;
    }
    .upload-stats .stat-item {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }
    .upload-stats .stat-item .label {
      color: #64748b;
      font-weight: 500;
    }
    .upload-stats .stat-item .val {
      font-size: 1.1rem;
      font-weight: 700;
      color: #1e293b;
    }
    .upload-stats .stat-item .val.success {
      color: var(--secondary-color);
    }
    .upload-stats .stat-item .val.danger {
      color: #ef4444;
    }

    /* When files are added, show the dropzone with content compactly */
    .seluna-dropzone .dz-preview {
        /* previews render inline — do not hide */
    }

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

    /* Premium Donasi Sekarang Button Styles */
    .btn-secondary-custom {
        background-color: var(--secondary-color) !important;
        border: none;
        color: white !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        cursor: pointer;
    }
    .btn-secondary-custom:hover {
        background-color: var(--primary-color) !important;
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 12px 24px rgba(124, 169, 130, 0.25) !important;
    }
    .btn-secondary-custom:active {
        transform: translateY(0) scale(0.98) !important;
    }
</style>
@endpush

@section('content')

{{-- Real-time Alerts Container --}}
<div id="alert-container">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 mb-3 shadow-sm d-flex align-items-center gap-2 seluna-flash" role="alert">
            <i data-lucide="check-circle" style="width:16px; flex-shrink:0;"></i>
            <span class="flex-grow-1">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-sm ms-2" aria-label="Close" onclick="this.closest('.seluna-flash').remove()"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 mb-3 shadow-sm d-flex align-items-center gap-2 seluna-flash" role="alert">
            <i data-lucide="alert-circle" style="width:16px; flex-shrink:0;"></i>
            <span class="flex-grow-1">{{ session('error') }}</span>
            <button type="button" class="btn-close btn-close-sm ms-2" aria-label="Close" onclick="this.closest('.seluna-flash').remove()"></button>
        </div>
    @endif
</div>
<script>
    // Auto-dismiss session flash alerts after 5s
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.seluna-flash').forEach(function(el) {
            setTimeout(function() {
                el.style.transition = 'opacity 0.4s, transform 0.4s';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(function() { el.remove(); }, 420);
            }, 5000);
        });
    });
</script>

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
                <img src="https://picsum.photos/seed/header-{{ $campaign->id }}/1200/500" class="w-100" style="height: 350px; object-fit: cover;" loading="lazy">
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
                            <a class="nav-link active rounded-pill px-4" data-bs-toggle="tab" href="#updates">{{ __('Updates') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-4" data-bs-toggle="tab" href="#gallery">{{ __('Gallery') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-4" data-bs-toggle="tab" href="#reports">{{ __('Report') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="updates">
                            @if(Auth::id() === $campaign->user_id)
                                <div class="text-end mb-4">
                                    <button class="btn btn-accent-custom rounded-pill fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
                                        <i data-lucide="plus-circle" class="me-2" style="width:18px;"></i> {{ __('Create News Update') }}
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
                                                    <img src="{{ $update->media_url }}" class="w-100" style="max-height: 400px; object-fit: cover;" loading="lazy">
                                                @endif
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="fw-bold m-0 text-primary-custom">{{ $update->title }}</h5>
                                                    <small class="text-muted">{{ $update->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if(Auth::id() === $campaign->user_id)
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-light border p-1 rounded-circle" 
                                                                title="Edit Update"
                                                                onclick="editUpdate({{ $update->id }}, '{{ addslashes($update->title) }}', '{{ addslashes($update->content) }}')">
                                                            <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger p-1 rounded-circle" 
                                                                title="Hapus Update"
                                                                data-seluna-btn data-action="delete"
                                                                data-href="{{ route('campaigns.updates.delete', [$campaign->id, $update->id]) }}"
                                                                data-confirm-title="Hapus Update Berita?"
                                                                data-confirm-body="Update berita ini akan dihapus secara permanen.">
                                                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                                        </button>
                                                    </div>
                                                @endif
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
                                            <img src="{{ $m->url }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
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
                                        <h6 class="fw-bold m-0 text-primary-custom">{{ __('Manage Reports') }}</h6>
                                        <button class="btn btn-sm btn-accent-custom rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#uploadReportModal">
                                            <i data-lucide="plus" style="width:14px;" class="me-1"></i> {{ __('Add New') }}
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="small py-2 border-0">{{ __('File') }}</th>
                                                    <th class="small py-2 border-0">{{ __('Size') }}</th>
                                                    <th class="small py-2 border-0">{{ __('Status') }}</th>
                                                    <th class="small py-2 border-0 text-end">{{ __('Action') }}</th>
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
                                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill" style="font-size: 9px;">{{ __('Verified') }}</span>
                                                            @elseif($report->status === 'rejected')
                                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill" style="font-size: 9px;">{{ __('Rejected') }}</span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill" style="font-size: 9px;">{{ __('Pending') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="small border-0 text-end">
                                                            <div class="d-flex justify-content-end gap-1">
                                                                <a href="{{ $report->url }}" target="_blank" class="btn btn-sm btn-light p-1" title="View Source">
                                                                    <i data-lucide="external-link" style="width:14px;"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-sm btn-danger p-1" title="Delete"
                                                                        data-seluna-btn data-action="delete"
                                                                        data-href="{{ route('campaigns.reports.delete', $report->id) }}"
                                                                        data-confirm-title="Hapus laporan ini?"
                                                                        data-confirm-body="Laporan akan dihapus secara permanen.">
                                                                    <i data-lucide="trash-2" style="width:14px;"></i>
                                                                </button>
                                                                {{-- Hidden form for delete if needed, but the script handles direct navigation --}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3 small">{{ __('Belum ada laporan diupload.') }}</td>
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
        <div class="card border-0 shadow-sm p-4 sticky-top" style="
            border-radius: 24px;
            top: 80px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        ">
            <h5 class="fw-bold mb-4">{{ __('Donation Progress') }}</h5>
            <div class="d-flex justify-content-between mb-2">
                <span id="collected-label" class="h4 fw-bold m-0 text-accent-custom">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                <span class="text-muted">{{ __('Goal') }}: Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</span>
            </div>
            <div class="progress mb-4" style="height: 12px; border-radius: 12px;">
                <div id="progress-bar-el" class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color);"></div>
            </div>
            
            <div class="d-grid gap-2">
                {{-- Share Button --}}
                <button class="btn btn-light py-3 fw-bold rounded-pill shadow-sm mb-1 d-flex align-items-center justify-content-center gap-2 border border-light-custom" 
                        onclick="openShareModal()">
                    <i data-lucide="share-2" style="width: 20px;"></i>
                    {{ __('Share Campaign') }}
                </button>

                @if(Auth::id() != $campaign->user_id)
                    <button class="btn btn-secondary-custom py-3 fw-bold rounded-pill shadow-sm text-white mb-1 d-flex align-items-center justify-content-center gap-2 w-100" 
                            data-bs-toggle="modal" data-bs-target="#donationModal"
                            onclick="setTimeout(() => updateFee(document.getElementById('donation-amount')?.value || 0), 200)">
                        <i data-lucide="heart" style="width: 20px; height: 20px;"></i>
                        {{ __('Donasi Sekarang') }}
                    </button>
                @endif
                @if(Auth::id() == $campaign->user_id)
                <button class="btn btn-outline-secondary py-2 rounded-pill small fw-bold"
                        data-bs-toggle="modal" data-bs-target="#campaignSettingsModal">
                    <i data-lucide="settings" style="width: 16px;" class="me-2"></i> {{ __('Campaign Settings') }}
                </button>
                @endif
            </div>

            @if(Auth::id() === $campaign->user_id)
            <!-- Upload Media Section -->
            <div class="mt-4 pt-4 border-top">
                <h6 class="fw-bold mb-1">{{ __('Upload Field Evidence') }}</h6>
                <p class="text-muted small mb-3">{{ __('Tambahkan foto atau video dokumentasi terbaru.') }}</p>
                
                <div class="drop-card p-0 border-0 shadow-none">
                    <div id="media-dropzone" class="seluna-dropzone" style="
                        min-height: 130px;
                        padding: 12px;
                        display: flex;
                        flex-direction: column;
                        align-items: stretch;
                    ">
                        <div class="dz-message" style="text-align:center; padding: 20px 0;">
                            <div class="icon-wrap mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <h3 style="font-size:1rem;">{{ __('Select Files') }}</h3>
                            <p class="small text-muted" style="font-size:0.75rem;">Maks 10 file • 7MB/file</p>
                            <button type="button" class="seluna-btn-browse py-1 px-3" style="font-size:0.75rem;">Browse</button>
                        </div>
                        {{-- Previews render here, inline --}}
                        <div id="media-thumb-grid" style="display:flex; flex-wrap:wrap; gap:4px; padding: 0 2px;"></div>
                    </div>

                    <div class="upload-stats" style="font-size: 0.75rem;">
                        <div class="stat-item">
                            <span class="label">{{ __('Total File') }}</span>
                            <span class="val" id="media-stat-total" style="font-size:0.95rem;">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">{{ __('Berhasil') }}</span>
                            <span class="val success" id="media-stat-success" style="font-size:0.95rem;">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">{{ __('Gagal') }}</span>
                            <span class="val danger" id="media-stat-error" style="font-size:0.95rem;">0</span>
                        </div>
                    </div>
                    
                    <button type="button" id="btn-upload-media" class="btn btn-primary w-100 rounded-pill fw-bold mt-3 d-none">
                        Upload Media
                    </button>
                </div>

                <div id="uploaded-media-container" class="mt-4 {{ $campaign->media->count() === 0 ? 'd-none' : '' }}">
                    <h6 class="fw-bold small text-muted mb-3">Media Terunggah (<span id="media-count">{{ $campaign->media->count() }}</span>)</h6>
                    <div id="media-grid-sidebar" style="display:grid; grid-template-columns: repeat(auto-fill, 60px); gap: 8px;">
                        @foreach($campaign->media as $m)
                            <div class="position-relative media-item-sidebar" id="sidebar-media-{{ $m->id }}" style="width:60px; height:60px;">
                                @if($m->isImage())
                                    <img src="{{ $m->url }}" style="width:60px;height:60px;object-fit:cover;border-radius:10px;" loading="lazy">
                                @else
                                    <div style="width:60px;height:60px;border-radius:10px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;">
                                        <i data-lucide="film" style="width:24px;color:white;"></i>
                                    </div>
                                @endif
                                <button onclick="confirmDeleteMedia({{ $m->id }})" 
                                        data-seluna-btn data-action="delete"
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
<script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Dropzone.autoDiscover = false;

    // Helper to update stats
    function updateStats(dz, prefix) {
        const total = dz.files.length;
        const success = dz.getAcceptedFiles().filter(f => f.status === Dropzone.SUCCESS).length;
        const error = dz.getRejectedFiles().length + dz.files.filter(f => f.status === Dropzone.ERROR).length;
        
        document.getElementById(prefix + "-stat-total").innerText = total;
        document.getElementById(prefix + "-stat-success").innerText = success;
        document.getElementById(prefix + "-stat-error").innerText = error;
    }

    // Initialize Report Dropzone
    const reportDropzoneEl = document.getElementById('report-dropzone');
    if (reportDropzoneEl) {
        const reportDzMessage = reportDropzoneEl.querySelector('.dz-message');
        new Dropzone("#report-dropzone", {
            url: "{{ route('campaigns.reports.store', $campaign->id) }}",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            maxFilesize: 50, // MB
            acceptedFiles: ".pdf,.docx,.xlsx,.zip",
            paramName: "files",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            previewTemplate: `
                <div class="dz-preview dz-file-preview" style="
                    display:flex; align-items:center; gap:10px;
                    background:#f8fafc; border:1px solid #e2e8f0;
                    border-radius:10px; padding:10px 12px; margin:6px 8px;
                ">
                  <div style="width:38px; height:38px; border-radius:8px; background:#e8f0fe;
                              flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none'
                         stroke='#243E36' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'
                         viewBox='0 0 24 24'>
                      <path d='M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'/>
                      <polyline points='14 2 14 8 20 8'/>
                    </svg>
                  </div>
                  <div style="flex:1; min-width:0;">
                    <div style="font-size:12px; font-weight:700; color:#243E36;
                                white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                         data-dz-name></div>
                    <div style="font-size:10px; color:#888; margin-top:2px;" data-dz-size></div>
                    <div style="height:3px; background:#e2e8f0; border-radius:2px; overflow:hidden; margin-top:5px;">
                      <span data-dz-uploadprogress
                            style="display:block; height:100%; background:#7CA982; width:0; transition:width 0.2s;"></span>
                    </div>
                    <div class="dz-error-message" data-dz-errormessage
                         style="font-size:10px; color:#e74c3c; margin-top:3px;"></div>
                  </div>
                  <button type="button" data-dz-remove
                          style="background:none; border:none; cursor:pointer; padding:0; color:#aaa; flex-shrink:0;">
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none'
                         stroke='currentColor' stroke-width='2' stroke-linecap='round'
                         stroke-linejoin='round' viewBox='0 0 24 24'>
                      <circle cx='12' cy='12' r='10'/><line x1='15' y1='9' x2='9' y2='15'/>
                      <line x1='9' y1='9' x2='15' y2='15'/>
                    </svg>
                  </button>
                </div>
            `,
            init: function() {
                const dz = this;

                dz.on("addedfile", function() {
                    updateStats(dz, "report");
                    if (reportDzMessage) reportDzMessage.style.display = 'none';
                });
                dz.on("removedfile", function() {
                    updateStats(dz, "report");
                    if (dz.files.length === 0 && reportDzMessage)
                        reportDzMessage.style.display = '';
                });
                dz.on("successmultiple", function() {
                    updateStats(dz, "report");
                    if (typeof showAlert === 'function') showAlert('success', 'Laporan berhasil diupload!');
                    setTimeout(() => { window.location.reload(); }, 1000);
                });
                dz.on("errormultiple", function(files, response) {
                    updateStats(dz, "report");
                    if (typeof showAlert === 'function') showAlert('danger', response.message || 'Gagal mengunggah file.');
                });
                dz.on("error", function() {
                    updateStats(dz, "report");
                });

                const uploadBtn = document.getElementById("btn-upload-reports");
                if (uploadBtn) {
                    uploadBtn.addEventListener("click", function() {
                        if (dz.getQueuedFiles().length > 0) {
                            dz.processQueue();
                        } else {
                            if (typeof showAlert === 'function') showAlert('warning', 'Pilih file terlebih dahulu.');
                        }
                    });
                }
            }
        });
    }

    // Initialize Media (Bukti Lapangan) Dropzone
    const mediaDropzoneEl = document.getElementById('media-dropzone');
    if (mediaDropzoneEl) {
        const mediaDzMessage = mediaDropzoneEl.querySelector('.dz-message');
        new Dropzone("#media-dropzone", {
            url: "{{ route('campaigns.media.upload', $campaign->id) }}",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            maxFilesize: 7, // MB
            acceptedFiles: "image/png,image/jpeg,image/gif,image/heic,image/heif,video/*",
            paramName: "media",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            createImageThumbnails: true,
            thumbnailWidth: 140,
            thumbnailHeight: 140,
            previewContainer: "#media-thumb-grid",
            previewTemplate: `
                <div class="dz-preview" style="
                    display:inline-block; vertical-align:top;
                    width:72px; height:72px; margin:4px;
                    position:relative; border-radius:10px; overflow:hidden;
                    border:2px solid #e2e8f0; background:#f1f5f9;
                ">
                  <img data-dz-thumbnail style="
                    width:100%; height:100%; object-fit:cover;
                    display:block;
                  " />
                  <div style="
                    position:absolute; inset:0; background:rgba(0,0,0,0.45);
                    display:flex; flex-direction:column;
                    align-items:center; justify-content:center;
                    opacity:0; transition:opacity 0.2s;
                  " class="dz-hover-overlay">
                    <button type="button" data-dz-remove style="
                      background:rgba(231,76,60,0.9); border:none;
                      border-radius:50%; width:24px; height:24px;
                      cursor:pointer; color:#fff; font-size:14px;
                      line-height:1; display:flex; align-items:center; justify-content:center;
                    ">×</button>
                  </div>
                  <div class="dz-progress" style="
                    position:absolute; bottom:0; left:0; right:0;
                    height:3px; background:rgba(255,255,255,0.3);
                  ">
                    <span data-dz-uploadprogress style="
                      display:block; height:100%;
                      background:#7CA982; width:0; transition:width 0.2s;
                    "></span>
                  </div>
                  <div class="dz-error-message" data-dz-errormessage style="
                    position:absolute; bottom:0; left:0; right:0;
                    background:rgba(231,76,60,0.85); color:#fff;
                    font-size:8px; padding:2px 4px; text-align:center;
                    display:none;
                  "></div>
                  <div class="dz-success-mark" style="display:none; position:absolute; top:2px; right:2px;
                    width:16px; height:16px; background:#7CA982; border-radius:50%;
                    align-items:center; justify-content:center; color:#fff; font-size:10px;">✓</div>
                  <div class="dz-error-mark" style="display:none; position:absolute; top:2px; right:2px;
                    width:16px; height:16px; background:#e74c3c; border-radius:50%;
                    align-items:center; justify-content:center; color:#fff; font-size:10px;">✕</div>
                </div>
            `,
            init: function() {
                const dz = this;

                // Show thumbnail overlay on hover
                dz.on("addedfile", function(file) {
                    updateStats(dz, "media");
                    document.getElementById("btn-upload-media").classList.remove("d-none");
                    if (mediaDzMessage) mediaDzMessage.style.display = 'none';

                    // Hover effect on preview
                    if (file.previewElement) {
                        const overlay = file.previewElement.querySelector('.dz-hover-overlay');
                        if (overlay) {
                            file.previewElement.addEventListener('mouseenter', () => overlay.style.opacity = '1');
                            file.previewElement.addEventListener('mouseleave', () => overlay.style.opacity = '0');
                        }
                    }
                });

                dz.on("thumbnail", function(file) {
                    // For video files show a play icon instead
                    if (file.type.startsWith('video/') && file.previewElement) {
                        const img = file.previewElement.querySelector('img[data-dz-thumbnail]');
                        if (img) {
                            img.style.display = 'none';
                            const icon = document.createElement('div');
                            icon.style.cssText = 'width:100%;height:100%;background:#1a1a2e;display:flex;align-items:center;justify-content:center;';
                            icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
                            img.parentNode.insertBefore(icon, img);
                        }
                    }
                });

                dz.on("success", function(file) {
                    if (file.previewElement) {
                        const mark = file.previewElement.querySelector('.dz-success-mark');
                        if (mark) mark.style.display = 'flex';
                    }
                });

                dz.on("error", function(file, msg) {
                    updateStats(dz, "media");
                    if (file.previewElement) {
                        const mark = file.previewElement.querySelector('.dz-error-mark');
                        if (mark) mark.style.display = 'flex';
                        const errDiv = file.previewElement.querySelector('.dz-error-message');
                        if (errDiv) { errDiv.style.display = 'block'; errDiv.textContent = typeof msg === 'string' ? msg : 'Error'; }
                    }
                });

                dz.on("removedfile", function() {
                    updateStats(dz, "media");
                    if (dz.files.length === 0) {
                        document.getElementById("btn-upload-media").classList.add("d-none");
                        if (mediaDzMessage) mediaDzMessage.style.display = '';
                    }
                });

                dz.on("successmultiple", function(files, response) {
                    updateStats(dz, "media");
                    if (typeof showAlert === 'function') showAlert('success', 'Media berhasil diupload!');
                    if (response.success) {
                        response.media.forEach(m => appendMediaToUI(m));
                        dz.removeAllFiles();
                    }
                });
                dz.on("errormultiple", function(files, response) {
                    updateStats(dz, "media");
                    if (typeof showAlert === 'function') showAlert('danger', response.message || 'Gagal mengunggah file.');
                });

                const uploadBtn = document.getElementById("btn-upload-media");
                if (uploadBtn) {
                    uploadBtn.addEventListener("click", function() {
                        if (dz.getQueuedFiles().length > 0) {
                            dz.processQueue();
                        }
                    });
                }
            }
        });
    }

    const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    let deleteTargetId = null;

    window.editUpdate = function(id, title, content) {
        const form = document.getElementById('edit-update-form');
        form.action = `/my-campaigns/{{ $campaign->id }}/updates/${id}`;
        document.getElementById('edit-update-title').value = title;
        document.getElementById('edit-update-content').value = content;
        const modal = new bootstrap.Modal(document.getElementById('editUpdateModal'));
        modal.show();
    };

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
            thumb.innerHTML = `<img src="${media.url}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">`;
        }
        strip.appendChild(thumb);

        const sidebarGrid = document.getElementById('media-grid-sidebar');
        const container = document.getElementById('uploaded-media-container');
        container.classList.remove('d-none');
        const sideItem = document.createElement('div');
        sideItem.className = 'position-relative'; sideItem.id = `sidebar-media-${media.id}`; sideItem.style.width = '60px'; sideItem.style.height = '60px';
        if (media.type === 'image') {
            sideItem.innerHTML = `<img src="${media.url}" style="width:60px;height:60px;object-fit:cover;border-radius:10px;" loading="lazy">`;
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
        
        if (btn.disabled) return;
        btn.disabled = true;
        btn.style.opacity = '0.6';
        
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
        })
        .finally(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
        });
    };

    window.showAlert = function(type, msg) {
        const container = document.getElementById('alert-container');
        if (!container) return;

        const div = document.createElement('div');
        div.className = `alert alert-${type} border-0 rounded-4 mb-3 shadow-sm d-flex align-items-center gap-2 animate__animated animate__fadeInDown seluna-flash`;
        div.innerHTML = `
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" style="width:16px; flex-shrink:0;"></i>
            <span class="flex-grow-1">${msg}</span>
            <button type="button" class="btn-close btn-close-sm ms-2" aria-label="Close"></button>
        `;
        // Close button
        div.querySelector('.btn-close').addEventListener('click', () => {
            div.style.transition = 'opacity 0.3s, transform 0.3s';
            div.style.opacity = '0';
            div.style.transform = 'translateY(-8px)';
            setTimeout(() => div.remove(), 320);
        });
        container.prepend(div);

        // Auto-dismiss after 5s
        setTimeout(() => {
            div.classList.replace('animate__fadeInDown', 'animate__fadeOutUp');
            setTimeout(() => div.remove(), 500);
        }, 5000);

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
                            <i class="fa-brands fa-whatsapp text-success" style="font-size: 26px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">WhatsApp</span>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i class="fa-brands fa-facebook text-primary" style="font-size: 26px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">Facebook</span>
                    </a>

                    {{-- Twitter / X --}}
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($campaign->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-dark bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i class="fa-brands fa-x-twitter text-dark" style="font-size: 26px;"></i>
                        </div>
                        <span class="small fw-bold text-muted">X / Twitter</span>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/" target="_blank" 
                       onclick="copyCampaignLinkSilent()"
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s; background-color: rgba(225, 48, 108, 0.1);">
                            <i class="fa-brands fa-instagram" style="font-size: 26px; color: #E1306C;"></i>
                        </div>
                        <span class="small fw-bold text-muted">Instagram</span>
                    </a>

                    {{-- Telegram --}}
                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($campaign->title) }}" target="_blank" 
                       class="text-decoration-none text-center flex-shrink-0 share-icon-btn" style="width: 70px;">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                             style="width: 55px; height: 55px; transition: transform 0.2s;">
                            <i class="fa-brands fa-telegram text-info" style="font-size: 26px;"></i>
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

    function copyCampaignLinkSilent() {
        const input = document.getElementById('copyLinkInput');
        navigator.clipboard.writeText(input.value);
        if (typeof showAlert === 'function') {
            showAlert('success', 'Link disalin! Silakan tempel di postingan, bio, atau cerita Instagram Anda.');
        }
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
            <form action="{{ route('campaigns.updates.store', $campaign->id) }}" method="POST" enctype="multipart/form-data" data-seluna>
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
                        <input type="file" name="media" class="form-control" accept="image/png,image/jpeg,image/gif,image/heic,image/heif,video/*">
                        <p class="text-muted small mt-1">Video, Foto (PNG, JPG, JPEG, HEIC, HEIF), atau GIF.</p>
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

{{-- Edit Update Modal --}}
<div class="modal fade" id="editUpdateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 28px; background-color: var(--bg-color);">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Edit Update Berita</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="edit-update-form" action="" method="POST" enctype="multipart/form-data" data-seluna>
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Judul Update</label>
                        <input type="text" id="edit-update-title" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold required">Isi Berita</label>
                        <textarea id="edit-update-content" name="content" class="form-control" rows="6" required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Ganti Media / Laporan (Opsional)</label>
                        <input type="file" name="media" class="form-control" accept="image/png,image/jpeg,image/gif,image/heic,image/heif,video/*">
                        <p class="text-muted small mt-1">Biarkan kosong jika tidak ingin mengubah media. Format: Video, Foto (PNG, JPG, JPEG, HEIC, HEIF), atau GIF.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow">Simpan Perubahan</button>
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
            <form id="donation-form" action="{{ route('campaigns.donate', $campaign->id) }}" method="POST" enctype="multipart/form-data" data-seluna>
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
                    <button type="button" id="confirm-delete-btn" class="btn btn-danger w-100 rounded-pill fw-bold" data-seluna-btn data-action="delete">Ya, Hapus</button>
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
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 rounded-4 small mb-4" style="background-color: #eef3f0; color: #243e36;">
                    <i data-lucide="info" class="me-2" style="width:16px;"></i>
                    Unggah laporan pertanggungjawaban berupa file PDF, DOCX, XLSX, atau ZIP. Maksimal 50MB per file.
                </div>
                
                <div class="drop-card border border-light shadow-sm">
                    <div id="report-dropzone" class="seluna-dropzone" style="min-height: 140px; padding: 12px;">
                        <div class="dz-message" style="text-align:center; padding: 20px 0;">
                            <div class="icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <h3>Seret &amp; letakkan file di sini</h3>
                            <p class="small text-muted">atau pilih file dari komputer Anda</p>
                            <button type="button" class="seluna-btn-browse">Pilih File</button>
                        </div>
                        {{-- Report file previews render inline here --}}
                    </div>

                    <div class="upload-stats">
                        <div class="stat-item">
                            <span class="label">Total File</span>
                            <span class="val" id="report-stat-total">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Berhasil</span>
                            <span class="val success" id="report-stat-success">0</span>
                        </div>
                        <div class="stat-item">
                            <span class="label">Gagal</span>
                            <span class="val danger" id="report-stat-error">0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light px-4 py-2 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btn-upload-reports" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">Upload Dokumen</button>
            </div>
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
            <form action="{{ route('campaigns.update', $campaign->id) }}" method="POST" data-seluna>
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