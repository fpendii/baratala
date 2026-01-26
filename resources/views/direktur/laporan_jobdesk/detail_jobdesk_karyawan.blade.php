@extends('layout.direktur-template')

@section('title', 'Laporan Jobdesk Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        Laporan Jobdesk Karyawan - {{ $laporans->first()->pengguna->nama ?? 'Karyawan' }}
    </h5>
    <a href="{{ url('/direktur/jobdesk-laporan/jobdesk-karyawan') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<div class="card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Jobdesk</th>
                    <th>Deskripsi Laporan</th>
                    <th>Lampiran</th>
                    <th>Status</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporans as $index => $laporan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $laporan->jobdesk->judul ?? '-' }}</td>
                    <td>{{ $laporan->deskripsi }}</td>
                    <td>
                        @if($laporan->lampiran)
                            <a href="{{ asset('storage/public/' . $laporan->lampiran) }}" target="_blank">Lihat</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($laporan->status === 'dikerjakan')
                            <span class="badge bg-label-primary rounded-pill">Dikerjakan</span>
                        @else
                            <span class="badge bg-label-danger rounded-pill">Belum Dikerjakan</span>
                        @endif
                    </td>
                    <td>{{ $laporan->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('direktur.laporan-jobdesk.detail', $laporan->id) }}"
                           class="btn btn-primary btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
