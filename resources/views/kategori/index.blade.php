@extends('layout.app') {{-- Sesuaikan dengan layout kamu --}}

@section('title', 'Kelola Kategori')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Kelola Kategori Produk</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Form Tambah Kategori -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Tambah Kategori</div>
                <div class="card-body">
                    <form action="{{ route('admin.kategori.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Contoh: Ban, Velg, Oli" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Kategori</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Kategori -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">Daftar Kategori</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $index => $kategori)
                                <tr>
                                    <td>{{ $kategoris->firstItem() + $index }}</td>
                                    <td>{{ $kategori->nama_kategori }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection