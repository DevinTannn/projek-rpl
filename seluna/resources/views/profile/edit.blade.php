@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    .img-container img {
        max-width: 100%;
        max-height: 400px;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 p-lg-5" style="border-radius: 28px;">
            <div class="d-flex align-items-center gap-3 mb-5">
                <div class="bg-surface p-3 rounded-circle">
                    <i data-lucide="user-cog" class="text-primary-custom" style="width: 32px; height: 32px;"></i>
                </div>
                <h3 class="fw-bold m-0 text-primary-custom">{{ __('Edit Profile Settings') }}</h3>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="cropped_image" id="cropped_image">

                <div class="row g-4">
                    <!-- Photo Header -->
                    <div class="col-12 text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name='.$user->username.'&background=7CA982' }}" 
                                 class="rounded-circle border border-4 border-white shadow-sm mb-3" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <label for="profile_photo" class="btn btn-sm btn-accent position-absolute bottom-0 start-50 translate-middle-x rounded-pill shadow-sm" style="margin-bottom: -10px;">
                                <i data-lucide="camera" style="width: 14px;"></i> {{ __('Edit') }}
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold required">{{ __('Username') }}</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold required">{{ __('Email Address') }}</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('Date of Birth') }}</label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $user->date_of_birth) }}" max="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('Gender') }}</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">{{ __('Select Gender') }}</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('Choose Language') }}</label>
                        <select name="locale" class="form-select">
                            <option value="id" {{ session('locale', 'id') == 'id' ? 'selected' : '' }}>{{ __('Indonesia') }}</option>
                            <option value="en" {{ session('locale', 'id') == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">{{ __('Description / Bio') }}</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $user->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">{{ __('Update Profile') }}</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-light px-5 py-2 fw-bold rounded-pill">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary-custom" id="cropperModalLabel">{{ __('Crop Profile Photo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container d-flex justify-content-center align-items-center" style="max-height: 400px; overflow: hidden; background-color: #f7f9fa; border-radius: 12px;">
                    <img id="imageToCrop" src="" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" id="cropAndSaveBtn">{{ __('Crop & Save') }}</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profile_photo');
        const cropperModalEl = document.getElementById('cropperModal');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropAndSaveBtn = document.getElementById('cropAndSaveBtn');
        const croppedImageInput = document.getElementById('cropped_image');
        const profileImagePreview = document.querySelector('.rounded-circle.border.border-4');
        
        let cropper = null;
        let cropperModal = null;
        
        if (cropperModalEl) {
            cropperModal = new bootstrap.Modal(cropperModalEl);
        }

        profilePhotoInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageToCrop.src = e.target.result;
                    cropperModal.show();
                };
                reader.readAsDataURL(file);
            }
        });

        cropperModalEl.addEventListener('shown.bs.modal', function() {
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false
            });
        });

        cropperModalEl.addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            // Reset file input so same file can trigger change event again
            profilePhotoInput.value = '';
        });

        cropAndSaveBtn.addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });
                
                const croppedDataUrl = canvas.toDataURL('image/jpeg');
                croppedImageInput.value = croppedDataUrl;
                profileImagePreview.src = croppedDataUrl;
                cropperModal.hide();
            }
        });
    });
</script>
@endpush
