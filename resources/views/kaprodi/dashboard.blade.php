@extends('kaprodi.layout')

@section('content')
<h4>Dashboard Kaprodi</h4>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>NPM</th>
            <th>Nama</th>
            <th>Total Poin</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->username }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->total_poin }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
