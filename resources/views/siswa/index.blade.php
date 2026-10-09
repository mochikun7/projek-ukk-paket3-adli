@extends('layouts.app')

@section('content')

    <!-- MENU 1: HALAMAN SELAMAT DATANG -->
    <div class="tab-pane fade show active" id="menu-welcome">
        <div class="card hero-card text-center">
            <div class="card-body">
                <h1 class="fw-bold">Selamat datang di Dashboard Siswa</h1>
                <p class="fs-5 mt-3 mb-0">Sistem Layanan Pengaduan Sarana & Prasarana Sekolah</p>
                <hr class="w-25 mx-auto my-4">
                <p class="mb-0">Silakan gunakan menu di sidebar sebelah kiri untuk mulai menulis laporan kerusakan<br>atau melacak status laporan yang sudah kamu kirimkan.</p>
            </div>
        </div>
    </div>

    <!-- MENU 2: FORM TULIS LAPORAN -->
    <div class="tab-pane fade" id="menu-buat">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Form Aspirasi Baru</h5></div>
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">NIS (Nomor Induk Siswa)</label>
                        <input type="number" name="nis" class="form-control" value="{{ session('nis') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Pengaduan</label>
                        <select name="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->ket_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Detail</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Misal: Lab RPL 1, Toilet Lt 2..." required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan / Detail Kerusakan</label>
                        <textarea name="ket" class="form-control" rows="4" placeholder="Jelaskan masalahnya secara singkat..." required maxlength="50"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Upload Foto (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Laporan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MENU 3: TABEL HISTORI -->
    <div class="tab-pane fade" id="menu-histori">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Histori Laporan Kamu</h5></div>
            <div class="card-body">
                @if($histori->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Lokasi & Detail</th>
                                    <th>Foto Bukti</th>
                                    <th>Status</th>
                                    <th>Umpan Balik Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histori as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $row->ket_kategori }}</td>
                                    <td><strong>{{ $row->lokasi }}</strong><br>{{ $row->ket }}</td>
                                    <td>
                                        @if($row->foto)
                                            <a href="{{ asset('storage/' . $row->foto) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $row->foto) }}" alt="Bukti" class="thumb-bukti">
                                            </a>
                                        @else
                                            <span class="status-badge status-tanpa">Tanpa Foto</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->status == 'Proses') <span class="status-badge status-proses">Proses</span>
                                        @elseif($row->status == 'Selesai') <span class="status-badge status-selesai">Selesai</span>
                                        @else <span class="status-badge status-menunggu">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->feedback ?? 'Belum ada balasan' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">Kamu belum pernah mengirim laporan.</div>
                @endif
            </div>
        </div>
    </div>

@endsection