@extends('layout.app')

@section('title', 'Edit Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Edit Produk</h4>

        <!-- Pilihan Kategori (Edit) -->
<div class="mb-3">
    <label for="kategori_id" class="form-label">Kategori Produk</label>
    <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $kategori)
            <option value="{{ $kategori->id }}" {{ (old('kategori_id', $produk->kategori_id) == $kategori->id) ? 'selected' : '' }}>
                {{ $kategori->nama_kategori }}
            </option>
        @endforeach
    </select>
    @error('kategori_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

        <form action="{{ route('admin.produk.update', $produk) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('produk._form')
        </form>
    </div>
</div>
@endsection