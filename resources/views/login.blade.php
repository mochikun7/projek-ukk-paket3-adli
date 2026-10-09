<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Pengaduan Sekolah</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/pengaduan.css') }}?v={{ @filemtime(public_path('css/pengaduan.css')) }}" rel="stylesheet">
</head>
<body class="login-page d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-4">
            <div class="card login-card">
                <div class="card-header">
                    <div class="login-logo">🏫</div>
                    <h4>Masuk</h4>
                    <p class="text-muted mb-0">Aplikasi Pengaduan Sarana Sekolah</p>
                </div>
                <div class="card-body">

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('proses.login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Masuk Sebagai</label>
                            <select name="role" id="roleSelect" class="form-select" onchange="togglePassword()" required>
                                <option value="siswa">Siswa (Gunakan NIS)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" id="labelUsername">NIS Siswa</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan NIS / Email Admin..." required>
                        </div>

                        <div class="mb-4" id="passwordField" style="display: none;">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Khusus Admin">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Masuk</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        var role = document.getElementById('roleSelect').value;
        var passField = document.getElementById('passwordField');
        var labelUser = document.getElementById('labelUsername');

        if(role === 'admin') {
            passField.style.display = 'block';
            labelUser.innerHTML = 'Email Admin';
        } else {
            passField.style.display = 'none';
            labelUser.innerHTML = 'NIS Siswa';
        }
    }
</script>
</body>
</html> 