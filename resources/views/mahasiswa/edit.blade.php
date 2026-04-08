@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Mahasiswa</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $mahasiswa->user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="npm" class="form-label">NPM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('npm') is-invalid @enderror" id="npm"
                            name="npm" value="{{ old('npm', $mahasiswa->npm) }}" required>
                        @error('npm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $mahasiswa->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prodi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('prodi') is-invalid @enderror" id="prodi"
                            name="prodi" value="{{ old('prodi', $mahasiswa->prodi) }}" required>
                        @error('prodi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="angkatan" class="form-label">Angkatan <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('angkatan') is-invalid @enderror" id="angkatan"
                            name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}" required>
                        @error('angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('semester') is-invalid @enderror" id="semester"
                            name="semester" value="{{ old('semester', $mahasiswa->semester) }}" required>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ipk" class="form-label">IPK</label>
                        <input type="number" step="0.01" class="form-control @error('ipk') is-invalid @enderror"
                            id="ipk" name="ipk" value="{{ old('ipk', $mahasiswa->ipk) }}">
                        @error('ipk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
