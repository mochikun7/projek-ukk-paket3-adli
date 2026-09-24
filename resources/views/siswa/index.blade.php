@extends('layouts.app')

@section('content')

    <!-- MENU 1: HALAMAN SELAMAT DATANG -->
    <div class="tab-pane fade show active" id="menu-welcome">
        <div class="card shadow-sm border-0 bg-primary text-white text-center py-5">
            <div class="card-body">
                <h1 class="fw-bold">SELAMAT DATANG DI DASHBOARD SISWA</h1>
                <p class="fs-5 mt-3">Sistem Layanan Pengaduan Sarana & Prasarana Sekolah</p>
                <hr class="w-25 mx-auto">
                <p>Silakan gunakan menu di sidebar sebelah kiri untuk mulai menulis laporan kerusakan<br>atau melacak status laporan yang sudah kamu kirimkan.</p>
            </div>
        </div>
    </div>

    <!-- MENU 2: FORM TULIS LAPORAN -->
    <div class="tab-pane fade" id="menu-buat">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h5 class="mb-0 fw-bold text-primary">Form Aspirasi Baru</h5></div>
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">NIS (Nomor Induk Siswa)</label>
                        <input type="number" name="nis" class="form-control bg-light" value="{{ session('nis') }}" readonly>
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
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Laporan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MENU 3: TABEL HISTORI -->
    <div class="tab-pane fade" id="menu-histori">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h5 class="mb-0 fw-bold text-success">Histori Laporan Kamu</h5></div>
            <div class="card-body">
                @if($histori->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Lokasi & Detail</th>
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
                                        @if($row->status == 'Proses') <span class="badge bg-warning text-dark">Proses</span>
                                        @elseif($row->status == 'Selesai') <span class="badge bg-success">Selesai</span>
                                        @else <span class="badge bg-secondary">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->feedback ?? 'Belum ada balasan' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">Kamu belum pernah mengirim laporan cuy.</div>
                @endif
            </div>
        </div>
    </div>

@endsection