<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengaduan Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .sidebar { height: 100vh; position: fixed; top: 0; left: 0; width: 250px; background-color: #212529; }
        .content-area { margin-left: 250px; min-height: 100vh; background-color: #f8f9fa; }
        .nav-pills .nav-link { color: #adb5bd; border-radius: 8px; margin-bottom: 5px; }
        .nav-pills .nav-link:hover { color: #fff; background-color: #343a40; }
        .nav-pills .nav-link.active { background-color: #0d6efd; color: #fff; }
    </style>
</head>
<body>

    <div class="sidebar p-3 shadow">
        <h5 class="text-white text-center fw-bold mt-2 mb-4">Pengaduan Sekolah</h5>
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
            @if(session('role') == 'admin')
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#menu-welcome">🏠 Dashboard Admin</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#menu-list">📋 List Aspirasi</button>
            @elseif(session('role') == 'siswa')
                <button class="nav-link active text-start" data-bs-toggle="pill" data-bs-target="#menu-welcome">🏠 Dashboard Siswa</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#menu-buat">📝 Tulis Laporan</button>
                <button class="nav-link text-start" data-bs-toggle="pill" data-bs-target="#menu-histori">🕒 Histori Laporan</button>
            @endif
        </div>
    </div>

    <div class="content-area">
    
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 px-4">
            <span class="navbar-brand mb-0 h5 text-primary fw-bold">
                Halo, {{ session('role') == 'admin' ? 'Administrator' : 'Siswa (NIS: '.session('nis').')' }}
            </span>
            <div class="ms-auto">
                <a href="{{ route('logout') }}" class="btn btn-danger btn-sm px-4">Logout</a>
            </div>
        </nav>

        <!-- KOTAK KONTEN (ISI BERUBAH TANPA LOADING) -->
        <div class="container-fluid px-4">
            <div class="tab-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Simpan klik menu terakhir biar pas form di-submit, halamannya gak balik ke awal
            document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(button => {
                button.addEventListener('shown.bs.tab', event => {
                    localStorage.setItem('activeMenu_{{ session("role") }}', event.target.getAttribute('data-bs-target'));
                });
            });

            // Buka menu terakhir pas halaman ter-refresh
            const activeMenu = localStorage.getItem('activeMenu_{{ session("role") }}');
            if (activeMenu) {
                const tabButton = document.querySelector(`button[data-bs-target="${activeMenu}"]`);
                if (tabButton) new bootstrap.Tab(tabButton).show();
            }
        });
    </script>
</body>
</html>