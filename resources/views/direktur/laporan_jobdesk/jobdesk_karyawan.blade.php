@extends('layout.direktur-template')

@section('title', 'Laporan Jobdesk Karyawan')

@section('content')
<div class="nav-align-top">
    @include('layout.navigasi-laporan-jobdesk')

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-truncate">Nama</th>
                        <th class="text-truncate">Email</th>
                        <th class="text-truncate">Divisi</th>
                        <th class="text-truncate">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $karyawan)
                    <tr>
                        <td>{{ $karyawan->nama ?? '-' }}</td>
                        <td>{{ $karyawan->email ?? '-' }}</td>
                        <td>{{ $karyawan->divisi ?? '-' }}</td>
                        <td>
                            <a href="{{ route('direktur.laporan-jobdesk.karyawan.detail', $karyawan->id) }}" class="btn btn-primary btn-sm">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada karyawan dengan laporan jobdesk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
