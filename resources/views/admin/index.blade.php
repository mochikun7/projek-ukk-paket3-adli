@extends('layouts.app')

@section('content')

    <!-- MENU 1: HALAMAN SELAMAT DATANG ADMIN -->
    <div class="tab-pane fade show active" id="menu-welcome">
        <div class="card shadow-sm border-0 bg-dark text-white text-center py-5">
            <div class="card-body">
                <h1 class="fw-bold">SELAMAT DATANG DI DASHBOARD ADMIN</h1>
                <p class="fs-5 mt-3">Panel Kelola Pengaduan Sarana Sekolah</p>
                <hr class="w-25 mx-auto border-light">
                <p>Tugas Anda adalah meninjau laporan masuk, mengubah status penyelesaian,<br>dan memberikan umpan balik kepada siswa.</p>
            </div>
        </div>
    </div>

    <!-- MENU 2: LIST ASPIRASI MASUK -->
    <div class="tab-pane fade" id="menu-list">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h5 class="mb-0 fw-bold text-dark">Daftar Semua Aspirasi Masuk</h5></div>
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                <!-- Form Filter Sesuai Syarat UKK -->
                <form action="{{ route('admin.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Filter Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Kategori::all() as $k)
                                <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->ket_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Cari NIS Siswa</label>
                        <input type="text" name="nis" class="form-control form-control-sm" placeholder="Ketik NIS..." value="{{ request('nis') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small">Filter Bulan</label>
                        <input type="month" name="bulan" class="form-control form-control-sm" value="{{ request('bulan') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary btn-sm w-100">Filter Data</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa (NIS)</th>
                                <th>Kategori</th>
                                <th>Detail Laporan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $row->nis }} <br> <small class="text-muted">Kelas: {{ $row->kelas }}</small></td>
                                <td>{{ $row->ket_kategori }}</td>
                                <td><strong>{{ $row->lokasi }}</strong> <br> {{ $row->ket }}</td>
                                <td>
                                    @if($row->status == 'Proses') <span class="badge bg-warning text-dark">Proses</span>
                                    @elseif($row->status == 'Selesai') <span class="badge bg-success">Selesai</span>
                                    @else <span class="badge bg-secondary">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $row->id_pelaporan }}">Tanggapi</button>
                                </td>
                            </tr>

                            <!-- Modal Tanggapan -->
                            <div class="modal fade" id="modalTanggapi{{ $row->id_pelaporan }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tanggapi Laporan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.tanggapi') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="id_pelaporan" value="{{ $row->id_pelaporan }}">
                                                <input type="hidden" name="id_kategori" value="{{ $row->id_kategori }}">

                                                <div class="mb-3">
                                                    <label class="form-label">Status Penyelesaian</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Menunggu" {{ $row->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                        <option value="Proses" {{ $row->status == 'Proses' ? 'selected' : '' }}>Diproses</option>
                                                        <option value="Selesai" {{ $row->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Umpan Balik</label>
                                                    <textarea name="feedback" class="form-control" rows="3" required>{{ $row->feedback }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-success">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection