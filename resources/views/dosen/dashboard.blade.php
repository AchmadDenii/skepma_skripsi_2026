@extends('dosen.layout')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">Dashboard Dosen Wali</h4>

        {{-- RINGKASAN --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Mahasiswa Bimbingan</small>
                        <h3>{{ $totalMahasiswa ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Pending</small>
                        <h3 class="text-warning">{{ $totalPending ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Disetujui</small>
                        <h3 class="text-success">{{ $totalApproved ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted">Ditolak</small>
                        <h3 class="text-danger">{{ $totalRejected ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- BUKTI PENDING --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong>Bukti Pending</strong>
                <a href="{{ route('dosen.bukti.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NPM</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buktiPending ?? [] as $row)
                            <tr>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->username }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('dosen.bukti.show', $row->id) }}"
                                        class="btn btn-sm btn-outline-success">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Tidak ada bukti pending
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
