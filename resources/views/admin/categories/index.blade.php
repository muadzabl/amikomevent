{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<style>
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .action-buttons .btn {
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        white-space: nowrap;
    }
    
    .action-buttons form {
        display: contents;
    }
    
    .table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .thead-dark {
        background-color: #4a5568;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manajemen Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>

    {{-- Alert sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Soal 3: Form Pencarian --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <h6 class="card-subtitle mb-3 text-muted text-uppercase fw-bold" style="letter-spacing: 0.5px;">
                <i class="bi bi-funnel"></i> Cari Kategori
            </h6>
            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2">
                <div class="col-12 col-lg">
                    <input
                        type="text"
                        name="search"
                        class="form-control form-control-lg"
                        placeholder="Cari nama kategori..."
                        value="{{ $search ?? '' }}"
                        style="border-radius: 0.375rem; border: 2px solid #e9ecef;"
                    >
                </div>
                <div class="col-12 col-lg-auto">
                    <button type="submit" class="btn btn-primary btn-lg w-100" style="padding: 0.625rem 1.75rem; border-radius: 0.375rem; font-weight: 600;">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
                @if ($search)
                    <div class="col-12 col-lg-auto">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-lg w-100" style="padding: 0.625rem 1.75rem; border-radius: 0.375rem; font-weight: 600; border: 2px solid #dee2e6;">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Success Banner --}}
    @if (isset($search) && $categories->total() > 0)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle"></i>
            <strong>Hasil Pencarian:</strong> Ditemukan <strong>{{ $categories->total() }}</strong> kategori dengan kata kunci "<strong>{{ $search }}</strong>"
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Soal 1: Tabel Daftar Kategori --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0">
                <i class="bi bi-list-ul"></i> Daftar Kategori
                <span class="badge bg-primary ms-2">{{ $categories->total() }}</span>
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 60px; text-align: center;">
                                <i class="bi bi-hash"></i>
                            </th>
                            <th style="width: 80px;">ID</th>
                            <th style="min-width: 200px;">Nama Kategori</th>
                            <th style="min-width: 150px;">Dibuat Pada</th>
                            <th style="min-width: 150px;">Diperbarui Pada</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $category)
                            <tr>
                                <td class="fw-bold text-center text-secondary">{{ $categories->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $category->id }}</span>
                                </td>
                                <td>
                                    <strong style="color: #2c3e50;">{{ $category->name }}</strong>
                                </td>
                                <td>
                                    <div style="line-height: 1.5;">
                                        <small class="text-muted">{{ $category->created_at->format('d M Y') }}</small>
                                        <br>
                                        <small style="color: #666;">{{ $category->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div style="line-height: 1.5;">
                                        <small class="text-muted">{{ $category->updated_at->format('d M Y') }}</small>
                                        <br>
                                        <small style="color: #666;">{{ $category->updated_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-warning"
                                           title="Edit kategori">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori &quot;{{ $category->name }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus kategori">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    @if ($search)
                                        <div style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.5;">
                                            <i class="bi bi-search"></i>
                                        </div>
                                        <h5 class="mb-2">Tidak ada hasil</h5>
                                        <p class="mb-3">Tidak ditemukan kategori dengan kata kunci "<strong>{{ $search }}</strong>"</p>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                    @else
                                        <div style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.5;">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h5 class="mb-2">Belum ada kategori</h5>
                                        <p class="mb-3">Mulai dengan menambahkan kategori baru</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-lg"></i> Tambah Kategori
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Footer --}}
        @if ($categories->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <strong>Halaman:</strong> {{ $categories->currentPage() }} dari {{ $categories->lastPage() }} 
                        | <strong>Total:</strong> {{ $categories->total() }} kategori
                    </small>
                    <div>
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection