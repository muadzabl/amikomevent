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
                <div class="mb-4 p-3 bg-light rounded text-center">
                    <p class="text-muted small mb-2">Logo Saat Ini:</p>
                    <img src="{{ $partner->logo_url }}"
                         alt="{{ $partner->name }}"
                         style="max-height: 80px; object-fit: contain;"
                         onerror="this.parentElement.innerHTML='<span class=\'text-danger small\'>Logo tidak dapat dimuat</span>'">
                </div>
            @endif

            <form action="{{ route('admin.partners.update', $partner) }}" method="POST">
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

                <div class="mb-4">
                    <label for="logo_url" class="form-label fw-semibold">URL Logo</label>
                    <input
                        type="url"
                        name="logo_url"
                        id="logo_url"
                        class="form-control @error('logo_url') is-invalid @enderror"
                        value="{{ old('logo_url', $partner->logo_url) }}"
                        placeholder="https://example.com/logo.png"
                    >
                    @error('logo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Kosongkan jika tidak ingin mengubah logo.</div>
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