@extends('layout.administrasi-template')

@section('title', 'Update Rencana Kerja')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Rencana Kerja /</span> Update</h4>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Update Rencana Kerja: {{ $rencana->judul_rencana }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('administrasi/rencana/update', $rencana->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Judul Rencana (READONLY) --}}
                <div class="mb-3">
                    <label for="judul_rencana" class="form-label">Judul Rencana</label>
                    <input type="text" id="judul_rencana" name="judul_rencana" class="form-control" value="{{ $rencana->judul_rencana }}">
                </div>

                {{-- Deskripsi (READONLY) --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="5">{{ $rencana->deskripsi }}</textarea>
                </div>

                <div class="row">
                    {{-- Tanggal Mulai (READONLY) --}}
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ $rencana->tanggal_mulai }}">
                    </div>
                    {{-- Tanggal Selesai (READONLY) --}}
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ $rencana->tanggal_selesai }}">
                    </div>
                </div>

                <div class="row">
                    {{-- Status (EDITABLE) --}}
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="belum dikerjakan" {{ $rencana->status == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                            <option value="sedang dikerjakan" {{ $rencana->status == 'sedang dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                            <option value="selesai" {{ $rencana->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    {{-- Prioritas (DISABLED) --}}
                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label">Prioritas</label>
                        <select id="prioritas" name="prioritas" class="form-select" disabled>
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="rendah" {{ $rencana->prioritas == 'rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="sedang" {{ $rencana->prioritas == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="tinggi" {{ $rencana->prioritas == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                        <small class="text-muted">Prioritas diatur oleh Direktur.</small>
                    </div>
                </div>
                <input type="hidden" name="jenis" value="rencana" id="">

                {{-- Pengguna yang Ditugaskan (Hanya Tampil, Tidak Bisa Diubah) --}}
                <div class="mb-3">
                    <label class="form-label d-block">Pengguna yang Ditugaskan</label>
                    @if (!empty($rencana->pengguna))
                        @foreach ($rencana->pengguna as $user)
                            <span class="badge bg-label-primary me-1">{{ $user->nama }} ({{ $user->email }})</span>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada pengguna yang ditugaskan.</p>
                    @endif
                    {{-- Hidden input agar ID pengguna tetap dikirim (walaupun tidak diubah) --}}
                    <input type="hidden" name="pengguna" value="{{ implode(',', $rencana->assigned_user_ids ?? []) }}">
                </div>

                {{-- Lampiran (Opsional/EDITABLE) --}}
                <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
                    @if ($rencana->lampiran)
                        <div class="alert alert-info d-flex justify-content-between align-items-center p-2 mb-2">
                            <span>File saat ini: <a href="{{ asset('storage/public/' . $rencana->lampiran) }}" target="_blank" class="fw-bold">{{ basename($rencana->lampiran) }}</a></span>
                        </div>
                    @endif
                    <input type="file" id="lampiran" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                    <div class="form-text">Upload file baru atau biarkan kosong.</div>
                </div>

                {{-- Catatan (EDITABLE) --}}
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan atau update progress pekerjaan">{{ $rencana->catatan }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Update</button>
                <a href="{{ url('administrasi/rencana') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
{{-- Tidak ada JS untuk Pengguna karena karyawan tidak bisa mengubah penugasan --}}
@endsection
