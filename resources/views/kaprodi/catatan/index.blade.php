@extends('kaprodi.layout')

@section('content')
<h4 class="mb-4">Catatan Kaprodi ke Dosen</h4>

<a href="{{ route('kaprodi.catatan.create') }}"
   class="btn btn-primary mb-3">
    + Kirim Catatan
</a>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Dosen Wali</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($catatan as $row)
                <tr>
                    <td>{{ $row->nama_dosen }}</td>
                    <td>{{ $row->catatan }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Belum ada catatan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection