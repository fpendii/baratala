@extends('layout.app')

@section('title', 'Edit Laporan Jobdesk')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
<div class="card">
<div class="card-header">
<h5 class="mb-0">Edit Laporan Jobdesk</h5>
</div>
<div class="card-body">
<form method="POST" action="{{ route('jobdesk.update', $laporan->id) }}" enctype="multipart/form-data">
@csrf
@method('PUT')

            <!-- Dropdown Jobdesk -->
            <div class="mb-3">
                <label for="id_jobdesk" class="form-label">Pilih Jobdesk</label>
                <select id="id_jobdesk" name="id_jobdesk" class="form-select @error('id_jobdesk') is-invalid @enderror" required>
                    <option value="">-- Pilih Jobdesk --</option>
                    @foreach($jobdesks as $jobdesk)
                        <option value="{{ $jobdesk->id }}" {{ ($laporan->id_jobdesk == $jobdesk->id || old('id_jobdesk') == $jobdesk->id) ? 'selected' : '' }}>
                            {{ $jobdesk->judul_jobdesk }}
                        </option>
                    @endforeach
                </select>
                @error('id_jobdesk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5" placeholder="Tuliskan deskripsi laporan" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Lampiran -->
            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
                <input type="file" id="lampiran" name="lampiran" class="form-control @error('lampiran') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png">
                @error('lampiran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($laporan->lampiran)
                    <small class="form-text text-muted mt-2">Lampiran saat ini: <a href="{{ asset('storage/public/' . $laporan->lampiran) }}" target="_blank">Lihat File</a>. Unggah file baru untuk mengganti.</small>
                @endif
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="dikerjakan" {{ ($laporan->status == 'dikerjakan' || old('status') == 'dikerjakan') ? 'selected' : '' }}>Dikerjakan</option>
                    <option value="tidak-dikerjakan" {{ ($laporan->status == 'tidak-dikerjakan' || old('status') == 'tidak-dikerjakan') ? 'selected' : '' }}>Tidak Dikerjakan</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Perbarui Laporan</button>
            <a href="{{ route('jobdesk.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

</div>
@endsection
