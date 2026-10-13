<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RestoUs - Restoran & Pemesanan Meja Online')</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #d97706;
            --primary-hover: #b45309;
            --dark-color: #1e293b;
            --accent-color: #f59e0b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }
        .nav-link {
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
        }
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-custom:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }
        footer {
            margin-top: auto;
            background-color: #0f172a;
            color: #94a3b8;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand text-white d-flex align-items-center" href="{{ route('customer.home') }}">
                <span class="badge bg-warning text-dark me-2 px-2 py-1 fs-6"><i class="bi bi-fire"></i></span>
                Resto<span class="text-warning">Us</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#customerNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="customerNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('customer.home') ? 'active' : 'text-white' }}" href="{{ route('customer.home') }}">
                            <i class="bi bi-house-door me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('customer.menu') ? 'active' : 'text-white' }}" href="{{ route('customer.menu') }}">
                            <i class="bi bi-cup-hot me-1"></i> Daftar Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('customer.rules') ? 'active' : 'text-white' }}" href="{{ route('customer.rules') }}">
                            <i class="bi bi-info-circle me-1"></i> Rules Restoran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('customer.my-reservations') ? 'active' : 'text-white' }}" href="{{ route('customer.my-reservations') }}">
                            <i class="bi bi-clock-history me-1"></i> Riwayat Pesanan
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('customer.booking.create') }}" class="btn btn-warning fw-bold px-3 shadow-sm">
                        <i class="bi bi-calendar-plus me-1"></i> Booking Meja
                    </a>

                    @auth
                        <!-- Dropdown Akun Pengguna yang Sudah Login -->
                        <div class="dropdown ms-2">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span class="small fw-semibold">{{ Auth::user()->name }}</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="badge bg-danger" style="font-size: 0.65rem;">ADMIN</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li class="px-3 py-2 border-bottom">
                                    <small class="text-muted d-block">Masuk sebagai:</small>
                                    <strong class="text-dark">{{ Auth::user()->email }}</strong>
                                </li>
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('admin.reservations.index') }}">
                                            <i class="bi bi-shield-lock-fill me-2"></i> Panel Super Admin
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('customer.my-reservations') }}">
                                        <i class="bi bi-receipt me-2"></i> Riwayat Reservasi Saya
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Tombol Masuk & Daftar jika Belum Login -->
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm ms-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-warning btn-sm">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                    <strong>Perhatian!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2 fs-5 align-middle"></i>
                    <strong>Terjadi Kesalahan:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center text-md-start">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-md-6">
                    <div class="fw-bold text-white fs-5 mb-1">Resto<span class="text-warning">Us</span></div>
                    <small>Sistem Reservasi Meja & Menu Online | Buka Pukul <strong>10:00 - 21:00 WIB</strong></small>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="text-white small mb-1">
                        <i class="bi bi-whatsapp text-success me-1"></i> Konfirmasi WhatsApp: 
                        <a href="https://wa.me/6282254781995" target="_blank" class="text-warning text-decoration-none fw-semibold">0822-5478-1995</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
