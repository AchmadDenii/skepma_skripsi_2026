<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transkrip Poin</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h3 align="center">TRANSKRIP POIN KEGIATAN MAHASISWA</h3>

<p>
    Nama Mahasiswa : {{ $user->name }} <br>
    NIM : {{ $user->username }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kegiatan</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Poin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sertifikat as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_kegiatan }}</td>
            <td>{{ $item->kategori }}</td>
            <td>{{ $item->tanggal_kegiatan }}</td>
            <td align="center">{{ $item->poin }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:15px;">
    <strong>Total Poin: {{ $totalPoin }}</strong>
</p>

</body>
</html>