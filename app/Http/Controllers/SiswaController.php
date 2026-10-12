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
            
            $namaAsli = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $namaFile = time() . '_' . str_replace(' ', '_', $namaAsli) . '.svg';
            
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('foto_bukti')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('foto_bukti');
            }

            // 1. Ambil resolusi gambar buat settingan SVG nanti
            $imageSize = getimagesize($file);
            $width = $imageSize[0] ?? 800;
            $height = $imageSize[1] ?? 600;

            // Baca gambar asli pakai GD Library
            $image = imagecreatefromstring(file_get_contents($file));
            
            ob_start(); // Mulai menahan output
            imagejpeg($image, null, 40); // 40 ini tingkat kualitas (0 jelek - 100 bagus)
            $jpegData = ob_get_clean(); // Tangkap hasil JPEG-nya

            //Ubah JPEG hasil kompresi tadi ke teks Base64
            $base64Data = base64_encode($jpegData);
            
            //Rakit ke dalam SVG
            $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'">
                <image width="'.$width.'" height="'.$height.'" href="data:image/jpeg;base64,'.$base64Data.'" />
            </svg>';
            
            // Simpan file SVG-nya ke storage
            $pathSimpan = storage_path('app/public/foto_bukti/' . $namaFile);
            file_put_contents($pathSimpan, $svgContent);
            
            $pathFoto = 'foto_bukti/' . $namaFile;
        }

        \App\Models\InputAspirasi::create([
            'nis' => $request->nis,
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'ket' => $request->ket,
            'foto' => $pathFoto,
        ]);

        return back()->with('success', 'Aspirasi kamu berhasil dikirim');
    }

}