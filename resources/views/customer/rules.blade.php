@extends('layouts.customer')

@section('title', 'Rules & Kebijakan Reservasi - RestoUs')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-lg-9">
        <div class="text-center mb-5">
            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="bi bi-shield-check me-1"></i> Kebijakan Resmi
            </span>
            <h2 class="fw-bold text-dark">Tata Tertib & Aturan Reservasi RestoUs</h2>
            <p class="text-muted">Harap membaca ketentuan berikut demi kelancaran dan kenyamanan pengalaman bersantap Anda</p>
        </div>

        <div class="card card-custom bg-white p-4 p-md-5 mb-4 shadow-sm border-0">
            <div class="row g-4">
                <!-- Aturan 1 -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-3 me-3 fs-4">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">1. Jam Operasional</h5>
                            <p class="text-muted small mb-0">
                                Restoran buka setiap hari mulai pukul <strong>10:00 WIB hingga 21:00 WIB</strong>. Slot kedatangan reservasi terakhir yang dapat dipilih adalah pukul <strong>19:00 WIB</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Aturan 2 -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-3 me-3 fs-4">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">2. Ketentuan Reservasi (Minimal H-1)</h5>
                            <p class="text-muted small mb-0">
                                Tamu tidak dapat memesan di hari yang sama atau tanggal lampau. Pemesanan wajib dilakukan <strong>minimal 1 hari sebelum hari H kedatangan</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Aturan 3 -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-3 me-3 fs-4">
                            <i class="bi bi-hourglass-bottom"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">3. Durasi Makan Maksimal 2 Jam</h5>
                            <p class="text-muted small mb-0">
                                Setiap meja yang direservasi memiliki batas waktu bersantap maksimal <strong>2 jam</strong> sejak jam kedatangan yang dipesan. Meja akan disiapkan untuk tamu berikutnya setelah durasi berakhir.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Aturan 4 -->
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="bg-warning-subtle text-warning-emphasis rounded-circle p-3 me-3 fs-4">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">4. Kuota Harian 50 Porsi / Menu</h5>
                            <p class="text-muted small mb-0">
                                Untuk menjaga kesegaran bahan baku, setiap jenis makanan dan minuman dibatasi maksimal <strong>50 porsi per hari</strong>. Jika kuota menu habis, sistem akan menandainya <em>"Menu Habis"</em> dan tamu dapat memilih menu yang masih tersedia.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Aturan 5 -->
                <div class="col-12">
                    <div class="p-3 bg-danger-subtle rounded-3 border border-danger-subtle d-flex align-items-start">
                        <div class="text-danger me-3 fs-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div>
                            <h5 class="fw-bold text-danger mb-1">5. Batas Waktu Pembayaran & Konfirmasi (Maksimal 1 Jam)</h5>
                            <p class="text-dark small mb-2">
                                Setelah mengisi formulir reservasi, status pesanan adalah <strong>MENUNGGU (PENDING)</strong>. Tamu wajib segera melakukan konfirmasi dan pengiriman bukti pembayaran ke WhatsApp resmi RestoUs di nomor <strong>0822-5478-1995</strong> dalam waktu <strong>1 jam (60 menit)</strong> sejak pemesanan.
                            </p>
                            <span class="badge bg-danger">PERHATIAN</span>
                            <small class="text-danger fw-semibold">
                                Jika melewati batas 1 jam belum ada konfirmasi pembayaran, sistem secara otomatis akan <strong>MEMBATALKAN PESANAN</strong> dan meja serta kuota menu akan kembali dilepas untuk pelanggan lain.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-center pt-2">
                <a href="{{ route('customer.booking.create') }}" class="btn btn-warning btn-lg fw-bold px-5 shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Saya Paham, Lanjut Booking Meja
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
