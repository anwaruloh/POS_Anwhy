<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - pos ajril </title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 m-0">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                
                {{-- Card Login --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary mb-1">pos ajril</h3>
                        <p class="text-muted small">Silakan masuk ke akun Anda</p>
                    </div>

                    {{-- Alert Notifikasi jika ada error --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 text-center small" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('auth') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">Email address</label>
                            <input type="email" name="email" id="email" class="form-control bg-light border-0 py-2" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                            <input type="password" name="password" id="password" class="form-control bg-light border-0 py-2" required>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small text-muted" for="remember">
                                Ingat Saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3">
                            Login
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">&copy; {{ date('Y') }} POS Ajril - All Rights Reserved</small>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>