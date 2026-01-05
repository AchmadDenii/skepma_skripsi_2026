<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKEPMA - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <nav class="col-md-3 col-lg-2 bg-dark text-white p-3">
            <h5 class="text-center mb-4">ADMIN SKEPMA</h5>

            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="/admin/dashboard" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/admin/master-poin" class="nav-link text-white">Master Poin</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="/admin/users" class="nav-link text-white">User</a>
                </li>

                <li class="nav-item mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-danger w-100">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 col-lg-10 p-4">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>