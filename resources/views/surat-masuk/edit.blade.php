@extends('layout.app')

@section('title', 'Edit Surat Masuk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Surat Masuk: {{ $suratMasuk->judul }}</h5>
        </div>
        <div class="card-body">
            {{-- Pastikan method PUT/PATCH dan enctype ada --}}
            <form method="POST" action="{{ route('surat-masuk.update', $suratMasuk->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Surat / Perihal</label>
                    <input type="text" id="judul" name="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan judul surat" value="{{ old('judul', $suratMasuk->judul) }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pengirim" class="form-label">Pengirim Surat</label>
                        <input type="text" id="pengirim" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror" placeholder="Nama atau Instansi Pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}" required>
                        @error('pengirim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nomor_surat" class="form-label">Nomor Surat (Opsional)</label>
                        <input type="text" id="nomor_surat" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" placeholder="Contoh: 001/SK/III/2024" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}">
                        @error('nomor_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_terima" class="form-label">Tanggal Terima</label>
                        <input type="date" id="tanggal_terima" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima) }}" required>
                        @error('tanggal_terima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label">Prioritas Surat</label>
                        <select id="prioritas" name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="rendah" {{ old('prioritas', $suratMasuk->prioritas) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ old('prioritas', $suratMasuk->prioritas) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ old('prioritas', $suratMasuk->prioritas) == 'tinggi' ? 'selected' : '' }}>Tinggi (Urgent)</option>
                        </select>
                        @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lampiran" class="form-label">Ganti Lampiran (Kosongkan jika tidak ingin ganti)</label>
                    @if ($suratMasuk->lampiran)
                        <p class="text-muted small">
                            File saat ini: <a href="{{ asset('storage/public/' . $suratMasuk->lampiran) }}" target="_blank">{{ basename($suratMasuk->lampiran) }}</a>
                        </p>
                    @endif
                    <input type="file" id="lampiran" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    @error('lampiran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="form-label">Keterangan / Isi Ringkas</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="4" placeholder="Tuliskan keterangan singkat mengenai isi surat atau tujuan." required>{{ old('keterangan', $suratMasuk->keterangan) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-refresh me-1"></i> Perbarui Surat
                </button>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
