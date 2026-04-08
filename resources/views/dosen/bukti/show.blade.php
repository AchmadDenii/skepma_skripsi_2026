@extends('dosen.layout')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Bukti Mahasiswa</h5>
                <a href="{{ route('dosen.bukti.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Mahasiswa:</div>
                    <div class="col-md-9">{{ $bukti->nama_mahasiswa }} ({{ $bukti->username }})</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Deskripsi Kegiatan:</div>
                    <div class="col-md-9">{{ $bukti->keterangan ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Poin yang diajukan:</div>
                    <div class="col-md-9">{{ $bukti->poin ?? 0 }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Status:</div>
                    <div class="col-md-9">
                        @if ($bukti->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($bukti->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($bukti->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">File Bukti:</div>
                    <div class="col-md-9">
                        @if ($bukti->file)
                            <a href="{{ asset('storage/' . $bukti->file) }}" class="btn btn-sm btn-primary" target="_blank">
                                Lihat File
                            </a>
                        @else
                            <span class="text-muted">Tidak ada file</span>
                        @endif
                    </div>
                </div>
                @if ($bukti->catatan_dosen)
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Catatan Dosen:</div>
                        <div class="col-md-9">{{ $bukti->catatan_dosen }}</div>
                    </div>
                @endif
            </div>
        </div>

        @if ($bukti->status == 'pending')
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Verifikasi Bukti</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <form action="{{ route('dosen.bukti.approve', $bukti->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success w-100">✔ Approve</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('dosen.bukti.reject', $bukti->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-2">
                                    <textarea name="catatan_dosen" class="form-control" rows="2" placeholder="Alasan penolakan (wajib)" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">✘ Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-secondary mt-4">
                Bukti sudah diverifikasi pada {{ \Carbon\Carbon::parse($bukti->updated_at)->format('d-m-Y H:i') }}
            </div>
        @endif
    </div>
@endsection
