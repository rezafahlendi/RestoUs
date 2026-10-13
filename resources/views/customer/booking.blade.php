@extends('layouts.customer')

@section('title', 'Form Booking Meja & Menu - RestoUs')

@section('content')
<div class="row justify-content-center py-3">
    <div class="col-lg-10">
        <!-- Banner Singkat Rules -->
        <div class="alert alert-warning border-warning-subtle shadow-sm mb-4 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-info-circle-fill me-2 fs-5 align-middle"></i>
                <strong>Aturan Reservasi:</strong> Buka 10:00-21:00 WIB • Min H-1 • Maks 2 Jam Makan • Batas Bayar 1 Jam via WA
            </div>
            <a href="{{ route('customer.rules') }}" target="_blank" class="text-dark fw-bold small text-decoration-underline">Baca Selengkapnya</a>
        </div>

        <div class="card card-custom bg-white border-0 shadow-sm overflow-hidden">
            <div class="bg-dark text-white p-4">
                <h4 class="fw-bold mb-1"><i class="bi bi-calendar-check text-warning me-2"></i>Form Pemesanan Meja & Menu RestoUs</h4>
                <p class="text-light small mb-0">Isi data reservasi dengan lengkap. Status awal adalah <strong>PENDING</strong> sampai diverifikasi via WhatsApp.</p>
            </div>

            <div class="card-body p-4 p-md-5">
                <form action="{{ route('customer.booking.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <!-- STEP 1: INFORMASI PEMESAN -->
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center">
                        <span class="badge bg-warning text-dark me-2">1</span> Data Pemesan
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" required placeholder="Contoh: Budi Santoso">
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', Auth::check() ? Auth::user()->phone : '') }}" required placeholder="Contoh: 08123456789">
                            <small class="text-muted" style="font-size: 0.8rem;">Digunakan untuk konfirmasi dan cek riwayat pesanan.</small>
                            @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Email (Opsional)</label>
                            <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', Auth::check() ? Auth::user()->email : '') }}" placeholder="budi@example.com">
                            @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- STEP 2: WAKTU & PILIHAN MEJA -->
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center">
                        <span class="badge bg-warning text-dark me-2">2</span> Jadwal Reservasi & Meja (Batas Makan 2 Jam)
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Reservasi (Min. H-1) <span class="text-danger">*</span></label>
                            <input type="date" name="reservation_date" id="reservation_date" class="form-control @error('reservation_date') is-invalid @enderror" value="{{ old('reservation_date', $selectedDate) }}" min="{{ $minDate }}" required>
                            <small class="text-muted" style="font-size: 0.8rem;">Minimal tanggal: {{ \Carbon\Carbon::parse($minDate)->format('d M Y') }}</small>
                            @error('reservation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam Kedatangan <span class="text-danger">*</span></label>
                            <select name="reservation_time" id="reservation_time" class="form-select @error('reservation_time') is-invalid @enderror" required>
                                @foreach($operationalHours as $hour)
                                    <option value="{{ $hour }}" {{ old('reservation_time', $selectedTime) == $hour ? 'selected' : '' }}>
                                        {{ $hour }} WIB (Selesai {{ \Carbon\Carbon::parse($hour)->addHours(2)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted" style="font-size: 0.8rem;">Jam buka: 10:00 - 21:00 WIB</small>
                            @error('reservation_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah Tamu <span class="text-danger">*</span></label>
                            <input type="number" name="guest_count" class="form-control @error('guest_count') is-invalid @enderror" min="1" max="20" value="{{ old('guest_count', 2) }}" required>
                            @error('guest_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Pilih Meja Restoran <span class="text-danger">*</span></label>
                            <select name="table_id" id="table_id" class="form-select form-select-lg @error('table_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Meja yang Tersedia --</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" 
                                            {{ old('table_id') == $table->id ? 'selected' : '' }}
                                            {{ $table->is_booked ? 'disabled class=text-danger' : '' }}>
                                        {{ $table->table_number }} (Kapasitas {{ $table->capacity }} Orang)
                                        {{ $table->is_booked ? '— [SUDAH DIPESAN PADA JAM INI]' : '— [TERSEDIA]' }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="table-loading" class="text-muted small mt-1 d-none">
                                <span class="spinner-border spinner-border-sm me-1"></span> Memperbarui ketersediaan meja...
                            </div>
                            @error('table_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Khusus (Opsional)</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Contoh: Meja sudut tenang, butuh baby chair">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- STEP 3: PRE-ORDER MENU (50 Porsi / Hari) -->
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center">
                        <span class="badge bg-warning text-dark me-2">3</span> Pilih Menu Makanan & Minuman (Kuota 50 Porsi/Hari)
                    </h5>
                    <p class="text-muted small mb-3">
                        Pesan menu lebih awal agar siap disajikan saat Anda tiba. Menu yang kuotanya habis ditandai <strong>"Habis"</strong>.
                    </p>

                    <div class="row g-3 mb-4">
                        @foreach($menus as $m)
                            <div class="col-md-6">
                                <div class="card p-3 h-100 border bg-light menu-card" id="menu-card-{{ $m->id }}">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $m->image_url }}" alt="{{ $m->name }}" class="rounded me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="fw-bold mb-1 text-dark">{{ $m->name }}</h6>
                                                <span class="badge {{ $m->category == 'food' ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }}">
                                                    {{ $m->category == 'food' ? 'Makanan' : 'Minuman' }}
                                                </span>
                                            </div>
                                            <div class="text-primary fw-bold mb-1">Rp {{ number_format($m->price, 0, ',', '.') }}</div>
                                            <div class="small quota-label-{{ $m->id }}">
                                                @if($m->remaining_quota > 0)
                                                    <span class="badge bg-success-subtle text-success">Sisa {{ $m->remaining_quota }} Porsi</span>
                                                @else
                                                    <span class="badge bg-danger">Habis (Sold Out)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-2 border-top d-flex align-items-center justify-content-between">
                                        <label class="small fw-semibold mb-0">Jumlah Porsi:</label>
                                        <div style="width: 110px;">
                                            <input type="number" 
                                                   name="menu_quantities[{{ $m->id }}]" 
                                                   class="form-control form-control-sm menu-qty-input" 
                                                   data-price="{{ $m->price }}"
                                                   data-id="{{ $m->id }}"
                                                   value="{{ old('menu_quantities.'.$m->id, 0) }}" 
                                                   min="0" 
                                                   max="{{ $m->remaining_quota }}"
                                                   {{ $m->remaining_quota <= 0 ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- RINGKASAN BIAYA & PERINGATAN 1 JAM -->
                    <div class="card bg-warning-subtle border-warning p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Estimasi Total Biaya Menu:</h6>
                                <small class="text-muted">Biaya meja GRATIS, hanya membayar makanan/minuman yang dipesan.</small>
                            </div>
                            <div class="fs-3 fw-extrabold text-dark" id="totalAmountDisplay">
                                Rp 0
                            </div>
                        </div>
                        <hr class="border-warning my-3">
                        <div class="small text-dark">
                            <i class="bi bi-clock-history text-danger me-1 fs-6"></i>
                            <strong>PENTING:</strong> Setelah formulir ini dikirim, status pesanan menjadi <strong>PENDING</strong>. Anda memiliki waktu <strong>1 jam</strong> untuk melakukan konfirmasi & pembayaran via WhatsApp ke nomor <strong>082254781995</strong>. Jika lewat 1 jam belum ada konfirmasi, pesanan otomatis batal.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('customer.home') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-warning btn-lg fw-bold px-5 shadow-sm">
                            <i class="bi bi-send-check me-2"></i> Kirim Pemesanan (Pending)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('reservation_date');
        const timeInput = document.getElementById('reservation_time');
        const tableSelect = document.getElementById('table_id');
        const loadingIndicator = document.getElementById('table-loading');
        const qtyInputs = document.querySelectorAll('.menu-qty-input');
        const totalDisplay = document.getElementById('totalAmountDisplay');

        // Hitung total harga
        function calculateTotal() {
            let total = 0;
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseInt(input.getAttribute('data-price')) || 0;
                total += qty * price;
            });
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        qtyInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
        calculateTotal();

        // AJAX update ketersediaan meja & kuota saat tanggal / jam berubah
        function fetchAvailability() {
            const date = dateInput.value;
            const time = timeInput.value;
            if (!date || !time) return;

            loadingIndicator.classList.remove('d-none');

            fetch(`{{ route('customer.booking.check') }}?date=${date}&time=${time}`)
                .then(res => res.json())
                .then(data => {
                    loadingIndicator.classList.add('d-none');

                    // Update dropdown meja
                    const currentSelected = tableSelect.value;
                    tableSelect.innerHTML = '<option value="">-- Pilih Meja yang Tersedia --</option>';

                    data.tables.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.id;
                        option.textContent = `${table.table_number} (Kapasitas ${table.capacity} Orang) ${table.is_booked ? '— [SUDAH DIPESAN PADA JAM INI]' : '— [TERSEDIA]'}`;
                        if (table.is_booked) {
                            option.disabled = true;
                            option.classList.add('text-danger');
                        } else if (table.id == currentSelected) {
                            option.selected = true;
                        }
                        tableSelect.appendChild(option);
                    });

                    // Update kuota menu
                    data.menus.forEach(menu => {
                        const input = document.querySelector(`.menu-qty-input[data-id="${menu.id}"]`);
                        const label = document.querySelector(`.quota-label-${menu.id}`);
                        if (input && label) {
                            input.setAttribute('max', menu.remaining_quota);
                            if (menu.remaining_quota <= 0) {
                                input.disabled = true;
                                input.value = 0;
                                label.innerHTML = '<span class="badge bg-danger">Habis (Sold Out)</span>';
                            } else {
                                input.disabled = false;
                                label.innerHTML = `<span class="badge bg-success-subtle text-success">Sisa ${menu.remaining_quota} Porsi</span>`;
                            }
                        }
                    });

                    calculateTotal();
                })
                .catch(err => {
                    loadingIndicator.classList.add('d-none');
                    console.error('Gagal mengambil ketersediaan:', err);
                });
        }

        dateInput.addEventListener('change', fetchAvailability);
        timeInput.addEventListener('change', fetchAvailability);
    });
</script>
@endsection
