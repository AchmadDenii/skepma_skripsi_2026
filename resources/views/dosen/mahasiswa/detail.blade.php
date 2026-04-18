@extends('dosen.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Mahasiswa</h4>
    </div>

    {{-- KOTAK IDENTITAS --}}
    <div class="card mb-4">
        <div class="card-body">
            <strong>{{ $mahasiswa->name }}</strong><br>
            <small class="text-muted">NPM: {{ $mahasiswa->username }}</small>
        </div>
    </div>

    {{-- RIWAYAT BUKTI --}}
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Riwayat Bukti</strong>
            <a href="{{ route('dosen.mahasiswa.export-pdf', $mahasiswa->id) }}" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Bukti</th>
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
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $row->nama_kegiatan }}</td>
                            <td class="text-center">{{ $row->jenis_kegiatan }}</td>
                            <td class="text-center">{{ $row->tanggal_kegiatan }}</td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $row->poin }}</span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge 
                                    @if ($row->status == 'approved') bg-success
                                    @elseif($row->status == 'rejected') bg-danger
                                    @else bg-warning @endif">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ asset('storage/' . $row->file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada bukti</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
