<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [SiswaController::class, 'index'])->name('siswa.index');
Route::post('/kirim-aspirasi', [SiswaController::class, 'store'])->name('siswa.store');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
});

Route::post('/tanggapi', [AdminController::class, 'tanggapi'])->name('admin.tanggapi');

// Route Login & Logout
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('proses.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Siswa 
Route::prefix('siswa')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/kirim-aspirasi', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/histori', [SiswaController::class, 'histori'])->name('siswa.histori');
});

// Route Admin
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/tanggapi', [AdminController::class, 'tanggapi'])->name('admin.tanggapi');
});