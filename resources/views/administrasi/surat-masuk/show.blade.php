@extends('layout.administrasi-template')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Surat Masuk /</span> Detail Surat
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Informasi Detail Surat Masuk</h5>
            <div class="btn-group">
                <a href="{{ url('administrasi/surat-masuk/edit', $suratMasuk->id) }}" class="btn btn-sm btn-warning me-2">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <a href="{{ url('administrasi/surat-masuk') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <dl class="row detail-list-custom">

                {{-- JUDUL DAN NOMOR SURAT --}}
                <dt class="col-sm-3 text-nowrap">Judul Surat</dt>
                <dd class="col-sm-9 text-break fw-bold">{{ $suratMasuk->judul }}</dd>

                <dt class="col-sm-3 text-nowrap">Nomor Surat</dt>
                <dd class="col-sm-9 text-break">{{ $suratMasuk->nomor_surat ?? '-' }}</dd>

                {{-- PENGIRIM DAN PRIORITAS --}}
                <dt class="col-sm-3 text-nowrap">Pengirim</dt>
                <dd class="col-sm-9 text-break">{{ $suratMasuk->pengirim }}</dd>

                <dt class="col-sm-3 text-nowrap">Prioritas</dt>
                <dd class="col-sm-9">
                    @php
                        $prioritasClass = [
                            'tinggi' => 'danger',
                            'sedang' => 'warning',
                            'rendah' => 'secondary',
                        ];
                        $currentPrioritas = strtolower($suratMasuk->prioritas ?? 'rendah');
                    @endphp
                    <span class="badge bg-{{ $prioritasClass[$currentPrioritas] ?? 'secondary' }}">
                        {{ ucfirst($currentPrioritas) }}
                    </span>
                </dd>

                <hr class="my-3">

                {{-- TANGGAL DAN WAKTU --}}
                <dt class="col-sm-3 text-nowrap">Tanggal Diterima</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->format('l, d M Y') }}</dd>

                <dt class="col-sm-3 text-nowrap">Waktu Dicatat</dt>
                <dd class="col-sm-9">{{ $suratMasuk->created_at->format('d M Y H:i:s') }}</dd>

                <hr class="my-3">

                {{-- LAMPIRAN --}}
                <dt class="col-sm-3 text-nowrap">Lampiran File</dt>
                <dd class="col-sm-9">
                    @if($suratMasuk->lampiran)
                        <a href="{{ asset('storage/' . $suratMasuk->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0">
                            <i class="bx bx-download me-1"></i> Unduh Lampiran ({{ basename($suratMasuk->lampiran) }})
                        </a>
                    @else
                        Tidak ada file lampiran.
                    @endif
                </dd>
            </dl>

            <h6 class="mt-4 mb-2">Keterangan / Isi Ringkas</h6>
            <p class="border p-3 rounded bg-light">{{ $suratMasuk->keterangan ?? 'Tidak ada keterangan singkat.' }}</p>

            {{-- Anda bisa menambahkan bagian untuk respon Direktur di sini di masa mendatang --}}

        </div>
    </div>

    <a href="{{ url('administrasi/surat-masuk') }}" class="btn btn-secondary mt-3">
        <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Surat
    </a>
</div>
@endsection
