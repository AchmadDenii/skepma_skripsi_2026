@extends('admin.layout')

@section('content')
<h4 class="mb-4">Master Poin Kegiatan</h4>

<a href="/admin/master-poin/create" class="btn btn-primary mb-3">
    Tambah Master Poin
</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-primary">
        <tr>
            <th>Kode</th>
            <th>Nama Kegiatan</th>
            <th>Poin</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->nama_kegiatan }}</td>
            <td>{{ $item->poin }}</td>
            <td>
                <a href="/admin/master-poin/{{ $item->id }}/edit" class="btn btn-sm btn-warning">
                    Edit
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
