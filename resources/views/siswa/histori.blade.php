@extends('layouts.app')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Histori Aspirasi Kamu</h5>
    </div>
    <div class="card-body">

        @if($data->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
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
                                    <span class="status-badge status-proses">Proses</span>
                                @elseif($row->status == 'Selesai')
                                    <span class="status-badge status-selesai">Selesai</span>
                                @else
                                    <span class="status-badge status-menunggu">Menunggu</span>
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
@endsection