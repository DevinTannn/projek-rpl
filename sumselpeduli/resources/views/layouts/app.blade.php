<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SumselPeduli') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #243E36;
            --secondary-color: #7CA982;
            --accent-color: #C2A83E;
            --bg-color: #F1F7ED;
            --surface-color: #E0EEC6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-color);
        }

        .navbar {
            background-color: var(--primary-color) !important;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .btn-accent:hover {
            background-color: #a89235;
            border-color: #a89235;
            color: white;
        }

        .card {
            background-color: var(--surface-color);
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(194, 168, 62, 0.25);
        }

        .text-primary-custom {
            color: var(--primary-color);
        }

        .text-accent-custom {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">SUMSELPEDULI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-link text-white me-3">Hi, {{ Auth::user()->username }}</li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link ms-lg-2 btn btn-accent px-3" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
