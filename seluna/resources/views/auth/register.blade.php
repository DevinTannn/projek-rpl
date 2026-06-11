@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <h3 class="fw-bold" style="color:#243E36;">{{ __('Buat Akun') }}</h3>
    <p class="text-muted">{{ __('Mulai langkah kebaikan Anda hari ini') }}</p>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 rounded-4 mb-4 small">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label class="form-label small fw-bold">{{ __('Username') }}</label>
        <input type="text" name="username" class="form-control" placeholder="jokosusilo" value="{{ old('username') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold">{{ __('Email Address') }}</label>
        <input type="email" name="email" class="form-control" placeholder="joko@email.com" value="{{ old('email') }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label small fw-bold">{{ __('Kata Sandi') }}</label>
        <div class="password-wrapper">
            <input type="password" id="reg-password" name="password" class="form-control" placeholder="{{ __('Min. 8 karakter') }}" required>
            <button type="button" class="password-toggle" onclick="togglePassword('reg-password', this)" aria-label="{{ __('Tampilkan sandi') }}">
                <i data-lucide="eye"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label small fw-bold">{{ __('Konfirmasi Sandi') }}</label>
        <div class="password-wrapper">
            <input type="password" id="reg-password-confirm" name="password_confirmation" class="form-control" placeholder="{{ __('Ulangi kata sandi') }}" required>
            <button type="button" class="password-toggle" onclick="togglePassword('reg-password-confirm', this)" aria-label="{{ __('Tampilkan konfirmasi sandi') }}">
                <i data-lucide="eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-auth">{{ __('Daftar Sekarang') }}</button>

    <div class="auth-switch">
        {{ __('Sudah punya akun?') }} <a href="{{ route('login') }}">{{ __('Masuk') }}</a>
    </div>
</form>
@endsection
