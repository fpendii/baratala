@extends('layout.administrasi-template')

@section('title', 'Edit Jobdesk')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Jobdesk</h5>
        <small class="text-body-secondary float-end">Form Edit Data Jobdesk</small>
    </div>
    <div class="card-body">

        {{-- Pesan Peringatan Validasi (Jika ada error) --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="icon-base ri ri-error-warning-line me-1"></i>
                Gagal memperbarui data. Mohon periksa kembali formulir Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ url('administrasi/jobdesk/update', $jobdesk->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- FIELD: JUDUL JOBDESK --}}
            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Jobdesk</label>
                <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ri ri-task-line"></i></span>
                        <input
                            type="text"
                            class="form-control @error('judul_jobdesk') is-invalid @enderror"
                            name="judul_jobdesk"
                            {{-- Gunakan old() jika ada error, jika tidak, gunakan data dari model --}}
                            value="{{ old('judul_jobdesk', $jobdesk->judul_jobdesk) }}"
                            required
                        />
                        @error('judul_jobdesk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>



            {{-- FIELD: DIVISI --}}
            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Divisi</label>
                <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ri ri-group-line"></i></span>
                        <select
                            class="form-select @error('divisi') is-invalid @enderror"
                            name="divisi"
                            required
                        >
                            {{-- Default value jika tidak ada data lama --}}
                            <option value="" disabled {{ old('divisi') == null && $jobdesk->divisi == null ? 'selected' : '' }}>Pilih Divisi</option>

                            {{-- Menggunakan old() dengan fallback ke $jobdesk->divisi --}}
                            @php
                                $currentDivisi = old('divisi', $jobdesk->divisi);
                            @endphp

                            <option value="direktur" {{ $currentDivisi == 'direktur' ? 'selected' : '' }}>Direktur</option>
                            <option value="kepala teknik" {{ $currentDivisi == 'kepala teknik' ? 'selected' : '' }}>Kepala Teknik</option>
                            <option value="enginer" {{ $currentDivisi == 'enginer' ? 'selected' : '' }}>Enginer</option>
                            <option value="produksi" {{ $currentDivisi == 'produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="keuangan" {{ $currentDivisi == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                        </select>
                        @error('divisi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <a href="{{ url('/administrasi/jobdesk') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
