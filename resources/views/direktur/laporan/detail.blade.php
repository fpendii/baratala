@extends('layout.direktur-template')

@section('title', 'Detail Laporan Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Detail Laporan Karyawan</h5>
    <a href="{{ route('direktur.laporan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <h6 class="card-title mb-2">Judul Laporan</h6>
        <p>{{ $laporan->judul }}</p>

        <h6 class="card-title mb-2">Karyawan</h6>
        <p>{{ $laporan->pengguna->nama ?? 'Tidak diketahui' }}</p>

        <h6 class="card-title mb-2">Deskripsi</h6>
        <p>{{ $laporan->deskripsi }}</p>

        <h6 class="card-title mb-2">Lampiran</h6>
        <p>
            @if($laporan->lampiran)
                <a href="{{ asset('storage/public/' . $laporan->lampiran) }}" target="_blank">Lihat Lampiran</a>
            @else
                <span class="text-muted">Tidak ada lampiran</span>
            @endif
        </p>

        <h6 class="card-title mb-2">Status</h6>
        <p>
            @if($laporan->status == 'pending')
                <span class="badge bg-warning rounded-pill">Pending</span>
            @elseif($laporan->status == 'diterima')
                <span class="badge bg-success rounded-pill">Diterima</span>
            @elseif($laporan->status == 'ditolak')
                <span class="badge bg-danger rounded-pill">Ditolak</span>
            @else
                <span class="badge bg-secondary rounded-pill">{{ ucfirst($laporan->status) }}</span>
            @endif
        </p>

        <h6 class="card-title mb-2">Keputusan</h6>
        <p>
            {{ $laporan->keputusan ?? '-' }}
            <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#keputusanModal">
                Lihat / Ubah Keputusan
            </button>
        </p>

        <h6 class="card-title mb-2">Tanggal Dibuat</h6>
        <p>{{ $laporan->created_at ? $laporan->created_at->format('d M Y H:i') : '-' }}</p>

        <h6 class="card-title mb-2">Tanggal Diperbarui</h6>
        <p>{{ $laporan->updated_at ? $laporan->updated_at->format('d M Y H:i') : '-' }}</p>
    </div>
</div>

<!-- ✅ Modal Update Keputusan -->
<div class="modal fade" id="keputusanModal" tabindex="-1" aria-labelledby="keputusanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="keputusanModalLabel">Update Keputusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('direktur.laporan.keputusan', $laporan->id) }}" method="POST" id="formKeputusan">
            @csrf
            <div class="mb-3">
                <label for="keputusan" class="form-label">Catatan / Keputusan</label>
                <textarea name="keputusan" id="keputusan" class="form-control" rows="3">{{ old('keputusan', $laporan->keputusan) }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="status" value="diterima" class="btn btn-success">Setujui</button>
                <button type="submit" name="status" value="ditolak" class="btn btn-danger">Tolak</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
