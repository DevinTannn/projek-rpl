@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="fw-bold mb-4 text-center">Join SumselPeduli</h3>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Register Now</button>

                <div class="mt-3 text-center">
                    <small>Already have an account? <a href="{{ route('login') }}" class="text-accent-custom text-decoration-none">Login here</a></small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
