<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-4 mb-4 p-2">
  <div class="container-fluid px-3">
    
    {{-- Brand Logo / Name --}}
    <a class="navbar-brand fw-bold text-primary fs-4 me-4" href="{{ route('dashboard') }}">
      POS
    </a>

    {{-- Toggle Mobile Button --}}
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      
      {{-- Menu Navigasi Kiri --}}
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
        
        {{-- Menu Dashboard --}}
        <li class="nav-item">
          <a class="nav-link px-3 rounded-3 {{ request()->routeIs('dashboard') ? 'active fw-bold text-primary bg-primary-subtle' : 'text-secondary' }}" 
             href="{{ route('dashboard') }}">
            Dashboard
          </a>
        </li>
        
        {{-- Ambil Role User dengan Aman --}}
        @php
            $userRole = auth()->check() ? strtolower(is_object(auth()->user()->role) ? auth()->user()->role->name : auth()->user()->role) : '';
        @endphp

        {{-- Menu Users (Khusus Admin) --}}
        @if($userRole === 'admin')
        <li class="nav-item">
          <a class="nav-link px-3 rounded-3 {{ request()->routeIs('admin.users*') ? 'active fw-bold text-primary bg-primary-subtle' : 'text-secondary' }}" 
             href="{{ route('admin.users') }}">
            Users
          </a>
        </li>
        @endif

        {{-- Menu Produk (Admin & Kasir) --}}
        @if(in_array($userRole, ['admin', 'kasir']))
        <li class="nav-item">
          <a class="nav-link px-3 rounded-3 {{ request()->routeIs('produk.*') ? 'active fw-bold text-primary bg-primary-subtle' : 'text-secondary' }}" 
             href="{{ route('produk.index') }}">
            Produk
          </a>
        </li>
        @endif

        {{-- Menu Suplier) --}}
        @if(in_array($userRole, ['admin', 'kasir']))
        <li class="nav-item">
          <a class="nav-link px-3 rounded-3 {{ request()->routeIs('produk.*') ? 'active fw-bold text-primary bg-primary-subtle' : 'text-secondary' }}" 
             href="{{ route('produk.index') }}">
            Suplier
          </a>
        </li>
        @endif

        {{-- Menu Penjualan (Admin & Kasir) --}}
        @if(in_array($userRole, ['admin', 'kasir']))
        <li class="nav-item">
          <a class="nav-link px-3 rounded-3 {{ request()->routeIs('penjualan.*') ? 'active fw-bold text-primary bg-primary-subtle' : 'text-secondary' }}" 
             href="{{ route('penjualan.index') }}">
            Penjualan
          </a>
        </li>
        @endif

      </ul>

      {{-- Bagian Kanan: User Info & Logout --}}
      @auth
      <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 border-top pt-2 pt-lg-0 border-lg-0">
        <div class="text-end d-none d-sm-block">
          <div class="fw-bold text-dark mb-0 leading-none" style="font-size: 0.9rem;">
            {{ auth()->user()->name }}
          </div>
          <small class="text-muted text-capitalize" style="font-size: 0.75rem;">
            Role: {{ $userRole }}
          </small>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-3 fw-semibold">
            Logout
          </button>
        </form>
      </div>
      @endauth

    </div>
  </div>
</nav>