@extends('layouts.customer')

@section('title', 'Bukti Pemesanan Reservasi - RestoUs')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-lg-8">
        <!-- Status Box -->
        @if($reservation->status === 'pending')
            <div class="card bg-warning-subtle border-warning p-4 mb-4 text-center">
                <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill mx-auto mb-2">
                    <i class="bi bi-hourglass-split me-1"></i> STATUS: MENUNGGU KONFIRMASI & PEMBAYARAN
                </span>
                <h4 class="fw-bold text-dark mt-2 mb-1">Pesanan Anda Berhasil Dicatat!</h4>
                <p class="text-muted small mb-3">
                    Silakan lakukan konfirmasi dan pengiriman bukti pembayaran via WhatsApp sebelum batas waktu habis.
                </p>

                <!-- Countdown Timer 1 Jam -->
                <div class="d-inline-flex align-items-center justify-content-center bg-white border rounded-pill px-4 py-2 mx-auto shadow-sm">
                    <i class="bi bi-alarm text-danger fs-5 me-2"></i>
                    <span class="small fw-bold text-secondary me-2">Sisa Waktu Pembayaran:</span>
                    <span id="countdownDisplay" class="fs-5 fw-extrabold text-danger font-monospace">--:--:--</span>
                </div>
                <small class="text-danger mt-2 d-block" id="expiredNotice" style="font-size: 0.8rem;">
                    *Jika melewati batas 1 jam belum dikonfirmasi, pesanan otomatis dibatalkan sistem.
                </small>
            </div>
        @elseif($reservation->status === 'confirmed')
            <div class="alert alert-success border-0 shadow-sm p-4 text-center mb-4">
                <span class="badge bg-success px-3 py-2 fs-6 rounded-pill mb-2">
                    <i class="bi bi-check-circle-fill me-1"></i> STATUS: TERKONFIRMASI (RESMI)
                </span>
                <h4 class="fw-bold text-success mb-1">Pembayaran Terverifikasi & Meja Terkunci!</h4>
                <p class="mb-0 text-muted small">Sampai jumpa di RestoUs pada jadwal yang telah Anda tentukan.</p>
            </div>
        @elseif($reservation->status === 'completed')
            <div class="alert alert-primary border-0 shadow-sm p-4 text-center mb-4">
                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill mb-2">
                    <i class="bi bi-stars me-1"></i> STATUS: SELESAI
                </span>
                <h4 class="fw-bold mb-0">Reservasi Telah Selesai. Terima kasih atas kunjungan Anda!</h4>
            </div>
        @else
            <div class="alert alert-danger border-0 shadow-sm p-4 text-center mb-4">
                <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill mb-2">
                    <i class="bi bi-x-circle-fill me-1"></i> STATUS: DIBATALKAN / EXPIRED
                </span>
                <h4 class="fw-bold text-danger mb-1">Reservasi Ini Dibatalkan</h4>
                <p class="mb-0 text-muted small">Batas waktu 1 jam pembayaran telah habis atau pesanan dibatalkan.</p>
            </div>
        @endif

        <!-- Card Detail Reservasi -->
        <div class="card card-custom bg-white shadow-sm border-0 mb-4 overflow-hidden">
            <div class="bg-dark text-white p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-bold"><i class="bi bi-receipt me-2"></i>Rincian Reservasi RestoUs</span>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('customer.booking.invoice', $reservation->id) }}" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-printer me-1"></i> Cetak Invoice
                    </a>
                    <span class="badge bg-warning text-dark font-monospace fs-6">#RES-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Pemesan</small>
                        <span class="fw-bold text-dark fs-6">{{ $reservation->customer->name ?? '-' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">No. WhatsApp / HP</small>
                        <span class="fw-bold text-dark fs-6">{{ $reservation->customer->phone ?? '-' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Meja yang Dipesan</small>
                        <span class="badge bg-secondary fs-6 px-3 py-2">
                            {{ $reservation->table->table_number ?? '-' }} (Kapasitas {{ $reservation->table->capacity ?? 0 }} Orang)
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Jadwal Kedatangan (Maks 2 Jam)</small>
                        <div class="fw-bold text-primary">
                            <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d F Y') }}
                        </div>
                        <small class="text-dark">
                            Pukul <strong>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }} WIB</strong> 
                            s/d <strong>{{ \Carbon\Carbon::parse($reservation->reservation_time)->addHours(2)->format('H:i') }} WIB</strong>
                        </small>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Jumlah Tamu</small>
                        <span class="fw-semibold text-dark">{{ $reservation->guest_count }} Orang</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Waktu Pemesanan Dibuat</small>
                        <span class="text-muted small">{{ $reservation->created_at->format('d M Y, H:i:s') }} WIB</span>
                    </div>
                    @if($reservation->notes)
                        <div class="col-12">
                            <small class="text-muted d-block">Catatan Khusus</small>
                            <span class="fst-italic text-dark">"{{ $reservation->notes }}"</span>
                        </div>
                    @endif
                </div>

                <!-- Detail Menu Makanan -->
                <h6 class="fw-bold text-dark mb-3 pt-3 border-top">Daftar Menu yang Dipesan</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservation->reservationMenus as $rm)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $rm->menu->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $rm->menu->category == 'food' ? 'Makanan' : 'Minuman' }}</small>
                                    </td>
                                    <td class="text-center fw-semibold">{{ $rm->quantity }} Porsi</td>
                                    <td class="text-end">Rp {{ number_format($rm->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($rm->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada menu tambahan yang dipesan (Hanya reservasi meja).</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end fs-6">Total Pembayaran Menu:</th>
                                <th class="text-end fs-5 text-dark fw-extrabold">Rp {{ number_format($reservation->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Tombol Direct WhatsApp -->
                @if($reservation->status === 'pending')
                    <div class="p-3 bg-light rounded-3 text-center mt-4 border">
                        <h6 class="fw-bold mb-2">Langkah Selanjutnya: Hubungi Admin RestoUs</h6>
                        <p class="text-muted small mb-3">
                            Klik tombol di bawah ini untuk mengirim rincian reservasi Anda secara otomatis ke WhatsApp kami dan dapatkan nomor rekening pembayaran.
                        </p>
                        <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-lg fw-bold px-4 shadow">
                            <i class="bi bi-whatsapp me-2 fs-5"></i> Konfirmasi Pembayaran via WhatsApp
                        </a>
                        <div class="mt-2 text-muted small">
                            Nomor Admin RestoUs: <strong>0822-5478-1995</strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('customer.booking.invoice', $reservation->id) }}" target="_blank" class="btn btn-danger fw-bold shadow-sm px-4">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak / Unduh Invoice PDF
            </a>
            <a href="{{ route('customer.my-reservations', ['phone' => $reservation->customer->phone ?? '']) }}" class="btn btn-outline-dark">
                <i class="bi bi-clock-history me-1"></i> Cek Semua Riwayat Pesanan
            </a>
            <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Ke Halaman Utama
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($reservation->status === 'pending' && $reservation->payment_deadline)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Countdown batas 1 jam
        const deadline = new Date("{{ $reservation->payment_deadline->toIso8601String() }}").getTime();
        const display = document.getElementById('countdownDisplay');
        const notice = document.getElementById('expiredNotice');

        function updateCountdown() {
            const now = new Date().getTime();
            const diff = deadline - now;

            if (diff <= 0) {
                display.textContent = "00:00:00";
                display.classList.add('text-secondary');
                if (notice) {
                    notice.textContent = "Waktu pembayaran telah habis. Pesanan dibatalkan otomatis.";
                }
                clearInterval(interval);
                return;
            }

            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const pad = (n) => n < 10 ? '0' + n : n;
            display.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
        }

        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);
    });
</script>
@endif
@endsection
