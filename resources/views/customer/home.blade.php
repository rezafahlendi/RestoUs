@extends('layouts.customer')

@section('title', 'Selamat Datang di RestoUs - Restoran Pilihan Terbaik')

@section('content')
<!-- Hero Section -->
<div class="row align-items-center py-5 gy-4">
    <div class="col-lg-6">
        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill fw-bold mb-3">
            <i class="bi bi-clock-history me-1"></i> Buka Setiap Hari: 10:00 - 21:00 WIB
        </span>
        <h1 class="display-4 fw-extrabold text-dark mb-3">
            Nikmati Hidangan Lezat dengan <span class="text-warning">Reservasi Mudah</span>
        </h1>
        <p class="lead text-muted mb-4">
            Pesan meja dan menu favorit Anda lebih awal tanpa perlu mengantre. Tersedia kuota harian segar 50 porsi per menu dengan durasi makan nyaman 2 jam.
        </p>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('customer.booking.create') }}" class="btn btn-warning btn-lg fw-bold px-4 shadow-sm">
                <i class="bi bi-calendar-check me-2"></i> Booking Meja Sekarang
            </a>
            <a href="{{ route('customer.menu') }}" class="btn btn-outline-dark btn-lg fw-semibold px-4">
                <i class="bi bi-book me-2"></i> Lihat Daftar Menu
            </a>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom overflow-hidden border-0 shadow">
            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=80" class="img-fluid rounded-3" alt="RestoUs Interior" style="max-height: 400px; object-fit: cover;">
        </div>
    </div>
</div>

<!-- Highlight 4 Aturan Utama -->
<div class="row gy-3 my-4">
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center h-100 bg-white">
            <div class="text-warning fs-1 mb-2"><i class="bi bi-calendar-day"></i></div>
            <h6 class="fw-bold mb-1">Minimal H-1</h6>
            <small class="text-muted">Pemesanan wajib paling lambat 1 hari sebelum jadwal makan.</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center h-100 bg-white">
            <div class="text-warning fs-1 mb-2"><i class="bi bi-hourglass-split"></i></div>
            <h6 class="fw-bold mb-1">Durasi 2 Jam</h6>
            <small class="text-muted">Waktu santap di tempat maksimal 2 jam demi rotasi meja.</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center h-100 bg-white">
            <div class="text-warning fs-1 mb-2"><i class="bi bi-box2-heart"></i></div>
            <h6 class="fw-bold mb-1">50 Porsi / Menu / Hari</h6>
            <small class="text-muted">Bahan masakan segar dengan kuota harian terbatas.</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom p-3 text-center h-100 bg-white">
            <div class="text-warning fs-1 mb-2"><i class="bi bi-whatsapp"></i></div>
            <h6 class="fw-bold mb-1">Batas Bayar 1 Jam</h6>
            <small class="text-muted">Konfirmasi bayar via WA ke 082254781995 dalam 60 menit.</small>
        </div>
    </div>
</div>

<!-- Preview Menu Populer -->
<div class="mt-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Menu Andalan RestoUs</h3>
            <p class="text-muted mb-0">Cicipi 4 varian makanan dan 4 minuman pilihan terbaik kami</p>
        </div>
        <a href="{{ route('customer.menu') }}" class="btn btn-outline-warning text-dark fw-bold">
            Lihat Semua Menu <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        @foreach($popularFoods as $food)
            <div class="col-md-4">
                <div class="card card-custom h-100 bg-white overflow-hidden">
                    <img src="{{ $food->image_url }}" class="card-img-top" alt="{{ $food->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <span class="badge bg-danger-subtle text-danger mb-2">Makanan</span>
                        <h5 class="fw-bold mb-1">{{ $food->name }}</h5>
                        <p class="text-muted small mb-3">{{ Str::limit($food->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark fs-5">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                            <span class="badge bg-secondary-subtle text-secondary">Kuota: {{ $food->daily_quota }} / hari</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
