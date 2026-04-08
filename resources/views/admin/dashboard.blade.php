@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4">Dashboard Admin</h4>

        {{-- Statistik Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <i class="bi bi-people fs-1 text-primary"></i>
                        <h6 class="mt-2">Total Mahasiswa</h6>
                        <h3>{{ $totalMahasiswa ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <i class="bi bi-file-earmark-text fs-1 text-info"></i>
                        <h6 class="mt-2">Total Bukti</h6>
                        <h3>{{ $totalBukti ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                        <h6 class="mt-2">Bukti Pending</h6>
                        <h3>{{ $pending ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center border-0">
                    <div class="card-body">
                        <i class="bi bi-trophy fs-1 text-success"></i>
                        <h6 class="mt-2">Master Poin</h6>
                        <h3>{{ $masterPoin ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Aksi --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-trophy fs-2 text-success"></i>
                        <h5 class="mt-2">Master Poin</h5>
                        <p class="text-muted">Kelola aturan poin sertifikat</p>
                        <a href="{{ route('admin.master-poin.index') }}" class="btn btn-primary btn-sm">
                            Kelola
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-2 text-primary"></i>
                        <h5 class="mt-2">Manajemen User</h5>
                        <p class="text-muted">Kelola akun mahasiswa, dosen, admin</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                            Kelola
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-check fs-2 text-info"></i>
                        <h5 class="mt-2">Semua Bukti</h5>
                        <p class="text-muted">Lihat seluruh bukti yang diupload</p>
                        <a href="{{ route('admin.bukti.index') }}" class="btn btn-primary btn-sm">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
