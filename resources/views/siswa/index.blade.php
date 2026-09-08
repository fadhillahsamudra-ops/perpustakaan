<!DOCTYPE html>
<html lang="id" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Daftar Siswa & QR Code - Tamansiswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .table thead {
            background-color: #198754;
            color: white;
        }
        .qr-box {
            padding: 8px;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            display: inline-block;
        }
    </style>
</head>
<body>
    @include('navbar')

    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-users text-success me-2"></i> Data Anggota / Siswa</h2>
                        <p class="text-muted mb-0">Perpustakaan SD Swasta Taman Siswa Cab. Tanjung Sari</p>
                    </div>
                </div>

                <!-- Tabel Data Siswa -->
                <div class="card p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px;">No</th>
                                    <th scope="col">ID Anggota</th>
                                    <th scope="col">Nama Anggota</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" class="text-center">QR Kartu Anggota</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswas as $index => $siswa)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-success fs-6">{{ $siswa->member_id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $siswa->member_name }}</div>
                                    </td>
                                    <td>{{ $siswa->member_email ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="qr-box">
                                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($siswa->member_id) !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('siswa.cetak', $siswa->member_id) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                            <i class="fa-solid fa-id-card me-1"></i> Cetak Kartu
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-user-slash fa-2x mb-2 d-block"></i>
                                        Belum ada data anggota/siswa.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>