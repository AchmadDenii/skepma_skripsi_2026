@extends('admin.layout')

@section('content')

<div class="container-fluid">

<h4 class="mb-4">Master View Bukti Mahasiswa</h4>

<div class="card shadow-sm mb-3">
<div class="card-body">

<form method="GET" class="row g-2">

<div class="col-md-3">
<input type="text"
       name="search"
       value="{{ request('search') }}"
       class="form-control"
       placeholder="Cari mahasiswa / NPM">
</div>

<div class="col-md-3">
<select name="status" class="form-control">
<option value="">Semua Status</option>
<option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
<option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
<option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
</select>
</div>

<div class="col-md-3">
<select name="semester" class="form-control">

<option value="">Semua Semester</option>

@foreach($semester as $s)
<option value="{{ $s->id }}"
{{ request('semester')==$s->id?'selected':'' }}>
{{ $s->nama }}
</option>
@endforeach

</select>
</div>

<div class="col-md-2">
<button class="btn btn-primary w-100">
Filter
</button>
</div>

<div class="col-md-1">
<a href="{{ route('admin.bukti.index') }}"
class="btn btn-secondary w-100">
Reset
</a>
</div>

</form>

</div>
</div>


<div class="card shadow-sm">
<div class="card-body p-0">

<table class="table table-hover mb-0">

<thead class="table-light">
<tr>
<th>Mahasiswa</th>
<th>NPM</th>
<th>Kegiatan</th>
<th>Semester</th>
<th>Poin</th>
<th>Status</th>
<th>Tanggal</th>
<th>File</th>
</tr>
</thead>

<tbody>

@forelse($bukti as $row)

<tr>

<td>{{ $row->name }}</td>

<td>{{ $row->username }}</td>

<td>{{ $row->nama_kegiatan }}</td>

<td>{{ $row->semester }}</td>

<td>{{ $row->poin }}</td>

<td>

@if($row->status == 'approved')
<span class="badge bg-success">Approved</span>

@elseif($row->status == 'pending')
<span class="badge bg-warning">Pending</span>

@else
<span class="badge bg-danger">Rejected</span>

@endif

</td>

<td>
{{ \Carbon\Carbon::parse($row->tanggal_kegiatan)->format('d-m-Y') }}
</td>

<td>
<a href="{{ asset('storage/'.$row->file) }}"
target="_blank"
class="btn btn-sm btn-primary">
Lihat
</a>
</td>

</tr>

@empty

<tr>
<td colspan="8" class="text-center p-4">
Data bukti belum tersedia
</td>
</tr>

@endforelse

</tbody>

</table>

</div>
</div>


<div class="mt-3">
{{ $bukti->links() }}
</div>

</div>

@endsection