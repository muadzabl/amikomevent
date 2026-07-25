{{-- resources/views/admin/partners/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Partner')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h1 class="h3 mb-0">Edit Partner: <em>{{ $partner->name }}</em></h1>
    </div>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">

            {{-- Preview logo saat ini --}}
            @if ($partner->logo_url)
                @php
                    $previewUrl = \Illuminate\Support\Str::startsWith($partner->logo_url, ['http://', 'https://'])
                        ? $partner->logo_url
                        : (\Illuminate\Support\Facades\Storage::disk('public')->exists($partner->logo_url)
                            ? asset('storage/' . $partner->logo_url)
                            : 'https://' . $partner->logo_url);
                @endphp
                <div class="mb-4 p-3 bg-light rounded text-center">
                    <p class="text-muted small mb-2">Logo Saat Ini:</p>
                    <img src="{{ $previewUrl }}"
                         alt="{{ $partner->name }}"
                         style="max-height: 80px; object-fit: contain;"
                         onerror="this.onerror=null; this.src='https://placehold.co/100x50/eef2ff/6366f1?text=No+Logo';">
                </div>
            @endif

            <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Partner <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $partner->name) }}"
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="logo_file" class="form-label fw-semibold">Upload File Logo Baru (Gambar)</label>
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
                    <div class="form-text">Pilih file dari komputer jika ingin mengganti logo dengan file baru (Otomatis disimpan ke Cloudinary).</div>
                </div>

                <div class="mb-4">
                    <label for="logo_url" class="form-label fw-semibold">Atau Masukkan URL Logo (Link Web)</label>
                    <input
                        type="text"
                        name="logo_url"
                        id="logo_url"
                        class="form-control @error('logo_url') is-invalid @enderror"
                        value="{{ old('logo_url', $partner->logo_url) }}"
                        placeholder="https://example.com/logo.png"
                    >
                    @error('logo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Bisa menggunakan link gambar eksternal (misal: https://...).</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update Partner
                    </button>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection