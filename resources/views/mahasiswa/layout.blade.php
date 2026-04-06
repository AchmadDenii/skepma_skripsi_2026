<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKEPMA - Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- SIDEBAR -->
        <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
            <h5 class="text-center mb-4">SKEPMA</h5>

            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="/mahasiswa/dashboard" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/mahasiswa/bukti/upload" class="nav-link text-white">
                        Unggah bukti
                    </a>
                </li>
                <li class="nav-item mb-2">              
                    <a href="/mahasiswa/bukti" class="nav-link text-white">
                        Riwayat bukti
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger w-100">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- CONTENT -->
        <main class="col-md-9 col-lg-10 p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>

    </div>
</div>

@yield('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>