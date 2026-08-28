@extends('layout.app')

@section('title', 'Suplier')

@section('content')
    <table class="table">
      <div>
                <a href="{{ route('suplier.create') }}" class="btn btn-primary px-3 rounded-3 fw-semibold shadow-sm">
                    + Tambah Suplier
                </a>
            </div>
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Suplier</th>
      <th scope="col">Alamat</th>
      <th scope="col">No. Telp</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $index => $suplier)
    <tr>
      <th scope="row">{{ $index + 1 }}</th>
      <td>{{ $suplier->nama_suplier }}</td>
      <td>{{ $suplier->alamat }}</td>
      <td>{{ $suplier->no_telp }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection