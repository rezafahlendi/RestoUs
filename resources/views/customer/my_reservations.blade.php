@extends('layouts.customer')

@section('title', 'Riwayat Pesanan Saya - RestoUs')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-lg-10">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">Riwayat Pemesanan Meja Anda</h3>
            <p class="text-muted">Masukkan nomor WhatsApp/telepon yang digunakan saat melakukan pemesanan</p>
        </div>

        <!-- Form Cari Nomor HP -->
        <div class="card card-custom bg-white p-4 mb-4 shadow-sm border-0">
            <form action="{{ route('customer.my-reservations') }}" method="GET" class="row g-3 align-items-center justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789" value="{{ $phone }}" required>
                    </div>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-warning fw-bold px-4">
                        <i class="bi bi-search me-1"></i> Cek Riwayat
                    </button>
                </div>
            </form>
        </div>

        <!-- Hasil Pencarian -->
        @if($phone)
            <div class="card card-custom bg-white shadow-sm border-0 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Kode</th>
                                    <th>Meja</th>
                                    <th>Jadwal Kedatangan</th>
                                    <th>Tamu</th>
                                    <th>Total Menu</th>
                                    <th>Status</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $res)
                                    <tr>
                                        <td class="ps-4 font-monospace fw-bold text-dark">
                                            #RES-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $res->table->table_number ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div><i class="bi bi-calendar-event me-1 text-primary"></i>{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') }}</div>
                                            <small class="text-muted">Pukul {{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }} WIB (2 Jam)</small>
                                        </td>
                                        <td>{{ $res->guest_count }} Orang</td>
                                        <td class="fw-bold">Rp {{ number_format($res->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($res->status === 'confirmed')
                                                <span class="badge bg-success">Terkonfirmasi</span>
                                            @elseif($res->status === 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                            @elseif($res->status === 'completed')
                                                <span class="badge bg-primary">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">Dibatalkan / Kadaluarsa</span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('customer.booking.show', $res->id) }}" class="btn btn-sm btn-outline-dark" title="Lihat Detail">
                                                    <i class="bi bi-eye me-1"></i> Detail
                                                </a>
                                                <a href="{{ route('customer.booking.invoice', $res->id) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Cetak / Unduh Invoice PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i> Invoice
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                            Tidak ditemukan data reservasi untuk nomor telepon <strong>{{ $phone }}</strong>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
