<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap SKEPMA Mahasiswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }

        /* Kop surat - full width, tanpa jarak */
        .kop-header {
            width: 100%;
            margin: 0;
            padding: 0;
            line-height: 0;
            /* hilangkan jarak bawah gambar */
        }

        .kop-header img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
        }

        /* Konten setelah kop surat, beri jarak agar tidak mepet */
        .content {
            margin: 0 1.5cm 0.75cm 2cm;
            /* top:0, right:1.5cm, bottom:0.75cm, left:2cm */
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 30px;
        }

        /* Tabel identitas 2 kolom */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 4px;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        /* Tabel data kegiatan */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .data-table th {
            background-color: white;
            font-weight: bold;
            text-align: center;
        }

        /* Alignment isi tabel */
        .data-table td:nth-child(1) {
            text-align: center;
        }

        .data-table td:nth-child(2) {
            text-align: left;
        }

        .data-table td:nth-child(3) {
            text-align: left;
        }

        .data-table td:nth-child(4) {
            text-align: center;
        }

        .data-table td:nth-child(5) {
            text-align: center;
        }

        .data-table td:nth-child(6) {
            text-align: center;
        }

        .total-poin {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
        }

        .ttd {
            margin-top: 40px;
            text-align: right;
        }

        .ttd-line {
            margin-top: 40px;
            width: 150px;
            border-top: 1px solid #000;
            display: inline-block;
        }

        .ttd-name {
            margin-top: 5px;
            font-weight: bold;
            text-decoration: underline;
        }

        .nip {
            font-size: 11px;
        }
    </style>
</head>

<body>

    <!-- Gambar kop surat full lebar, menempel ke tepi -->
    <div class="kop-header">
        <img src="{{ public_path('images/kop_surat.png') }}" alt="Kop Surat ITATS">
    </div>

    <div class="content">
        <div class="title">REKAPITULASI SKEPMA MAHASISWA</div>

        {{-- Identitas Mahasiswa --}}
        <table class="info-table">
            <tr>
                <td class="info-label">Nama</td>
                <td>: {{ $mahasiswa->name }}</td>
                <td class="info-label">Program Studi</td>
                <td>: {{ $mahasiswa->mahasiswa->prodi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">NPM</td>
                <td>: {{ $mahasiswa->mahasiswa->npm ?? '-' }}</td>
                <td class="info-label">Dosen Wali</td>
                <td>: {{ $dosenWali->user->name ?? '-' }}</td>
            </tr>
        </table>

        {{-- Tabel Kegiatan --}}
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Kode Singkatan</th>
                    <th style="width: 30%;">Nama Kegiatan</th>
                    <th style="width: 25%;">Tingkat Lomba/Kegiatan</th>
                    <th style="width: 10%;">Kategori</th>
                    <th style="width: 10%;">Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bukti as $index => $item)
                    @php
                        $master = $item->masterPoin;
                        $jenis = $master->jenisKegiatan ?? null;
                        $tingkat = $master->tingkat ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $master->kode ?? '-' }}</td>
                        <td>{{ $jenis->nama ?? '-' }}</td>
                        <td>{{ $tingkat }}</td>
                        <td>{{ $jenis->kategori ?? '-' }}</td>
                        <td>{{ $master->poin ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada kegiatan yang disetujui</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-poin">
            Total Poin Keseluruhan : {{ $totalPoin }}
        </div>

        <div class="ttd">
            <div>Mengetahui,</div>
            <div class="ttd-line"></div>
            <div class="ttd-name">{{ $dosenWali->user->name ?? '______________________' }}</div>
            <div class="nip">NIP. {{ $dosenWali->nip ?? '__________________' }}</div>
        </div>
    </div>
</body>

</html>
