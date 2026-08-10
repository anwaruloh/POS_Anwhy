@extends('layout.app')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="container-fluid py-2">

    <!-- Hero Header Component -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="card-body p-4 text-white position-relative">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative z-1">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fs-7">
                            <i class="bi bi-clock-history me-1"></i> Transaksi Penjualan
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1">Riwayat Penjualan</h3>
                    <p class="text-white-50 small mb-0">Pantau seluruh histori transaksi, status faktur, dan rekapan pembayaran toko Anda.</p>
                </div>
                <div>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-lg px-4 rounded-3 fw-semibold shadow-sm fs-6 d-inline-flex align-items-center gap-2 transition-all">
                        <i class="bi bi-plus-circle-fill"></i> Transaksi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card Container -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">

            <!-- Alert Notifikasi -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 bg-success-subtle text-success-emphasis mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Toolbar & Filter -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <form action="{{ route('penjualan.index') }}" method="GET" class="w-100" style="max-width: 380px;">
                    <div class="input-group input-group-flat border rounded-3 overflow-hidden bg-light">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-transparent border-0 ps-2 shadow-none" placeholder="Cari ID TRX atau Kasir..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('penjualan.index') }}" class="btn btn-transparent border-0 text-muted pe-3 d-flex align-items-center">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <div class="text-muted small">
                    Menampilkan <span class="fw-bold text-dark">{{ $sales->count() }}</span> dari <span class="fw-bold text-dark">{{ $sales->total() }}</span> transaksi
                </div>
            </div>

            <!-- Tabel Data Penjualan -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted text-uppercase fs-7 border-bottom">
                        <tr>
                            <th class="py-3 px-3 text-center" style="width: 5%">#</th>
                            <th class="py-3">No. Faktur / Tanggal</th>
                            <th class="py-3">Kasir</th>
                            <th class="py-3 text-center">Metode</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end">Total Bayar</th>
                            <th class="py-3 text-center px-3" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($sales as $index => $sale)
                            <tr>
                                <td class="text-center text-muted fw-medium fs-7">
                                    {{ $sales->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="bi bi-receipt fs-6"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block">TRX-{{ $sale->id }}</span>
                                            <span class="text-muted fs-7">
                                                <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($sale->created_at)->translatedFormat('d M Y, H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2 bg-light border rounded-pill px-2.5 py-1">
                                        <i class="bi bi-person-circle text-secondary fs-7"></i>
                                        <span class="fw-medium text-dark fs-7">{{ $sale->user->name ?? 'Kasir' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1.5 rounded-2 fw-semibold fs-7">
                                        <i class="bi bi-credit-card-2-front me-1"></i>{{ strtoupper($sale->metode_pembayaran ?? 'CASH') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($sale->status === 'COMPLETED')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-medium fs-7">
                                            ● Selesai
                                        </span>
                                    @elseif(in_array($sale->status, ['DRAFT', 'OPEN']))
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fw-medium fs-7">
                                            ● Draft
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1.5 rounded-pill fw-medium fs-7">
                                            {{ $sale->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-success fs-6">
                                        Rp {{ number_format($sale->total_pembayaran ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center px-3">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('penjualan.show', $sale->id) }}" 
                                           class="btn btn-icon btn-light text-primary border-0 rounded-3" 
                                           data-bs-toggle="tooltip" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        {{-- Jika Status Draft / Open --}}
                                        @if(in_array($sale->status, ['DRAFT', 'OPEN']))
                                            <a href="{{ route('penjualan.edit', $sale->id) }}" 
                                               class="btn btn-icon btn-light text-warning border-0 rounded-3" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit / Lanjutkan">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form method="POST" action="{{ route('penjualan.destroy', $sale->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus draft transaksi TRX-{{ $sale->id }} ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-icon btn-light text-danger border-0 rounded-3" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Hapus Draft">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                        <p class="mb-0 fw-medium">Belum ada riwayat transaksi penjualan.</p>
                                        <small class="text-muted">Transaksi baru yang berhasil diproses akan muncul di sini.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            @if($sales->hasPages())
                <div class="d-flex justify-content-between align-items-center pt-4 border-top mt-3">
                    <div class="text-muted small">
                        Halaman {{ $sales->currentPage() }} dari {{ $sales->lastPage() }}
                    </div>
                    <div>
                        {{ $sales->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    .fs-7 { font-size: 0.825rem; }
    .btn-icon {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
    }
    .btn-icon:hover {
        background-color: #f9fbfd !important;
        transform: translateY(-2px);
    }
    .table > :not(caption) > * > * {
        padding: 0.9rem 0.75rem;
    }
</style>
@endsection