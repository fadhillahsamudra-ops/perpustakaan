<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak QR Code - {{ $buku->item_code ?? 'Buku' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        .card-qr {
            border: 2px solid #000;
            border-radius: 10px;
            padding: 15px;
            width: 250px;
            margin: 0 auto;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .code {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print" style="margin-bottom: 20px; padding: 8px 16px; cursor: pointer;">
        🖨️ Cetak / Print Label
    </button>

    <div class="card-qr">
        <div class="code">{{ $buku->item_code }}</div>
        
        <!-- Panggil Namespace Lengkap SimpleSoftwareIO -->
        <div>
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($buku->item_code) !!}
        </div>

        <div class="title">{{ $buku->biblio->title ?? 'Judul Tidak Ditemukan' }}</div>
    </div>

</body>
</html>