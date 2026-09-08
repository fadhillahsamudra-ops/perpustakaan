<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        // Ambil semua data anggota/siswa dari tabel 'member' SLiMS
        $siswas = Siswa::all();

        return view('siswa.index', compact('siswas'));
    }

    // Menggunakan nama fungsi 'cetakKartu' sesuai rute di web.php
    public function cetakKartu($id)
    {
        // Cari data siswa berdasarkan member_id di SLiMS
        $siswa = Siswa::where('member_id', $id)->firstOrFail();

        return view('siswa.cetak', compact('siswa'));
    }
}