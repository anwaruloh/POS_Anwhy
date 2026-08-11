<div class="row g-3">
    @forelse($products as $product)
        <div class="col-md-4 col-6">
            <div class="card h-100 border border-light-subtle shadow-sm rounded-3 product-card p-2 text-center bg-white hover-shadow transition-all">
                <div class="card-body p-2 d-flex flex-column justify-content-between">
                    
                    <div>
                        {{-- Badge Kategori --}}
                        <span class="badge bg-secondary bg-opacity-10 text-secondary mb-2" style="font-size: 0.7rem;">
                            {{ $product->kategori->nama_kategori ?? $product->kategori->nama ?? 'Umum' }}
                        </span>

                        {{-- Display Foto Produk --}}
                        <div class="mb-2 d-flex justify-content-center align-items-center" style="height: 100px;">
                            @if(!empty($product->foto))
                                <img src="{{ asset('storage/'.$product->foto) }}" 
                                     alt="{{ $product->nama ?? $product->nama_produk }}" 
                                     class="rounded-3 object-fit-cover w-100 h-100">
                            @elseif(!empty($product->gambar))
                                <img src="{{ asset('storage/'.$product->gambar) }}" 
                                     alt="{{ $product->nama ?? $product->nama_produk }}" 
                                     class="rounded-3 object-fit-cover w-100 h-100">
                            @else
                                <div class="bg-light text-secondary rounded-3 d-flex align-items-center justify-content-center fw-bold w-100 h-100" 
                                     style="font-size: 2rem;">
                                    📦
                                </div>
                            @endif
                        </div>

                        {{-- Nama Produk --}}
                        <h6 class="fw-bold mb-1 text-dark text-truncate" title="{{ $product->nama ?? $product->nama_produk }}">
                            {{ $product->nama ?? $product->nama_produk ?? 'Tanpa Nama' }}
                        </h6>
                    </div>

                    {{-- Stok & Harga --}}
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">
                            Stok: {{ $product->stok }}
                        </small>
                        <span class="fw-bold text-primary fs-6">
                            Rp {{ number_format($product->harga_jual ?? $product->harga ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Tombol Tambah ke Keranjang --}}
                    <form method="POST" action="{{ route('itempenjualan.store') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3 fw-bold" {{ $product->stok < 1 ? 'disabled' : '' }}>
                            + Tambah
                        </button>
                    </form>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <div class="fs-2 mb-2">🔍</div>
            <small>Produk tidak ditemukan.</small>
        </div>
    @endforelse
</div>