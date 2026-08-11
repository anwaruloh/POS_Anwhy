<div class="sidebar d-flex flex-column p-3 text-white">
    
    <!-- Brand / Logo Aplikasi -->
    <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none px-2 py-3 mb-3 border-bottom border-secondary border-opacity-25">
        <span class="fs-4">🛒</span>
        <span class="fs-5 fw-bold tracking-wide">POS Kasir</span>
    </a>

    @auth
        @php
            $userRole = strtolower(Auth::user()->role->name ?? Auth::user()->role ?? '');
        @endphp
    @endauth

    <!-- Menu Navigasi Samping -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        
        {{-- 1. Beranda --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 fs-5"></i>
                <span>Beranda</span>
            </a>
        </li>

        @auth
            @if($userRole === 'admin')
                {{-- 2. Kelola User (Khusus Admin) --}}
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" 
                       class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill fs-5"></i>
                        <span>Kelola User</span>
                    </a>
                </li>

                {{-- 3. Data Produk (Khusus Admin) --}}
                <li class="nav-item">
                    <a href="{{ route('admin.produk.index') }}" 
                        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam fs-5"></i>
                        <span>Data Produk</span>
                    </a>
                </li>
            @endif
        @endauth

        {{-- 4. Transaksi POS --}}
        <li class="nav-item">
            <a href="{{ route('penjualan.create') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('penjualan.create') ? 'active' : '' }}">
                <i class="bi bi-calculator-fill fs-5"></i>
                <span>Transaksi (POS)</span>
            </a>
        </li>

        {{-- 5. Riwayat Penjualan --}}
        <li class="nav-item">
            <a href="{{ route('penjualan.index') }}" 
               class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('penjualan.index*') ? 'active' : '' }}">
                <i class="bi bi-receipt fs-5"></i>
                <span>Riwayat Penjualan</span>
            </a>
        </li>

        @auth
            @if($userRole === 'admin')
                {{-- 6. Suplier (Khusus Admin) --}}
                <li class="nav-item">
                    <a href="{{ route('suplier.index') }}" 
                        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('suplier.index*') ? 'active' : '' }}">
                        <i class="bi bi-truck fs-5"></i>
                        <span>Suplier</span>
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