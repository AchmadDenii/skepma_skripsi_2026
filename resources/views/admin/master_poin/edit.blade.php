@extends('admin.layout')

@section('content')
<h4 class="mb-4">Edit Master Poin</h4>

<form method="POST" action="/admin/master-poin/{{ $data->id }}">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Kategori</label>
    <input type="text" name="kategori" class="form-control"
           value="{{ $data->kategori }}" required>
</div>

<div class="mb-3">
    <label>Jenis Kegiatan</label>
    <select name="jenis_kegiatan_id" class="form-control" required>

        @foreach($jenis as $j)

        <option value="{{ $j->id }}"
            {{ $data->jenis_kegiatan_id == $j->id ? 'selected' : '' }}>
            {{ $j->nama }}
        </option>

        @endforeach

    </select>
</div>

<div class="mb-3">
    <label>Tingkat</label>
    <input type="text" name="tingkat" class="form-control"
           value="{{ $data->tingkat }}">
</div>

<div class="mb-3">
    <label>Peran</label>
    <input type="text" name="peran" class="form-control"
           value="{{ $data->peran }}" required>
</div>

<div class="mb-3">
    <label>Kode</label>
    <input type="text" class="form-control"
           value="{{ $data->kode }}" disabled>
</div>

<div class="mb-3">
    <label>Poin</label>
    <input type="number" name="poin" class="form-control"
           value="{{ $data->poin }}" required>
</div>

<button class="btn btn-primary">Update</button>

</form>
@endsection