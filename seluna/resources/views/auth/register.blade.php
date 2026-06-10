@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <h3 class="fw-bold text-primary-custom">Buat Akun</h3>
    <p class="text-muted">Mulai langkah kebaikan Anda hari ini</p>
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
        <label class="form-label small fw-bold">Username</label>
        <input type="text" name="username" class="form-control" placeholder="jokosusilo" value="{{ old('username') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label small fw-bold">Email</label>
        <input type="email" name="email" class="form-control" placeholder="joko@email.com" value="{{ old('email') }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label small fw-bold">Kata Sandi</label>
        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
    </div>

    <div class="mb-4">
        <label class="form-label small fw-bold">Konfirmasi Sandi</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
    </div>

    <button type="submit" class="btn btn-auth">Daftar Sekarang</button>

    <div class="auth-switch">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </div>
</form>
@endsection
