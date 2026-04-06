<!DOCTYPE html>
<html>
<head>
    <title>Laporan Poin SKEPMA</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background: #eee;
        }
        .text-right {
            text-align: right;
        }
        .status-ok {
            font-weight: bold;
        }
        .status-no {
            font-weight: bold;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <h2>LAPORAN CAPAIAN POIN SKEPMA</h2>
    <h4>
        Program Studi Teknik Informatika<br>
        {{ $tahun ? 'Tahun '.$tahun : 'Semua Tahun' }}
    </h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>NPM</th>
                <th>Total Poin</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($query as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $row->name }}</td>
                <td>{{ $row->username }}</td>
                <td>{{ $row->total_poin }}</td>
                <td>
                    @if($row->total_poin >= $target)
                        <span class="status-ok">Memenuhi</span>
                    @else
                        <span class="status-no">Belum Memenuhi</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>
    <div class="text-right">
        Surabaya, {{ date('d F Y') }}<br><br><br>
        <strong>Kaprodi Teknik Informatika</strong>
    </div>

    <br>
    <button onclick="window.print()">Cetak</button>

</body>
</html>
