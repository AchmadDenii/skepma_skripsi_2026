@extends('admin.layout')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">Tambah Mahasiswa</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>NPM</label>
                        <input type="text" name="npm" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Program Studi</label>
                        <input type="text" name="prodi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Angkatan</label>
                        <input type="number" name="angkatan" class="form-control" min="1900" max="{{ date('Y') }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Semester</label>
                        <input type="number" name="semester" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>IPK</label>
                        <input type="number" step="0.01" name="ipk" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

            </div>
        </div>

    </div>
@endsection
