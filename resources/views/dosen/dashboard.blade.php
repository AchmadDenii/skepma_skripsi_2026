@extends('dosen.layout')

@section('content')
<h4 class="mb-4">Dashboard Dosen Wali</h4>

<div class="row">

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Sertifikat Menunggu Verifikasi</h6>
                <h2>{{ DB::table('sertifikat')->where('status','pending')->count() }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Sertifikat Disetujui</h6>
                <h2>{{ DB::table('sertifikat')->where('status','approved')->count() }}</h2>
            </div>
        </div>
    </div>

</div>
@endsection