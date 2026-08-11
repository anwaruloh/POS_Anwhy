@extends('layout.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        {{-- Header Detail --}}
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Detail Transaksi #TRX-{{ $penjualan->id }}</h4>
                <p class="text-muted small mb-0">
                    Tanggal: {{ \Carbon\Carbon::parse($penjualan->created_at)->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>
            <div>
                <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary px-3 rounded-3 fw-semibold">
                    &larr; Kembali
                </a>
            </div>
        </div>

        {{-- Info Ringkasan --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block mb-1">Penjual / Kasir</small>
                    <span class="fw-bold text-dark">{{ $penjualan->user->name ?? 'Kasir' }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 fw-bold">
                        {{ $penjualan->metode_pembayaran ?? 'CASH' }}
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded-3">
                    <small class="text-muted d-block mb-1">Status Transaksi</small>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold">
                        {{ $penjualan->status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Tabel Item Produk --}}
        <h5 class="fw-bold mb-3 text-dark">Daftar Produk Dibeli</h5>
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>NAMA PRODUK</th>
                        <th class="text-end">HARGA SATUAN</th>
                        <th class="text-center">QTY</th>
                        <th class="text-end">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penjualan->itemPenjualan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $item->produk->nama ?? 'Produk Dihapus' }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga ?? ($item->subtotal / max($item->kuantitas, 1)), 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->kuantitas }}</td>
                            <td class="text-end fw-semibold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada item transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end fs-5 fw-bold">TOTAL PEMBAYARAN:</th>
                        <th class="text-end fs-5 fw-bold text-primary">
                            Rp {{ number_format($penjualan->total_pembayaran ?? 0, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
@endsection