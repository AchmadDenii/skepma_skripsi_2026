@extends('admin.layout')

@section('content')
<h4 class="mb-4">Dashboard Admin</h4>

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6>Total Mahasiswa</h6>
                <h3>{{ $totalMahasiswa }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6>Total Bukti</h6>
                <h3>{{ $totalBukti }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6>Bukti Pending</h6>
                <h3>{{ $pending }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6>Master Poin</h6>
                <h3>{{ $masterPoin }}</h3>
            </div>
        </div>
    </div>

</div>


<div class="row">

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Master Poin</h6>
                <a href="/admin/master-poin" class="btn btn-primary btn-sm mt-2">
                    Kelola
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Manajemen User</h6>
                <a href="/admin/users" class="btn btn-primary btn-sm mt-2">
                    Kelola
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Master Bukti</h6>
                <a href="/admin/bukti" class="btn btn-primary btn-sm mt-2">
                    Lihat
                </a>
            </div>
        </div>
    </div>

</div>

@endsection