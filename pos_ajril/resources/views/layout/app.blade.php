<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS System')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (Opsional untuk icon) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Styling Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #1e293b; /* Warna Gelap Modern (Slate) */
            flex-shrink: 0;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #0d6efd; /* Warna Primary Bootstrap */
        }

        /* Area Konten Utama */
        .main-content {
            flex-grow: 1;
            min-height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<div class="d-flex">

    <!-- 1. Include Sidebar -->
    @include('layout.sidebar')

    <!-- 2. Main Content (Otomatis menyesuaikan sisa ruang di kanan) -->
    <div class="main-content p-4">

        <!-- Top Header Sederhana (Opsional: Info Profil / User) -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom bg-white p-3 rounded-3 shadow-sm">
            <h5 class="fw-bold mb-0 text-secondary">@yield('title', 'Dashboard')</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                    👤 {{ Auth::user()->name ?? 'Kasir' }} ({{ ucfirst(Auth::user()->role->name ?? 'User') }})
                </span>
            </div>
        </div>

        <!-- Halaman View (POS / Index / Detail) akan dirender di sini -->
        @yield('content')

    </div>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>