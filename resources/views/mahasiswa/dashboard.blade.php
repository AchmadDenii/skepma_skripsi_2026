@extends('mahasiswa.layout')

@section('content')
<div class="container-fluid">

<div class="mb-4">
<h4 class="mb-1">Dashboard</h4>
</div>

{{-- IDENTITAS --}}
<div class="row mb-4">
<div class="col-md-12">
<div class="card shadow-sm border-0">
<div class="card-body d-flex align-items-center gap-3">

<div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
style="width:60px;height:60px;">
<i class="bi bi-person fs-3 text-muted"></i>
</div>

<div>
<h6 class="mb-1">{{ $mahasiswa->name }}</h6>
<small class="text-muted">
NPM : {{ $mahasiswa->username }}
</small>
</div>

</div>
</div>
</div>
</div>


{{-- STATISTIK --}}
<div class="row mb-4">

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<small class="text-muted">Total Poin</small>
<h3 class="mt-2">{{ $totalPoin }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<small class="text-muted">Target Kelulusan</small>
<h3 class="mt-2">{{ $targetKelulusan }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<small class="text-muted">Poin Kurang</small>
<h3 class="mt-2 text-danger">{{ $poinKurang }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<small class="text-muted">Progress</small>
<h3 class="mt-2 text-success">{{ number_format($progress,1) }}%</h3>
</div>
</div>
</div>

</div>


{{-- PROGRESS BAR --}}
<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<h6 class="mb-3">Progress Pengumpulan Poin</h6>

<div class="progress" style="height:20px;">
<div class="progress-bar bg-success"
role="progressbar"
style="width: {{ $progress }}%">
{{ number_format($progress,1) }}%
</div>
</div>

</div>
</div>


{{-- DATA BUKTI --}}
<div class="card shadow-sm border-0">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">
<h5 class="mb-0">Bukti Terbaru</h5>
</div>

<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
<th>Nama Kegiatan</th>
<th>Kategori</th>
<th>Tahun</th>
<th>File</th>
<th>Poin</th>
<th>Status</th>
</tr>
</thead>

<tbody>

@forelse ($buktiTerbaru as $row)

<tr>

<td>{{ $row->nama_kegiatan }}</td>
<td>{{ $row->kategori }}</td>
<td>{{ \Carbon\Carbon::parse($row->created_at)->format('Y') }}</td>

<td>
<a href="{{ asset('storage/'.$row->file) }}"
class="btn btn-sm btn-primary">
File
</a>
</td>

<td>{{ $row->poin }}</td>

<td>
@if($row->status === 'pending')
<span class="badge bg-warning">Pending</span>

@elseif($row->status === 'approved')
<span class="badge bg-success">Approved</span>

@else
<span class="badge bg-danger">Rejected</span>
@endif
</td>

</tr>

@empty

<tr>
<td colspan="6" class="text-center text-muted py-4">
Belum ada bukti
</td>
</tr>

@endforelse

</tbody>

</table>

<div class="d-flex justify-content-end mt-3">

<a href="{{ route('mahasiswa.bukti.index') }}"
class="btn btn-sm btn-outline-primary">
Lihat Semua
</a>

</div>

</div>
</div>

</div>
@endsection