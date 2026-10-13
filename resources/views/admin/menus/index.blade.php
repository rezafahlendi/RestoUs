@extends('layouts.admin')

@section('title', 'Kelola Menu & Kuota Harian - Super Admin RestoUs')
@section('page_title', 'Pengaturan Menu & Kuota Harian (Batas 50 Porsi/Hari)')

@section('content')
<div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
    <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
    <div>
        <strong>Kebijakan Kuota Restoran:</strong> Secara default, setiap menu memiliki kuota <strong>50 porsi per hari</strong>. 
        Anda dapat menyesuaikan kuota harian, harga, atau menonaktifkan menu jika bahan baku habis.
    </div>
</div>

<!-- Section Makanan -->
<div class="card shadow-sm border-0 bg-white mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-fire text-danger me-2"></i>Daftar Makanan (4 Varian)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Foto</th>
                        <th>Nama Menu & Deskripsi</th>
                        <th style="width: 140px;">Harga (Rp)</th>
                        <th style="width: 140px;">Kuota / Hari</th>
                        <th style="width: 130px;">Ketersediaan</th>
                        <th class="text-center pe-3" style="width: 100px;">Simpan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foods as $food)
                        <tr>
                            <td class="ps-3">
                                <img src="{{ $food->image_url }}" alt="{{ $food->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $food->name }}</div>
                                <small class="text-muted">{{ Str::limit($food->description, 70) }}</small>
                            </td>
                            <form action="{{ route('admin.menus.update', $food->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>
                                    <input type="number" name="price" class="form-control form-control-sm" value="{{ $food->price }}" min="0" required>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="daily_quota" class="form-control" value="{{ $food->daily_quota }}" min="0" required>
                                        <span class="input-group-text">porsi</span>
                                    </div>
                                </td>
                                <td>
                                    <select name="is_available" class="form-select form-select-sm">
                                        <option value="1" {{ $food->is_available ? 'selected' : '' }}>Tersedia</option>
                                        <option value="0" {{ !$food->is_available ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </td>
                                <td class="text-center pe-3">
                                    <button type="submit" class="btn btn-sm btn-primary fw-semibold">
                                        <i class="bi bi-save"></i> Ubah
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Section Minuman -->
<div class="card shadow-sm border-0 bg-white mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cup-straw text-primary me-2"></i>Daftar Minuman (4 Varian)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Foto</th>
                        <th>Nama Menu & Deskripsi</th>
                        <th style="width: 140px;">Harga (Rp)</th>
                        <th style="width: 140px;">Kuota / Hari</th>
                        <th style="width: 130px;">Ketersediaan</th>
                        <th class="text-center pe-3" style="width: 100px;">Simpan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drinks as $drink)
                        <tr>
                            <td class="ps-3">
                                <img src="{{ $drink->image_url }}" alt="{{ $drink->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $drink->name }}</div>
                                <small class="text-muted">{{ Str::limit($drink->description, 70) }}</small>
                            </td>
                            <form action="{{ route('admin.menus.update', $drink->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>
                                    <input type="number" name="price" class="form-control form-control-sm" value="{{ $drink->price }}" min="0" required>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="daily_quota" class="form-control" value="{{ $drink->daily_quota }}" min="0" required>
                                        <span class="input-group-text">porsi</span>
                                    </div>
                                </td>
                                <td>
                                    <select name="is_available" class="form-select form-select-sm">
                                        <option value="1" {{ $drink->is_available ? 'selected' : '' }}>Tersedia</option>
                                        <option value="0" {{ !$drink->is_available ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </td>
                                <td class="text-center pe-3">
                                    <button type="submit" class="btn btn-sm btn-primary fw-semibold">
                                        <i class="bi bi-save"></i> Ubah
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
