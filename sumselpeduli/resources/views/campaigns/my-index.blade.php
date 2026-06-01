@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary-custom">Your Campaign</h2>
    <button class="btn btn-accent px-4 py-2 fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createCampaignModal">
        <i data-lucide="plus-circle" style="width: 20px;"></i>
        Create a Campaign
    </button>
</div>

@if($campaigns->isEmpty())
    <div class="card border-0 shadow-sm p-5 text-center" style="background-color: var(--surface-color); border-radius: 24px;">
        <div class="mb-4">
            <i data-lucide="layers" class="text-accent-custom" style="width: 80px; height: 80px; opacity: 0.5;"></i>
        </div>
        <h4 class="fw-bold text-primary-custom mb-2">YOU HAVEN'T CREATED ANY CAMPAIGN</h4>
        <p class="text-muted mb-4 text-uppercase fw-semibold" style="letter-spacing: 1px;">CREATE A CAMPAIGN?</p>
        <button class="btn btn-primary px-5 py-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#createCampaignModal">Create a Campaign</button>
    </div>
@else
    <div class="row g-4">
        @foreach($campaigns as $campaign)
            <div class="col-12 col-md-6 col-xl-4" id="campaign-card-{{ $campaign->id }}">
                <div class="dummy-card shadow-sm h-100" style="background-color: white; border: 1px solid rgba(0,0,0,0.05); border-radius: 20px; overflow: hidden; position: relative;">
                    <a href="{{ route('campaigns.show', $campaign->id) }}" class="text-decoration-none">
                        @php $banner = $campaign->media->first(); @endphp
                        @if($banner)
                            <img src="{{ $banner->url }}" class="w-100" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://picsum.photos/seed/campaign-{{ $campaign->id }}/600/400" class="w-100" style="height: 200px; object-fit: cover;">
                        @endif
                        <div style="position: absolute; top: 15px; left: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                            <h5 class="fw-bold text-white m-0">{{ $campaign->title }}</h5>
                            <div class="d-flex gap-2">
                                @if($campaign->tag)
                                    <span class="badge bg-accent-custom mt-2">{{ $campaign->tag }}</span>
                                @endif
                                @if($campaign->status === 'active')
                                    <span class="badge bg-success mt-2">Aktif</span>
                                @elseif($campaign->status === 'pending')
                                    <span class="badge bg-warning mt-2">Menunggu Verifikasi</span>
                                @elseif($campaign->status === 'rejected')
                                    <span class="badge bg-danger mt-2">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </a>

                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold">{{ $campaign->percentage }}% Terkumpul</span>
                            <span class="text-muted small">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }} / {{ number_format($campaign->goal_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar" style="width: {{ $campaign->percentage }}%; background-color: var(--secondary-color); border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Create Campaign Modal -->
<div class="modal fade" id="createCampaignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 24px; background-color: var(--bg-color);">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Create New Campaign</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('campaigns.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Entah campaign name..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bio / Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Short campaign description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Campaign Tag / Category</label>
                        <select name="tag" class="form-select">
                            <option value="">Pilih Kategori...</option>
                            <option value="Sosial & Kemanusiaan">Sosial & Kemanusiaan</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Bencana Alam">Bencana Alam</option>
                            <option value="Lingkungan">Lingkungan</option>
                            <option value="Keagamaan">Keagamaan</option>
                            <option value="Pembangunan & Infrastruktur">Pembangunan & Infrastruktur</option>
                            <option value="Pemberdayaan Ekonomi Komunitas">Pemberdayaan Ekonomi Komunitas</option>
                            <option value="Seni & Budaya">Seni & Budaya</option>
                            <option value="Penelitian & Inovasi">Penelitian & Inovasi</option>
                            <option value="Animal Safety and Care">Animal Safety and Care</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Goal Amount (Target)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">Rp</span>
                            <input type="number" name="goal_amount" id="create_goal_amount" class="form-control border-start-0" placeholder="5000000" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary-custom mb-3 mt-4">Milestone Rewards</h6>
                    <div class="row g-3">
                        @foreach([25, 50, 75, 100] as $perc)
                            <div class="col-12">
                                <div class="p-3 bg-white rounded-3 d-flex align-items-center gap-3">
                                    <div class="fw-bold text-accent-custom" style="width: 50px;">{{ $perc }}%</div>
                                    <div class="text-muted small flex-grow-1" style="min-width: 120px;">
                                        Rp <span id="create-milestone-{{ $perc }}">0</span>
                                    </div>
                                    <input type="text" name="milestones[{{ $perc }}]" class="form-control flex-grow-1" placeholder="Badge label for {{ $perc }}% milestone" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">Launch Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Campaign Modal -->
<div class="modal fade" id="editCampaignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 24px; background-color: var(--bg-color);">
            <div class="modal-header border-0 pb-0">
                <h4 class="fw-bold text-primary-custom m-0">Edit Campaign</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCampaignForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Bio / Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Campaign Tag / Category</label>
                        <select name="tag" id="edit_tag" class="form-select">
                            <option value="">Pilih Kategori...</option>
                            <option value="Sosial & Kemanusiaan">Sosial & Kemanusiaan</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Bencana Alam">Bencana Alam</option>
                            <option value="Lingkungan">Lingkungan</option>
                            <option value="Keagamaan">Keagamaan</option>
                            <option value="Pembangunan & Infrastruktur">Pembangunan & Infrastruktur</option>
                            <option value="Pemberdayaan Ekonomi Komunitas">Pemberdayaan Ekonomi Komunitas</option>
                            <option value="Seni & Budaya">Seni & Budaya</option>
                            <option value="Penelitian & Inovasi">Penelitian & Inovasi</option>
                            <option value="Animal Safety and Care">Animal Safety and Care</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Goal Amount (Target)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">Rp</span>
                            <input type="number" name="goal_amount" id="edit_goal_amount" class="form-control border-start-0" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-primary-custom mb-3 mt-4">Milestone Rewards</h6>
                    <div class="row g-3">
                        @foreach([25, 50, 75, 100] as $perc)
                            <div class="col-12">
                                <div class="p-3 bg-white rounded-3 d-flex align-items-center gap-3">
                                    <div class="fw-bold text-accent-custom" style="width: 50px;">{{ $perc }}%</div>
                                    <div class="text-muted small flex-grow-1" style="min-width: 120px;">
                                        Rp <span id="edit-milestone-{{ $perc }}">0</span>
                                    </div>
                                    <input type="text" name="milestones[{{ $perc }}]" id="edit_milestone_{{ $perc }}" class="form-control flex-grow-1" required>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">Update Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/uzyi3qni0rl59wmj5i3t38v3cebtp184ygnuw2vto9ugxut5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description, #edit_description',
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

    // Create Calculation
    document.getElementById('create_goal_amount').addEventListener('input', function(e) {
        const goal = parseFloat(e.target.value) || 0;
        [25, 50, 75, 100].forEach(perc => {
            const amount = Math.round(goal * (perc / 100));
            document.getElementById('create-milestone-' + perc).innerText = amount.toLocaleString('id-ID');
        });
    });

    // Edit Calculation
    document.getElementById('edit_goal_amount').addEventListener('input', function(e) {
        const goal = parseFloat(e.target.value) || 0;
        [25, 50, 75, 100].forEach(perc => {
            const amount = Math.round(goal * (perc / 100));
            document.getElementById('edit-milestone-' + perc).innerText = amount.toLocaleString('id-ID');
        });
    });

    function openEditModal(campaign, milestones) {
        const form = document.getElementById('editCampaignForm');
        form.action = `/my-campaigns/${campaign.id}`;
        
        document.getElementById('edit_title').value = campaign.title;
        tinymce.get('edit_description').setContent(campaign.description);
        document.getElementById('edit_goal_amount').value = campaign.goal_amount;
        document.getElementById('edit_tag').value = campaign.tag || '';
        
        milestones.forEach(m => {
            const input = document.getElementById(`edit_milestone_${m.percentage}`);
            if (input) {
                input.value = m.badge_label;
            }
            const amountDisplay = document.getElementById(`edit-milestone-${m.percentage}`);
            if (amountDisplay) {
                amountDisplay.innerText = Math.round(m.amount).toLocaleString('id-ID');
            }
        });

        const modal = new bootstrap.Modal(document.getElementById('editCampaignModal'));
        modal.show();
    }

    // ── Real-time Cross-tab Sync ──
    const updateUI = (data) => {
        const card = document.getElementById(`campaign-card-${data.id}`);
        if (card) {
            const img = card.querySelector('img');
            if (img && img.src !== data.banner_url) {
                img.style.opacity = '0';
                setTimeout(() => {
                    img.src = data.banner_url;
                    img.style.opacity = '1';
                }, 300);
                img.style.transition = 'opacity 0.3s ease';
            }
        }
    };

    try {
        new BroadcastChannel('campaign_sync').onmessage = (e) => updateUI(e.data);
    } catch(e) {}

    window.addEventListener('storage', function(e) {
        if (e.key === 'campaign_update') {
            updateUI(JSON.parse(e.newValue));
        }
    });
</script>
@endpush
@endsection
