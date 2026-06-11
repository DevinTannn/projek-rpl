@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <div style="width:52px;height:52px;background:#EFF8F1;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i data-lucide="lock-keyhole" style="width:24px;height:24px;color:#243E36;"></i>
    </div>
    <h3 class="fw-bold" style="color:#243E36;">{{ __('Lupa Sandi?') }}</h3>
    <p class="text-muted" style="font-size:14px;">{{ __('Masukkan alamat email Anda untuk menerima kode OTP.') }}</p>
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

<form action="{{ route('password.send-otp') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="form-label small fw-bold">{{ __('Email Address') }}</label>
        <div style="position:relative;">
            <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#9ca3af;">
                <i data-lucide="mail" style="width:16px;height:16px;"></i>
            </span>
            <input
                type="email"
                name="email"
                class="form-control"
                style="padding-left:44px;"
                placeholder="nama@email.com"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>
    </div>

    <button type="submit" class="btn btn-auth">
        {{ __('Kirim Kode OTP') }}
    </button>

    <div class="auth-switch">
        {{ __('Kembali ke Login') }} <a href="{{ route('login') }}">{{ __('Masuk Sekarang') }}</a>
    </div>
</form>
@endsection
