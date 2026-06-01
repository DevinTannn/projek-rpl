<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Donasi - {{ $donation->order_id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #243E36;
            margin: 0;
            padding: 0;
            background: #F1F7ED;
        }
        .container {
            width: 100%;
            height: 100%;
            padding: 50px;
            box-sizing: border-box;
            border: 20px solid #7CA982;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            font-size: 48px;
            margin: 0;
            color: #243E36;
            letter-spacing: 5px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 18px;
            color: #7CA982;
            margin-top: 10px;
        }
        .content {
            text-align: center;
            margin-top: 50px;
        }
        .content .proudly {
            font-style: italic;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .content .name {
            font-size: 36px;
            font-weight: bold;
            color: #C2A83E;
            text-decoration: underline;
            margin-bottom: 30px;
        }
        .content .details {
            font-size: 18px;
            line-height: 1.6;
            width: 80%;
            margin: 0 auto 40px;
        }
        .content .amount {
            font-size: 24px;
            font-weight: bold;
            color: #243E36;
            background: #E0EEC6;
            display: inline-block;
            padding: 10px 30px;
            border-radius: 50px;
            margin-bottom: 40px;
        }
        .footer {
            position: absolute;
            bottom: 80px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .signature {
            margin-bottom: 20px;
        }
        .signature-line {
            width: 200px;
            border-bottom: 2px solid #243E36;
            margin: 0 auto 10px;
        }
        .order-id {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 10px;
            color: #aaa;
        }
        .decorative-heart {
            color: #dc3545;
            font-size: 40px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SumselPeduli</h1>
            <p>Penyalur Kebaikan untuk Sumatera Selatan</p>
        </div>

        <div class="content">
            <div class="decorative-heart">&hearts;</div>
            <div class="proudly">Sertifikat Penghargaan Diberikan Kepada:</div>
            <div class="name">{{ $donation->user->username }}</div>
            
            <div class="details">
                Terima kasih atas kontribusi tulus Anda dalam mendukung kampanye:<br>
                <strong>"{{ $donation->campaign->title }}"</strong><br><br>
                Donasi Anda membantu kami mewujudkan perubahan positif bagi mereka yang membutuhkan. 
                Semoga kebaikan Anda dibalas dengan keberkahan yang melimpah.
            </div>

            <div class="amount">
                Total Donasi: Rp {{ number_format($donation->amount, 0, ',', '.') }}
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                <div class="signature-line"></div>
                <strong>Team SumselPeduli</strong>
                <p style="font-size: 12px; margin-top: 5px;">{{ date('d F Y') }}</p>
            </div>
        </div>

        <div class="order-id">ID Transaksi: {{ $donation->order_id }}</div>
    </div>
</body>
</html>
