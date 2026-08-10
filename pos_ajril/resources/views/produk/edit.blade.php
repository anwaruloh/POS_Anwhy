@extends('layout.app')

@section('title', 'Edit Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Edit Produk</h4>

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