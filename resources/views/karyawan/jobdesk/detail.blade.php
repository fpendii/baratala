@extends('layout.karyawan-template')

@section('title', 'Detail Laporan Jobdesk')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Detail Laporan Jobdesk</h5>
        <a href="{{ route('karyawan.jobdesk.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card p-4">
        <h5 class="card-title">{{ $laporan->jobdesk->judul_jobdesk }}</h5>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Deskripsi:</strong></p>
                <p>{{ $laporan->deskripsi }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong>
                    @if ($laporan->status == 'diterima')
                        <span class="badge bg-label-success">{{ $laporan->status }}</span>
                    @elseif($laporan->status == 'ditolak')
                        <span class="badge bg-label-danger">{{ $laporan->status }}</span>
                    @else
                        <span class="badge bg-label-warning">{{ $laporan->status }}</span>
                    @endif
                </p>
                <p><strong>Lampiran:</strong>
                    @if ($laporan->lampiran)
                        <a href="{{ asset('storage/public/' . $laporan->lampiran) }}" target="_blank">Lihat Lampiran</a>
                    @else
                        Tidak ada lampiran
                    @endif
                </p>
                <p><strong>Tanggal Dibuat:</strong> {{ $laporan->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
