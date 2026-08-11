@extends('layout.app')

@section('title', 'Tambah User')

@section('content')
<h4>Tambah User</h4>

<form action="{{ route('admin.users.store') }}" method="POST">
    @include('user._form')
</form>
@endsection