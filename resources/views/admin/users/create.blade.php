@extends('admin.layout')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">Tambah User</h4>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                            Kembali
                        </a>
                        <button class="btn btn-primary">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
