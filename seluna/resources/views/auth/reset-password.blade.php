@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <div style="width:52px;height:52px;background:#EFF8F1;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i data-lucide="key-round" style="width:24px;height:24px;color:#243E36;"></i>
    </div>
    <h3 class="fw-bold" style="color:#243E36;">Buat Password Baru</h3>
    <p class="text-muted" style="font-size:14px;">Buat password yang kuat dan mudah diingat.</p>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 rounded-4 mb-4 small">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-bold">Password Baru</label>
        <div class="password-wrapper">
            <input
                type="password"
                id="new-password"
                name="password"
                class="form-control"
                placeholder="Min. 8 karakter"
                required
                minlength="8"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('new-password', this)" aria-label="Tampilkan password">
                <i data-lucide="eye"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label small fw-bold">Konfirmasi Password</label>
        <div class="password-wrapper">
            <input
                type="password"
                id="new-password-confirm"
                name="password_confirmation"
                class="form-control"
                placeholder="Ulangi password baru"
                required
                minlength="8"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('new-password-confirm', this)" aria-label="Tampilkan konfirmasi">
                <i data-lucide="eye"></i>
            </button>
        </div>
    </div>

    {{-- Password strength bar --}}
    <div class="mb-4">
        <div style="height:4px;background:#E5E7EB;border-radius:4px;overflow:hidden;">
            <div id="strength-bar" style="height:100%;width:0;border-radius:4px;transition:all 0.3s;"></div>
        </div>
        <p id="strength-label" class="text-muted mt-1" style="font-size:11px;"></p>
    </div>

    <button type="submit" class="btn btn-auth">
        Simpan Password Baru
    </button>

    <div class="auth-switch">
        Kembali ke <a href="{{ route('login') }}">halaman masuk</a>
    </div>
</form>

<script>
document.getElementById('new-password').addEventListener('input', function () {
    const val  = this.value;
    const bar  = document.getElementById('strength-bar');
    const label = document.getElementById('strength-label');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { color: '#EF4444', text: 'Sangat lemah', w: '25%' },
        { color: '#F97316', text: 'Lemah',         w: '50%' },
        { color: '#EAB308', text: 'Cukup',         w: '75%' },
        { color: '#22C55E', text: 'Kuat',           w: '100%' },
    ];

    if (val.length === 0) { bar.style.width = '0'; label.textContent = ''; return; }
    const lvl = levels[Math.max(0, score - 1)];
    bar.style.width       = lvl.w;
    bar.style.background  = lvl.color;
    label.textContent     = lvl.text;
    label.style.color     = lvl.color;
});
</script>
@endsection
