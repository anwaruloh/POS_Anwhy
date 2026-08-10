@extends('layout.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="container-fluid py-2">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Daftar Produk</h3>
            <p class="text-muted mb-0 small">Kelola katalog produk, penyesuaian harga, dan kontrol stok barang secara akurat.</p>
        </div>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Produk Baru
        </a>
    </div>

    <!-- Card Container -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            
            <!-- Toolbar: Search Bar -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mb-4">
                <form action="{{ route('admin.produk.index') }}" method="GET" class="w-100" style="max-width: 360px;">
                    <div class="input-group input-group-flat border rounded-3 overflow-hidden bg-light">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-transparent border-0 ps-2 shadow-none" placeholder="Cari nama produk..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('admin.produk.index') }}" class="btn btn-transparent border-0 text-muted pe-3 d-flex align-items-center">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </div>
                </form>
                
                <div class="text-muted small">
                    Menampilkan <span class="fw-bold text-dark">{{ $produks->count() }}</span> dari <span class="fw-bold text-dark">{{ $produks->total() }}</span> produk
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-3 text-center" style="width: 50px;">#</th>
                            <th class="py-3" style="width: 80px;">Foto</th>
                            <th class="py-3">Detail Produk</th>
                            <th class="py-3">Harga Beli</th>
                            <th class="py-3">Harga Jual</th>
                            <th class="py-3 text-center">Status Stok</th>
                            <th class="py-3 text-end px-3" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($produks as $index => $produk)
                            <tr>
                                <td class="text-center text-muted fw-medium fs-7">
                                    {{ $produks->firstItem() + $index }}
                                </td>
                                <td>
                                    @if($produk->foto)
                                        <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="rounded-3 object-fit-cover shadow-sm" width="48" height="48">
                                    @else
                                        <div class="bg-secondary-subtle text-secondary rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                                            <i class="bi bi-image fs-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block fw-bold text-dark mb-0 fs-6">{{ $produk->nama }}</span>
                                    <span class="text-muted fs-7">ID: #{{ $produk->id }}</span>
                                </td>
                                <td>
                                    <span class="text-secondary fw-medium">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    @if($produk->stok == 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill fw-medium">
                                            ● Habis
                                        </span>
                                    @elseif($produk->stok < 5)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill fw-medium">
                                            ● Kritis ({{ $produk->stok }})
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fw-medium">
                                            {{ $produk->stok }} Pcs
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end px-3">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-icon btn-light text-warning border-0 rounded-3" data-bs-toggle="tooltip" title="Edit Produk">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-light text-danger border-0 rounded-3" data-bs-toggle="tooltip" title="Hapus Produk">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                        <p class="mb-0 fw-medium">Data produk tidak ditemukan.</p>
                                        <small class="text-muted">Coba ubah kata kunci pencarian Anda.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination Container -->
            @if($produks->hasPages())
                <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-3">
                    <div class="text-muted small">
                        Halaman {{ $produks->currentPage() }} dari {{ $produks->lastPage() }}
                    </div>
                    <div>
                        {{ $produks->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Tambahkan sedikit CSS Khusus jika belum ada di file CSS utama -->
<style>
    .fs-7 { font-size: 0.825rem; }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
    }
    .btn-icon:hover {
        background-color: #707f8e !important;
        transform: translateY(-1px);
    }
    .table > :not(caption) > * > * {
        padding: 0.85rem 0.75rem;
    }
</style>
@endsection