@extends('layouts.app')

@section('title', '403 Forbidden - Akses Ditolak')

@section('content')
<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                <div class="mb-4">
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fs-6 fw-bold">
                        <i class="bi bi-shield-lock-fill me-1"></i> HTTP 403 Forbidden
                    </span>
                </div>
                <h1 class="display-4 fw-bold text-dark mb-2">Akses Ditolak</h1>
                <p class="text-secondary mb-4 fs-5">
                    {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses (permission) untuk membuka halaman ini. Halaman ini diproteksi khusus untuk Administrator.' }}
                </p>
                <div class="alert alert-warning text-start mb-4 rounded-3 border-0 bg-warning-subtle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-warning fs-4 me-3"></i>
                        <div>
                            <strong>Mengapa terjadi HTTP 403?</strong><br>
                            <small class="text-dark">
                                Anda sudah berhasil login, namun peran (<em>role</em>) akun Anda adalah <strong>Pelanggan (User)</strong>, bukan <strong>Administrator</strong>. Middleware <code>AdminMiddleware</code> menolak akses ini demi keamanan sistem.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                        <i class="bi bi-house me-1"></i> Kembali ke Beranda
                    </a>
                    <a href="{{ route('user.catalog') }}" class="btn btn-success px-4 py-2 rounded-3" style="background-color: var(--forest-primary); border-color: var(--forest-primary);">
                        <i class="bi bi-book me-1"></i> Jelajahi Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
