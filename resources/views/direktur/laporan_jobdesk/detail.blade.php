@extends('layout.direktur-template')

@section('title', 'Detail Laporan Jobdesk Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Detail Laporan Jobdesk Karyawan</h5>
    <a href="{{ url('/direktur/jobdesk-laporan') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="card-title mb-2">Karyawan</h6>
        <p>{{ $laporan->pengguna->nama ?? '-' }}</p>

        <h6 class="card-title mb-2">Judul Jobdesk</h6>
        <p>{{ $laporan->jobdesk->judul ?? '-' }}</p>

        <h6 class="card-title mb-2">Deskripsi Jobdesk</h6>
        <p>{{ $laporan->jobdesk->deskripsi ?? '-' }}</p>

        <h6 class="card-title mb-2">Deskripsi Laporan</h6>
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
            @if($laporan->status === 'dikerjakan')
                <span class="badge bg-label-primary rounded-pill">Dikerjakan</span>
            @else
                <span class="badge bg-label-danger rounded-pill">Belum Dikerjakan</span>
            @endif
        </p>

        <h6 class="card-title mb-2">Tanggal Dibuat</h6>
        <p>{{ $laporan->created_at->format('d M Y H:i') }}</p>

        <h6 class="card-title mb-2">Tanggal Diperbarui</h6>
        <p>{{ $laporan->updated_at->format('d M Y H:i') }}</p>
    </div>
</div>
@endsection
