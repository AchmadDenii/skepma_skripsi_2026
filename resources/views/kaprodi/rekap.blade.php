@extends('kaprodi.layout')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Rekap bukti Mahasiswa</h4>

    {{-- FILTER --}}
    <form method="GET" class="card shadow-sm mb-4">
        <div class="card-body row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun')==$tahun?'selected':'' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select">
                    <option value="">Semua</option>
                    <option value="ganjil" {{ request('semester')=='ganjil'?'selected':'' }}>Ganjil</option>
                    <option value="genap" {{ request('semester')=='genap'?'selected':'' }}>Genap</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Jenis bukti</label>
                <select name="jenis" class="form-select">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisList as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis')==$jenis?'selected':'' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Terapkan</button>
            </div>

        </div>
    </form>

    {{-- TABEL --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>NPM</th>
                        <th>Dosen Wali</th>
                        <th>Jenis</th>
                        <th>Tingkatan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                    <tr>
                        <td class="text-center">{{ $i+1 }}</td>
                        <td>{{ $row->nama_mahasiswa }}</td>
                        <td>{{ $row->username }}</td>
                        <td>{{ $row->nama_dosen }}</td>
                        <td>{{ $row->jenis_kegiatan }}</td>
                        <td>{{ $row->tingkatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_kegiatan)->format('d M Y') }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ 
                                $row->status=='approved'?'success':
                                ($row->status=='pending'?'warning':'danger') 
                            }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ asset('storage/'.$row->file) }}" 
                               class="btn btn-sm btn-outline-primary"
                               target="_blank">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Tidak ada data bukti
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
