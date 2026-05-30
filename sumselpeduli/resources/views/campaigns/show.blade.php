@extends('layouts.app')

@section('content')
<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 24px;">
            <img src="https://picsum.photos/seed/header-{{ $campaign->id }}/1200/500" class="w-100" style="height: 350px; object-fit: cover;">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="fw-bold text-primary-custom m-0">{{ $campaign->title }}</h2>
                    <div id="tag-container" class="d-flex align-items-center gap-2">
                        <span id="campaign-tag" class="badge {{ $campaign->tag ? 'bg-accent-custom' : 'bg-secondary' }} px-3 py-2">
                            {{ $campaign->tag ?? 'No tag' }}
                        </span>
                        <button onclick="editTag()" class="btn btn-sm btn-outline-secondary rounded-pill">Edit</button>
                    </div>
                    
                    <!-- Inline Tag Editor (Hidden) -->
                    <div id="tag-editor" class="d-none d-flex align-items-center gap-2">
                        <input type="text" id="tag-input" class="form-control form-control-sm" value="{{ $campaign->tag }}" placeholder="New tag...">
                        <button onclick="saveTag()" class="btn btn-sm btn-primary">Save</button>
                        <button onclick="cancelTagEdit()" class="btn btn-sm btn-light">x</button>
                    </div>
                </div>

                <p class="text-muted mb-5 lead">{{ $campaign->description }}</p>

                <h5 class="fw-bold mb-4">Milestone Tracker</h5>
                <div class="row g-3 mb-5">
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

                <!-- Placeholder Sections -->
                <div class="mb-5">
                    <ul class="nav nav-tabs border-0 gap-3 mb-4" id="campaignTabs">
                        <li class="nav-item">
                            <a class="nav-link active rounded-pill px-4" data-bs-toggle="tab" href="#updates">Updates</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-4" data-bs-toggle="tab" href="#donors">Donors</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill px-4" data-bs-toggle="tab" href="#gallery">Gallery</a>
                        </li>
                    </ul>
                    <div class="tab-content text-center py-5 bg-light rounded-4">
                        <div class="tab-pane fade show active" id="updates">
                            <i data-lucide="clock" class="text-muted mb-3" style="width: 40px; height: 40px;"></i>
                            <p class="text-muted">Timeline and fund usage updates will appear here.</p>
                        </div>
                        <div class="tab-pane fade" id="donors">
                            <i data-lucide="users" class="text-muted mb-3" style="width: 40px; height: 40px;"></i>
                            <p class="text-muted">List of generous donors will be shown here.</p>
                        </div>
                        <div class="tab-pane fade" id="gallery">
                            <i data-lucide="image" class="text-muted mb-3" style="width: 40px; height: 40px;"></i>
                            <p class="text-muted">Photos documenting the penanganan kasus will be here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 100px;">
            <h5 class="fw-bold mb-4">Donation Progress</h5>
            <div class="d-flex justify-content-between mb-2">
                <span class="h4 fw-bold m-0 text-accent-custom">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                <span class="text-muted">Goal: Rp {{ number_format($campaign->goal_amount, 0, ',', '.') }}</span>
            </div>
            <div class="progress mb-4" style="height: 12px; border-radius: 12px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color);"></div>
            </div>
            
            <div class="d-grid gap-2">
                <button class="btn btn-primary py-3 fw-bold rounded-pill shadow-sm" disabled>Shared to Social Media</button>
                <button class="btn btn-outline-secondary py-2 rounded-pill small">Campaign Settings</button>
            </div>
        </div>
    </div>
</div>

<script>
    function editTag() {
        document.getElementById('tag-container').classList.add('d-none');
        document.getElementById('tag-editor').classList.remove('d-none');
        document.getElementById('tag-input').focus();
    }

    function cancelTagEdit() {
        document.getElementById('tag-container').classList.remove('d-none');
        document.getElementById('tag-editor').classList.add('d-none');
    }

    function saveTag() {
        const newTag = document.getElementById('tag-input').value;
        fetch('{{ route('campaigns.update-tag', $campaign->id) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tag: newTag })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tagSpan = document.getElementById('campaign-tag');
                tagSpan.innerText = data.tag || 'No tag';
                if (data.tag) {
                    tagSpan.classList.remove('bg-secondary');
                    tagSpan.classList.add('bg-accent-custom');
                } else {
                    tagSpan.classList.remove('bg-accent-custom');
                    tagSpan.classList.add('bg-secondary');
                }
                cancelTagEdit();
            }
        });
    }
</script>
@endsection
