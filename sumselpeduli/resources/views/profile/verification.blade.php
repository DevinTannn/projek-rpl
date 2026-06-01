@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 28px;">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <a href="{{ route('profile.show') }}" class="btn btn-light rounded-circle p-2">
                        <i data-lucide="arrow-left"></i>
                    </a>
                    <h3 class="fw-bold m-0 text-primary-custom">Verifikasi Identitas Fundraiser</h3>
                </div>

                @if($user->isVerified())
                    <div class="alert alert-success border-0 rounded-4 p-4 mb-4">
                        <div class="d-flex gap-3">
                            <i data-lucide="shield-check" style="width: 32px; height: 32px;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Akun Terverifikasi</h5>
                                <p class="mb-0 opacity-75">Identitas Anda telah divalidasi oleh tim SumselPeduli. Anda dapat membuat dan mengelola campaign secara penuh.</p>
                            </div>
                        </div>
                    </div>
                @elseif($verification && $verification->status === 'pending')
                    <div class="alert alert-warning border-0 rounded-4 p-4 mb-4">
                        <div class="d-flex gap-3">
                            <i data-lucide="clock" style="width: 32px; height: 32px;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Menunggu Verifikasi</h5>
                                <p class="mb-0 opacity-75">Dokumen Anda sedang ditinjau oleh admin. Proses ini biasanya memakan waktu 1-2 hari kerja.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ $user->isVerified() ? route('profile.verification.updateOrg') : route('profile.verification.store') }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">NAMA LENGKAP (SESUAI KTP)</label>
                        <input type="text" name="full_name" class="form-control rounded-3 @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name', $verification->full_name ?? '') }}" 
                               {{ $user->isVerified() || ($verification && $verification->status === 'pending') ? 'disabled' : 'required' }}>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">NOMOR INDUK KEPENDUDUKAN (NIK)</label>
                        <input type="text" name="nik" class="form-control rounded-3 @error('nik') is-invalid @enderror" maxlength="16"
                               value="{{ old('nik', $verification->nik ?? '') }}" 
                               {{ $user->isVerified() || ($verification && $verification->status === 'pending') ? 'disabled' : 'required' }}>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">NAMA KOMUNITAS / YAYASAN / ORGANISASI</label>
                        <input type="text" name="organization_name" class="form-control rounded-3 @error('organization_name') is-invalid @enderror" 
                               value="{{ old('organization_name', $verification->organization_name ?? '') }}" required>
                        @error('organization_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(!$user->isVerified())
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">UNGGAH FOTO IDENTITAS (KTP)</label>
                        @if($verification && $verification->status === 'pending')
                            <div class="p-3 bg-light rounded-3 text-center border">
                                <i data-lucide="image" class="text-muted mb-2"></i>
                                <div class="small text-muted text-truncate">{{ $verification->ktp_photo }}</div>
                                <div class="small fw-bold text-warning">Dokumen sedang ditinjau</div>
                            </div>
                        @else
                            <div id="ktp-drop-zone" class="p-4 rounded-4 text-center @error('ktp_photo') border-danger @enderror" 
                                 style="border: 2px dashed var(--secondary-color); background: #fbfdfb; cursor: pointer;">
                                <i data-lucide="camera" class="mb-2" style="color: var(--secondary-color);"></i>
                                <div class="small fw-bold">Pilih atau Seret Foto KTP</div>
                                <input type="file" name="ktp_photo" id="ktp-input" class="d-none" accept="image/*" required>
                                <div id="ktp-preview" class="mt-2 d-none">
                                    <img src="" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px;">
                                </div>
                            </div>
                            @error('ktp_photo')
                                <div class="small text-danger mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    @if(!$verification || $verification->status !== 'pending')
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('agreement') is-invalid @enderror" type="checkbox" name="agreement" id="agreement" required>
                            <label class="form-check-label small text-muted" for="agreement">
                                Saya menyatakan bersedia mempertanggungjawabkan keaslian berkas dan siap menyajikan transparansi aliran dana publik secara penuh.
                            </label>
                            @error('agreement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif

                    @endif

                    <div class="d-grid mt-5">
                        @if(!$user->isVerified() && (!$verification || $verification->status !== 'pending'))
                            <button type="submit" class="btn btn-primary py-3 rounded-pill fw-bold shadow-sm">Kirim Dokumen Verifikasi</button>
                        @elseif($user->isVerified())
                            <button type="submit" class="btn btn-secondary-color py-3 rounded-pill fw-bold text-white shadow-sm" style="background-color: var(--secondary-color);">Simpan Perubahan Organisasi</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropZone = document.getElementById('ktp-drop-zone');
        const input = document.getElementById('ktp-input');
        const preview = document.getElementById('ktp-preview');
        const previewImg = preview?.querySelector('img');

        if (dropZone) {
            dropZone.onclick = () => input.click();
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (re) => {
                        previewImg.src = re.target.result;
                        preview.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            };
        }
        
        lucide.createIcons();
    });
</script>
@endpush
@endsection
