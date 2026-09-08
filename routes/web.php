<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Utama Redirect ke Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route Terkunci (Harus Login)
Route::middleware(['auth'])->group(function () {
    
    // Beranda / Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/cetak/{id}', [BukuController::class, 'cetak'])->name('buku.cetak');

    // Data Siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/cetak/{id}', [SiswaController::class, 'cetakKartu'])->name('siswa.cetak');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/cek-member/{id}', [TransaksiController::class, 'cekMember'])->name('transaksi.cekMember');
    Route::get('/transaksi/cek-buku/{code}', [TransaksiController::class, 'cekBuku'])->name('transaksi.cekBuku');
    Route::post('/transaksi/kembalikan/{id}', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
    Route::get('/transaksi/cek-kembali/{code}', [TransaksiController::class, 'cekKembali'])->name('transaksi.cekKembali');
    Route::post('/transaksi/kembali-qr', [TransaksiController::class, 'kembalikanViaQr'])->name('transaksi.kembaliQr');

});