@extends('mahasiswa.layout')

@section('content')
<h4>Upload Bukti Kegiatan</h4>

<form action="{{ route('mahasiswa.bukti.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <select id="kategori" class="form-select">
            <option value="">Pilih</option>
            <option value="akademik">Akademik</option>
            <option value="non-akademik">Non Akademik</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Jenis Kegiatan</label>
        <select id="jenis" class="form-select" disabled></select>
    </div>

    <div class="mb-3">
        <label>Peran</label>
        <select id="peran" class="form-select" disabled></select>
    </div>

    <div class="mb-3">
        <label>Tingkat</label>
        <select name="master_poin_id" id="tingkat" class="form-select" required></select>
    </div>

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="tanggal_kegiatan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>File</label>
        <input type="file" name="file" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Upload</button>
</form>
@endsection

@section('scripts')
<script>
const kategori = document.getElementById('kategori');
const jenis = document.getElementById('jenis');
const peran = document.getElementById('peran');
const tingkat = document.getElementById('tingkat');

function reset(el){
    el.innerHTML = '<option value="">Pilih</option>';
    el.disabled = true;
}

kategori.addEventListener('change', async () => {
    reset(jenis); reset(peran); reset(tingkat);

    if (!kategori.value) return;

    try {
        const res = await fetch(`/mahasiswa/master-poin/jenis/${kategori.value}`);
        const data = await res.json();

        if (data.length === 0) return;

        jenis.innerHTML = '<option value="">Pilih</option>';
        data.forEach(item => {
            jenis.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
        });

        jenis.disabled = false;

    } catch (err) {
        console.error(err);
    }
});

jenis.addEventListener('change', async () => {
    reset(peran); reset(tingkat);

    if (!jenis.value) return;

    try {
        const res = await fetch(`/mahasiswa/master-poin/peran/${jenis.value}`);
        const data = await res.json();

        if (data.length === 0) return;

        peran.innerHTML = '<option value="">Pilih</option>';
        data.forEach(val => {
            peran.innerHTML += `<option value="${val}">${val}</option>`;
        });

        peran.disabled = false;

    } catch (err) {
        console.error(err);
    }
});

peran.addEventListener('change', async () => {
    reset(tingkat);

    if (!peran.value) return;

    try {
        const res = await fetch(`/mahasiswa/master-poin/tingkat/${jenis.value}/${encodeURIComponent(peran.value)}`);
        const data = await res.json();

        if (data.length === 0) return;

        tingkat.innerHTML = '<option value="">Pilih</option>';
        data.forEach(item => {
            tingkat.innerHTML += `<option value="${item.id}">${item.tingkat}</option>`;
        });

        tingkat.removeAttribute('disabled');

    } catch (err) {
        console.error(err);
    }
});
</script>
@endsection