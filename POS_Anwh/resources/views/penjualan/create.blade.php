<!-- Filter & Search Bar Produk -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2">
            <!-- Search Bar -->
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="search-produk" class="form-control border-start-0" placeholder="Cari nama produk atau kode/barcode...">
                </div>
            </div>
            <!-- Filter Kategori -->
            <div class="col-md-5">
                <select id="filter-kategori" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Container Tempat Produk Ditampilkan -->
<div id="container-produk">
    @include('penjualan.partials.produk_list')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function fetchProduk() {
        let search = $('#search-produk').val();
        let kategoriId = $('#filter-kategori').val();

        $.ajax({
            url: "{{ route('penjualan.create') }}",
            type: "GET",
            data: { 
                search: search, 
                kategori_id: kategoriId 
            },
            success: function(response) {
                $('#container-produk').html(response);
            },
            error: function(xhr) {
                console.error('Gagal memuat produk', xhr);
            }
        });
    }

    // Trigger otomatis saat kasir mengetik di kolom pencarian
    $('#search-produk').on('keyup search', function() {
        fetchProduk();
    });

    // Trigger otomatis saat kasir mengganti filter kategori
    $('#filter-kategori').on('change', function() {
        fetchProduk();
    });
});
</script>