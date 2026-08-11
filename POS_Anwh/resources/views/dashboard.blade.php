@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
@php
    $userRole = strtolower(Auth::user()->role->name ?? Auth::user()->role ?? '');
@endphp

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Dashboard Ringkasan</h4>
        <span class="text-muted small">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</span>
    </div>

    {{-- ================================================================= --}}
    {{-- 1. TAMPILAN KHUSUS ADMIN (TETAP SEPERTI SEMULA / LENGKAP)         --}}
    {{-- ================================================================= --}}
    @if($userRole === 'admin')
        
        {{-- Card Stats 4 Kolom --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                    <small class="text-muted fw-bold">TOTAL PENDAPATAN</small>
                    <h4 class="fw-bold text-success mt-1 mb-0">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                    <small class="text-muted fw-bold">TRANSAKSI SELESAI</small>
                    <h4 class="fw-bold text-primary mt-1 mb-0">{{ $totalTransaksi ?? 0 }} Transaksi</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                    <small class="text-muted fw-bold">STOK MENIPIS (<= 5)</small>
                    <h4 class="fw-bold text-warning mt-1 mb-0">{{ count($stokMenipis ?? []) }} Produk</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 rounded-3 bg-white">
                    <small class="text-muted fw-bold">STOK HABIS</small>
                    <h4 class="fw-bold text-danger mt-1 mb-0">{{ count($stokHabis ?? []) }} Produk</h4>
                </div>
            </div>
        </div>

        {{-- Tabel Produk Terlaris & Perhatian Stok --}}
        <div class="row g-3">
            <div class="col-md-7">
                <div class="card border-0 shadow-sm p-3 rounded-3 bg-white h-100">
                    <h6 class="fw-bold mb-3">🔥 Top 5 Produk Terlaris</h6>
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NAMA PRODUK</th>
                                <th class="text-center">TERJUAL</th>
                                <th class="text-end">TOTAL OMSET</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produkTerlaris ?? [] as $item)
    <tr>
        {{-- Panggil relasi produk lalu ke atribut 'nama' --}}
        <td class="fw-semibold">
    {{ $item->nama ?? 'Produk Tidak Ditemukan' }}
</td>
        
        <td class="text-center">
            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                {{ $item->total_terjual }} pcs
            </span>
        </td>
        
        <td class="text-end fw-bold">
            Rp {{ number_format($item->total_pendapatan ?? 0, 0, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center text-muted">Belum ada data penjualan.</td>
    </tr>
@endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-5">
    <div class="card border-0 shadow-sm p-3 rounded-3 bg-white h-100">
        <h6 class="fw-bold mb-3">⚠️ Perhatian Stok Produk</h6>
        @if(count($stokMenipis ?? []) == 0 && count($stokHabis ?? []) == 0)
            <p class="text-muted text-center my-auto">Semua stok produk masih aman.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach($stokHabis ?? [] as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        {{-- DIUBAH: dari $item->nama_produk menjadi $item->nama --}}
                        <span>{{ $item->nama }}</span>
                        <span class="badge bg-danger rounded-pill">Habis (0)</span>
                    </li>
                @endforeach
                @foreach($stokMenipis ?? [] as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        {{-- DIUBAH: dari $item->nama_produk menjadi $item->nama --}}
                        <span>{{ $item->nama }}</span>
                        <span class="badge bg-warning text-dark rounded-pill">Sisa {{ $item->stok }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
    {{-- ================================================================= --}}
    {{-- 2. TAMPILAN KHUSUS KASIR (HANYA INFORMASI STOK MENIPIS & HABIS)  --}}
    {{-- ================================================================= --}}
    @else

        <div class="row g-3">
            {{-- Card Stok Menipis --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                    <div class="card-header bg-warning bg-opacity-10 border-0 p-3">
                        <h6 class="fw-bold text-warning-emphasis mb-0">⚠️ Stok Menipis (<= 5)</h6>
                    </div>
                    <div class="card-body p-3">
                        @forelse($stokMenipis ?? [] as $item)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $item->nama_produk }}</h6>
                                    <span>{{ $item->nama }} <small class="text-muted">(Rp {{ number_format($item->harga_jual, 0, ',', '.') }})</small></span>
                                </div>
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Sisa {{ $item->stok }} Pcs</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                🟢 Tidak ada produk dengan stok menipis.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Card Stok Habis --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                    <div class="card-header bg-danger bg-opacity-10 border-0 p-3">
                        <h6 class="fw-bold text-danger mb-0">🚫 Stok Habis (0)</h6>
                    </div>
                    <div class="card-body p-3">
                        @forelse($stokHabis ?? [] as $item)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <h6 class="fw-semibold mb-0">{{ $item->nama_produk }}</h6>
                                    <small class="text-muted">Harga: Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</small>
                                </div>
                                <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">Habis</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                🟢 Tidak ada produk yang stoknya habis.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    @endif
</div>
@endsection