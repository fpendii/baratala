@extends('layout.app')

@section('title', 'Disposisi Saya')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-3 mb-0">
        Disposisi Saya
    </h4>
     <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">
        <i class="icon-base ri ri-arrow-left-line icon-18px me-1"></i> Kembali
    </a>
</div>

{{-- ALERT --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <h5 class="card-header">
        Daftar Surat Disposisi
    </h5>

    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Judul Surat</th>
                    <th>Pengirim</th>
                    <th>Tanggal Disposisi</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Dokumen</th>
                </tr>
            </thead>
            <tbody>

            @forelse ($disposisi as $index => $item)
                @php
                    $surat = $item->suratMasuk;
                @endphp

                <tr class="{{ $item->status_baca === 'belum' ? 'table-danger' : '' }}">
                    <td>{{ $disposisi->firstItem() + $index }}</td>

                    <td>
                        <strong>{{ $surat->judul }}</strong><br>
                        <small class="text-muted">
                            {{ $surat->nomor_surat ?? '-' }}
                        </small>
                    </td>

                    <td>{{ $surat->pengirim }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal_disposisi)->format('d M Y') }}
                    </td>

                    <td>
                        {{ $item->catatan ?? '-' }}
                    </td>

                    <td>
                        @if ($item->status_baca === 'belum')
                            <span class="badge bg-danger">
                                Belum Dibaca
                            </span>
                        @else
                            <span class="badge bg-success">
                                Sudah Dibaca
                            </span>
                        @endif
                    </td>

                    {{-- DOKUMEN --}}
                    <td>
                        @if ($surat->lampiran)
                            @php
                                $fileName = basename($surat->lampiran);
                                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                $fileUrl = route('surat.tampil', $fileName);

                                $officeExt = ['doc','docx','xls','xlsx','ppt','pptx'];
                                $finalUrl = in_array($extension, $officeExt)
                                    ? 'https://docs.google.com/viewer?url=' . urlencode($fileUrl) . '&embedded=true'
                                    : $fileUrl;
                            @endphp

                            <a href="{{ $finalUrl }}" target="_blank"
                               class="btn btn-sm btn-outline-info">
                                <i class="ri ri-file-2-line"></i> Lihat
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        Tidak ada disposisi untuk Anda
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

    {{ $disposisi->links() }}
</div>

@endsection
