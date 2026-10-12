@extends('layouts.app')

@section('content')

    <!-- MENU 1: HALAMAN SELAMAT DATANG ADMIN -->
    <div class="tab-pane fade show active" id="menu-welcome">
        <div class="card hero-card hero-admin text-center">
            <div class="card-body">
                <h1 class="fw-bold">Selamat datang di Dashboard Admin</h1>
                <p class="fs-5 mt-3 mb-0">Panel Kelola Pengaduan Sarana Sekolah</p>
                <hr class="w-25 mx-auto my-4">
                <p class="mb-0">Tugas Anda adalah meninjau laporan masuk, mengubah status penyelesaian,<br>dan memberikan umpan balik kepada siswa.</p>
            </div>
        </div>
    </div>

    <!-- MENU 2: LIST ASPIRASI MASUK -->
    <div class="tab-pane fade" id="menu-list">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                <!-- Form Filter Sesuai Syarat UKK -->
                <form action="{{ route('admin.index') }}" method="GET" class="row g-3 filter-box mx-0">
                    <div class="col-md-3">
                        <label class="form-label small">Filter Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Kategori::all() as $k)
                                <option value="{{ $k->id_kategori }}" {{ request('kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->ket_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Cari NIS Siswa</label>
                        <input type="text" name="nis" class="form-control form-control-sm" placeholder="Ketik NIS..." value="{{ request('nis') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Filter Bulan</label>
                        <input type="month" name="bulan" class="form-control form-control-sm" value="{{ request('bulan') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filter Data</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa (NIS)</th>
                                <th>Kategori</th>
                                <th>Detail Laporan</th>
                                <th>Foto Bukti</th>
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
                                    @if($row->foto)
                                        <a href="{{ asset('storage/' . $row->foto) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $row->foto) }}" alt="Bukti" class="thumb-bukti">
                                        </a>
                                    @else
                                        <span class="status-badge status-tanpa">Tidak ada lampiran</span>
                                    @endif
                                </td>
                                <td>
                                    @if($row->status == 'Proses') <span class="status-badge status-proses">Proses</span>
                                    @elseif($row->status == 'Selesai') <span class="status-badge status-selesai">Selesai</span>
                                    @else <span class="status-badge status-menunggu">Menunggu</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Tambahin align-items-center biar sejajar vertikal secara sempurna -->
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-sm btn-primary m-0" data-bs-toggle="modal" data-bs-target="#modalTanggapi{{ $row->id_pelaporan }}">Tanggapi</button>
                                        
                                        <!-- Tambahin class m-0 p-0 biar form tidak membuat tombol meleset ke atas/bawah -->
                                        <form action="{{ route('admin.hapus', $row->id_pelaporan) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Yakin mau hapus laporan ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger m-0"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Tanggapan (di luar tabel supaya HTML-nya valid) -->
                @foreach($data as $row)
                <div class="modal fade" id="modalTanggapi{{ $row->id_pelaporan }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
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
                                    <div class="mb-0">
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
            </div>
        </div>
    </div>

@endsection