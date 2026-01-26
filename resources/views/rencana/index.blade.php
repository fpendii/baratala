@extends('layout.app')

@section('title', 'Rencana Kerja Saya')

@section('content')
    {{-- Header dengan Judul dan Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <i class="bx bx-calendar-check me-2 text-primary"></i> Rencana Kerja @if (Auth::user()->role != 'direktur')
                Saya
            @endif
        </h4>
        <a href="{{ route('rencana.create') }}" class="btn btn-primary shadow-sm">
            <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
        </a>
    </div>

    {{-- Alert Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Daftar Rencana Kerja --}}
    <div class="card">
        <h5 class="card-header border-bottom">Daftar Semua Rencana Kerja</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Rencana Kerja & Detail</th>
                        <th style="width: 15%;">Periode & Batas Waktu</th>
                        <th style="width: 5%;">Prioritas</th>
                        <th style="width: 5%;">Status</th>
                        <th style="width: 10%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($tugas as $index => $item)
                        <tr>
                            <td><span class="fw-bold">{{ $index + 1 }}</span></td>
                            <td>
                                <strong class="text-dark">
                                    <a href="{{ route('rencana.show', $item->id) }}"
                                        class="text-decoration-none text-primary"
                                        title="Lihat Detail: {{ $item->judul_rencana }}">
                                        {{ $item->judul_rencana }}
                                    </a>
                                </strong>
                                @if ($item->lampiran)
                                    <a href="{{ asset('storage/public/' . $item->lampiran) }}" target="_blank"
                                        class="ms-1 text-info" title="Lampiran tersedia">
                                        <i class="bx bx-paperclip"></i>
                                    </a>
                                @endif
                                <br>
                                <small class="text-muted d-block mt-1">
                                    <i class="bx bx-hash me-1"></i> Jenis: {{ ucfirst($item->jenis) }}
                                </small>
                            </td>
                            <td>
                                {{-- Tanggal Mulai --}}
                                <span class="d-block text-muted">
                                    <i class="bx bx-play-circle me-1"></i> Mulai:
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                </span>
                                {{-- Tanggal Selesai (Batas Waktu) --}}
                                <span class="d-block text-muted">
                                    <i class="bx bx-calendar-check me-1"></i> Batas:
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                                </span>

                                {{-- Indikator Keterlambatan / Sisa Hari --}}
                                @php
                                    $deadline = \Carbon\Carbon::parse($item->tanggal_selesai);
                                    $isOverdue = $deadline->isPast() && strtolower($item->status) != 'selesai';
                                    $remainingDays = now()->diffInDays($deadline, false);
                                @endphp
                                @if ($isOverdue)
                                    <span class="badge bg-danger mt-1">Terlambat</span>
                                @elseif ($remainingDays <= 3 && $remainingDays >= 0)
                                    {{-- Menggunakan (int) untuk memastikan angka yang ditampilkan adalah bilangan bulat --}}
                                    <span class="badge bg-warning mt-1">Deadline Dekat ({{ (int) $remainingDays }}
                                        hari)</span>
                                @elseif ($remainingDays < 0)
                                    {{-- Jika status sudah selesai, jangan tampilkan Terlambat lagi, tapi tidak ada lagi sisa hari --}}
                                @else
                                    {{-- Menggunakan (int) untuk memastikan angka yang ditampilkan adalah bilangan bulat --}}
                                    <span class="badge bg-label-info mt-1">Sisa {{ (int) $remainingDays }} hari</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $prioritasClass = [
                                        'tinggi' => 'danger',
                                        'sedang' => 'warning',
                                        'rendah' => 'secondary',
                                    ];
                                    $currentPrioritas = strtolower($item->prioritas);
                                @endphp
                                <span class="badge bg-{{ $prioritasClass[$currentPrioritas] ?? 'light' }}">
                                    {{ ucfirst($currentPrioritas) }}
                                </span>
                            </td>

                            {{-- ---------------------------------------------- --}}
                            {{-- FITUR UPDATE STATUS DENGAN DROPDOWN (3 STATUS) --}}
                            {{-- ---------------------------------------------- --}}
                            <td>
                                @php
                                    $statusList = ['belum dikerjakan', 'on progress', 'selesai'];
                                    $currentStatus = strtolower($item->status);
                                    $statusClass = [
                                        'selesai' => 'success',
                                        'on progress' => 'primary',
                                        'belum dikerjakan' => 'warning',
                                    ];
                                    // Handle status lain yang mungkin terkirim dari database (misal: 'direview', 'ditunda')
                                    // dan petakan ke badge yang sesuai, atau default ke secondary.
                                    $badgeClass = $statusClass[$currentStatus] ?? 'secondary';
                                    if ($currentStatus == 'direview' || $currentStatus == 'ditunda') {
                                        $badgeClass = $currentStatus == 'direview' ? 'info' : 'secondary';
                                    }
                                @endphp
                                @if (Auth::user()->role == 'direktur')
                                    {{-- Direktur hanya melihat status, tanpa bisa mengubah --}}
                                    <span class="badge bg-label-{{ $badgeClass }}">
                                        {{ ucfirst($currentStatus) }}
                                    </span>
                                @else
                                    {{-- Karyawan bisa mengubah status --}}
                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn btn-sm badge bg-label-{{ $badgeClass }} dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                            {{ ucfirst($currentStatus) }}
                                        </button>
                                        <div class="dropdown-menu">
                                            @foreach ($statusList as $status)
                                                <form
                                                    action="{{ url('karyawan/rencana/' . $item->id . '/update-status') }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                    <button type="submit" class="dropdown-item"
                                                        {{ $currentStatus === $status ? 'disabled' : '' }}>
                                                        <span
                                                            class="badge bg-label-{{ $statusClass[$status] ?? 'secondary' }} me-1"></span>
                                                        {{ ucfirst($status) }}
                                                        @if ($currentStatus === $status)
                                                            <i class="ri ri-check-line text-success ms-1"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </td>

                            {{-- ---------------------------------------------- --}}
                            {{-- KOLOM AKSI (MODAL DETAIL DI SINI) --}}
                            {{-- ---------------------------------------------- --}}
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('rencana.show', $item->id) }}">
                                            <i class="icon-base ri ri-eye-line icon-18px me-1"></i>
                                            Detail
                                        </a>

                                        @if (Auth::user()->role != 'direktur')
                                            {{-- Tombol Edit yang sudah ada --}}
                                            <a class="dropdown-item"
                                                href="{{ url('karyawan/rencana/' . $item->id . '/edit') }}">
                                                <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                                Edit
                                            </a>
                                            {{-- Form Delete yang sudah ada --}}
                                            <form action="{{ url('/karyawan/rencana/delete/' . $item->id) }}"
                                                method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bx bx-calendar-plus bx-lg d-block mb-3 text-muted"></i>
                                <h6 class="mb-1 text-dark">Belum ada Rencana Kerja @if (Auth::user()->role != 'direktur')
                                        yang Anda buat.
                                    @endif
                                </h6>
                                <p class="text-muted">Ayo mulai catat rencana kerja Anda untuk minggu/bulan ini!</p>
                                <a href="{{ route('rencana.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bx bx-plus me-1"></i> Buat Rencana
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

{{-- ---------------------------------------------- --}}
{{-- MODAL DETAIL RENCANA KERJA --}}
{{-- ---------------------------------------------- --}}
<div class="modal fade" id="detailRencanaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="icon-base ri ri-file-list-line me-2"></i> Detail Rencana Kerja
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Utama</h6>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Judul Rencana:</strong>
                        <span id="detail-judul" class="text-wrap text-end fw-bold"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Jenis:</strong>
                        <span id="detail-jenis"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Prioritas:</strong>
                        <span id="detail-prioritas"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Status:</strong>
                        <span id="detail-status"></span>
                    </li>
                </ul>

                <h6 class="text-primary border-bottom pb-2 mb-3">Periode Waktu</h6>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Tanggal Mulai:</strong>
                        <span id="detail-mulai"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Batas Waktu (Selesai):</strong>
                        <span id="detail-selesai"></span>
                    </li>
                </ul>

                <h6 class="text-primary border-bottom pb-2 mb-3">Deskripsi & Lampiran</h6>
                <div class="p-3 border rounded mb-3">
                    <strong>Deskripsi Tugas:</strong>
                    <p id="detail-deskripsi" class="mb-0 mt-1"></p>
                </div>

                <div class="p-3 border rounded">
                    <strong>Lampiran:</strong>
                    <div id="detail-lampiran-container" class="mt-2">
                        <span class="text-muted">Tidak ada lampiran</span>
                    </div>
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
            const detailRencanaModal = document.getElementById('detailRencanaModal');

            detailRencanaModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const data = {
                    judul: button.getAttribute('data-judul'),
                    jenis: button.getAttribute('data-jenis'),
                    deskripsi: button.getAttribute('data-deskripsi'),
                    mulai: button.getAttribute('data-mulai'),
                    selesai: button.getAttribute('data-selesai'),
                    prioritas: button.getAttribute('data-prioritas'),
                    status: button.getAttribute('data-status'),
                    lampiran: button.getAttribute('data-lampiran')
                };

                // Mapping class untuk status dan prioritas
                const prioritasClassMap = {
                    'tinggi': 'danger',
                    'sedang': 'warning',
                    'rendah': 'secondary',
                };
                // Mapping status yang disederhanakan
                const statusClassMap = {
                    'selesai': 'success',
                    'on progress': 'primary',
                    'belum dikerjakan': 'warning',
                    // Menambahkan mapping untuk status yang mungkin ada dari data lama
                    'direview': 'info',
                    'ditunda': 'secondary',
                };

                // Mengisi data teks
                document.getElementById('detail-judul').textContent = data.judul;
                document.getElementById('detail-deskripsi').textContent = data.deskripsi;
                document.getElementById('detail-mulai').textContent = data.mulai;
                document.getElementById('detail-selesai').textContent = data.selesai;

                // Badge Prioritas
                const currentPrioritasClass = prioritasClassMap[data.prioritas] || 'light';
                const prioritasBadge = document.createElement('span');
                prioritasBadge.className = 'badge bg-' + currentPrioritasClass;
                prioritasBadge.textContent = data.prioritas.charAt(0).toUpperCase() + data.prioritas.slice(
                    1);
                document.getElementById('detail-prioritas').innerHTML = '';
                document.getElementById('detail-prioritas').appendChild(prioritasBadge);

                // Badge Status
                const currentStatusClass = statusClassMap[data.status] || 'secondary';
                const statusBadge = document.createElement('span');
                statusBadge.className = 'badge bg-label-' + currentStatusClass;
                statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                document.getElementById('detail-status').innerHTML = '';
                document.getElementById('detail-status').appendChild(statusBadge);

                // Badge Jenis
                const jenisBadge = document.createElement('span');
                jenisBadge.className = 'badge bg-label-info';
                jenisBadge.textContent = data.jenis;
                document.getElementById('detail-jenis').innerHTML = '';
                document.getElementById('detail-jenis').appendChild(jenisBadge);


                // Lampiran
                const lampiranContainer = document.getElementById('detail-lampiran-container');
                lampiranContainer.innerHTML = data.lampiran ?
                    `<a href="${data.lampiran}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bx bx-paperclip me-1"></i> Lihat Lampiran</a>` :
                    '<span class="text-muted">Tidak ada lampiran</span>';
            });
        });
    </script>
@endpush
