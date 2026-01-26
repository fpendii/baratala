@extends('layout.karyawan-template')

@section('title', 'Detail Rencana Kerja')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Rencana Kerja /</span> Detail
    </h4>

    {{-- Notifikasi (Opsional: Tambahkan notifikasi success/error di sini) --}}
    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    <div class="row">

        {{-- KOLOM KIRI (7/12): DETAIL RENCANA KERJA & KOMENTAR --}}
        <div class="col-lg-7 col-md-12 mb-4">
            {{-- CARD 1: DETAIL RENCANA KERJA --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Rencana Kerja</h5>
                    <a href="{{ route('karyawan.rencana.edit', $tugas->id) }}" class="btn btn-sm btn-warning">
                        <i class="bx bx-edit-alt me-1"></i> Edit Status & Detail
                    </a>
                </div>
                <div class="card-body">
                    <dl class="row detail-list-custom">
                        <dt class="col-sm-4 text-nowrap">Judul Rencana</dt>
                        <dd class="col-sm-8 text-break fw-bold">{{ $tugas->judul_rencana }}</dd>

                        <dt class="col-sm-4 text-nowrap">Deskripsi</dt>
                        <dd class="col-sm-8 text-break">{{ $tugas->deskripsi }}</dd>

                        <dt class="col-sm-4 text-nowrap">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-label-{{ $tugas->status == 'selesai' ? 'success' : ($tugas->status == 'sedang dikerjakan' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($tugas->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4 text-nowrap">Prioritas</dt>
                        <dd class="col-sm-8">{{ $tugas->prioritas ?? '-' }}</dd>

                        <dt class="col-sm-4 text-nowrap">Jenis</dt>
                        <dd class="col-sm-8">{{ ucfirst($tugas->jenis) }}</dd>
                    </dl>

                    <hr class="my-3">

                    <dl class="row detail-list-custom">
                        <dt class="col-sm-4 text-nowrap">Tanggal Mulai</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($tugas->tanggal_mulai)->format('d F Y') }}</dd>

                        <dt class="col-sm-4 text-nowrap">Tanggal Selesai</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($tugas->tanggal_selesai)->format('d F Y') }}</dd>

                        <dt class="col-sm-4 text-nowrap">Lampiran</dt>
                        <dd class="col-sm-8">
                            @if($tugas->lampiran)
                                <a href="{{ asset('storage/public/' . $tugas->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0">
                                    <i class="bx bx-download me-1"></i> Lihat File
                                </a>
                            @else
                                Tidak ada
                            @endif
                        </dd>
                    </dl>

                    <h6 class="mt-4 mb-2">Catatan dari Direktur/Pembuat Rencana</h6>
                    <p class="border p-3 rounded bg-light">{{ $tugas->catatan ?? 'Tidak ada catatan khusus untuk rencana kerja ini.' }}</p>
                </div>
            </div>

            {{-- CARD 2: LIST KOMENTAR & TAMBAH KOMENTAR (KARYAWAN HANYA BISA MELIHAT & MENAMBAH) --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Diskusi dan Komentar</h5>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#tambahKomentarModal">
                        <i class="bx bx-message-add me-1"></i> Tambah Komentar
                    </button>
                </div>
                <div class="card-body komentar-list-container">
                    @forelse($tugas->komentar as $komentar)
                        <div class="d-flex mb-3 p-3 border rounded">
                            <div class="flex-shrink-0 me-3">
                                {{-- Icon atau Avatar --}}
                                <div class="avatar avatar-sm rounded-circle bg-label-secondary d-flex align-items-center justify-content-center">
                                    <span class="avatar-initial rounded-circle">{{ strtoupper(substr($komentar->pengguna->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $komentar->pengguna->name }}</h6>
                                    <small class="text-muted">{{ $komentar->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="text-muted mb-2 small">{{ $komentar->pengguna->role }}</p>
                                <p class="mb-2">{{ $komentar->isi }}</p>

                                <div class="d-flex flex-wrap align-items-center">
                                    <span class="badge me-2 bg-{{ $komentar->status == 'setuju' ? 'success' : ($komentar->status == 'tolak' ? 'danger' : 'warning') }}" title="Status diset oleh Direktur">
                                        Status: {{ ucfirst($komentar->status) }}
                                    </span>
                                    {{-- Tombol Setujui/Tolak (Moderasi) dihilangkan untuk karyawan --}}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light mb-0 text-center">
                            Belum ada komentar atau diskusi untuk rencana kerja ini. Klik "Tambah Komentar" untuk memulai.
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('karyawan.rencana.index') }}" class="btn btn-secondary mt-3">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Rencana
            </a>
        </div>


        {{-- KOLOM KANAN (5/12): PENGGUNA YANG DITUGASKAN (Read-Only) --}}
        <div class="col-lg-5 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Pengguna yang Ditugaskan</h5>
                    <small class="text-muted">Daftar tim pelaksana untuk rencana kerja ini.</small>
                </div>
                <div class="card-body">
                    @if($tugas->pengguna->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach($tugas->pengguna as $user)
                                <li class="mb-3 d-flex align-items-center p-2 border rounded bg-light">
                                    <div class="avatar avatar-sm rounded-circle bg-label-primary me-3 d-flex align-items-center justify-content-center">
                                        <span class="avatar-initial rounded-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }} ({{ $user->role }})</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info text-center py-2 mb-0">
                            Belum ada pengguna yang ditugaskan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UNTUK TAMBAH KOMENTAR (KARYAWAN) --}}
<div class="modal fade" id="tambahKomentarModal" tabindex="-1" aria-labelledby="tambahKomentarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('karyawan.rencana.komentar', $tugas->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKomentarModalLabel">Tulis Komentar & Masukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="komentar_karyawan" class="form-label">Komentar Anda</label>
                        <textarea class="form-control" id="komentar_karyawan" name="komentar_karyawan" rows="5" placeholder="Berikan laporan progress, pertanyaan, atau masukan..." required></textarea>
                        <small class="text-muted">Komentar akan dicatat atas nama Anda dan statusnya akan menjadi 'pertimbangkan' sampai disetujui Direktur.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-send me-1"></i> Kirim Komentar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- END MODAL --}}
@endsection
