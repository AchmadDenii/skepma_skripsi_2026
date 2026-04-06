@extends('admin.layout')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Edit User</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role Pengguna</label>
                    <select name="role" class="form-select" required>
                        <option value="mahasiswa" @selected($user->role=='mahasiswa')>Mahasiswa</option>
                        <option value="dosen_wali" @selected($user->role=='dosen_wali')>Dosen Wali</option>
                        <option value="kaprodi" @selected($user->role=='kaprodi')>Kaprodi</option>
                        <option value="admin" @selected($user->role=='admin')>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                        Kembali
                    </a>
                    <button class="btn btn-warning">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection