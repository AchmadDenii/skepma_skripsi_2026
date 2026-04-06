@extends('kaprodi.layout')

@section('content')
<h4 class="mb-4">Kirim Catatan ke Dosen Wali</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('kaprodi.catatan.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Pilih Dosen Wali</label>
                <select name="dosen_id" class="form-select" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach($dosen as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan"
                          class="form-control"
                          rows="5"
                          required></textarea>
            </div>

            <div class="text-end">
                <button class="btn btn-primary">
                    Kirim Catatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
