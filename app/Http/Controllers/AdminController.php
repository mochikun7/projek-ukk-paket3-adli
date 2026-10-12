<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('input_aspirasis')
            ->join('siswas', 'input_aspirasis.nis', '=', 'siswas.nis')
            ->join('kategoris', 'input_aspirasis.id_kategori', '=', 'kategoris.id_kategori')
            ->leftJoin('aspirasis', 'input_aspirasis.id_pelaporan', '=', 'aspirasis.id_pelaporan')
            ->select('input_aspirasis.*', 'siswas.kelas', 'kategoris.ket_kategori', 'aspirasis.status', 'aspirasis.feedback', 'aspirasis.id_aspirasi')
            ->orderBy('input_aspirasis.created_at', 'desc');

        if ($request->kategori) { $query->where('input_aspirasis.id_kategori', $request->kategori); }
        if ($request->nis) { $query->where('input_aspirasis.nis', $request->nis); }
        if ($request->bulan) { $query->whereMonth('input_aspirasis.created_at', date('m', strtotime($request->bulan))); }

        // Eksekusi query
        $data = $query->get();

        return view('admin.index', compact('data'));
    }

    public function tanggapi(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_pelaporan' => 'required',
            'id_kategori' => 'required',
            'status' => 'required',
            'feedback' => 'required'
        ]);

        
        $cek = DB::table('aspirasis')->where('id_pelaporan', $request->id_pelaporan)->first();

        if ($cek) {
            DB::table('aspirasis')
                ->where('id_pelaporan', $request->id_pelaporan)
                ->update([
                    'status' => $request->status,
                    'feedback' => $request->feedback,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('aspirasis')->insert([ 
                'id_pelaporan' => $request->id_pelaporan,
                'id_kategori' => $request->id_kategori,
                'status' => $request->status,
                'feedback' => $request->feedback,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Tanggapan berhasil disimpan cuy!');
    }

    public function hapus($id_pelaporan)
    {
        // 1. Cari data laporannya dulu
        $laporan = \App\Models\InputAspirasi::where('id_pelaporan', $id_pelaporan)->first();

        if ($laporan) {
            // 2. Cek apakah laporan ini punya foto? Kalau ada, hapus file fisik fotonya
            if ($laporan->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($laporan->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->foto);
            }

            // 3. Hapus data tanggapan di tabel 'aspirasis' (kalau sudah pernah ditanggapi admin)
            // Ini penting biar nggak error relasi (foreign key constraint)
            \Illuminate\Support\Facades\DB::table('aspirasis')->where('id_pelaporan', $id_pelaporan)->delete();
            
            // 4. Hapus data utamanya dari tabel 'input_aspirasis'
            $laporan->delete();

            return back()->with('success', 'Data laporan berhasil dihapus');
        }

        return back()->with('error', 'data tidak ditemukan.');
    }
}