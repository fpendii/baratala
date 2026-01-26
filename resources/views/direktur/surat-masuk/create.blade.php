@extends('layout.direktur-template')

@section('title', 'Input Surat Masuk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Input Surat Masuk Baru ✉️</h5>
        </div>
        <div class="card-body">
            {{-- Pastikan enctype="multipart/form-data" ada untuk upload file --}}
            <form method="POST" action="{{ route('direktur.surat-masuk.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Surat / Perihal</label>
                    <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan judul surat" value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pengirim" class="form-label">Pengirim Surat</label>
                        <input type="text" id="pengirim" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror" placeholder="Nama atau Instansi Pengirim" value="{{ old('pengirim') }}" required>
                        @error('pengirim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nomor_surat" class="form-label">Nomor Surat (Opsional)</label>
                        <input type="text" id="nomor_surat" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" placeholder="Contoh: 001/SK/III/2024" value="{{ old('nomor_surat') }}">
                        @error('nomor_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_terima" class="form-label">Tanggal Terima</label>
                        <input type="date" id="tanggal_terima" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', date('Y-m-d')) }}" required>
                        @error('tanggal_terima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label">Prioritas Surat</label>
                        <select id="prioritas" name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi (Urgent)</option>
                        </select>
                        @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran File (PDF/Gambar/Dokumen - Maks 5MB)</label>
                    <input type="file" id="lampiran" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    @error('lampiran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="form-label">Keterangan / Isi Ringkas</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="4" placeholder="Tuliskan keterangan singkat mengenai isi surat atau tujuan." required>{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Surat Masuk
                </button>
                <a href="{{ route('direktur.surat-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
