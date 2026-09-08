<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Siswa;

class TransaksiController extends Controller
{
    // 1. Tampilkan Halaman Utama Transaksi
  public function index()
{
    // Ambil data peminjaman aktif
    $transaksis = Transaksi::where('is_return', 0)
                    ->orderBy('loan_date', 'desc')
                    ->get();

    return view('transaksi.index', compact('transaksis'));
}

    // 2. Simpan Peminjaman Baru via Scan QR (dengan Pencatatan Waktu Eksekusi)
    public function store(Request $request)
    {
        // Mulai hitung waktu (Start Time)
        $startTime = microtime(true);

        $request->validate([
            'member_id' => 'required',
            'item_code' => 'required',
        ]);

        // Simpan transaksi peminjaman baru
        $transaksi = new Transaksi();
        $transaksi->member_id = $request->member_id;
        $transaksi->item_code = $request->item_code;
        $transaksi->loan_date = now();
        $transaksi->due_date  = now()->addDays(7); // Pinjam 7 hari
        $transaksi->is_return = 0;
        $transaksi->save();

        // Selesai hitung waktu (End Time)
        $executionTime = number_format(microtime(true) - $startTime, 2);

        return back()->with('success', "Peminjaman berhasil dicatat! (Waktu proses: {$executionTime} detik)");
    }

    public function cekMember($id)
{
    // Cari member berdasarkan member_id di SLiMS
    $member = \DB::table('member')->where('member_id', $id)->first();

    if (!$member) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data siswa dengan ID tersebut tidak ditemukan!'
        ], 404);
    }

    // Cek expire_date
    $today = date('Y-m-d');
    $isExpired = false;

    if ($member->expire_date && $member->expire_date < $today) {
        $isExpired = true;
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'member_id' => $member->member_id,
            'member_name' => $member->member_name,
            'instansi' => $member->inst_name ?? 'SD Swasta Taman Siswa',
            'expire_date' => date('d-m-Y', strtotime($member->expire_date)),
            'is_expired' => $isExpired
        ]
    ]);
}

    public function cekBuku($code)
{
    // Cari data buku berdasarkan item_code di SLiMS
    $buku = \DB::table('item')
        ->join('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
        ->where('item.item_code', $code)
        ->select('item.item_code', 'biblio.title', 'item.item_status_id')
        ->first();

    if (!$buku) {
        return response()->json([
            'status' => 'error',
            'message' => 'Buku dengan kode tersebut tidak ditemukan!'
        ], 404);
    }

    // Hitung sisa stok buku yang belum dikembalikan
    $totalEksemplar = \DB::table('item')->where('biblio_id', function($q) use ($code) {
        $q->select('biblio_id')->from('item')->where('item_code', $code);
    })->count();

    $sedangDipinjam = \DB::table('loan')
        ->whereIn('item_code', function($q) use ($code) {
            $q->select('item_code')->from('item')->where('biblio_id', function($q2) use ($code) {
                $q2->select('biblio_id')->from('item')->where('item_code', $code);
            });
        })
        ->where('is_return', 0)
        ->count();

    $stokTersedia = $totalEksemplar - $sedangDipinjam;

    return response()->json([
        'status' => 'success',
        'data' => [
            'item_code' => $buku->item_code,
            'title' => $buku->title,
            'stok' => $stokTersedia > 0 ? $stokTersedia : 0,
            'is_available' => $stokTersedia > 0
        ]
    ]);
}

    public function cekKembali($code)
{
    // Cari transaksi peminjaman aktif berdasarkan kode buku
    $transaksi = \DB::table('loan')
        ->leftJoin('member', 'loan.member_id', '=', 'member.member_id')
        ->leftJoin('item', 'loan.item_code', '=', 'item.item_code')
        ->leftJoin('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
        ->where('loan.item_code', $code)
        ->where('loan.is_return', 0)
        ->select(
            'loan.loan_id',
            'loan.member_id',
            'member.member_name',
            'loan.item_code',
            'biblio.title',
            'loan.loan_date',
            'loan.due_date'
        )
        ->first();

    if (!$transaksi) {
        return response()->json([
            'status' => 'error',
            'message' => 'Buku ini sedang tidak dalam status dipinjam / transaksi tidak ditemukan!'
        ], 404);
    }

    // Hitung denda Keterlambatan
    $tglHarusKembali = \Carbon\Carbon::parse($transaksi->due_date);
    $tglSekarang = \Carbon\Carbon::now();
    
    $hariTerlambat = 0;
    $denda = 0;
    $tarifDendaPerHari = 1000; // Atur nominal denda per hari di sini

    if ($tglSekarang->gt($tglHarusKembali)) {
        $hariTerlambat = $tglSekarang->diffInDays($tglHarusKembali);
        $denda = $hariTerlambat * $tarifDendaPerHari;
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'loan_id' => $transaksi->loan_id ?? null,
            'member_id' => $transaksi->member_id,
            'member_name' => $transaksi->member_name ?? 'Siswa Tidak Ditemukan',
            'item_code' => $transaksi->item_code,
            'title' => $transaksi->title ?? 'Buku Tidak Ditemukan',
            'loan_date' => date('d-m-Y', strtotime($transaksi->loan_date)),
            'due_date' => date('d-m-Y', strtotime($transaksi->due_date)),
            'hari_terlambat' => $hariTerlambat,
            'denda' => $denda,
            'denda_formatted' => 'Rp ' . number_format($denda, 0, ',', '.')
        ]
    ]);
}

    public function kembalikanViaQr(Request $request)
{
    $startTime = microtime(true);

    $request->validate([
        'item_code' => 'required',
    ]);

    // Cari transaksi yang item_code cocok dan BELUM dikembalikan (is_return = 0)
    $transaksi = Transaksi::where('item_code', $request->item_code)
                          ->where('is_return', 0)
                          ->first();

    if (!$transaksi) {
        return back()->with('error', 'Buku dengan kode tersebut tidak ditemukan dalam daftar peminjaman aktif!');
    }

    // Ubah status jadi dikembalikan (1)
    $transaksi->is_return = 1;
    $transaksi->return_date = now();
    $transaksi->save();

    $executionTime = number_format(microtime(true) - $startTime, 2);

    return back()->with('success', "Buku (Kode: {$request->item_code}) berhasil dikembalikan! (Waktu proses: {$executionTime} detik)");
}

    // 4. Tombol Kembalikan Manual (Opsional)
    public function kembalikan($id)
    {
        $startTime = microtime(true);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->is_return = 1;
        $transaksi->return_date = now();
        $transaksi->save();

        $executionTime = number_format(microtime(true) - $startTime, 2);

        return back()->with('success', "Buku berhasil dikembalikan! (Waktu proses: {$executionTime} detik)");
    }
}