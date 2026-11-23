@extends('layouts.app')

@section('title', '402 Payment Required - Pembayaran Diperlukan')

@section('content')
<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                <div class="mb-4">
                    <span class="badge px-3 py-2 rounded-pill fs-6 fw-bold" style="background-color: rgba(194, 139, 87, 0.15); color: #8c5b2a;">
                        <i class="bi bi-credit-card-2-front-fill me-1"></i> HTTP 402 Payment Required
                    </span>
                </div>
                <h1 class="display-5 fw-bold text-dark mb-2 font-serif">Pembayaran Diperlukan</h1>
                <p class="text-secondary mb-4 fs-5">
                    {{ $exception->getMessage() ?: 'Akses ke dokumen, kwitansi, atau unduhan buku digital ini memerlukan transaksi pembayaran yang valid dan terkonfirmasi terlebih dahulu.' }}
                </p>
                <div class="alert text-start mb-4 rounded-3 border-0" style="background-color: var(--forest-sand);">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-wallet2 fs-3 me-3" style="color: var(--forest-accent);"></i>
                        <div>
                            <strong class="text-dark">Mengapa terjadi HTTP 402?</strong><br>
                            <small class="text-secondary">
                                Dalam standar HTTP dan aplikasi e-commerce, status <strong>402 Payment Required</strong> muncul ketika pengguna mencoba mengakses sumber daya berbayar (seperti e-book, invoice lunas, atau layanan premium) sebelum menyelesaikan tagihan pembayaran.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="{{ route('orders.history') }}" class="btn btn-success px-4 py-2 rounded-3 fw-semibold shadow-sm" style="background-color: var(--forest-primary); border-color: var(--forest-primary);">
                        <i class="bi bi-receipt me-1"></i> Buka Riwayat Pesanan
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
                        <i class="bi bi-house me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
