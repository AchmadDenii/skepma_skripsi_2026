@extends('admin.layout')

@section('content')
    <h4>Edit Master Poin Kegiatan</h4>

    <form method="POST" action="{{ route('admin.master-poin.update', $data->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Jenis Kegiatan <span class="text-danger">*</span></label>
            <select name="jenis_kegiatan_id" class="form-control" required>
                <option value="">-- Pilih Jenis Kegiatan --</option>
                @foreach ($jenis as $j)
                    <option value="{{ $j->id }}"
                        {{ old('jenis_kegiatan_id', $data->jenis_kegiatan_id) == $j->id ? 'selected' : '' }}>
                        {{ $j->nama }} ({{ $j->kategori }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Peran <span class="text-danger">*</span></label>
            <input type="text" name="peran" class="form-control" value="{{ old('peran', $data->peran) }}" required>
        </div>

        <div class="mb-3">
            <label>Tingkat</label>
            <select name="tingkat" class="form-control">
                <option value="">-- Pilih Tingkat --</option>
                <option value="internasional" {{ old('tingkat', $data->tingkat) == 'internasional' ? 'selected' : '' }}>
                    Internasional</option>
                <option value="nasional" {{ old('tingkat', $data->tingkat) == 'nasional' ? 'selected' : '' }}>Nasional
                </option>
                <option value="regional" {{ old('tingkat', $data->tingkat) == 'regional' ? 'selected' : '' }}>Regional
                </option>
                <option value="institut" {{ old('tingkat', $data->tingkat) == 'institut' ? 'selected' : '' }}>Institut
                </option>
                <option value="fakultas" {{ old('tingkat', $data->tingkat) == 'fakultas' ? 'selected' : '' }}>Fakultas
                </option>
                <option value="jurusan" {{ old('tingkat', $data->tingkat) == 'jurusan' ? 'selected' : '' }}>Jurusan
                <option value="lokal" {{ old('tingkat', $data->tingkat) == 'lokal' ? 'selected' : '' }}>Lokal</option>
                </option>
            </select>
            <small class="text-muted">Kosongkan jika tidak ada tingkat</small>
        </div>

        <div class="mb-3">
            <label>Kode <span class="text-danger">*</span></label>
            <input type="text" name="kode" class="form-control" value="{{ old('kode', $data->kode) }}" required>
            <small class="text-muted">Kode harus unik</small>
        </div>

        <div class="mb-3">
            <label>Poin <span class="text-danger">*</span></label>
            <input type="number" name="poin" class="form-control" value="{{ old('poin', $data->poin) }}" required
                min="0">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="butuh_bukti" class="form-check-input" id="butuh_bukti" value="1"
                {{ old('butuh_bukti', $data->butuh_bukti) ? 'checked' : '' }}>
            <label class="form-check-label" for="butuh_bukti">Wajib upload bukti</label>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="aktif" class="form-check-input" id="aktif" value="1"
                {{ old('aktif', $data->aktif) ? 'checked' : '' }}>
            <label class="form-check-label" for="aktif">Aktif</label>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.master-poin.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
