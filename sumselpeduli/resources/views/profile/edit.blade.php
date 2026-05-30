@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="fw-bold mb-4">Complete Your Profile</h3>
            <p class="text-muted mb-4">Tell us a bit more about yourself to get started.</p>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Short Bio / Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $user->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="/" class="text-muted text-decoration-none">Skip for now</a>
                    <button type="submit" class="btn btn-accent px-4 py-2 fw-semibold">Save Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
