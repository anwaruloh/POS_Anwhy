@extends('layout.app')

@section('title', 'About Aplikasi')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Tentang Aplikasi</h4>

    <div class="row">
        <!-- Profil Pembuat -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="card-body">
                    <img src="{{ asset('images/me.jpeg') }}" 
                         alt="Foto Pembuat" 
                         class="rounded-circle img-thumbnail mb-3" 
                         style="width: 140px; height: 140px; object-fit: cover;">
                    
                    <h5 class="card-title fw-bold">Anwarullah Azzahra</h5>
                    <p class="text-muted mb-3">Developer POS System</p>
                    <hr>

                    <div class="text-start">
                        <p class="mb-2"><strong>NIS:</strong> 242510182</p>
                        <p class="mb-2"><strong>Kelas/Jurusan:</strong> XII RPL</p>
                        <p class="mb-2"><strong>Sekolah:</strong> SMK Negeri 4 Tasikmalaya</p>
                        <p class="mb-0"><strong>Email:</strong> anwarulaharul@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Spesifikasi System -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i>Deskripsi Aplikasi
                    </h5>
                    <p class="card-text">
                        Aplikasi Point of Sale (POS) Kasir ini dibuat untuk mengelola transaksi penjualan, pencatatan stok produk, serta data pengguna secara efisien.
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">
                        <i class="bi bi-cpu me-2"></i>Spesifikasi & Versi
                    </h5>
                    
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <td width="35%"><strong>Framework</strong></td>
                                <td>: Laravel</td>
                            </tr>
                            <tr>
                                <td><strong>Bahasa Pemrograman</strong></td>
                                <td>: PHP</td>
                            </tr>
                            <tr>
                                <td><strong>Database</strong></td>
                                <td>: MariaDB / MySQL</td>
                            </tr>
                            <tr>
                                <td><strong>Versi Aplikasi</strong></td>
                                <td>: <span class="badge bg-success">v1.0.0</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection