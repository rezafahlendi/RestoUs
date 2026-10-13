@extends('layouts.admin')

@section('title', 'Kelola Reservasi Tamu - Super Admin RestoUs')
@section('page_title', 'Manajemen Pemesanan & Pembayaran Tamu')

@section('content')
<!-- Ringkasan Statistik Status -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 border-start border-warning border-4 shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted text-uppercase fw-bold">Menunggu Konfirmasi</small>
                    <h3 class="fw-bold mb-0 text-warning">{{ $totalPending }}</h3>
                </div>
                <div class="fs-1 text-warning opacity-75"><i class="bi bi-hourglass-split"></i></div>
            </div>
            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Batas konfirmasi 1 jam</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-success border-4 shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted text-uppercase fw-bold">Terkonfirmasi (Lunas)</small>
                    <h3 class="fw-bold mb-0 text-success">{{ $totalConfirmed }}</h3>
                </div>
                <div class="fs-1 text-success opacity-75"><i class="bi bi-check-circle-fill"></i></div>
            </div>
            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Meja resmi terkunci</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-primary border-4 shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted text-uppercase fw-bold">Selesai</small>
                    <h3 class="fw-bold mb-0 text-primary">{{ $totalCompleted }}</h3>
                </div>
                <div class="fs-1 text-primary opacity-75"><i class="bi bi-trophy-fill"></i></div>
            </div>
            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Tamu telah selesai bersantap</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-danger border-4 shadow-sm bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted text-uppercase fw-bold">Dibatalkan / Expired</small>
                    <h3 class="fw-bold mb-0 text-danger">{{ $totalCancelled }}</h3>
                </div>
                <div class="fs-1 text-danger opacity-75"><i class="bi bi-x-circle-fill"></i></div>
            </div>
            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Meja & kuota dilepas kembali</small>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card shadow-sm border-0 bg-white mb-4">
    <div class="card-header bg-white py-3 border-0">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm {{ !$statusFilter ? 'btn-dark' : 'btn-outline-dark' }}">
                    Semua ({{ $reservations->total() }})
                </a>
                <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $statusFilter == 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-outline-warning text-dark' }}">
                    Pending ({{ $totalPending }})
                </a>
                <a href="{{ route('admin.reservations.index', ['status' => 'confirmed']) }}" class="btn btn-sm {{ $statusFilter == 'confirmed' ? 'btn-success' : 'btn-outline-success' }}">
                    Terkonfirmasi ({{ $totalConfirmed }})
                </a>
                <a href="{{ route('admin.reservations.index', ['status' => 'completed']) }}" class="btn btn-sm {{ $statusFilter == 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Selesai ({{ $totalCompleted }})
                </a>
                <a href="{{ route('admin.reservations.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $statusFilter == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                    Dibatalkan ({{ $totalCancelled }})
                </a>
            </div>
            <small class="text-muted fst-italic">
                <i class="bi bi-arrow-repeat me-1"></i> Auto-cancel 1 jam aktif otomatis
            </small>
        </div>
    </div>

    <!-- Table Data -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID / Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Meja & Jam (2 Jam)</th>
                        <th>Menu Dipesan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center pe-3">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-dark font-monospace">#RES-{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                    Dibuat: {{ $res->created_at->format('d/m/y H:i') }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $res->customer->name ?? '-' }}</div>
                                <div class="small text-muted">
                                    <i class="bi bi-whatsapp text-success me-1"></i>{{ $res->customer->phone ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary mb-1">
                                    {{ $res->table->table_number ?? '-' }} ({{ $res->guest_count }} Tamu)
                                </span>
                                <div class="small fw-semibold text-primary">
                                    <i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->reservation_time)->addHours(2)->format('H:i') }} WIB
                                </small>
                            </td>
                            <td>
                                @if($res->reservationMenus->count() > 0)
                                    <ul class="list-unstyled mb-0 small" style="font-size: 0.8rem;">
                                        @foreach($res->reservationMenus as $rm)
                                            <li>
                                                <i class="bi bi-dot text-warning"></i>{{ $rm->menu->name ?? '-' }} (<strong>{{ $rm->quantity }}x</strong>)
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted small fst-italic">Hanya meja</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($res->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($res->status === 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                @elseif($res->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @if($res->payment_deadline)
                                        <div class="text-danger small mt-1" style="font-size: 0.75rem;">
                                            Sisa: {{ $res->payment_deadline->diffForHumans(['parts' => 1]) }}
                                        </div>
                                    @endif
                                @elseif($res->status === 'completed')
                                    <span class="badge bg-primary">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group" role="group">
                                    @if($res->status === 'pending')
                                        <!-- Tombol Konfirmasi Pembayaran -->
                                        <form action="{{ route('admin.reservations.update-status', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi pembayaran tamu ini? Meja akan resmi terkunci.')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-success fw-bold" title="Konfirmasi Pembayaran">
                                                <i class="bi bi-check-lg"></i> Terima
                                            </button>
                                        </form>

                                        <!-- Tombol Batalkan -->
                                        <form action="{{ route('admin.reservations.update-status', $res->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Batalkan reservasi ini? Meja akan kembali bebas.')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak / Batalkan">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @elseif($res->status === 'confirmed')
                                        <!-- Tombol Tandai Selesai -->
                                        <form action="{{ route('admin.reservations.update-status', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai reservasi ini selesai?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-primary fw-semibold" title="Tandai Selesai">
                                                <i class="bi bi-trophy"></i> Selesaikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada data reservasi dengan status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reservations->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $reservations->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
