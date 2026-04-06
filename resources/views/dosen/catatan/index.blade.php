@extends('dosen.layout')

@section('content')
<h4 class="mb-4">Catatan dari Kaprodi</h4>

@if($catatan->isEmpty())
    <div class="alert alert-info">
        Belum ada catatan dari Kaprodi.
    </div>
@else
<div class="card">
    <div class="card-body">
        @foreach($catatan as $row)
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <strong>{{ $row->nama_kaprodi }}</strong>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}
                    </small>
                </div>
                <p class="mb-0">
                    {{ $row->catatan }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection