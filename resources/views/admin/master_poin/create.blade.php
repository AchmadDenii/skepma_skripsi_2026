@extends('admin.layout')

@section('content')
<h4>Tambah Master Poin Kegiatan</h4>

<form method="POST" action="/admin/master-poin">
    @csrf

    <div class="mb-3">
        <label>Kelompok Kegiatan</label>
        <input type="text" name="kelompok_kegiatan" class="form-control" placeholder="LKTI / KFKI">
    </div>

    <div class="mb-3">
        <label>Tingkat</label>
        <input type="text" name="tingkat" class="form-control" placeholder="Internasional / Nasional / dll">
    </div>

    <div class="mb-3">
        <label>Peran</label>
        <input type="text" name="peran" class="form-control" placeholder="Juara / Peserta / Panitia">
    </div>

    <div class="mb-3">
        <label>Kode</label>
        <input type="text" name="kode" class="form-control">
    </div>

    <div class="mb-3">
        <label>Poin</label>
        <input type="number" name="poin" class="form-control">
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>
@endsection
