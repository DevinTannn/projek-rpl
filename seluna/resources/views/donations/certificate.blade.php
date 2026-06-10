<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Donasi - {{ $donation->order_id }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'Georgia', serif;
            color: #1A2F28;
            margin: 0;
            padding: 0;
            background: #ffffff;
            width: 297mm;
            height: 210mm;
            box-sizing: border-box;
        }
        .certificate-wrapper {
            position: relative;
            width: 297mm;
            height: 210mm;
            padding: 20mm;
            box-sizing: border-box;
            background-color: #faf8f5;
        }
        .outer-border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 8px double #1A2F28;
            box-sizing: border-box;
        }
        .inner-border {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            border: 2px solid #D4AF37;
            box-sizing: border-box;
        }
        .content {
            text-align: center;
            padding: 10mm 15mm;
        }
        .logo {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 24px;
            font-weight: bold;
            color: #1A2F28;
            letter-spacing: 6px;
            margin-bottom: 5px;
        }
        .tagline {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #D4AF37;
            letter-spacing: 3px;
            margin-bottom: 25px;
        }
        .cert-title {
            font-family: 'Georgia', serif;
            font-size: 32px;
            font-weight: normal;
            color: #1A2F28;
            letter-spacing: 4px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .award-to {
            font-family: 'Georgia', serif;
            font-style: italic;
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }
        .name {
            font-family: 'Georgia', serif;
            font-size: 36px;
            font-weight: bold;
            color: #1A2F28;
            border-bottom: 1px solid #D4AF37;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        .description {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #4a4a4a;
            max-width: 180mm;
            margin: 0 auto 25px;
        }
        .campaign-title {
            color: #1A2F28;
            font-weight: bold;
            font-style: italic;
        }
        .amount-box {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            background: #1A2F28;
            display: inline-block;
            padding: 10px 30px;
            border-radius: 4px;
            margin-bottom: 25px;
        }
        .footer-section {
            position: absolute;
            bottom: 25mm;
            left: 25mm;
            right: 25mm;
        }
        .footer-table {
            width: 100%;
        }
        .footer-table td {
            vertical-align: bottom;
        }
        .signature-line {
            width: 180px;
            border-bottom: 1px solid #1A2F28;
            margin: 0 auto 8px;
        }
        .signer-title {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            font-weight: bold;
            color: #1A2F28;
        }
        .date {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #666;
            margin-top: 4px;
        }
        .transaction-id {
            position: absolute;
            bottom: 18mm;
            left: 25mm;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="outer-border"></div>
        <div class="inner-border"></div>
        
        <div class="content">
            <div class="logo">SUMSELPEDULI</div>
            <div class="tagline">KEMANUSIAAN &bull; TRANSPARANSI &bull; KEBERKAHAN</div>
            
            <div class="cert-title">Sertifikat Penghargaan</div>
            
            <div class="award-to">Dengan apresiasi setinggi-tingginya, penghargaan ini diberikan kepada:</div>
            
            <div class="name">{{ strtoupper($donation->user->username) }}</div>
            
            <div class="description">
                Atas kebaikan hati, kedermawanan, dan kontribusi nyata dalam mendukung program sosial melalui kampanye:<br>
                <span class="campaign-title">"{{ $donation->campaign->title }}"</span><br><br>
                Donasi Anda telah resmi diterima dan disalurkan sepenuhnya untuk membantu sesama serta memberikan harapan baru bagi mereka yang membutuhkan di wilayah Sumatera Selatan.
            </div>
            
            <div class="amount-box">
                Rp {{ number_format($donation->amount, 0, ',', '.') }}
            </div>
        </div>
        
        <div class="footer-section">
            <table class="footer-table">
                <tr>
                    <td width="50%" style="text-align: left; vertical-align: bottom;">
                        <span class="signer-title">SUMSELPEDULI Foundation</span><br>
                        <span class="date">Diterbitkan pada: {{ date('d F Y') }}</span>
                    </td>
                    <td width="50%" style="text-align: right; vertical-align: bottom;">
                        <div class="signature-line" style="margin-right: 0;"></div>
                        <span class="signer-title">YAYASAN SUMSEL PEDULI</span>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="transaction-id">ID Transaksi Resmi: {{ $donation->order_id }}</div>
    </div>
</body>
</html>
