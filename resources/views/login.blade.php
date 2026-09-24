<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Pengaduan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-white pt-4">
                    <h4>Login Portal</h4>
                    <p class="text-muted">Aplikasi Pengaduan Sarana Sekolah</p>
                </div>
                <div class="card-body p-4">
                    
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

                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
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