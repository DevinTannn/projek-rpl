<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Donasi - {{ $donation->order_id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1A2F28;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .container {
            width: 100%;
            height: 100%;
            padding: 60px;
            box-sizing: border-box;
            border: 25px solid #1A2F28;
            position: relative;
        }
        .inner-border {
            width: 100%;
            height: 100%;
            border: 2px solid #D4AF37;
            padding: 40px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 52px;
            margin: 0;
            color: #1A2F28;
            letter-spacing: 8px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 16px;
            color: #D4AF37;
            font-weight: bold;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        .certificate-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #1A2F28;
            margin: 30px 0;
            text-transform: uppercase;
            border-bottom: 2px solid #D4AF37;
            display: inline-block;
            padding-bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            position: relative;
        }
        .content {
            text-align: center;
            margin-top: 30px;
        }
        .proudly {
            font-style: italic;
            font-size: 22px;
            margin-bottom: 15px;
            color: #666;
        }
        .name {
            font-size: 42px;
            font-weight: bold;
            color: #1A2F28;
            margin-bottom: 25px;
            font-family: 'Georgia', serif;
        }
        .details {
            font-size: 19px;
            line-height: 1.8;
            width: 85%;
            margin: 0 auto 30px;
            color: #333;
        }
        .amount-box {
            font-size: 26px;
            font-weight: bold;
            color: #ffffff;
            background: #1A2F28;
            display: inline-block;
            padding: 15px 40px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .footer-table {
            width: 100%;
            border: none;
        }
        .signature {
            text-align: center;
        }
        .signature-line {
            width: 220px;
            border-bottom: 1px solid #1A2F28;
            margin: 0 auto 10px;
        }
        .order-id {
            position: absolute;
            bottom: 40px;
            right: 40px;
            font-size: 11px;
            color: #999;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            opacity: 0.03;
            color: #1A2F28;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner-border">
            <div class="watermark">SUMSELPEDULI</div>
            
            <div class="header">
                <h1>SUMSELPEDULI</h1>
                <p>KEMANUSIAAN &bull; TRANSPARANSI &bull; KEBERKAHAN</p>
            </div>

            <div class="certificate-title">SERTIFIKAT PENGHARGAAN</div>

            <div class="content">
                <div class="proudly">Dengan apresiasi tulus, kami berikan kepada:</div>
                <div class="name">{{ strtoupper($donation->user->username) }}</div>
                
                <div class="details">
                    Atas kontribusi tulus dan kepedulian Anda dalam menyukseskan kampanye:<br>
                    <span style="color: #1A2F28; font-weight: bold;">"{{ $donation->campaign->title }}"</span><br><br>
                    Donasi Anda telah kami terima dan akan disalurkan sepenuhnya untuk membawa perubahan nyata bagi mereka yang membutuhkan di wilayah Sumatera Selatan.
                </div>

                <div class="amount-box">
                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                </div>
            </div>

            <div class="footer">
                <table class="footer-table">
                    <tr>
                        <td width="33%"></td>
                        <td width="33%" class="signature">
                            <div class="signature-line"></div>
                            <strong style="color: #1A2F28;">YAYASAN SUMSEL PEDULI</strong><br>
                            <span style="font-size: 13px; color: #666;">Diterbitkan pada: {{ date('d F Y') }}</span>
                        </td>
                        <td width="33%"></td>
                    </tr>
                </table>
            </div>

            <div class="order-id">ID Transaksi Resmi: {{ $donation->order_id }}</div>
        </div>
    </div>
</body>
</html>
