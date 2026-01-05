@extends('mahasiswa.layout')

@section('content')
<h4 class="mb-4">Dashboard Mahasiswa</h4>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Poin</h6>
                <h2>{{ $totalPoin }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Target Poin</h6>
                <h2>{{ $target }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Sertifikat Pending</h6>
                <h2>{{ $pending }}</h2>
            </div>
        </div>
    </div>
</div>
<a href="{{ route('mahasiswa.transkrip.pdf') }}" 
   class="btn btn-success">
   Cetak Transkrip Poin
</a>

@endsection
