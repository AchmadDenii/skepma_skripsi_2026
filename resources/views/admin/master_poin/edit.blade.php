@extends('admin.layout')

@section('content')
<h4 class="mb-4">Edit Master Poin</h4>

<form method="POST" action="/admin/master-poin/{{ $data->id }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Kode Kegiatan</label>
        <input type="text" class="form-control" value="{{ $data->kode }}" disabled>
    </div>

    <div class="mb-3">
        <label>Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan" class="form-control"
               value="{{ $data->nama_kegiatan }}" required>
    </div>

    <div class="mb-3">
        <label>Poin</label>
        <input type="number" name="poin" class="form-control"
               value="{{ $data->poin }}" min="1" required>
    </div>

    <button class="btn btn-primary">Update</button>
</form>
@endsection
