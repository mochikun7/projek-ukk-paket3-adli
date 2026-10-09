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

        $kategori = \App\Models\Kategori::all();
        
       
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $pathFoto = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // 1. Ambil nama asli tanpa ekstensi, lalu paksa ganti jadi .webp
            $namaAsli = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $namaFile = time() . '_' . str_replace(' ', '_', $namaAsli) . '.webp';
            
            // 2. Cek dan bikin folder 'foto_bukti' kalau belum ada di storage
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('foto_bukti')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('foto_bukti');
            }

            // 3. Ubah file yang di-upload jadi data gambar mentah di memori
            $image = imagecreatefromstring(file_get_contents($file));
            
            // 4. Tentukan lokasi simpan fisiknya
            $pathSimpan = storage_path('app/public/foto_bukti/' . $namaFile);
            
            // 5. KONVERSI & KOMPRES: Simpan sebagai WebP dengan kualitas 75% (angka 1-100)
            imagewebp($image, $pathSimpan, 75);
            
            // 6. Bersihkan memori server biar aplikasi nggak ngelag
            imagedestroy($image);
            
            // 7. Simpan path ini ke database
            $pathFoto = 'foto_bukti/' . $namaFile;
        }

        \App\Models\InputAspirasi::create([
            'nis' => $request->nis,
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'ket' => $request->ket,
            'foto' => $pathFoto,
        ]);

        return back()->with('success', 'Mantap! Aspirasi kamu berhasil dikirim.');
    }

}