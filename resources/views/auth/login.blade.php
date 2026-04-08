<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Sistem SKEPMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="card shadow-sm" style="width:420px;">
            <div class="card-body">

                <!-- Header -->
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1">SISTEM SKEPMA</h4>
                    <small class="text-muted">
                        Sistem Kredit Prestasi Mahasiswa
                    </small>
                </div>

                <!-- Error -->
                @if ($errors->any())
                    <div class="alert alert-danger text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">NPM / Username / Email</label>
                        <input type="text" name="login" class="form-control"
                            placeholder="Masukkan NPM / Username / Email" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password"
                            required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        Hak akses ditentukan berdasarkan peran pengguna
                    </small>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
