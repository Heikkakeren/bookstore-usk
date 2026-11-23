@extends('layouts.admin')
@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-tag me-2 text-primary"></i>Form Tambah Kategori Buku</h6>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $error)
                            <div class="small"><i class="bi bi-exclamation-triangle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Contoh: Teknologi, Fiksi, Sains, dll." required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Kategori</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
