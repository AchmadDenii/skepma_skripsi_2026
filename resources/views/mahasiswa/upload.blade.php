@extends('mahasiswa.layout')

@section('content')
<h4>Upload Sertifikat Kegiatan Mahasiswa</h4>

<form action="{{ route('mahasiswa.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Kategori Kegiatan</label>
        <select id="kategori" name='kategori' class="form-select">
            <option value="">Pilih Kategori</option>
            <option value="akademik">Akademik</option>
            <option value="non-akademik">Non Akademik</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Pilih Jenis Kegiatan</label>
        <select id="jenis_kegiatan" class="form-select" disabled>
            <option value="">Pilih Jenis Kegiatan</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Pilih Prestasi/Peran</label>
        <select id="peran" class="form-select" disabled>
        <option value="">-- Pilih Prestasi/Peran --</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Pilih Tingkatan Lomba/Kegiatan</label>
        <select name="master_poin_id" id="tingkat" class="form-select" disabled>
            <option value="">Pilih Tingkat</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Tanggal Kegiatan</label>
        <input type="date" name="tanggal_kegiatan" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Upload Sertifikat</label>
        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.png" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Keterangan (Opsional)</label>
        <textarea name="keterangan" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Upload</button>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const kategori = document.getElementById('kategori');
    const jenis = document.getElementById('jenis_kegiatan');
    const peran = document.getElementById('peran');
    const tingkat = document.getElementById('tingkat');

    kategori.addEventListener('change', async () => {
        reset(jenis); reset(peran); reset(tingkat);
        if (!kategori.value) return;

        const res = await fetch(`/mahasiswa/master-poin/jenis/${kategori.value}`);
        const data = await res.json();

        fill(jenis, data);
        jenis.disabled = false;
    });

    jenis.addEventListener('change', async () => {
        reset(peran); reset(tingkat);
        if (!jenis.value) return;

        const res = await fetch(`/mahasiswa/master-poin/peran/${kategori.value}/${encodeURIComponent(jenis.value)}`);
        const data = await res.json();

        fill(peran, data);
        peran.disabled = false;
    });

    peran.addEventListener('change', async () => {
        reset(tingkat);
        if (!peran.value) return;

        const url = `/mahasiswa/master-poin/tingkat/${kategori.value}/${jenis.value}/${encodeURIComponent(peran.value)}`;
        const res = await fetch(url);
        const data = await res.json();

        tingkat.innerHTML = '<option value="">Pilih Tingkat</option>';
        data.forEach(item => {
            tingkat.innerHTML += `<option value="${item.id}">${item.tingkat}</option>`;
        });
        tingkat.disabled = false;
    });

    function reset(el) {
        el.innerHTML = '<option value="">---</option>';
        el.disabled = true;
    }

    function fill(el, data) {
        el.innerHTML = '<option value="">Pilih</option>';
        data.forEach(val => {
            el.innerHTML += `<option value="${val}">${val}</option>`;
        });
    }
});
</script>
@endsection