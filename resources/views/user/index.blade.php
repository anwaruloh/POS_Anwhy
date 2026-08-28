@extends('layout.app')

@section('title', 'Manajemen Users')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        {{-- Header & Tombol Tambah --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Manajemen Users</h4>
                <p class="text-muted small mb-0">Kelola daftar pengguna dan hak akses aplikasi POS Anda.</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary px-3 rounded-3 fw-semibold shadow-sm">
                    + Tambah User
                </a>
            </div>
        </div>

        {{-- Form Pencarian --}}
        <form action="{{ route('admin.users') }}" method="GET" class="mb-4">
            <div class="input-group" style="max-width: 350px;">
                <input type="text" name="search" class="form-control rounded-start-3" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary px-3 rounded-end-3" type="submit">Search</button>
            </div>
        </form>

        {{-- Tabel Data Users --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th class="text-center">ROLE</th>
                        <th class="text-end" style="width: 15%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users ?? [] as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td class="text-center">
                                @php
                                    $roleName = is_object($user->role) ? $user->role->name : $user->role;
                                @endphp
                                @if(strtolower($roleName) === 'admin')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-semibold text-capitalize">
                                        {{ $roleName }}
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-semibold text-capitalize">
                                        {{ $roleName }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning rounded-2 px-2">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <small>Data user tidak ditemukan.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection