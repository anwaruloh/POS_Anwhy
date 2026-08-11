@extends('layout.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        {{-- Header & Tombol Tambah --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Daftar Produk</h4>
                <p class="text-muted small mb-0">Kelola katalog produk, harga beli/jual, dan ketersediaan stok barang.</p>
            </div>
            <div>
                <a href="{{ route('admin.produk.create') }}" class="btn btn-primary px-3 rounded-3 fw-semibold shadow-sm">
                    + Tambah Produk
                </a>
            </div>
        </div>

        {{-- Form Pencarian --}}
        <form action="{{ route('admin.produk.index') }}" method="GET" class="mb-4">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="search" class="form-control rounded-start-3" placeholder="Cari nama produk..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary px-3 rounded-end-3" type="submit">Cari</button>
            </div>
        </form>

        {{-- Tabel Data Produk --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 8%">FOTO</th>
                        <th>NAMA PRODUK</th>
                        <th>HARGA BELI</th>
                        <th>HARGA JUAL</th>
                        <th class="text-center">STOK</th>
                        <th class="text-end" style="width: 15%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produks as $index => $produk)
                        <tr>
                            <td>{{ $produks->firstItem() + $index }}</td>
                            <td>
                                @if($produk->foto)
                                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="rounded-2 object-fit-cover" width="40" height="40">
                                @else
                                    <div class="bg-light rounded-2 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px; font-size: 0.75rem;">
                                        No Pic
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold text-dark">{{ $produk->nama }}</td>
                            <td class="text-muted">
                                Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="text-success fw-bold">
                                Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($produk->stok == 0)
                                    <span class="badge bg-danger px-2 py-1 rounded">Habis</span>
                                @elseif($produk->stok < 5)
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1 rounded">
                                        {{ $produk->stok }} Pcs
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-dark px-2 py-1 rounded">
                                        {{ $produk->stok }} Pcs
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-sm btn-outline-warning rounded-2 px-2">Edit</a>
                                    <form action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <small>Data produk tidak ditemukan.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $produks->links() }}
        </div>

    </div>
</div>
@endsection