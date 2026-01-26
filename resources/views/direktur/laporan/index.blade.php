@extends('layout.direktur-template')

@section('title', 'Laporan Karyawan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Laporan Karyawan</h5>
        <a href="{{ url('/direktur/laporan/tabel') }}" class="btn btn-primary">
            <i class="ri ri-table-2 me-1"></i> Tampilan Tabel
        </a>
    </div>

    <div class="row g-4">
        @foreach ($laporans as $laporan)
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-medium">{{ $laporan->judul }}</span>
                        <span class="badge bg-label-{{ $laporan->status == 'Selesai' ? 'success' : ($laporan->status == 'Menunggu' ? 'warning' : 'info') }}">
                            {{ $laporan->status }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-body-secondary">Dari: {{ $laporan->pengguna->nama ?? 'Unknown' }}</h6>
                        <p class="card-text text-truncate">{{ $laporan->deskripsi }}</p>
                        <a href="{{ route('direktur.laporan.show', $laporan->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                    </div>
                    <div class="card-footer text-body-secondary">
                        Dikirim pada: {{ $laporan->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
