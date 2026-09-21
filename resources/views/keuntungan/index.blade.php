@extends('layout.app')

@section('title', 'Riwayat Keuntungan')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Riwayat Keuntungan</h4>
                <p class="text-muted small mb-0">Daftar rekapan keuntungan penjualan di toko.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>NO. FAKTUR / TANGGAL</th>
                        <th class="text-end">TOTAL BAYAR</th>
                        <th class="text-end">TOTAL HPP</th>
                        <th class="text-end">KEUNTUNGAN</th>
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
                            <td class="text-end fw-bold text-primary">
                                Rp {{ number_format($sale->total_pembayaran ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold text-warning">
                                Rp {{ number_format($sale->total_hpp ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($sale->keuntungan ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada transaksi selesai yang bisa dihitung keuntungannya.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sales->hasPages())
            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection