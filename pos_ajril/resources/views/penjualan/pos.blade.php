@extends('layout.app')

@section('title', 'POS - Kasir')

@section('content')
<div class="container-fluid px-0">

    {{-- Alert Success / Error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('errors'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('errors') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- ========================================== --}}
        {{-- BAGIAN KIRI: KATALOG PRODUK                --}}
        {{-- ========================================== --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                
                {{-- Header & Search Bar --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Daftar Produk</h5>
                        <small class="text-muted">Pilih produk untuk ditambahkan ke keranjang</small>
                    </div>
                    <div class="position-relative" style="min-width: 240px;">
                        <input type="text" 
                               id="searchProduct" 
                               class="form-control bg-light border-0 py-2 ps-3 rounded-3" 
                               placeholder="🔍 Cari produk...">
                    </div>
                </div>

                {{-- Grid List Produk --}}
                <div class="row g-3 overflow-auto pe-1" style="max-height: 70vh;" id="productList">
                    @forelse($products as $product)
                        <div class="col-12 product-item" data-name="{{ strtolower($product->nama) }}">
                            <form method="POST" action="{{ route('itempenjualan.store') }}" class="m-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="card border border-light-subtle rounded-3 shadow-none hover-shadow transition-all p-2 {{ $product->stok < 1 ? 'bg-light opacity-75' : 'bg-white' }}">
                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                        
                                        {{-- Foto & Detail Produk --}}
                                        <div class="d-flex align-items-center gap-3">
                                            @if($product->foto)
                                                <img src="{{ asset('storage/'.$product->foto) }}" 
                                                     alt="{{ $product->nama }}" 
                                                     class="rounded-3 object-fit-cover" 
                                                     style="width: 55px; height: 55px;">
                                            @else
                                                <div class="bg-light text-secondary rounded-3 d-flex align-items-center justify-content-center fw-bold" 
                                                     style="width: 55px; height: 55px; font-size: 1.3rem;">
                                                    📦
                                                </div>
                                            @endif

                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark text-truncate" style="max-width: 220px;">
                                                    {{ $product->nama }}
                                                </h6>
                                                <div class="text-primary fw-semibold small">
                                                    Rp {{ number_format($product->harga_jual ?? $product->harga ?? 0, 0, ',', '.') }}
                                                </div>
                                                
                                                {{-- Indicator Stok --}}
                                                <div class="mt-1">
                                                    @if($product->stok > 2)
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                                            Stok: {{ $product->stok }}
                                                        </span>
                                                    @elseif($product->stok > 0)
                                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                                            Sisa: {{ $product->stok }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                                                            Stok Habis
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Control Qty & Submit Button --}}
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="1" 
                                                   min="1" 
                                                   max="{{ $product->stok }}" 
                                                   class="form-control text-center rounded-3 bg-light border-0 fw-semibold" 
                                                   style="width: 65px;"
                                                   {{ $product->stok < 1 ? 'disabled' : '' }}
                                                   oninput="if(parseInt(this.value) > {{ $product->stok }}) this.value = {{ $product->stok }};">

                                            <button type="submit" 
                                                    class="btn btn-primary fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" 
                                                    {{ $product->stok < 1 ? 'disabled' : '' }}>
                                                +
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <div class="fs-2">🔍</div>
                            <small>Tidak ada produk yang tersedia.</small>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- ========================================== --}}
        {{-- BAGIAN KANAN: KERANJANG BELANJA            --}}
        {{-- ========================================== --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                
                {{-- Header Keranjang --}}
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">🛒 Keranjang Belanja</h6>
                    @if(isset($sale) && $sale->itemPenjualan->count() > 0)
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">
                            {{ $sale->itemPenjualan->sum('kuantitas') }} Item
                        </span>
                    @endif
                </div>

                {{-- Table Item Keranjang --}}
                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-2 border-0">Produk</th>
                                <th class="py-2 text-center border-0" style="width: 75px;">Qty</th>
                                <th class="py-2 border-0">Subtotal</th>
                                <th class="pe-4 py-2 text-center border-0" style="width: 45px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($sale) && $sale->itemPenjualan && $sale->itemPenjualan->count() > 0)
                                @foreach($sale->itemPenjualan as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 130px;">
                                                {{ $item->produk->nama ?? 'Produk Dihapus' }}
                                            </div>
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                @ Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('itempenjualan.update', $item->id) }}">
                                                @csrf 
                                                @method('PUT')
                                                <input type="number" 
                                                       name="quantity"
                                                       value="{{ $item->kuantitas }}"
                                                       min="1"
                                                       onchange="this.form.submit()"
                                                       class="form-control form-control-sm text-center border-0 bg-light rounded-2 fw-bold"
                                                       {{ ($sale->status === 'COMPLETED') ? 'disabled' : '' }}>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-dark small">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td class="pe-4 text-center">
                                            <form method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}" onsubmit="return confirm('Hapus item ini?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm text-danger border-0 p-1" 
                                                        title="Hapus" 
                                                        {{ ($sale->status === 'COMPLETED') ? 'disabled' : '' }}>
                                                    ❌
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="fs-1 mb-2 opacity-50">🛍️</div>
                                        <div class="fw-semibold small">Keranjang Masih Kosong</div>
                                        <small class="text-secondary" style="font-size: 0.75rem;">Klik tombol <strong>+</strong> pada produk di sebelah kiri.</small>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

             {{-- Footer & Action Form --}}
<div class="card-footer bg-light p-4 border-top">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted small fw-semibold">Total Pembayaran</span>
        <span class="fs-4 fw-bold text-success">
            Rp {{ number_format(isset($sale) ? ($sale->total_pembayaran ?? 0) : 0, 0, ',', '.') }}
        </span>
    </div>

    @if(isset($sale) && $sale->id && $sale->itemPenjualan->count() > 0)
        <form method="POST" action="{{ route('penjualan.update', $sale->id) }}">
            @csrf
            @method('PUT')
            
            {{-- Dropdown Payment dengan margin bawah lebih lega --}}
            <div class="mb-3">
                <select name="payment_method" class="form-select border-0 shadow-none bg-white py-2 rounded-3 text-dark small" required>
                    <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                    <option value="CASH">Cash (Tunai)</option>
                    <option value="QRIS">QRIS</option>
                    <option value="TRANSFER">Transfer Bank</option>
                </select>
            </div>

            {{-- Container Tombol Aksi dengan d-flex & gap untuk space yang rapi --}}
            <div class="d-flex flex-column gap-2">
                {{-- Tombol Utama: Checkout --}}
                <button type="submit" name="action" value="checkout" class="btn btn-success fw-bold py-2.5 rounded-3 shadow-sm w-100">
                    ✅ Checkout Transaksi
                </button>

                {{-- Baris Tombol Sekunder: Simpan Draft & Batalkan --}}
                <div class="d-flex gap-2">
                    <button type="submit" name="action" value="draft" class="btn btn-outline-warning text-dark fw-bold w-50 py-2 rounded-3">
                        📝 Simpan Draft
                    </button>
                    <button type="submit" name="action" value="cancel" onclick="return confirm('Batalkan transaksi ini?')" class="btn btn-outline-danger fw-bold w-50 py-2 rounded-3">
                        🚫 Batalkan
                    </button>
                </div>
            </div>
        </form>
    @else
        <button class="btn btn-secondary w-100 fw-bold py-2 rounded-3 border-0 opacity-75" disabled>
            Pilih Produk Terlebih Dahulu
        </button>
    @endif
</div>

            </div>
        </div>
    </div>

</div>

{{-- Style Tambahan untuk Efek Hover Smooth --}}
<style>
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-shadow:hover { 
        border-color: #0d6efd !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.08) !important;
    }
</style>

{{-- Client-side Realtime Product Search --}}
<script>
    document.getElementById('searchProduct').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.product-item');

        items.forEach(function (item) {
            let name = item.getAttribute('data-name');
            if (name.includes(filter)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection