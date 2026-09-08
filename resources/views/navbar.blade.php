<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <!-- LOGO & NAMA PERPUSTAKAAN -->
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('dashboard') }}">
            <!-- Logo Sekolah -->
            <img src="{{ asset('img/tamansiswa.png') }}" alt="Logo Sekolah" width="35" height="35" class="me-2 d-inline-block align-text-top">
            
            <span>Manajemen Perpustakaan Tamansiswa</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu Utama -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard*') ? 'active fw-bold' : '' }}" href="{{ url('/dashboard') }}">
                        <i class="fa-solid fa-house me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('buku*') ? 'active fw-bold' : '' }}" href="{{ url('/buku') }}">
                        <i class="fa-solid fa-book me-1"></i> Data Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('siswa*') ? 'active fw-bold' : '' }}" href="{{ url('/siswa') }}">
                        <i class="fa-solid fa-users me-1"></i> Data Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('transaksi*') ? 'active fw-bold' : '' }}" href="{{ url('/transaksi') }}">
                        <i class="fa-solid fa-right-left me-1"></i> Transaksi
                    </a>
                </li>
            </ul>

            <!-- Info Petugas & Logout -->
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-circle-user me-1"></i> {{ Auth::user()->realname ?? Auth::user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><span class="dropdown-item-text text-muted small">Petugas Aktif</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-bold">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>