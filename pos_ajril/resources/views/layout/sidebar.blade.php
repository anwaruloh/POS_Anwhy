<div class="sidebar d-flex flex-column p-3 text-white">
    
    <!-- Brand / Logo Aplikasi -->
    <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none px-2 py-3 mb-3 border-bottom border-secondary border-opacity-25">
        <span class="fs-4">🛒</span>
        <span class="fs-5 fw-bold tracking-wide">POS Kasir</span>
    </a>

    <!-- Menu Navigasi Samping -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        
        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Transaksi POS --}}
        <li class="nav-item">
            <a href="{{ route('penjualan.create') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('penjualan.create') ? 'active' : '' }}">
                <i class="bi bi-calculator-fill fs-5"></i>
                <span>Transaksi (POS)</span>
            </a>
        </li>

        {{-- Riwayat Penjualan --}}
        <li class="nav-item">
            <a href="{{ route('penjualan.index') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('penjualan.index*') ? 'active' : '' }}">
                <i class="bi bi-receipt fs-5"></i>
                <span>Riwayat Penjualan</span>
            </a>
        </li>

        {{-- Data Produk (Hanya Tampil Jika User Login Adalah Admin) --}}
@auth
    @php
        $userRole = strtolower(Auth::user()->role->name ?? Auth::user()->role ?? '');
    @endphp

    @if($userRole === 'admin')
        <li class="nav-item">
            <a href="{{ route('admin.produk.index') }}" 
                class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam fs-5"></i>
                <span>Data Produk</span>
            </a>
        </li>
    @endif
@endauth
     {{-- Kelola User (Hanya Tampil Jika User Login Adalah Admin) --}}
@auth
    @php
        // Ambil nama role, baik dari relasi ($user->role->name) maupun kolom biasa ($user->role)
        $userRole = strtolower(Auth::user()->role->name ?? Auth::user()->role ?? '');
    @endphp

    @if($userRole === 'admin')
        <li class="nav-item">
            <a href="{{ route('admin.users') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-people-fill fs-5"></i>
                <span>Kelola User</span>
            </a>
        </li>
    @endif
@endauth

    </ul>

    <!-- Footer Sidebar / Tombol Logout -->
    <div class="pt-3 border-top border-secondary border-opacity-25">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 fw-semibold">
                🚪 Logout
            </button>
        </form>
    </div>

</div>