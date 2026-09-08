<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total statistik dari database SLiMS
        $totalBuku      = Buku::count();
        $totalSiswa     = Siswa::count();
        $sedangDipinjam = Transaksi::where('is_return', 0)->count();

        return view('dashboard', compact('totalBuku', 'totalSiswa', 'sedangDipinjam'));
    }
}