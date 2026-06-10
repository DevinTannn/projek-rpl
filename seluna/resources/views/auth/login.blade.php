@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <h3 class="fw-bold text-primary-custom">Selamat Datang</h3>
    <p class="text-muted">Masuk ke akun SumselPeduli Anda</p>
</div>

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
        <label class="form-label small fw-bold">Email atau Username</label>
        <input type="text" name="login" class="form-control" placeholder="user@example.com" value="{{ old('login') }}" required>
    </div>
    
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label small fw-bold m-0">Kata Sandi</label>
            <a href="#" class="text-muted small text-decoration-none">Lupa sandi?</a>
        </div>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>

    <button type="submit" class="btn btn-auth">Masuk Sekarang</button>

    <div class="auth-switch">
        Belum punya akun? <a href="{{ route('register') }}">Daftar Gratis</a>
    </div>
</form>
@endsection
