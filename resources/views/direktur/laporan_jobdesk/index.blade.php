@extends('layout.direktur-template')

@section('title', 'Laporan Jobdesk Karyawan')

@section('content')
<div class="nav-align-top">
    @include('layout.navigasi-laporan-jobdesk')
    <div class="tab-content">
        <!-- TAB JOBDESK -->
        <div class="tab-pane fade show active">
            <!-- Filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Cari nama / jobdesk...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">Filter Divisi</option>
                        <option value="Tambang">Tambang</option>
                        <option value="Keuangan">Keuangan</option>
                        <option value="HRD">HRD</option>
                        <option value="Operasional">Operasional</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </div>

            <!-- Tabel -->
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Divisi</th>
                            <th>Jobdesk</th>
                            <th>Lampiran</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($laporans as $laporan)
                        <tr>
                            <td>{{ $laporan->pengguna->nama ?? '-' }}</td>
                            <td>{{ $laporan->pengguna->divisi ?? '-' }}</td>
                            <td>{{ $laporan->jobdesk->nama ?? '-' }}</td>
                            <td>
                                @if($laporan->lampiran)
                                    <a href="{{ asset('storage/' . $laporan->lampiran) }}" target="_blank">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($laporan->status == 'dikerjakan')
                                    <span class="badge rounded-pill bg-label-primary me-1">Dikerjakan</span>
                                @else
                                    <span class="badge rounded-pill bg-label-danger me-1">Belum Dikerjakan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('direktur.laporan-jobdesk.detail', $laporan->id) }}" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada laporan jobdesk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
