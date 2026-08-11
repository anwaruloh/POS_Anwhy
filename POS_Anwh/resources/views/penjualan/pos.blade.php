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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0">Daftar Produk</h5>
                        <small class="text-muted">Pilih produk untuk ditambahkan ke keranjang</small>
                    </div>

                    <!-- Filter & Search -->
                    <div class="d-flex gap-2">
                     <!-- Dropdown Kategori -->
                        <select id="filter-kategori" class="form-select form-select-sm" style="width: 170px;" onchange="filterProduk()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nama_kategori ?? $category->nama }}</option>
                            @endforeach
                        </select>
                        <!-- Input Search -->
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" id="search-produk" class="form-control form-control-sm" placeholder="Cari produk..." oninput="filterProduk()">
                        </div>
                    </div>
                </div>

                <!-- Container Tempat Produk Ditampilkan (Dikelola AJAX) -->
                <div id="container-produk" style="max-height: 70vh; overflow-y: auto;">
                    @include('penjualan.partials.produk_list', ['products' => $products])
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
                                                name="qty" 
                                                class="form-control input-qty" 
                                                value="{{ $item->kuantitas }}" 
                                                min="1" 
                                                max="{{ $item->produk->stok }}" 
                                                data-stok="{{ $item->produk->stok }}"
                                                onchange="checkStok(this)">
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
                            
                            <div class="mb-3">
                                <select name="payment_method" class="form-select border-0 shadow-none bg-white py-2 rounded-3 text-dark small" required>
                                    <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                                    <option value="CASH">Cash (Tunai)</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="TRANSFER">Transfer Bank</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <button type="submit" name="action" value="checkout" class="btn btn-success fw-bold py-2.5 rounded-3 shadow-sm w-100">
                                    ✅ Checkout Transaksi
                                </button>

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

{{-- Style Tambahan --}}
<style>
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-shadow:hover { 
        border-color: #0d6efd !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.08) !important;
    }
</style>
@endsection

<!-- CDN jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// 1. Fungsi untuk Filter & Pencarian Produk
function filterProduk() {
    let keyword = $('#search-produk').val();
    let kategoriId = $('#filter-kategori').val();

    console.log("Mencari data -> Keyword:", keyword, "Kategori ID:", kategoriId);

    $.ajax({
        url: "{{ route('penjualan.create') }}",
        type: "GET",
        data: { 
            search: keyword, 
            kategori_id: kategoriId 
        },
        success: function(response) {
            console.log("AJAX Berhasil!");
            $('#container-produk').html(response);
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error, xhr.responseText);
        }
    });
}

// 2. Fungsi Validasi Stok (Harus Sejajar/Di Luar filterProduk)
function checkStok(input) {
    let maxStok = parseInt($(input).attr('max')) || parseInt($(input).data('stok'));
    let inputQty = parseInt($(input).val());

    if (inputQty > maxStok) {
        alert("Jumlah melebihi stok! Stok maksimum yang tersedia adalah " + maxStok);
        $(input).val(maxStok); // Riset nilai ke stok maksimal
        return false;
    } else if (inputQty < 1 || isNaN(inputQty)) {
        $(input).val(1); // Riset ke 1 jika input kosong atau kurang dari 1
    }
}
</script>