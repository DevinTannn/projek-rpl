@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm p-4 p-lg-5" style="border-radius: 28px;">
            <div class="d-flex align-items-center gap-3 mb-5">
                <div class="bg-surface p-3 rounded-circle">
                    <i data-lucide="user-cog" class="text-primary-custom" style="width: 32px; height: 32px;"></i>
                </div>
                <h3 class="fw-bold m-0 text-primary-custom">Edit Profile Settings</h3>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Photo Header -->
                    <div class="col-12 text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name='.$user->username.'&background=7CA982' }}" 
                                 class="rounded-circle border border-4 border-white shadow-sm mb-3" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                            <label for="profile_photo" class="btn btn-sm btn-accent position-absolute bottom-0 start-50 translate-middle-x rounded-pill shadow-sm" style="margin-bottom: -10px;">
                                <i data-lucide="camera" style="width: 14px;"></i> Edit
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" class="d-none">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Description / Bio</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $user->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">Update Profile</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-light px-5 py-2 fw-bold rounded-pill">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
