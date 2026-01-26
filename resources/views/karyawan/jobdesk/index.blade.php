@extends('layout.karyawan-template')

@section('title', 'Laporan Jobdesk')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Laporan Jobdesk Saya</h5>
        <a href="/karyawan/jobdesk/create" class="btn btn-primary btn-sm">Tambah Laporan</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Judul Jobdesk</th>
                        <th>Deskripsi Laporan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanJobdesks as $item)
                        <tr>
                            {{-- Kolom data lainnya... --}}
                            <td><strong class="text-primary">{{ $item->jobdesk->judul_jobdesk }}</strong></td>
                            <td>{{ Str::limit($item->deskripsi, 50) }}</td>

                            <td>
                                @php
                                    $statusClass =
                                        [
                                            'diterima' => 'success',
                                            'ditolak' => 'danger',
                                            'menunggu' => 'warning',
                                            'direvisi' => 'info', // Tambahkan status lain jika ada
                                        ][$item->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-label-{{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                            <td>
                                {{-- Tombol Detail yang memicu modal --}}
                                <a href="javascript:void(0);"
                                    class="btn btn-sm btn-primary btn-detail-jobdesk"
                                    data-bs-toggle="modal" data-bs-target="#detailJobdeskModal"
                                    data-judul="{{ $item->jobdesk->judul_jobdesk }}" data-deskripsi="{{ $item->deskripsi }}"
                                    data-status="{{ $item->status }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}"
                                    data-lampiran="{{ $item->lampiran ? asset('storage/' . $item->lampiran) : '' }}">
                                    <i class="bx bx-show me-1"></i> Detail
                                    </a>
                                </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada laporan jobdesk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

{{--------------------------------------------------}}
{{-- MODAL DETAIL LAPORAN JOBDESK --}}
{{--------------------------------------------------}}
<div class="modal fade" id="detailJobdeskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-detail me-2 text-primary"></i> Detail Laporan Jobdesk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Utama Laporan</h6>

                {{-- Judul Laporan --}}
                <div class="mb-3">
                    <strong class="d-block mb-1">Judul Tugas:</strong>
                    <h4 class="fw-bold text-dark" id="detailJudulJobdesk"></h4>
                </div>

                {{-- Detail Status dan Tanggal --}}
                <ul class="list-group list-group-flush mb-4 border rounded">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <i class="bx bx-check-shield me-2 text-success"></i>
                            <strong>Status Persetujuan:</strong>
                        </div>
                        {{-- Status akan diisi oleh JS sebagai badge --}}
                        <span id="detailStatus"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <i class="bx bx-calendar-alt me-2 text-secondary"></i>
                            <strong>Waktu Laporan Dibuat:</strong>
                        </div>
                        <span id="detailTanggal" class="text-wrap text-end text-dark fw-medium"></span>
                    </li>
                </ul>

                {{-- Deskripsi Laporan --}}
                <h6 class="text-primary border-bottom pb-2 mb-3">Deskripsi / Hasil Pekerjaan</h6>
                <div class="p-3 bg-light rounded mb-4">
                    <p style="white-space: pre-wrap; margin-bottom: 0;" class="text-dark" id="detailDeskripsi"></p>
                </div>

                {{-- Lampiran --}}
                <h6 class="text-primary border-bottom pb-2 mb-3">Lampiran Bukti Kerja</h6>
                <div class="d-flex align-items-center">
                    <a href="#" target="_blank" id="detailLampiran"
                        class="btn btn-sm btn-outline-info d-none shadow-sm">
                        <i class="bx bx-paperclip me-1"></i> Lihat Lampiran
                    </a>
                    <span id="noLampiranText" class="text-muted ms-3 d-none">
                        <i class="bx bx-info-circle me-1"></i> Tidak ada lampiran.
                    </span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailJobdeskModal = document.getElementById('detailJobdeskModal');

    detailJobdeskModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        // Ambil data dari data-attribute
        const data = {
            judul: button.getAttribute('data-judul'),
            deskripsi: button.getAttribute('data-deskripsi'),
            status: button.getAttribute('data-status'),
            tanggal: button.getAttribute('data-tanggal'),
            lampiran: button.getAttribute('data-lampiran')
        };

        // Mapping warna badge untuk Status
        const statusClassMap = {
            'diterima': 'success',
            'ditolak': 'danger',
            'menunggu': 'warning',
            'direvisi': 'info',
        };
        const currentStatus = data.status.toLowerCase();
        const statusClass = statusClassMap[currentStatus] || 'secondary';
        const statusText = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);

        // 1. Mengisi data teks
        document.getElementById('detailJudulJobdesk').textContent = data.judul;
        document.getElementById('detailDeskripsi').textContent = data.deskripsi || 'Deskripsi laporan kosong.';
        document.getElementById('detailTanggal').textContent = data.tanggal;

        // 2. Mengisi Badge Status
        const statusElement = document.getElementById('detailStatus');
        statusElement.innerHTML = `<span class="badge bg-label-${statusClass}">${statusText}</span>`;

        // 3. Mengatur Lampiran
        const lampiranLink = document.getElementById('detailLampiran');
        const noLampiranText = document.getElementById('noLampiranText');

        if (data.lampiran) {
            lampiranLink.href = data.lampiran;
            lampiranLink.classList.remove('d-none');
            noLampiranText.classList.add('d-none');
        } else {
            // Jika tidak ada lampiran
            lampiranLink.classList.add('d-none');
            noLampiranText.classList.remove('d-none');
        }
    });
});
</script>
@endpush
