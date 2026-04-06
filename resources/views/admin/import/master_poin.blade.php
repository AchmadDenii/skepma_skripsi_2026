@extends('admin.layout')

@section('content')

<h4>Import Master Poin</h4>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('errors'))
<div class="alert alert-danger">
    <ul>
        @foreach(session('errors') as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.import.master-poin.process') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Upload Excel</label>
    <input type="file" name="file" class="form-control" required>
</div>

<button class="btn btn-primary">Import</button>

</form>

<hr>

<h5>Format Excel WAJIB</h5>

<table class="table table-bordered">
<thead>
<tr>
    <th>jenis_kegiatan</th>
    <th>tingkat</th>
    <th>peran</th>
    <th>kode</th>
    <th>poin</th>
    <th>kategori</th>
</tr>
</thead>
<tbody>
<tr>
    <td>LKTI</td>
    <td>internasional</td>
    <td>Finalis</td>
    <td>LKTI-INTER-FIN</td>
    <td>100</td>
    <td>akademik</td>
</tr>
</tbody>
</table>

@endsection