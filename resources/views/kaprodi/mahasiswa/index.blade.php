@extends('kaprodi.layout')

@section('content')

<h4 class="mb-4">Monitoring Poin Mahasiswa</h4>

<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-hover mb-0">

<thead class="table-light">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NPM</th>
        <th>Dosen Wali</th>
        <th>Total Poin</th>
        <th>Progress</th>
        <th>Poin Kurang</th>
        <th>Status</th>
    </tr>
</thead>

<tbody>
    @foreach($data as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->username }}</td>
                        <td>
                        {{ $row->nama_dosen ?? '-' }}
                        </td>
                    
                        <td>
                            <span class="badge bg-primary">
                            {{ $row->total_poin }}
                            </span>
                        </td>

                        <td style="width:200px">
                            <div class="progress" style="height:20px">
                                <div class="progress-bar bg-success"
                                style="width: {{ $row->progress }}%">
                                {{ $row->progress }}%
                                </div>
                            </div>
                        </td>
                    <td>
                        @if($row->poin_kurang > 0)
                            <span class="badge bg-danger">
                            {{ $row->poin_kurang }}
                            </span>
                        @else
                            <span class="badge bg-success">
                            0
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($row->total_poin >= $targetPoin)
                            <span class="badge bg-success">
                            Memenuhi
                            </span>
                        @elseif($row->progress >= 70)
                            <span class="badge bg-info">
                            Hampir
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                            Belum
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection