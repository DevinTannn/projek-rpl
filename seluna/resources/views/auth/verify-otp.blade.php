@extends('layouts.auth')

@section('content')
<div class="text-center mb-5">
    <div style="width:52px;height:52px;background:#EFF8F1;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i data-lucide="shield-check" style="width:24px;height:24px;color:#243E36;"></i>
    </div>
    <h3 class="fw-bold" style="color:#243E36;">Masukkan Kode OTP</h3>
    <p class="text-muted" style="font-size:14px;">
        Kami telah mengirim kode 6 digit ke<br>
        <strong style="color:#243E36;">{{ $email }}</strong>
    </p>
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

<form action="{{ route('password.do-verify-otp') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="form-label small fw-bold">Kode OTP (6 digit)</label>
        {{-- OTP digit input boxes --}}
        <div id="otp-inputs" style="display:flex;gap:10px;justify-content:center;margin-bottom:8px;">
            @for($i = 0; $i < 6; $i++)
            <input
                type="text"
                class="otp-digit form-control"
                maxlength="1"
                inputmode="numeric"
                pattern="[0-9]"
                style="width:52px;height:60px;text-align:center;font-size:24px;font-weight:800;border-radius:14px;padding:0;"
                autocomplete="off"
            >
            @endfor
        </div>
        {{-- Hidden input that holds the concatenated OTP --}}
        <input type="hidden" name="otp" id="otp-value">
        <p class="text-center text-muted" style="font-size:12px;">Kode berlaku selama 10 menit</p>
    </div>

    <button type="submit" id="otp-submit" class="btn btn-auth" disabled>
        Verifikasi Kode
    </button>
</form>

<div class="auth-switch">
    Email salah? <a href="{{ route('password.request') }}">Ganti email</a>
</div>

<div class="auth-switch mt-2">
    Tidak menerima email?
    <form action="{{ route('password.send-otp') }}" method="POST" style="display:inline;">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <button type="submit" style="background:none;border:none;padding:0;color:#7CA982;font-weight:700;font-size:14px;cursor:pointer;">
            Kirim ulang
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const digits  = document.querySelectorAll('.otp-digit');
    const hidden  = document.getElementById('otp-value');
    const submit  = document.getElementById('otp-submit');

    digits.forEach((el, idx) => {
        el.addEventListener('input', () => {
            el.value = el.value.replace(/\D/g, '').slice(-1);
            if (el.value && idx < digits.length - 1) digits[idx + 1].focus();

            const full = [...digits].map(d => d.value).join('');
            hidden.value = full;
            submit.disabled = full.length < 6;
        });

        el.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !el.value && idx > 0) {
                digits[idx - 1].focus();
                digits[idx - 1].value = '';
            }
        });

        el.addEventListener('paste', e => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
            pasted.split('').slice(0, 6).forEach((char, i) => {
                if (digits[i]) digits[i].value = char;
            });
            const full = [...digits].map(d => d.value).join('');
            hidden.value = full;
            submit.disabled = full.length < 6;
            if (digits[Math.min(pasted.length, 5)]) digits[Math.min(pasted.length, 5)].focus();
        });
    });

    digits[0].focus();
});
</script>
@endsection
