@extends('layout.direktur-template')

@section('title', 'Laporan Karyawan (Tabel)')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Laporan Karyawan</h5>
        <a href="{{ url('/direktur/laporan') }}" class="btn btn-secondary">
            <i class="ri ri-layout-masonry-line me-1"></i> Tampilan Card
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Daftar Laporan</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Subjek</th>
                        <th>Pengirim</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($laporans as $laporan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="fw-medium">{{ $laporan->judul }}</span></td>
                            <td>{{ $laporan->pengguna->nama ?? 'Unknown' }}</td>
                            <td>{{ $laporan->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-label-{{ $laporan->status == 'Selesai' ? 'success' : ($laporan->status == 'Menunggu' ? 'warning' : 'info') }}">
                                    {{ $laporan->status }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('/direktur/laporan/detail/'.$laporan->id) }}">
                                            <i class="icon-base ri ri-eye-line icon-18px me-1"></i>
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($laporans->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada laporan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
