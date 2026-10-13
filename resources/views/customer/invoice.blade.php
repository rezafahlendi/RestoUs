<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Reservasi #RES-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }} - RestoUs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 20px 0;
        }

        .invoice-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            max-width: 820px;
            margin: 0 auto;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .invoice-badge-status {
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .font-mono {
            font-family: 'Space Mono', monospace;
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.75rem;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .brand-logo span {
            color: #f59e0b;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .invoice-table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            border-bottom: 2px solid #cbd5e1;
            padding: 12px 16px;
        }

        .invoice-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .notice-box {
            background-color: #f8fafc;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 14px 18px;
        }

        .watermark-paid {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 6.5rem;
            font-weight: 900;
            color: rgba(34, 197, 94, 0.08);
            border: 8px dashed rgba(34, 197, 94, 0.15);
            padding: 10px 40px;
            border-radius: 20px;
            pointer-events: none;
            user-select: none;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Toolbar Aksi Atas (Hanya tampil di layar, tersembunyi saat dicetak) */
        .action-toolbar {
            max-width: 820px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Aturan Khusus Cetak & PDF */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .action-toolbar, .no-print {
                display: none !important;
            }
            .invoice-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4;
                margin: 15mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar Tombol Aksi (Tidak Ikut Tercetak di PDF) -->
    <div class="action-toolbar no-print">
        <a href="{{ route('customer.booking.show', $reservation->id) }}" class="btn btn-outline-secondary fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Bukti Reservasi
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-danger fw-bold shadow-sm px-4">
                <i class="bi bi-printer-fill me-1"></i> Cetak / Unduh PDF
            </button>
        </div>
    </div>

    <!-- Lembar Faktur / Invoice Resmi RestoUs -->
    <div class="invoice-card">

        <!-- Watermark Status -->
        @if($reservation->status === 'confirmed')
            <div class="watermark-paid">LUNAS</div>
        @elseif($reservation->status === 'completed')
            <div class="watermark-paid" style="color: rgba(14, 165, 233, 0.08); border-color: rgba(14, 165, 233, 0.15);">SELESAI</div>
        @endif

        <!-- Header Faktur -->
        <div class="row align-items-center pb-4 mb-4 border-bottom">
            <div class="col-sm-6">
                <div class="brand-logo mb-1">
                    <i class="bi bi-cup-hot-fill text-warning me-2"></i>Resto<span>Us</span>
                </div>
                <div class="text-muted small">
                    Sistem Reservasi Meja & Menu Restoran Modern<br>
                    Jl. Gastronomi Kuliner No. 88, Samarinda<br>
                    WhatsApp Resmi: <strong>0822-5478-1995</strong>
                </div>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h3 class="fw-extrabold text-dark mb-1">FAKTUR RESERVASI</h3>
                <div class="font-mono fs-5 fw-bold text-warning mb-2">
                    #INV-RES-{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}
                </div>
                <div>
                    @if($reservation->status === 'confirmed')
                        <span class="invoice-badge-status bg-success-subtle text-success border border-success">
                            <i class="bi bi-check-circle-fill me-1"></i> Terkonfirmasi (Lunas)
                        </span>
                    @elseif($reservation->status === 'pending')
                        <span class="invoice-badge-status bg-warning-subtle text-warning border border-warning">
                            <i class="bi bi-hourglass-split me-1"></i> Menunggu Konfirmasi
                        </span>
                    @elseif($reservation->status === 'completed')
                        <span class="invoice-badge-status bg-info-subtle text-info border border-info">
                            <i class="bi bi-stars me-1"></i> Selesai
                        </span>
                    @else
                        <span class="invoice-badge-status bg-danger-subtle text-danger border border-danger">
                            <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan / Expired
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Pemesan & Detail Jadwal -->
        <div class="row g-4 mb-4 pb-3">
            <div class="col-6 col-md-3">
                <div class="info-label">Nama Tamu Pemesan</div>
                <div class="info-value">{{ $reservation->customer->name ?? '-' }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">No. WhatsApp / HP</div>
                <div class="info-value font-mono">{{ $reservation->customer->phone ?? '-' }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Alokasi Meja</div>
                <div class="info-value text-primary">
                    {{ $reservation->table->table_number ?? '-' }} 
                    <small class="text-muted fw-normal">({{ $reservation->table->capacity ?? 0 }} Kursi)</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Jumlah Tamu</div>
                <div class="info-value">{{ $reservation->guest_count }} Orang</div>
            </div>

            <div class="col-6 col-md-3">
                <div class="info-label">Tanggal Kedatangan</div>
                <div class="info-value text-dark">
                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d F Y') }}
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Rentang Waktu (Maks 2 Jam)</div>
                <div class="info-value font-mono">
                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }} - 
                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->addHours(2)->format('H:i') }} WIB
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Tanggal Terbit Faktur</div>
                <div class="info-value text-muted small">
                    {{ $reservation->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Tenggat Batas Bayar (1 Jam)</div>
                <div class="info-value text-danger small font-mono">
                    {{ $reservation->payment_deadline ? $reservation->payment_deadline->format('H:i') . ' WIB' : '-' }}
                </div>
            </div>
        </div>

        @if($reservation->notes)
            <div class="mb-4 p-2 px-3 bg-light rounded border text-muted small">
                <strong>Catatan Khusus Tamu:</strong> "{{ $reservation->notes }}"
            </div>
        @endif

        <!-- Tabel Rincian Menu yang Dipesan -->
        <div class="table-responsive mb-4">
            <table class="table invoice-table w-100">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 45%;">Rincian Hidangan / Menu</th>
                        <th class="text-center" style="width: 15%;">Jumlah</th>
                        <th class="text-end" style="width: 15%;">Harga Satuan</th>
                        <th class="text-end" style="width: 20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservation->reservationMenus as $idx => $rm)
                        <tr>
                            <td class="text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $rm->menu->name ?? 'Menu' }}</div>
                                <small class="text-muted text-uppercase" style="font-size: 0.75rem;">
                                    {{ $rm->menu->category == 'food' ? 'Makanan' : 'Minuman' }}
                                </small>
                            </td>
                            <td class="text-center fw-bold">{{ $rm->quantity }} Porsi</td>
                            <td class="text-end font-mono">Rp {{ number_format($rm->price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold font-mono">Rp {{ number_format($rm->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <em>Tidak ada pesanan menu tambahan (Hanya reservasi pemakaian meja makan).</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end py-3 fs-6">TOTAL BIAYA RESERVASI & MENU:</th>
                        <th class="text-end py-3 fs-5 font-mono text-dark fw-extrabold text-nowrap">
                            Rp {{ number_format($reservation->total_amount, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Ketentuan Pembayaran & Aturan Restoran -->
        <div class="row g-4 pt-2">
            <div class="col-sm-7">
                <div class="notice-box">
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-info-circle-fill text-warning me-1"></i> Ketentuan Resmi RestoUs:</h6>
                    <ul class="mb-0 ps-3 small text-muted" style="font-size: 0.82rem; line-height: 1.6;">
                        <li>Wajib hadir tepat waktu sesuai jadwal yang tertera.</li>
                        <li>Durasi maksimal santap di meja makan adalah <strong>2 jam</strong> demi kenyamanan rotasi tamu.</li>
                        <li>Batas konfirmasi pembayaran adalah <strong>1 jam (60 menit)</strong> sejak pemesanan dibuat.</li>
                        <li>Tunjukkan faktur ini kepada staf kasir/resepsionis saat tiba di lokasi.</li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-5 text-sm-end d-flex flex-column justify-content-between">
                <div>
                    <div class="info-label">Metode Pembayaran</div>
                    <div class="small text-dark mb-1">Transfer Bank / QRIS Resmi RestoUs</div>
                    <div class="small text-muted font-mono">BCA: 8820-1234-5678 (RestoUs Official)</div>
                </div>
                <div class="mt-4">
                    <div class="text-muted small mb-1">Disahkan secara digital oleh:</div>
                    <div class="fw-bold text-dark">Manajemen Operasional RestoUs</div>
                    <small class="text-success font-mono"><i class="bi bi-shield-check me-1"></i>E-INVOICE VERIFIED</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Skrip Auto-Print jika ada parameter ?print=1 -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            window.addEventListener('load', () => {
                setTimeout(() => window.print(), 300);
            });
        }
    </script>
</body>
</html>
