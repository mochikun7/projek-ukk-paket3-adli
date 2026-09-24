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

        // Cek ke tabel aspirasis, apakah laporan ini sudah punya status/feedback sebelumnya
        $cek = DB::table('aspirasis')->where('id_pelaporan', $request->id_pelaporan)->first();

        if ($cek) {
            // Kalau sudah ada di database, kita lakukan UPDATE
            DB::table('aspirasis')
                ->where('id_pelaporan', $request->id_pelaporan)
                ->update([
                    'status' => $request->status,
                    'feedback' => $request->feedback,
                    'updated_at' => now()
                ]);
        } else {
            // Kalau masih kosong (pertama kali ditanggapi), kita lakukan INSERT
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
}