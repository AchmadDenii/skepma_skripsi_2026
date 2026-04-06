@extends('dosen.layout')

@section('content')
<h4 class="mb-4">Verifikasi bukti Mahasiswa</h4>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text"
               name="keyword"
               value="{{ request('keyword') }}"
               class="form-control"
               placeholder="Cari nama mahasiswa">
    </div>

    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Pending</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>
                Pending
            </option>
            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>
                Approved
            </option>
            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>
                Rejected
            </option>
        </select>
    </div>

    <div class="col-md-2">
        <input type="date"
               name="tanggal_dari"
               value="{{ request('tanggal_dari') }}"
               class="form-control">
    </div>

    <div class="col-md-2">
        <input type="date"
               name="tanggal_sampai"
               value="{{ request('tanggal_sampai') }}"
               class="form-control">
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="{{ route('dosen.bukti.index') }}"
           class="btn btn-secondary">
           Reset
        </a>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>NPM</th>
            <th>Nama</th>
            <th>Kegiatan</th>
            <th>File</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->username }}</td>
            <td>{{ $row->nama_mahasiswa }}</td>
            <td>{{ $row->nama_kegiatan }}</td>
            <td>
                <a href="{{ asset('storage/'.$row->file) }}" target="_blank">Lihat</a>
            </td>
            <td>
                <!-- APPROVE -->
                <form action="/dosen/bukti/{{ $row->id }}/approve" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                </form>

                <!-- REJECT -->
                <form action="/dosen/bukti/{{ $row->id }}/reject" method="POST" class="d-inline">
                    @csrf
                    <textarea name="catatan_dosen" class="form-control mb-1" required></textarea>
                    <button class="btn btn-danger btn-sm">Reject</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection