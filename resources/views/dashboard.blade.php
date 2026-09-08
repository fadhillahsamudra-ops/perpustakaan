<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tamansiswa Perpustakaan - Home</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .menu-card {
            border: none;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
            color: inherit;
        }
    </style>
</head>
<body>

    <!-- Include Navbar Utama -->
    @include('navbar')

    <div class="container my-4">
        <!-- Banner Selamat Datang -->
        <div class="p-4 p-md-5 mb-4 rounded-4 bg-success text-white shadow-sm position-relative overflow-hidden">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold display-6 mb-2">Halo, {{ Auth::user()->realname ?? Auth::user()->username }}! 👋</h2>
                    <p class="lead mb-0 opacity-75">Selamat datang di Manajemen Perpustakaan Sekolah Tamansiswa. Siap melayani peminjaman dan pengembalian hari ini?</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block opacity-25">
                    <i class="fa-solid fa-book-reader fa-8x"></i>
                </div>
            </div>
        </div>

        <!-- Cards Ringkasan Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card p-3 shadow-sm bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 text-uppercase fw-bold mb-1">Total Koleksi Buku</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($totalBuku) }}</h2>
                        </div>
                        <i class="fa-solid fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3 shadow-sm bg-info text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 text-uppercase fw-bold mb-1">Total Siswa / Anggota</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($totalSiswa) }}</h2>
                        </div>
                        <i class="fa-solid fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-3 shadow-sm bg-warning text-dark">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-dark-50 text-uppercase fw-bold mb-1">Buku Sedang Dipinjam</h6>
                            <h2 class="fw-bold mb-0">{{ number_format($sedangDipinjam) }}</h2>
                        </div>
                        <i class="fa-solid fa-hand-holding-hand fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Pintas Menu Utama (Hub) -->
        <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-grip me-2"></i> Menu Layanan Cepat</h5>
        <div class="row g-4">
            <!-- Menu Transaksi -->
            <div class="col-md-4">
                <a href="{{ url('/transaksi') }}" class="card menu-card p-4 shadow-sm h-100 bg-white border-start border-warning border-5">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-dark p-3 rounded-circle me-3">
                            <i class="fa-solid fa-right-left fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Sirkulasi Transaksi</h5>
                            <small class="text-muted d-block">Scan QR Pinjam & Pengembalian Cepat</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Menu Data Buku -->
            <div class="col-md-4">
                <a href="{{ url('/buku') }}" class="card menu-card p-4 shadow-sm h-100 bg-white border-start border-primary border-5">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white p-3 rounded-circle me-3">
                            <i class="fa-solid fa-book-open fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Katalog Data Buku</h5>
                            <small class="text-muted d-block">Cari judul, pengarang, & stok buku</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Menu Data Siswa -->
            <div class="col-md-4">
                <a href="{{ url('/siswa') }}" class="card menu-card p-4 shadow-sm h-100 bg-white border-start border-info border-5">
                    <div class="d-flex align-items-center">
                        <div class="bg-info text-white p-3 rounded-circle me-3">
                            <i class="fa-solid fa-address-book fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Data Anggota / Siswa</h5>
                            <small class="text-muted d-block">Daftar siswa & cetak ID QR Code</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>