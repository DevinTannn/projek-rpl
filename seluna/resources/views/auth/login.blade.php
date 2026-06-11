@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <h3 class="fw-bold" style="color:#243E36;">{{ __('Selamat Datang') }}</h3>
    <p class="text-muted">{{ __('Masuk ke akun SELUNA Anda') }}</p>
</div>

@if(session('success'))
    <div class="alert border-0 rounded-4 mb-4 small" style="background:#EFF8F1;color:#243E36;">
        <i data-lucide="check-circle" style="width:14px;height:14px;vertical-align:-2px;margin-right:6px;"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 rounded-4 mb-4 small">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-bold">{{ __('Email atau Username') }}</label>
        <input type="text" name="login" class="form-control" placeholder="user@example.com" value="{{ old('login') }}" required>
    </div>
    
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label small fw-bold m-0">{{ __('Kata Sandi') }}</label>
            <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none" style="color:#7CA982 !important;font-weight:600;">{{ __('Lupa sandi?') }}</a>
        </div>
        <div class="password-wrapper">
            <input type="password" id="login-password" name="password" class="form-control" placeholder="••••••••" required>
            <button type="button" class="password-toggle" onclick="togglePassword('login-password', this)" aria-label="{{ __('Tampilkan sandi') }}">
                <i data-lucide="eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-auth">{{ __('Masuk Sekarang') }}</button>

    <div class="auth-switch">
        {{ __('Belum punya akun?') }} <a href="{{ route('register') }}">{{ __('Daftar Gratis') }}</a>
    </div>
</form>
@endsection
