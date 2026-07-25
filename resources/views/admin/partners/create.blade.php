{{-- resources/views/admin/partners/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Partner')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-0">Tambah Partner Baru</h1>
    </div>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Partner <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Tokopedia, Telkomsel"
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo_file" class="form-label fw-semibold">Upload File Logo (Gambar)</label>
                    <input
                        type="file"
                        name="logo_file"
                        id="logo_file"
                        accept="image/*"
                        class="form-control @error('logo_file') is-invalid @enderror"
                    >
                    @error('logo_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Pilih file logo dari komputer Anda (Otomatis disimpan ke Cloudinary).</div>
                </div>

                <div class="mb-4">
                    <label for="logo_url" class="form-label fw-semibold">Atau Masukkan URL Logo (Link Web)</label>
                    <input
                        type="text"
                        name="logo_url"
                        id="logo_url"
                        class="form-control @error('logo_url') is-invalid @enderror"
                        value="{{ old('logo_url') }}"
                        placeholder="https://example.com/logo.png"
                    >
                    @error('logo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Opsional. Digunakan jika tidak memilih upload file.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Partner
                    </button>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection