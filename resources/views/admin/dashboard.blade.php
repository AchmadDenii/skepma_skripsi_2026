@extends('admin.layout')

@section('content')
<h4 class="mb-4">Dashboard Admin</h4>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Master Poin</h6>
                <a href="/admin/master-poin" class="btn btn-primary btn-sm mt-2">
                    Kelola
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Manajemen User</h6>
                <a href="/admin/users" class="btn btn-primary btn-sm mt-2">
                    Kelola
                </a>
            </div>
        </div>
    </div>
</div>
@endsection