@extends('admin.layout')

@section('content')
    <div class="container-fluid">

        {{-- Judul Halaman --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Manajemen User</h4>
                <small class="text-muted">Kelola akun pengguna sistem SKEPMA</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    Tambah Admin
                </a>
                <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
                    Tambah Mahasiswa
                </a>
                <a href="{{ route('admin.dosen-wali.create') }}" class="btn btn-primary">
                    Tambah Dosen
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <strong>Daftar User</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Dosen Wali</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    <span
                                        class="badge 
                                @if ($user->role == 'admin') bg-danger
                                @elseif($user->role == 'kaprodi') bg-warning
                                @elseif($user->role == 'dosen_wali') bg-info
                                @else bg-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>

                                {{-- DOSEN WALI --}}
                                <td>
                                    @if ($user->role === 'mahasiswa')
                                        <form action="{{ route('admin.users.assign-dosen-wali', $user->id) }}"
                                            method="POST" class="d-flex gap-2">
                                            @csrf

                                            <select name="dosen_id" class="form-select form-select-sm" required>
                                                <option value="">Pilih Dosen</option>
                                                @foreach ($dosenWali as $dosen)
                                                    <option value="{{ $dosen->id }}"
                                                        @if (isset($relasiDosen[$user->id]) && $relasiDosen[$user->id] == $dosen->id) selected @endif>
                                                        {{ $dosen->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button class="btn btn-sm btn-outline-primary">
                                                Simpan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    @php
                                        $editRoute = null;
                                        if ($user->role === 'mahasiswa') {
                                            $mahasiswaId = $mahasiswaMap[$user->id] ?? null;
                                            if ($mahasiswaId) {
                                                $editRoute = route('admin.mahasiswa.edit', $mahasiswaId);
                                            }
                                        } elseif ($user->role === 'dosen_wali' || $user->role === 'kaprodi') {
                                            $dosenWaliId = $dosenWaliMap[$user->id] ?? null;
                                            if ($dosenWaliId) {
                                                $editRoute = route('admin.dosen-wali.edit', $dosenWaliId);
                                            }
                                        } else {
                                            // role: admin, kaprodi
                                            $editRoute = route('admin.users.edit', $user->id);
                                        }
                                    @endphp

                                    @if ($editRoute)
                                        <a href="{{ $editRoute }}" class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Data user belum tersedia
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
