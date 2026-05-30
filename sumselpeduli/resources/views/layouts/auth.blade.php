<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SumselPeduli') }} - Authentication</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary-color: #243E36;
            --secondary-color: #7CA982;
            --accent-color: #C2A83E;
            --bg-color: #F1F7ED;
            --surface-color: #E0EEC6;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 20% 20%, rgba(124, 169, 130, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 80% 80%, rgba(194, 168, 62, 0.05) 0%, transparent 40%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background-color: white;
            border-radius: 32px;
            box-shadow: 0 20px 60px rgba(36, 62, 54, 0.08);
            width: 100%;
            max-width: 480px;
            padding: 50px 40px;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 25px rgba(36, 62, 54, 0.2);
            transform: rotate(-10deg);
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 18px;
            background-color: #f8faf9;
            border: 2px solid transparent;
            transition: all 0.2s;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--secondary-color);
            box-shadow: 0 8px 15px rgba(124, 169, 130, 0.1);
        }

        .btn-auth {
            background-color: var(--primary-color);
            color: white;
            border-radius: 16px;
            padding: 14px;
            font-weight: 700;
            border: none;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-auth:hover {
            background-color: #2c4a40;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(36, 62, 54, 0.2);
            color: white;
        }

        .auth-switch {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #666;
        }

        .auth-switch a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="brand-logo">
            <i data-lucide="heart" class="text-white" style="fill: var(--accent-color); width: 32px; height: 32px;"></i>
        </div>
        
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
