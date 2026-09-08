<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BukuController extends Controller
{
    // 1. Menampilkan daftar semua buku dari SLiMS
    public function index()
    {
        // Ambil data dari tabel 'item' SLiMS beserta judulnya di 'biblio'
        $bukus = Buku::with('biblio')->get();

        return view('buku.index', compact('bukus'));
    }

    // 2. Fungsi cetak QR Code per buku berdasarkan item_code (misal: B001)
    public function cetak($id)
{
    // Cari berdasarkan item_code atau id buku SLiMS
    $buku = Buku::where('item_code', $id)
                ->orWhere('biblio_id', $id)
                ->firstOrFail();

    return view('buku.cetak', compact('buku'));
}
}