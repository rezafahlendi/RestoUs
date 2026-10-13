@extends('layouts.customer')

@section('title', 'Katalog Menu Makanan & Minuman - RestoUs')

@section('content')
<div class="py-3">
    <!-- Header & Date Filter -->
    <div class="row align-items-center mb-4 gy-3">
        <div class="col-md-7">
            <h2 class="fw-bold text-dark mb-1">Daftar Menu RestoUs</h2>
            <p class="text-muted mb-0">Setiap menu dibuat segar setiap hari dengan batas maksimal <strong>50 porsi / hari</strong>.</p>
        </div>
        <div class="col-md-5">
            <form action="{{ route('customer.menu') }}" method="GET" class="card p-2 bg-white shadow-sm border-0">
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 small fw-bold text-muted text-nowrap"><i class="bi bi-calendar-event me-1"></i> Cek Kuota Tanggal:</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate }}" min="{{ now()->addDay()->toDateString() }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    <!-- Alert info tanggal yang dicek -->
    <div class="alert alert-light border shadow-sm mb-4 d-flex align-items-center justify-content-between">
        <div>
            <i class="bi bi-info-circle text-warning me-2 fs-5 align-middle"></i>
            Menampilkan sisa kuota untuk tanggal: <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</strong>
        </div>
        <a href="{{ route('customer.booking.create', ['date' => $selectedDate]) }}" class="btn btn-warning btn-sm fw-bold">
            <i class="bi bi-calendar-plus me-1"></i> Pesan Untuk Tanggal Ini
        </a>
    </div>

    <!-- Section 1: Makanan (4 Jenis) -->
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-danger-subtle text-danger p-2 rounded-3 me-2 fs-5"><i class="bi bi-fire"></i></div>
            <h4 class="fw-bold text-dark mb-0">Menu Makanan (Foods)</h4>
        </div>

        <div class="row g-4">
            @foreach($foods as $food)
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom h-100 bg-white overflow-hidden d-flex flex-column">
                        <div class="position-relative">
                            <img src="{{ $food->image_url }}" class="card-img-top" alt="{{ $food->name }}" style="height: 180px; object-fit: cover;">
                            @if($food->remaining_quota <= 0)
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center">
                                    <span class="badge bg-danger fs-6 px-3 py-2 fw-bold text-uppercase">Menu Habis (Sold Out)</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="fw-bold text-dark mb-1">{{ $food->name }}</h6>
                            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.85rem;">{{ $food->description }}</p>
                            
                            <div class="border-top pt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark fs-6">Rp {{ number_format($food->price, 0, ',', '.') }}</span>
                                    @if($food->remaining_quota > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Sisa {{ $food->remaining_quota }} Porsi
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section 2: Minuman (4 Jenis) -->
    <div class="mb-5">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary-subtle text-primary p-2 rounded-3 me-2 fs-5"><i class="bi bi-cup-straw"></i></div>
            <h4 class="fw-bold text-dark mb-0">Menu Minuman (Drinks)</h4>
        </div>

        <div class="row g-4">
            @foreach($drinks as $drink)
                <div class="col-lg-3 col-md-6">
                    <div class="card card-custom h-100 bg-white overflow-hidden d-flex flex-column">
                        <div class="position-relative">
                            <img src="{{ $drink->image_url }}" class="card-img-top" alt="{{ $drink->name }}" style="height: 180px; object-fit: cover;">
                            @if($drink->remaining_quota <= 0)
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center">
                                    <span class="badge bg-danger fs-6 px-3 py-2 fw-bold text-uppercase">Menu Habis (Sold Out)</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="fw-bold text-dark mb-1">{{ $drink->name }}</h6>
                            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.85rem;">{{ $drink->description }}</p>
                            
                            <div class="border-top pt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark fs-6">Rp {{ number_format($drink->price, 0, ',', '.') }}</span>
                                    @if($drink->remaining_quota > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Sisa {{ $drink->remaining_quota }} Porsi
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
