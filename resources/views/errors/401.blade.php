@extends('layouts.app')

@section('title', '401 Unauthorized - Belum Terautentikasi')

@section('content')
<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                <div class="mb-4">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fs-6 fw-bold">
                        <i class="bi bi-person-x-fill me-1"></i> HTTP 401 Unauthorized
                    </span>
                </div>
                <h1 class="display-4 fw-bold text-dark mb-2">Belum Login</h1>
                <p class="text-secondary mb-4 fs-5">
                    {{ $exception->getMessage() ?: 'Sesi Anda tidak ditemukan atau Anda belum masuk (login) ke dalam sistem.' }}
                </p>
                <div class="alert alert-info text-start mb-4 rounded-3 border-0 bg-info-subtle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                        <div>
                            <strong>Mengapa terjadi HTTP 401?</strong><br>
                            <small class="text-dark">
                                HTTP 401 Unauthorized terjadi ketika pengunjung belum terotentikasi (belum login), sehingga sistem tidak mengetahui identitas Anda untuk memberikan akses ke resource ini.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-success px-4 py-2 rounded-3" style="background-color: var(--forest-primary); border-color: var(--forest-primary);">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login Sekarang
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                        <i class="bi bi-house me-1"></i> Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
