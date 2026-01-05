@extends('kaprodi.layout')

@section('content')
<h4 class="mb-4">Rekap Poin Kegiatan Mahasiswa</h4>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NPM</th>
            <th>Nama Mahasiswa</th>
            <th>Total Poin</th>
            <th>Status Capaian</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->username }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->total_poin }}</td>
            <td>
                @if($row->total_poin >= $target)
                    <span class="badge bg-success">Memenuhi</span>
                @else
                    <span class="badge bg-danger">Belum</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection