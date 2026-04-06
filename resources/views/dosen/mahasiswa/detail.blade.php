@extends('dosen.layout')

@section('content')
<h4 class="mb-3">Detail Mahasiswa</h4>

{{-- KOTAK IDENTITAS --}}
<div class="card mb-4">
    <div class="card-body">
        <strong>{{ $mahasiswa->name }}</strong><br>
        <small class="text-muted">NPM: {{ $mahasiswa->username }}</small>
    </div>
</div>

{{-- RIWAYAT bukti --}}
<div class="card">
    <div class="card-header bg-white">
        <strong>Riwayat bukti</strong>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama bukti</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Poin</th>
                    <th>Status</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bukti as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->nama_kegiatan }}</td>
                    <td>{{ $row->jenis_kegiatan }}</td>
                    <td>{{ $row->tanggal_kegiatan }}</td>
                    <td>{{ $row->poin }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                    <td>
                        <span class="badge bg-success">
                            {{ $row->poin }}
                        </span>
                    </td>
                    <td>
                        <span class="badge 
                            @if($row->status=='approved') bg-success
                            @elseif($row->status=='rejected') bg-danger
                            @else bg-warning
                            @endif">
                            {{ ucfirst($row->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ asset('storage/'.$row->file) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary">
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">
                        Belum ada bukti
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection