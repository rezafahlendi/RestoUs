<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin - RestoUs')</title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .admin-sidebar {
            background-color: #0f172a;
            min-height: 100vh;
        }
        .admin-nav-link {
            color: #94a3b8;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            text-decoration: none;
            margin-bottom: 0.3rem;
            transition: all 0.2s;
        }
        .admin-nav-link:hover, .admin-nav-link.active {
            color: #ffffff;
            background-color: #1e293b;
        }
        .admin-nav-link.active {
            border-left: 4px solid #f59e0b;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08);
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Admin -->
            <div class="col-lg-2 col-md-3 admin-sidebar p-3 text-white">
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-secondary">
                    <span class="badge bg-warning text-dark me-2 px-2 py-1 fs-5"><i class="bi bi-shield-lock-fill"></i></span>
                    <div>
                        <div class="fw-bold fs-5 leading-none">Resto<span class="text-warning">Us</span></div>
                        <small class="text-secondary" style="font-size: 0.75rem;">Super Admin Panel</small>
                    </div>
                </div>

                <div class="nav flex-column">
                    <div class="text-uppercase text-secondary small fw-bold px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Manajemen Restoran</div>
                    
                    <a href="{{ route('admin.reservations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2 fs-5"></i> Kelola Reservasi
                    </a>

                    <a href="{{ route('admin.menus.index') }}" class="admin-nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <i class="bi bi-egg-fried me-2 fs-5"></i> Menu & Kuota Harian
                    </a>

                    <a href="{{ route('admin.tables.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap me-2 fs-5"></i> Kelola Meja
                    </a>

                    <hr class="border-secondary my-4">

                    <div class="px-3 mb-2 small text-muted">
                        Login sebagai: <br>
                        <strong class="text-warning">{{ Auth::user()->name ?? 'Admin' }}</strong>
                    </div>

                    <a href="{{ route('customer.home') }}" class="admin-nav-link text-warning mb-2">
                        <i class="bi bi-box-arrow-up-right me-2"></i> Ke Web Customer
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-nav-link text-danger w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-10 col-md-9 py-4 px-md-4">
                <!-- Topbar -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">@yield('page_title', 'Dasbor Pengelolaan RestoUs')</h4>
                        <small class="text-muted">Kelola reservasi masuk, kuota porsi harian, dan meja</small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Sistem Aktif (10:00 - 21:00)
                        </span>
                        <a href="{{ route('customer.booking.create') }}" target="_blank" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Uji Coba Booking
                        </a>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
