@extends('kaprodi.layout')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Statistik Bukti</h4>
        </div>

        <!-- Filter Form -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="tahun" class="form-label">Tahun</label>
                <select id="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="semester" class="form-label">Semester</label>
                <select id="semester" class="form-select">
                    <option value="">Semua Semester</option>
                    @foreach ($semesterList as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <small class="text-muted">Total Bukti</small>
                        <h3 class="mb-0">{{ $totalBukti }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center border-danger">
                    <div class="card-body">
                        <small class="text-muted">Mahasiswa Kurang Target</small>
                        <h3 class="mb-0 text-danger">{{ $kurangPoin }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <strong>Statistik Bukti per Semester</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartSemester" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <strong>Komposisi Jenis Bukti</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartJenis"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-2">
            <div class="card-header bg-white">
                <strong>Daftar Bukti Terverifikasi</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Jenis Bukti</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listBukti as $row)
                            <tr>
                                <td>{{ $row->nama_mahasiswa }}</td>
                                <td>{{ $row->jenis_kegiatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kaprodi.rekap') }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada data Bukti
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chartSemester, chartJenis;

        function initCharts() {
            const semesterLabels = {!! json_encode($statistikSemester->pluck('nama')) !!};
            const semesterData = {!! json_encode($statistikSemester->pluck('total')) !!};
            const jenisLabels = {!! json_encode($statistikJenis->pluck('jenis_kegiatan')) !!};
            const jenisData = {!! json_encode($statistikJenis->pluck('total')) !!};

            chartSemester = new Chart(document.getElementById('chartSemester'), {
                type: 'bar',
                data: {
                    labels: semesterLabels,
                    datasets: [{
                        label: 'Jumlah Bukti',
                        data: semesterData,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });

            chartJenis = new Chart(document.getElementById('chartJenis'), {
                type: 'doughnut',
                data: {
                    labels: jenisLabels,
                    datasets: [{
                        data: jenisData,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }

        function filterData() {
            let tahun = document.getElementById('tahun').value;
            let semester = document.getElementById('semester').value;

            fetch(`/kaprodi/dashboard/filter?tahun=${tahun}&semester_id=${semester}`)
                .then(res => res.json())
                .then(data => {
                    if (data.semester) {
                        chartSemester.data.labels = data.semester.map(x => x.label);
                        chartSemester.data.datasets[0].data = data.semester.map(x => x.total);
                        chartSemester.update();
                    }
                    if (data.jenis) {
                        chartJenis.data.labels = data.jenis.map(x => x.jenis_kegiatan);
                        chartJenis.data.datasets[0].data = data.jenis.map(x => x.total);
                        chartJenis.update();
                    }
                    if (data.list) {
                        let tbody = document.querySelector('tbody');
                        if (tbody) {
                            tbody.innerHTML = '';
                            data.list.forEach(item => {
                                let row = `<tr>
                                <td>${item.nama_mahasiswa}</td>
                                <td>${item.jenis_kegiatan}</td>
                                <td>${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                                <td class="text-center"><a href="{{ route('kaprodi.rekap') }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                            </tr>`;
                                tbody.insertAdjacentHTML('beforeend', row);
                            });
                            if (data.list.length === 0) {
                                tbody.innerHTML =
                                    '<tr><td colspan="4" class="text-center text-muted py-4">Belum ada data Bukti</td></tr>';
                            }
                        }
                    }
                })
                .catch(err => console.error('Error:', err));
        }

        document.getElementById('tahun').addEventListener('change', filterData);
        document.getElementById('semester').addEventListener('change', filterData);
        document.addEventListener('DOMContentLoaded', initCharts);
    </script>
@endsection
