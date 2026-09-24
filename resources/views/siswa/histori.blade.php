@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Histori Aspirasi Kamu</h5>
    </div>
    <div class="card-body">
        
        <!-- Karena NIS udah disimpan di session login, form pencarian NIS dihapus aja. Langsung nampil data! -->
        @if($data->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal Lapor</th>
                            <th>Kategori</th>
                            <th>Lokasi & Detail</th>
                            <th>Status</th>
                            <th>Umpan Balik Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}</td>
                            <td>{{ $row->ket_kategori }}</td>
                            <td>
                                <strong>{{ $row->lokasi }}</strong><br>
                                {{ $row->ket }}
                            </td>
                            <td>
                                @if($row->status == 'Proses')
                                    <span class="badge bg-warning text-dark">Proses</span>
                                @elseif($row->status == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">Menunggu</span>
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
@endsection