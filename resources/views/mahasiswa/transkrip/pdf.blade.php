<!DOCTYPE html>
<html>
<head>
    <title>Transkrip Poin Mahasiswa</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th,td { border:1px solid #000; padding:6px; }
    </style>
</head>
<body>

<h3>TRANSKRIP POIN KEGIATAN MAHASISWA</h3>

<p>
Nama : {{ $user->name }} <br>
NPM  : {{ $user->username }}
</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Kegiatan</th>
    <th>Kategori</th>
    <th>Peran</th>
    <th>Tingkat</th>
    <th>Poin</th>
</tr>
</thead>
<tbody>
@foreach($data as $i => $row)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $row->nama_kegiatan }}</td>
    <td>{{ $row->kategori }}</td>
    <td>{{ $row->peran }}</td>
    <td>{{ $row->tingkat }}</td>
    <td>{{ $row->poin }}</td>
</tr>
@endforeach
</tbody>
</table>

<p><strong>Total Poin: {{ $total }}</strong></p>

</body>
</html>
