@extends('layouts.app')

@section('title', 'Ubah Data Reservasi - RestoUs')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Ubah Data Reservasi</h4>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Informasi Tamu</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $reservation->customer->name ?? '') }}" required>
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', $reservation->customer->phone ?? '') }}" required>
                            @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" value="{{ old('customer_email', $reservation->customer->email ?? '') }}">
                            @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Detail Reservasi & Status</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Meja <span class="text-danger">*</span></label>
                            <select name="table_id" class="form-select @error('table_id') is-invalid @enderror" required>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ old('table_id', $reservation->table_id) == $table->id ? 'selected' : '' }}>
                                        {{ $table->table_number }} (Kapasitas: {{ $table->capacity }} Orang)
                                    </option>
                                @endforeach
                            </select>
                            @error('table_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Reservasi <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                                <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi (Confirmed)</option>
                                <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                                <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah Tamu <span class="text-danger">*</span></label>
                            <input type="number" name="guest_count" min="1" class="form-control" value="{{ old('guest_count', $reservation->guest_count) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="reservation_date" class="form-control" value="{{ old('reservation_date', $reservation->reservation_date) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam <span class="text-danger">*</span></label>
                            <input type="time" name="reservation_time" class="form-control" value="{{ old('reservation_time', substr($reservation->reservation_time, 0, 5)) }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Catatan Khusus</label>
                            <textarea name="notes" rows="3" class="form-control">{{ old('notes', $reservation->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('reservations.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Reservasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
