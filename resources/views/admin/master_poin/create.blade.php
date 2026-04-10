@extends('admin.layout')

@section('content')
    <h4>Tambah Master Poin Kegiatan</h4>

    <form method="POST" action="{{ route('admin.master-poin.store') }}">
        @csrf

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Jenis Kegiatan --</option>
                <option value="Akademik">Akademik</option>
                <option value="Non Akademik">Non Akademik</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Jenis Kegiatan</label>
            <select name="jenis_kegiatan_id" class="form-control" required>
                <option value="">-- Pilih Jenis Kegiatan --</option>

                @foreach ($jenis as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->nama }} - {{ $item->deskripsi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tingkat</label>
            <select name="tingkat" class="form-control">
                <option value="">-- Pilih Tingkat --</option>
                <option value="internasional">Internasional</option>
                <option value="nasional">Nasional</option>
                <option value="regional">Regional</option>
                <option value="institut">Institut</option>
                <option value="fakultas">Fakultas</option>
                <option value="jurusan">Jurusan</option>
                <option value="lokal">Lokal</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Peran</label>
            <input type="text" name="peran" class="form-control" placeholder="Juara / Peserta / Panitia" required>
        </div>

        <div class="mb-3">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Poin</label>
            <input type="number" name="poin" class="form-control" required>
        </div>

        <button class="btn btn-primary">Simpan</button>

    </form>
@endsection
