@extends('admin.layout')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Manajemen Dosen Wali</h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.dosen-wali.store') }}" method="POST">
        @csrf

        {{-- Pilih Dosen --}}
        <div class="mb-3">
            <label class="form-label">Pilih Dosen Wali</label>
            <select name="dosen_id" class="form-select" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach($dosen as $d)
                    <option value="{{ $d->id }}">
                        {{ $d->name }} ({{ $d->username }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Mahasiswa --}}
        <div class="mb-3">
            <label class="form-label">Pilih Mahasiswa</label>

            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                @foreach($mahasiswa as $m)
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="mahasiswa[]"
                            value="{{ $m->id }}"
                            id="mhs{{ $m->id }}"
                        >

                        <label class="form-check-label" for="mhs{{ $m->id }}">
                            {{ $m->name }}

                            @if(isset($relasi[$m->id]))
                                <small class="text-muted">
                                    (Dosen: {{ \App\Models\User::find($relasi[$m->id]->dosen_id)->name }})
                                </small>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary">
            Simpan Dosen Wali
        </button>
    </form>

</div>
@endsection
