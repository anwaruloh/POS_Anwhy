@extends('layout.app')

@section('title', 'Tambah Produk')

@section('content')
<h4>Tambah Produk</h4>

<form action="{{ route('admin.produk.store') }}" 
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <!-- Dropdown Kategori -->
    <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori Produk</label>
        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Panggil sisanya (Nama, Harga, Stok, Foto, Tombol Simpan) dari _form -->
    @include('produk._form')
</form>
@endsection