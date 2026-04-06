@extends('admin.layout')

@section('content')

<h4 class="mb-4">Master Poin Kegiatan</h4>

<a href="/admin/master-poin/create" class="btn btn-primary mb-3">
    Tambah Master Poin
</a>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-primary">
        <tr>
            <th>Kode</th>
            <th>Kelompok Kegiatan</th>
            <th>Tingkat</th>
            <th>Peran</th>
            <th>Poin</th>
            <th>Status</th>
            <th width="200">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
    <tr>
        <td>{{ $item->kode }}</td>
        <td>{{ $item->jenis_kegiatan }}</td>
        <td>{{ $item->tingkat ?? '-' }}</td>
        <td>{{ $item->peran }}</td>
        <td>{{ $item->poin }}</td>
        <td>
            @if($item->aktif == 1)
            <span class="badge bg-success">
                Aktif
            </span>
            @else
            <span class="badge bg-secondary">
                Nonaktif
            </span>
            @endif
        </td>
        <td>
            <a href="/admin/master-poin/{{ $item->id }}/edit"class="btn btn-sm btn-warning">
                Edit
            </a>
            @if($item->aktif)
                <form action="{{ route('admin.master-poin.nonaktif',$item->id) }}"
                    method="POST"
                    style="display:inline-block">
                    @csrf
                        <button class="btn btn-sm btn-danger">
                        Nonaktifkan
                        </button>
                </form>
            @else
                <form action="{{ route('admin.master-poin.aktif',$item->id) }}"
                method="POST"
                style="display:inline-block">
                @csrf
                    <button class="btn btn-sm btn-success">
                    Aktifkan
                    </button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
</tbody>
</table>
@endsection