@extends('layout.app')

@section('title', 'Tambah Arsip Baru')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Arsip /</span> Tambah Dokumen Baru
        </h4>
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header d-flex align-items-center justify-content-between border-bottom">
                    <h5 class="mb-0">Formulir Pengarsipan</h5>
                    <small class="text-muted float-end">* Wajib diisi</small>
                </div>
                <div class="card-body mt-3">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Laporan Tahunan 2025" value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Nomor Dokumen</label>
                                <input type="text" name="doc_number" class="form-control"
                                       placeholder="Contoh: 001/SK/2026" value="{{ old('doc_number') }}">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Tambahkan catatan tentang dokumen ini...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4 p-3 bg-light rounded border border-dashed">
                            <label class="form-label fw-semibold">Unggah Dokumen <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            <div class="form-text mt-2 text-muted">
                                <i class="ri-information-line"></i> Format: PDF, DOCX, JPG, PNG. Maks: 10MB.
                            </div>
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="ri-save-line me-1"></i> Simpan Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
