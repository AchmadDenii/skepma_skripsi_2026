@extends('admin.layout')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">
        <h4 class="mb-1">Laporan Poin Mahasiswa</h4>
        <small class="text-muted">
            Cetak laporan capaian poin SKEPMA mahasiswa
        </small>
    </div>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form action="{{ route('admin.laporan.cetak') }}" method="POST" target="_blank">
                @csrf

                <div class="row align-items-end">

                    {{-- FILTER TAHUN --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Tahun Kegiatan (Opsional)
                        </label>
                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}">
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOMBOL --}}
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Cetak Laporan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- KETERANGAN --}}
    <div class="alert alert-info">
        <strong>Catatan:</strong>
        <ul class="mb-0">
            <li>Laporan hanya menghitung bukti dengan status <b>approved</b></li>
            <li>Status kelulusan ditentukan berdasarkan target poin SKEPMA</li>
            <li>Laporan akan terbuka di tab baru</li>
        </ul>
    </div>

</div>
@endsection