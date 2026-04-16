@extends('admin.layout')

@section('content')
    <h4>Import Master Poin</h4>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('errors'))
        <div class="alert alert-danger">
            <ul>
                @foreach (session('errors') as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.import.master-poin.process') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf

        <div class="mb-3">
            <label>Upload Excel / CSV</label>
            <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv" required>
            <small class="form-text text-muted">Format: .xlsx, .xls, .csv (max 2MB)</small>
        </div>

        <button class="btn btn-primary">Import</button>
        <a href="#" id="downloadTemplate" class="btn btn-secondary">Download Template CSV</a>
    </form>

    <hr>

    <h5>Format Excel WAJIB</h5>
    <p class="text-danger">*Baris pertama harus berisi header persis seperti di bawah, jangan diubah urutannya.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Jenis kegiatan</th>
                <th>Tingkat Lomba/Kegiatan</th>
                <th>Prestasi/Peran</th>
                <th>Kode Singkatan</th>
                <th>Poin</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Lomba Karya Tulis Ilmiah (LKTI)</td>
                <td>Internasional</td>
                <td>Juara I/II/III</td>
                <td>LKTI-INTER-JRA</td>
                <td>150</td>
                <td>Akademik</td>
            </tr>
            <tr>
                <td>Pengembangan Minat Bakat (PMB)</td>
                <td>Regional</td>
                <td>Finalis</td>
                <td>PMB-REG-FIN</td>
                <td>40</td>
                <td>Non-Akademik</td>
            </tr>
            <tr>
                <td>Kegiatan Kampus (KEGKAMPUS)</td>
                <td>-</td>
                <td>Panitia PPMB</td>
                <td>KEGKAMPUS-PPMB-PANITIA</td>
                <td>40</td>
                <td>Non-Akademik</td>
            </tr>
        </tbody>
    </table>
@endsection

@push('scripts')
    <script>
        document.getElementById('downloadTemplate').addEventListener('click', function(e) {
            e.preventDefault();
            // Header sesuai kolom wajib
            const headers = ['Jenis Kegiatan', 'Tingkat Lomba/Kegiatan', 'Prestasi/Peran', 'Kode Singkatan', 'Poin',
                'Kategori'
            ];
            // Contoh data 2 baris (bisa dihapus user nanti)
            const rows = [
                ['LKTI', 'internasional', 'Finalis', 'LKTI-INTER-FIN', '100', 'akademik'],
                ['Debat', 'nasional', 'Juara 1', 'DEB-NAS-1', '85', 'akademik']
            ];
            let csvContent = headers.join(',') + '\n';
            rows.forEach(row => {
                csvContent += row.map(cell => `"${cell}"`).join(',') + '\n';
            });
            const blob = new Blob(["\uFEFF" + csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.setAttribute('download', 'template_master_poin.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        });
    </script>
@endpush
