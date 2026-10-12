<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Pengaduan Sekolah</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/7dfda37f2a.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS kita sendiri (harus SETELAH bootstrap supaya bisa menimpa) -->
    <link href="{{ asset('css/pengaduan.css') }}?v={{ filemtime(public_path('css/pengaduan.css')) }}" rel="stylesheet">
</head>
<body>

    <div class="sidebar">
        <h5 class="sidebar-title">Pengaduan Sekolah</h5>
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
            @if(session('role') == 'admin')
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#menu-welcome"><i class="fa-solid fa-house"></i> Dashboard Admin</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#menu-list"><i class="fa-solid fa-list-ul"></i> List Aspirasi</button>
            @elseif(session('role') == 'siswa')
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#menu-welcome"><i class="fa-solid fa-house"></i> Dashboard Siswa</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#menu-buat"><i class="fa-regular fa-pen-to-square"></i> Tulis Laporan</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#menu-histori"><i class="fa-regular fa-clock"></i> Histori Laporan</button>
            @endif
        </div>
    </div>

    <div class="content-area">

        <nav class="top-bar">
            <span class="greeting">
                Halo, {{ session('role') == 'admin' ? 'Administrator' : 'Siswa (NIS: '.session('nis').')' }}
            </span>
            <a href="{{ route('logout') }}" class="btn-logout text-decoration-none">Logout</a>
        </nav>

        <div class="container-fluid px-4">
            <div class="tab-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(button => {
                button.addEventListener('shown.bs.tab', event => {
                    localStorage.setItem('activeMenu_{{ session("role") }}', event.target.getAttribute('data-bs-target'));
                });
            });

            const activeMenu = localStorage.getItem('activeMenu_{{ session("role") }}');
            if (activeMenu) {
                const tabButton = document.querySelector(`button[data-bs-target="${activeMenu}"]`);
                if (tabButton) new bootstrap.Tab(tabButton).show();
            }
        });
    </script>
</body>
</html>