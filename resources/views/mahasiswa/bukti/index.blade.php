@extends('mahasiswa.layout')

@section('content')
<h4 class="mb-4">Riwayat bukti</h4>

<div class="card shadow-sm">
    <div class="card-body">

        @if($data->isEmpty())
            <div class="alert alert-info">
                Belum ada bukti yang diunggah.
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
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d F Y') }}</td>
                        <td>
                            @if($item->status == 'approved')
                                <span class="badge bg-success">APPROVED</span>
                            @elseif($item->status == 'rejected')
                                <span class="badge bg-danger">REJECTED</span>
                            @else
                                <span class="badge bg-warning">PENDING</span>
                            @endif
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