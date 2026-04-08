@extends('dosen.layout')

@section('content')
    <h4 class="mb-4">Verifikasi Bukti Mahasiswa Bimbingan</h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Filter -->
    <form method="GET" action="{{ route('dosen.bukti.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Cari Nama Mahasiswa</label>
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                placeholder="Nama mahasiswa">
        </div>
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('dosen.bukti.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Tabel Data -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Deskripsi Kegiatan</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td>{{ $row->username }}</td>
                        <td>{{ $row->nama_mahasiswa }}</td>
                        <td>{{ $row->keterangan ?? '-' }}</td>
                        <td>
                            @if ($row->file)
                                <a href="{{ asset('storage/' . $row->file) }}" class="btn btn-sm btn-outline-primary"
                                    target="_blank">Lihat File</a>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($row->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($row->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">{{ $row->status }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($row->status == 'pending')
                                <div class="d-flex flex-column gap-1">
                                    <!-- Form Approve -->
                                    <form action="{{ route('dosen.bukti.approve', $row->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100">Approve</button>
                                    </form>

                                    <!-- Form Reject dengan catatan -->
                                    <form action="{{ route('dosen.bukti.reject', $row->id) }}" method="POST"
                                        class="d-inline">
                                        <div class="mb-2">
                                            <textarea name="catatan_dosen" class="form-control" rows="2" placeholder="Alasan penolakan (wajib)" required></textarea>
                                        </div>
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted">Sudah diverifikasi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data bukti yang ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
