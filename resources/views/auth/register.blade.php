@extends('layouts.customer')

@section('title', 'Daftar Akun Customer - RestoUs')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-6">
        <div class="card card-custom bg-white border-0 shadow-sm p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill mb-2">
                    <i class="bi bi-person-plus me-1"></i> Registrasi Tamu
                </span>
                <h3 class="fw-bold text-dark">Daftar Akun Baru</h3>
                <p class="text-muted small">Buat akun untuk kemudahan reservasi dan riwayat pesanan meja</p>
            </div>

            <!-- Tombol Masuk dengan Google -->
            <a href="{{ route('auth.google-demo') }}" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-3 fw-semibold shadow-sm border">
                <svg width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.55 10.78l7.98-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                <span>Daftar Cepat dengan Akun Google</span>
            </a>

            <div class="position-relative text-center my-3">
                <hr class="border-secondary-subtle">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">atau isi data lengkap</span>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="Contoh: budi123">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="Contoh: 08123456789">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="budi@example.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Min. 6 karakter">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ulangi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Buat Akun Customer Sekarang
                </button>
            </form>

            <div class="text-center mt-4 small">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="text-warning fw-bold text-decoration-none">Masuk di Sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
