<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\InputAspirasi;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        // Ambil kategori untuk form
        $kategori = \App\Models\Kategori::all();
        
        // Ambil histori laporan untuk tabel
        $histori = \Illuminate\Support\Facades\DB::table('input_aspirasis')
            ->join('kategoris', 'input_aspirasis.id_kategori', '=', 'kategoris.id_kategori')
            ->leftJoin('aspirasis', 'input_aspirasis.id_pelaporan', '=', 'aspirasis.id_pelaporan')
            ->where('input_aspirasis.nis', session('nis'))
            ->select('input_aspirasis.*', 'kategoris.ket_kategori', 'aspirasis.status', 'aspirasis.feedback')
            ->orderBy('input_aspirasis.created_at', 'desc')
            ->get();
            
        return view('siswa.index', compact('kategori', 'histori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|numeric',
            'id_kategori' => 'required',
            'lokasi' => 'required|max:50',
            'ket' => 'required|max:50',
        ]);

        $cekSiswa = Siswa::where('nis', $request->nis)->first();
        if(!$cekSiswa) {
            return back()->with('error', 'NIS tidak terdaftar cuy! Cek lagi.');
        }

        InputAspirasi::create([
            'nis' => $request->nis,
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'ket' => $request->ket,
        ]);

        return back()->with('success', 'Mantap! Aspirasi kamu berhasil dikirim.');
    }

}