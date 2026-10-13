@extends('layouts.admin')

@section('title', 'Kelola Meja Restoran - Super Admin RestoUs')
@section('page_title', 'Daftar Meja Restoran')

@section('content')
<div class="row g-4">
    @foreach($tables as $table)
        <div class="col-md-4">
            <div class="card p-4 bg-white shadow-sm border-0 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 fw-bold">
                        {{ $table->table_number }}
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        Aktif
                    </span>
                </div>
                <div class="mb-2">
                    <span class="text-muted small">Kapasitas Maksimal:</span>
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i>{{ $table->capacity }} Orang</h5>
                </div>
                <small class="text-muted">
                    Meja ini otomatis terkunci jika ada reservasi terkonfirmasi atau pending dalam rentang durasi 2 jam.
                </small>
            </div>
        </div>
    @endforeach
</div>
@endsection
