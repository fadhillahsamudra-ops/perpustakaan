<!DOCTYPE html>
<html lang="id" class="notranslate" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>Transaksi Peminjaman - Tamansiswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
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
            background-color: #ffc107;
            color: #000;
        }
        #reader {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
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
                        <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-right-left text-warning me-2"></i> Transaksi Peminjaman & Pengembalian</h2>
                        <p class="text-muted mb-0">Perpustakaan SD Swasta Taman Siswa Cab. Tanjung Sari</p>
                    </div>
                </div>

                <!-- Alert Pesan Sukses / Error -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Area Form Pinjam & Kembalikan -->
                <div class="row mb-4">
                    <!-- Form Peminjaman Baru -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card p-4 h-100">
                            <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-qrcode me-2"></i> Form Peminjaman Baru</h5>
                            <form action="{{ route('transaksi.store') }}" method="POST" onsubmit="prepareSubmit()">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">ID Anggota / Siswa</label>
                                    <div class="input-group">
                                        <input type="text" id="member_id_input" name="member_id" class="form-control" placeholder="Scan / Ketik ID Siswa (S001)" required autofocus>
                                        <button class="btn btn-outline-success" type="button" onclick="startScanner('member_id_input')">
                                            <i class="fa-solid fa-camera me-1"></i> Scan
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kode Buku / Eksemplar</label>
                                    <div class="input-group">
                                        <input type="text" id="item_code_input" name="item_code" class="form-control" placeholder="Scan / Ketik Kode Buku (B001)" required>
                                        <button class="btn btn-outline-primary" type="button" onclick="startScanner('item_code_input')">
                                            <i class="fa-solid fa-camera me-1"></i> Scan
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning fw-bold w-100 mt-2 py-2">
                                    <i class="fa-solid fa-plus me-1"></i> Proses Peminjaman
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Card Pengembalian Cepat -->
                    <div class="col-md-6">
                        <div class="card p-4 h-100 border-0 shadow-sm rounded-3">
                            <h5 class="fw-bold text-success mb-3">
                                <i class="fa-solid fa-rotate-left me-2"></i>Pengembalian Cepat
                            </h5>
                            <form id="formReturn" action="{{ route('transaksi.kembaliQr') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Scan QR / Kode Buku Kembalian</label>
                                    <div class="input-group">
                                        <input type="text" id="return_item_code_input" name="item_code" class="form-control" placeholder="Scan Kode Buku..." required>
                                        <button type="button" class="btn btn-outline-success" onclick="startScanner('return_item_code_input')">
                                            <i class="fa-solid fa-camera me-1"></i> Scan
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2" style="font-size: 11px;">Scan QR buku untuk memverifikasi data & denda sebelum dikembalikan.</small>
                                </div>

                                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-4">
                                    <i class="fa-solid fa-circle-check me-1"></i> Process Pengembalian
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data Transaksi -->
                <div class="card p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-list-check me-2"></i> Riwayat & Status Peminjaman</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px;">No</th>
                                    <th scope="col">Peminjam (Siswa)</th>
                                    <th scope="col">Buku Dipinjam</th>
                                    <th scope="col">Tgl Pinjam</th>
                                    <th scope="col">Tgl Harus Kembali</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $index => $t)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $t->siswa->member_name ?? 'Siswa Tidak Ditemukan' }}</div>
                                        <small class="text-muted">ID: {{ $t->member_id }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $t->buku->biblio->title ?? 'Judul Buku Tidak Ditemukan' }}</div>
                                        <small class="badge bg-secondary">{{ $t->item_code }}</small>
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($t->loan_date)) }}</td>
                                    <td>
                                        <span class="fw-bold text-danger">
                                            {{ date('d-m-Y', strtotime($t->due_date)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($t->is_return == 0)
                                            <span class="badge bg-warning text-dark px-3 py-2"><i class="fa-solid fa-clock me-1"></i> Dipinjam</span>
                                        @else
                                            <span class="badge bg-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($t->is_return == 0)
                                            <form action="{{ route('transaksi.kembalikan', $t->loan_id ?? $t->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3" onclick="return confirm('Apakah buku ini sudah dikembalikan?')">
                                                    <i class="fa-solid fa-arrow-rotate-left me-1"></i> Kembalikan
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-light border text-muted rounded-pill px-3" disabled>
                                                Selesai
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
                                        Belum ada data transaksi peminjaman.
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

    <!-- Modal Camera Scanner -->
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-camera me-2"></i> Scan QR Code via Kamera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopScanner()"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div id="reader" style="width: 100%; max-width: 350px; margin: 0 auto;" class="rounded-3 border"></div>
                    <small class="text-muted mt-3 d-block">Arahkan QR Code Kartu / Buku ke depan kamera.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up Validation Member -->
    <div class="modal fade" id="memberInfoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-id-card me-2"></i> Konfirmasi Data Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-circle-user text-primary fa-4x"></i>
                    </div>
                    <div id="memberStatusAlert"></div>
                    <table class="table table-borderless text-start mt-3">
                        <tr><th style="width: 35%;">ID Siswa</th><td>: <span id="popMemberId" class="fw-bold"></span></td></tr>
                        <tr><th>Nama Siswa</th><td>: <span id="popMemberName" class="fw-bold text-dark"></span></td></tr>
                        <tr><th>Instansi</th><td>: <span id="popMemberInstansi"></span></td></tr>
                        <tr><th>Masa Berlaku</th><td>: <span id="popMemberExpire" class="fw-bold"></span></td></tr>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnContinueLoan" class="btn btn-primary fw-bold" onclick="confirmMemberSelection()">
                        <i class="fa-solid fa-check me-1"></i> Lanjutkan Peminjaman
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up Preview Buku -->
    <div class="modal fade" id="bookInfoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-book me-2"></i> Preview Buku Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-book-bookmark text-info fa-4x"></i>
                    </div>
                    <div id="bookStatusAlert"></div>
                    <table class="table table-borderless text-start mt-3">
                        <tr><th style="width: 35%;">Kode Buku</th><td>: <span id="popBookCode" class="fw-bold"></span></td></tr>
                        <tr><th>Judul Buku</th><td>: <span id="popBookTitle" class="fw-bold text-dark"></span></td></tr>
                        <tr><th>Sisa Stok</th><td>: <span id="popBookStock" class="fw-bold"></span></td></tr>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnContinueBook" class="btn btn-info text-white fw-bold" onclick="confirmBookSelection()">
                        <i class="fa-solid fa-check me-1"></i> Gunakan Buku Ini
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up Konfirmasi Pengembalian -->
    <div class="modal fade" id="returnInfoModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-rotate-left me-2"></i> Konfirmasi Pengembalian Buku</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-box-archive text-success fa-4x"></i>
                    </div>
                    <div id="returnDendaAlert"></div>
                    <table class="table table-borderless text-start mt-3">
                        <tr><th style="width: 40%;">ID & Nama Siswa</th><td>: <span id="popReturnMember" class="fw-bold text-dark"></span></td></tr>
                        <tr><th>Kode & Judul Buku</th><td>: <span id="popReturnBook" class="fw-bold text-dark"></span></td></tr>
                        <tr><th>Tgl Pinjam</th><td>: <span id="popReturnLoanDate"></span></td></tr>
                        <tr><th>Tgl Harus Kembali</th><td>: <span id="popReturnDueDate" class="text-danger fw-bold"></span></td></tr>
                        <tr><th>Keterlambatan</th><td>: <span id="popReturnLate" class="fw-bold"></span></td></tr>
                        <tr><th>Total Denda</th><td>: <span id="popReturnFine" class="fw-bold fs-5 text-danger"></span></td></tr>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success fw-bold" onclick="confirmReturnSubmit()">
                        <i class="fa-solid fa-check me-1"></i> Konfirmasi & Selesaikan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & HTML5 QR JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let html5QrCode;
        let activeInputId = null;
        
        let tempScannedMemberId = '';
        let tempScannedMemberName = '';
        let tempScannedBookCode = '';
        let tempScannedBookTitle = '';
        let tempReturnBookCode = '';

        function startScanner(targetInputId) {
            activeInputId = targetInputId;

            const modalElement = document.getElementById('scannerModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();

            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    stopScanner();
                    modal.hide();
                    handleScanResult(activeInputId, decodedText);
                },
                (errorMessage) => {}
            ).catch(err => alert("Gagal membuka kamera: " + err));
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                }).catch(err => console.error(err));
            }
        }

        function handleScanResult(inputId, scannedText) {
            if (inputId === 'member_id_input') {
                // POP-UP MEMBER SISWA
                fetch(`/transaksi/cek-member/${scannedText}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === 'success') {
                            tempScannedMemberId = res.data.member_id;
                            tempScannedMemberName = res.data.member_name;

                            document.getElementById('popMemberId').innerText = res.data.member_id;
                            document.getElementById('popMemberName').innerText = res.data.member_name;
                            document.getElementById('popMemberInstansi').innerText = res.data.instansi;
                            document.getElementById('popMemberExpire').innerText = res.data.expire_date;

                            const alertDiv = document.getElementById('memberStatusAlert');
                            const btnLoan = document.getElementById('btnContinueLoan');

                            if (res.data.is_expired) {
                                alertDiv.innerHTML = `<div class="alert alert-danger py-2 mb-0 fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Kartu Sudah Kadaluarsa! Peminjaman Ditolak.</div>`;
                                btnLoan.disabled = true;
                                btnLoan.className = 'btn btn-secondary fw-bold';
                            } else {
                                alertDiv.innerHTML = `<div class="alert alert-success py-2 mb-0 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Kartu Aktif / Valid</div>`;
                                btnLoan.disabled = false;
                                btnLoan.className = 'btn btn-primary fw-bold';
                            }

                            const memberModal = new bootstrap.Modal(document.getElementById('memberInfoModal'));
                            memberModal.show();
                        } else {
                            alert(res.message);
                        }
                    })
                    .catch(err => alert("Gagal memvalidasi data siswa!"));

            } else if (inputId === 'item_code_input') {
                // POP-UP PREVIEW BUKU PINJAM
                fetch(`/transaksi/cek-buku/${scannedText}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === 'success') {
                            tempScannedBookCode = res.data.item_code;
                            tempScannedBookTitle = res.data.title;

                            document.getElementById('popBookCode').innerText = res.data.item_code;
                            document.getElementById('popBookTitle').innerText = res.data.title;
                            document.getElementById('popBookStock').innerText = res.data.stok + ' Eksemplar';

                            const alertDiv = document.getElementById('bookStatusAlert');
                            const btnBook = document.getElementById('btnContinueBook');

                            if (!res.data.is_available) {
                                alertDiv.innerHTML = `<div class="alert alert-danger py-2 mb-0 fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Stok Buku Habis / Tidak Bisa Dipinjam!</div>`;
                                btnBook.disabled = true;
                                btnBook.className = 'btn btn-secondary fw-bold';
                            } else {
                                alertDiv.innerHTML = `<div class="alert alert-success py-2 mb-0 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Buku Tersedia</div>`;
                                btnBook.disabled = false;
                                btnBook.className = 'btn btn-info text-white fw-bold';
                            }

                            const bookModal = new bootstrap.Modal(document.getElementById('bookInfoModal'));
                            bookModal.show();
                        } else {
                            alert(res.message);
                        }
                    })
                    .catch(err => alert("Gagal memvalidasi data buku!"));

            } else if (inputId === 'return_item_code_input') {
                // POP-UP PENGEMBALIAN BUKU
                fetch(`/transaksi/cek-kembali/${scannedText}`)
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === 'success') {
                            tempReturnBookCode = res.data.item_code;

                            document.getElementById('popReturnMember').innerText = `${res.data.member_id} - ${res.data.member_name}`;
                            document.getElementById('popReturnBook').innerText = `${res.data.item_code} - ${res.data.title}`;
                            document.getElementById('popReturnLoanDate').innerText = res.data.loan_date;
                            document.getElementById('popReturnDueDate').innerText = res.data.due_date;
                            document.getElementById('popReturnLate').innerText = res.data.hari_terlambat + ' Hari';
                            document.getElementById('popReturnFine').innerText = res.data.denda_formatted;

                            const alertDiv = document.getElementById('returnDendaAlert');

                            if (res.data.denda > 0) {
                                alertDiv.innerHTML = `<div class="alert alert-warning py-2 mb-0 fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Terlambat! Siswa dikenakan denda ${res.data.denda_formatted}</div>`;
                            } else {
                                alertDiv.innerHTML = `<div class="alert alert-success py-2 mb-0 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Tepat Waktu (Bebas Denda)</div>`;
                            }

                            const returnModal = new bootstrap.Modal(document.getElementById('returnInfoModal'));
                            returnModal.show();
                        } else {
                            alert(res.message);
                        }
                    })
                    .catch(err => alert("Gagal memproses data pengembalian!"));
            }
        }

        function confirmMemberSelection() {
            const memberInput = document.getElementById('member_id_input');
            memberInput.value = `${tempScannedMemberId} / ${tempScannedMemberName}`;
            memberInput.setAttribute('data-real-id', tempScannedMemberId);

            const memberModal = bootstrap.Modal.getInstance(document.getElementById('memberInfoModal'));
            if (memberModal) memberModal.hide();
            
            document.getElementById('item_code_input').focus();
        }

        function confirmBookSelection() {
            const bookInput = document.getElementById('item_code_input');
            bookInput.value = `${tempScannedBookCode} / ${tempScannedBookTitle}`;
            bookInput.setAttribute('data-real-code', tempScannedBookCode);

            const bookModal = bootstrap.Modal.getInstance(document.getElementById('bookInfoModal'));
            if (bookModal) bookModal.hide();
        }

        function confirmReturnSubmit() {
            document.getElementById('return_item_code_input').value = tempReturnBookCode;
            document.getElementById('formReturn').submit();
        }

        function prepareSubmit() {
            const memberInput = document.getElementById('member_id_input');
            const bookInput = document.getElementById('item_code_input');
            
            const realMemberId = memberInput.getAttribute('data-real-id');
            const realBookCode = bookInput.getAttribute('data-real-code');
            
            if (realMemberId) memberInput.value = realMemberId;
            if (realBookCode) bookInput.value = realBookCode;
        }
    </script>
</body>
</html>