@extends('mahasiswa.layout')

@section('content')
<h4 class="mb-4">Riwayat Sertifikat</h4>

<div class="card shadow-sm">
    <div class="card-body">

        @if($data->isEmpty())
            <div class="alert alert-info">
                Belum ada sertifikat yang diunggah.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_kegiatan }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->tanggal_kegiatan }}</td>
                        <td>
                            <span class="badge 
                                {{ $item->status == 'approved' ? 'bg-success' : 
                                ($item->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection