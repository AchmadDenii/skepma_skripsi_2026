@extends('dosen.layout')

@section('content')
<h4 class="mb-4">Mahasiswa Bimbingan</h4>

@if($mahasiswa->isEmpty())
    <div class="alert alert-info">
        Belum ada mahasiswa bimbingan.
    </div>
@else
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NPM</th>
                    <th>Total Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa as $index => $mhs)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mhs->name }}</td>
                    <td>{{ $mhs->username }}</td>
                    <td>
                        <span class="badge bg-success">
                            {{ $mhs->total_poin }} poin
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('dosen.mahasiswa.detail', $mhs->id) }}"
                        class="btn btn-sm btn-outline-primary">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection