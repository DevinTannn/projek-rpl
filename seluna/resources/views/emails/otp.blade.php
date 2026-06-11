<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP – SELUNA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #F1F7ED; font-family: 'Segoe UI', Arial, sans-serif; padding: 40px 20px; }
        .wrapper { max-width: 520px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 20px rgba(36,62,54,0.08); }
        .header { background-color: #243E36; padding: 36px 40px; text-align: center; }
        .header img { width: 56px; height: 56px; object-fit: contain; margin-bottom: 12px; }
        .header h1 { color: #C2A83E; font-size: 26px; font-weight: 800; letter-spacing: 3px; }
        .header p { color: rgba(255,255,255,0.6); font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-top: 4px; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #374151; margin-bottom: 16px; }
        .greeting strong { color: #243E36; }
        .desc { font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 32px; }
        .otp-box { background: #F1F7ED; border: 2px dashed #7CA982; border-radius: 16px; text-align: center; padding: 28px; margin-bottom: 32px; }
        .otp-label { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #7CA982; font-weight: 700; margin-bottom: 12px; }
        .otp-code { font-size: 48px; font-weight: 900; letter-spacing: 10px; color: #243E36; font-family: 'Courier New', monospace; }
        .otp-expiry { font-size: 12px; color: #9ca3af; margin-top: 10px; }
        .warn { background: #FEF3C7; border-left: 4px solid #C2A83E; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #92400E; margin-bottom: 32px; }
        .footer { border-top: 1px solid #f3f4f6; padding-top: 24px; font-size: 12px; color: #9ca3af; text-align: center; line-height: 1.8; }
        .footer strong { color: #243E36; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>SELUNA</h1>
                <p>Platform Donasi Terpercaya</p>
            </div>
            <div class="body">
                <p class="greeting">Halo, <strong>{{ $username }}</strong> 👋</p>
                <p class="desc">
                    Kami menerima permintaan reset password untuk akun Anda. Gunakan kode OTP berikut untuk melanjutkan proses.
                    Kode ini hanya berlaku selama <strong>10 menit</strong>.
                </p>

                <div class="otp-box">
                    <div class="otp-label">Kode OTP Anda</div>
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="otp-expiry">Berlaku selama 10 menit</div>
                </div>

                <div class="warn">
                    ⚠️ Jangan bagikan kode ini kepada siapapun. Tim SELUNA tidak pernah meminta kode OTP Anda.
                </div>

                <div class="footer">
                    Jika Anda tidak meminta reset password, abaikan email ini.<br>
                    Akun Anda tetap aman.<br><br>
                    Salam hangat,<br>
                    <strong>Tim SELUNA</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
