<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Anggota - {{ $siswa->member_id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .btn-print {
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #198754;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Desain Kartu Pelajar / Anggota SD */
        .card-member {
            width: 380px;
            background: #ffffff;
            border-radius: 12px;
            border: 2px solid #198754;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #198754;
            color: #ffffff;
            padding: 12px;
            text-align: center;
        }
        .card-header h3 {
            margin: 0;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .card-header p {
            margin: 2px 0 0;
            font-size: 11px;
            opacity: 0.9;
        }
        .card-body {
            padding: 15px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .photo-box {
            width: 90px;
            height: 110px;
            border: 2px solid #ddd;
            border-radius: 6px;
            object-fit: cover;
            background-color: #eee;
        }
        .info-box {
            flex: 1;
        }
        .info-table {
            font-size: 12px;
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 85px;
        }
        .qr-section {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 1px dashed #ddd;
        }
        .card-footer {
            background-color: #198754;
            color: white;
            padding: 6px;
            font-size: 9px;
            text-align: center;
        }

        @media print {
            body { background-color: #fff; }
            .btn-print { display: none; }
            .card-member { box-shadow: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print">
        🖨️ Cetak Kartu Anggota
    </button>

    <div class="card-member">
        <div class="card-header">
            <h3>KARTU ANGGOTA PERPUSTAKAAN</h3>
            <p>{{ $siswa->inst_name ?? 'SD NEGERI PERPUSTAKAAN' }}</p>
        </div>
        
        <div class="card-body">
            <!-- Foto Profil Siswa SLiMS (fallback jika tidak ada foto) -->
            @if($siswa->member_image && file_exists(public_path('images/persons/' . $siswa->member_image)))
                <img src="{{ asset('images/persons/' . $siswa->member_image) }}" class="photo-box" alt="Foto Siswa">
            @else
                <img src="https://via.placeholder.com/90x110?text=Foto+Siswa" class="photo-box" alt="Foto Siswa">
            @endif

            <!-- Data Detail Siswa SD -->
            <div class="info-box">
                <table class="info-table">
                    <tr>
                        <td class="info-label">ID Anggota</td>
                        <td>: <strong>{{ $siswa->member_id }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Nama</td>
                        <td>: <strong>{{ $siswa->member_name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Lembaga</td>
                        <td>: {{ $siswa->inst_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Masa Berlaku</td>
                        <td>: {{ $siswa->expire_date ? date('d-m-Y', strtotime($siswa->expire_date)) : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Section QR Code di Bawah Kartu -->
        <div class="qr-section">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($siswa->member_id) !!}
        </div>

        <div class="card-footer">
            Kartu ini wajib dibawa saat berkunjung dan meminjam buku.
        </div>
    </div>

</body>
</html>