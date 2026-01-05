@extends('dosen.layout')

@section('content')
<h4 class="mb-4">Verifikasi Sertifikat Mahasiswa</h4>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

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
                <form action="/dosen/sertifikat/{{ $row->id }}/approve" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                </form>

                <!-- REJECT -->
                <form action="/dosen/sertifikat/{{ $row->id }}/reject" method="POST" class="d-inline">
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