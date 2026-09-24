<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'role' => 'required'
        ]);

        if ($request->role == 'admin') {
            if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
                session(['role' => 'admin']);
                return redirect()->route('admin.index');
            }
            return back()->with('error', 'Kredensial Admin salah cuy!');
        } else {
            $siswa = Siswa::where('nis', $request->username)->first();
            if ($siswa) {
                session(['role' => 'siswa', 'nis' => $siswa->nis]);
                return redirect()->route('siswa.index');
            }
            return back()->with('error', 'NIS tidak terdaftar di database!');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}