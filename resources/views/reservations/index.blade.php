@extends('layouts.app')

@section('title', 'Daftar Reservasi - RestoUs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Daftar Reservasi Meja</h3>
        <p class="text-muted small mb-0">Kelola pemesanan meja dan status reservasi restoran</p>
    </div>
    <a href="{{ route('reservations.create') }}" class="btn btn-warning fw-semibold shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Reservasi
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Tamu</th>
                        <th>Kontak</th>
                        <th>Meja</th>
                        <th>Jadwal Reservasi</th>
                        <th>Jumlah Tamu</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $index => $res)
                        <tr>
                            <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold text-dark">{{ $res->customer->name ?? '-' }}</span>
                                @if($res->notes)
                                    <br><small class="text-muted fst-italic">"{{ Str::limit($res->notes, 30) }}"</small>
                                @endif
                            </td>
                            <td>
                                <div><i class="bi bi-telephone text-muted me-1"></i>{{ $res->customer->phone ?? '-' }}</div>
                                @if($res->customer->email)
                                    <small class="text-muted"><i class="bi bi-envelope text-muted me-1"></i>{{ $res->customer->email }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $res->table->table_number ?? '-' }} (Kapasitas {{ $res->table->capacity ?? 0 }})
                                </span>
                            </td>
                            <td>
                                <div><i class="bi bi-calendar-event me-1 text-primary"></i>{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') }}</div>
                                <small class="text-muted"><i class="bi bi-clock me-1 text-primary"></i>{{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <i class="bi bi-people-fill me-1"></i>{{ $res->guest_count }} Orang
                                </span>
                            </td>
                            <td>
                                @if($res->status == 'confirmed')
                                    <span class="badge bg-success">Terkonfirmasi</span>
                                @elseif($res->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($res->status == 'completed')
                                    <span class="badge bg-primary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reservations.edit', $reservation = $res->id) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('reservations.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus reservasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada data reservasi. Silakan klik tombol <strong>Tambah Reservasi</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
