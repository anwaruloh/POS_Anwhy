@extends('layout.app')

@section('title', 'Tambah Suplier')

@section('content')
<h4>Tambah Suplier</h4>

<form action="{{ route('suplier.store') }}" method="POST">
    @include('suplier._form')
</form>
@endsection