@extends('layouts.app')

@section('title', 'Buat Reservasi Baru - RestoUs')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Form Pemesanan Meja</h4>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Informasi Tamu</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required placeholder="Contoh: Budi Santoso">
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone') }}" required placeholder="Contoh: 08123456789">
                            @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Email (Opsional)</label>
                            <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email') }}" placeholder="budi@example.com">
                            @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Detail Reservasi</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Meja <span class="text-danger">*</span></label>
                            <select name="table_id" class="form-select @error('table_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Meja Restoran --</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                        {{ $table->table_number }} (Kapasitas: {{ $table->capacity }} Orang)
                                    </option>
                                @endforeach
                            </select>
                            @error('table_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Tamu <span class="text-danger">*</span></label>
                            <input type="number" name="guest_count" min="1" class="form-control @error('guest_count') is-invalid @enderror" value="{{ old('guest_count', 2) }}" required>
                            @error('guest_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Reservasi <span class="text-danger">*</span></label>
                            <input type="date" name="reservation_date" class="form-control @error('reservation_date') is-invalid @enderror" value="{{ old('reservation_date', date('Y-m-d')) }}" required>
                            @error('reservation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Kedatangan <span class="text-danger">*</span></label>
                            <input type="time" name="reservation_time" class="form-control @error('reservation_time') is-invalid @enderror" value="{{ old('reservation_time', '18:00') }}" required>
                            @error('reservation_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Catatan Khusus (Opsional)</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Contoh: Meja dekat jendela, request baby chair">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('reservations.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-warning fw-semibold px-4">
                            <i class="bi bi-save me-1"></i> Simpan Reservasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
