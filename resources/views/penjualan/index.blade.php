@extends('layout.app')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        {{-- Header & Tombol Transaksi Baru --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Riwayat Penjualan</h4>
                <p class="text-muted small mb-0">Daftar rekapan transaksi penjualan di toko.</p>
            </div>
            <div>
                <a href="{{ route('penjualan.create') }}" class="btn btn-success px-3 rounded-3 fw-semibold shadow-sm">
                    + Transaksi Baru
                </a>
            </div>
        </div>

        {{-- Form Pencarian Kasir / Penjual --}}
        <form action="{{ route('penjualan.index') }}" method="GET" class="mb-4">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="search" class="form-control rounded-start-3" placeholder="Cari nama kasir..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary px-3 rounded-end-3" type="submit">Cari</button>
            </div>
        </form>

        {{-- Alert Notifikasi Notif Success/Error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabel Data Penjualan --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>NO. FAKTUR / TANGGAL</th>
                        <th>PENJUAL</th>
                        <th class="text-center">METODE</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-end">TOTAL BAYAR</th>
                        <th class="text-center" style="width: 18%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $index => $sale)
                        <tr>
                            <td>{{ $sales->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark">TRX-{{ $sale->id }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($sale->created_at)->translatedFormat('d M Y, H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                    {{ $sale->user->name ?? 'Kasir' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                    {{ $sale->metode_pembayaran ?? 'CASH' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($sale->status === 'COMPLETED')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                        Selesai
                                    </span>
                                @elseif($sale->status === 'DRAFT' || $sale->status === 'OPEN')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">
                                        Draft
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                                        {{ $sale->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-primary">
                                Rp {{ number_format($sale->total_pembayaran ?? 0, 0, ',', '.') }}
                            </td>
                           <td class="text-center">
    {{-- Menggunakan d-flex & gap-1.5 agar tombol terpisah dengan rapi --}}
    <div class="d-flex justify-content-center align-items-center gap-2">
        
        {{-- Tombol Detail --}}
        <a href="{{ route('penjualan.show', $sale->id) }}" 
           class="btn btn-sm btn-outline-primary rounded-2 px-2.5 py-1" 
           title="Lihat Detail">
            👁️ Detail
        </a>

        {{-- Jika Status Draft / Open, tampilkan Tombol Edit & Hapus --}}
        @if(in_array($sale->status, ['DRAFT', 'OPEN']))
            <a href="{{ route('penjualan.edit', $sale->id) }}" 
               class="btn btn-sm btn-outline-warning text-dark rounded-2 px-2.5 py-1" 
               title="Edit / Lanjutkan">
                ✏️ Edit
            </a>

            <form method="POST" action="{{ route('penjualan.destroy', $sale->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus draft transaksi TRX-{{ $sale->id }} ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn btn-sm btn-outline-danger rounded-2 px-2 py-1" 
                        title="Hapus Draft">
                    🗑️
                </button>
            </form>
        @endif

    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <small>Belum ada riwayat transaksi penjualan.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $sales->links() }}
        </div>

    </div>
</div>
@endsection