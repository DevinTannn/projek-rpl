@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="fw-bold mb-4 text-center">Login to Your Account</h3>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="login" class="form-label">Username or Email</label>
                    <input type="text" name="login" id="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required autofocus>
                    @error('login')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Login</button>

                <div class="mt-3 text-center">
                    <small>Don't have an account? <a href="{{ route('register') }}" class="text-accent-custom text-decoration-none">Register here</a></small>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
