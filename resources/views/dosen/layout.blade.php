<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKEPMA - Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- SIDEBAR -->
        <nav class="col-md-3 col-lg-2 bg-primary text-white p-3">
            <h5 class="text-center mb-4">SKEPMA</h5>

            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="/dosen/dashboard" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="/dosen/sertifikat" class="nav-link text-white">
                        Verifikasi Sertifikat
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
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>