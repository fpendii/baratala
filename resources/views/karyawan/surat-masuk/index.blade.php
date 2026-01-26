@extends('layout.karyawan-template')

@section('title', 'Daftar Surat Masuk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Surat Masuk</h4>
        {{-- Tombol Tambah Surat Masuk --}}
        <a href="{{ route('karyawan.surat-masuk.create') }}" class="btn btn-primary">
            <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
        </a>
    </div>

    {{-- ALERT UNTUK PESAN SUKSES/ERROR --}}
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

    <div class="card">
        <h5 class="card-header">Daftar Surat Masuk</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Judul & Nomor Surat</th>
                        <th style="width: 20%;">Pengirim</th>
                        <th style="width: 15%;">Tanggal Terima</th>
                        <th style="width: 10%;">Prioritas</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- Loop data dari Controller (variabel $suratMasuk) --}}
                    @forelse($suratMasuk as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong title="{{ $item->nomor_surat ?? 'Tidak Ada Nomor' }}">
                                    <a href="{{ route('direktur.surat-masuk.show', $item->id) }}" class="text-primary">
                                        {{ $item->judul }}
                                    </a>
                                </strong>
                                <br>
                                <small class="text-muted">{{ $item->nomor_surat ?? 'No. Surat: -' }}</small>

                                {{-- Icon Lampiran --}}
                                @if ($item->lampiran)
                                    <a href="{{ asset('storage/public/' . $item->lampiran) }}" target="_blank"
                                        class="ms-1 text-info" title="Lampiran tersedia">
                                        <i class="bx bx-paperclip"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark">{{ $item->pengirim }}</strong>
                                <br>
                                <small class="text-muted" title="{{ $item->keterangan }}">Keterangan:
                                    {{ Str::limit($item->keterangan, 30) ?? '-' }}</small>
                            </td>
                            <td>
                                {{-- Menggunakan Carbon untuk format tanggal yang lebih baik --}}
                                <i class="bx bx-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}
                            </td>
                            <td>
                                {{-- Badge Prioritas --}}
                                @php
                                    $prioritasClass = [
                                        'tinggi' => 'danger',
                                        'sedang' => 'warning',
                                        'rendah' => 'secondary',
                                    ];
                                    $currentPrioritas = strtolower($item->prioritas ?? 'rendah');
                                @endphp
                                <span class="badge bg-{{ $prioritasClass[$currentPrioritas] ?? 'secondary' }}">
                                    {{ ucfirst($currentPrioritas) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                    </button>
                                    <div class="dropdown-menu">

                                        {{-- TOMBOL DETAIL BARU (Memicu Modal) --}}
                                        <a href="javascript:void(0);" class="dropdown-item btn-detail-surat"
                                            data-bs-toggle="modal" data-bs-target="#detailSuratModal" {{-- Data Attributes untuk injeksi ke Modal --}}
                                            data-judul="{{ $item->judul }}"
                                            data-nomor="{{ $item->nomor_surat ?? 'No. Surat: -' }}"
                                            data-pengirim="{{ $item->pengirim }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d F Y') }}"
                                            data-prioritas="{{ ucfirst(strtolower($item->prioritas ?? 'rendah')) }}"
                                            data-keterangan="{{ $item->keterangan ?? '-' }}"
                                            data-lampiran="{{ $item->lampiran ? asset('storage/' . $item->lampiran) : '' }}">
                                            <i class="icon-base ri ri-eye-line icon-18px me-1"></i> Detail
                                        </a>

                                        {{-- hanya pegguna yang upload bisa edit dan hapus --}}
                                        @if ($item->id_pengguna == Auth::id())
                                        {{-- Tombol Edit --}}
                                        <a class="dropdown-item"
                                            href="{{ url('/karyawan/surat-masuk/edit/' . $item->id) }}">
                                            <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                            Edit
                                        </a>
                                        <form action="{{ url('/karyawan/surat-masuk/delete/' . $item->id) }}"
                                            method="POST" style="display: contents;"
                                            onsubmit="return confirm('Yakin hapus data ini?')">
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
                            <td colspan="6" class="text-center py-4">
                                <i class="bx bx-mail-send bx-lg d-block mb-2 text-muted"></i>
                                <p class="mb-1">Kotak Surat Masuk Anda masih kosong.</p>
                                <a href="{{ route('karyawan.surat-masuk.create') }}"
                                    class="btn btn-sm btn-outline-primary mt-2">Input Surat Masuk Pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
    {{-- ================================================================= --}}
    {{-- MODAL DETAIL SURAT MASUK --}}
    {{-- ================================================================= --}}
    <div class="modal fade" id="detailSuratModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-envelope me-2 text-primary"></i> Detail Surat Masuk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    {{-- Bagian Informasi Surat --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Surat</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <strong class="d-block mb-1">Judul Surat:</strong>
                            <h4 class="fw-bold text-dark" id="detailJudul"></h4>
                        </div>
                    </div>

                    {{-- Detail Metadata Surat menggunakan List Group --}}
                    <ul class="list-group list-group-flush mb-4 border rounded">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-auto">
                                <i class="bx bx-hash me-2 text-info"></i>
                                <strong>Nomor Surat:</strong>
                            </div>
                            <span id="detailNomor" class="text-wrap text-end text-dark fw-medium"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-auto">
                                <i class="bx bx-user me-2 text-success"></i>
                                <strong>Pengirim:</strong>
                            </div>
                            <span id="detailPengirim" class="text-wrap text-end text-dark fw-medium"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-auto">
                                <i class="bx bx-calendar me-2 text-secondary"></i>
                                <strong>Tanggal Terima:</strong>
                            </div>
                            <span id="detailTanggal" class="text-wrap text-end text-dark fw-medium"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-auto">
                                <i class="bx bx-bolt-circle me-2 text-warning"></i>
                                <strong>Prioritas:</strong>
                            </div>
                            {{-- Prioritas akan diisi oleh JS sebagai badge --}}
                            <span id="detailPrioritas"></span>
                        </li>
                    </ul>

                    {{-- Bagian Keterangan --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">Keterangan / Isi Ringkas</h6>
                    <div class="p-3 bg-light rounded mb-4">
                        <p style="white-space: pre-wrap; margin-bottom: 0;" class="text-secondary" id="detailKeterangan">
                        </p>
                    </div>

                    {{-- Bagian Lampiran --}}
                    <h6 class="text-primary border-bottom pb-2 mb-3">Lampiran</h6>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('detailSuratModal');

            // Fungsi yang dijalankan saat modal akan ditampilkan
            detailModal.addEventListener('show.bs.modal', function(event) {
                // Tombol yang memicu modal
                const button = event.relatedTarget;

                // Ekstrak data dari data-* attributes yang disematkan di tombol
                const judul = button.getAttribute('data-judul');
                const nomor = button.getAttribute('data-nomor');
                const pengirim = button.getAttribute('data-pengirim');
                const tanggal = button.getAttribute('data-tanggal');
                const prioritas = button.getAttribute('data-prioritas');
                const keterangan = button.getAttribute('data-keterangan');
                const lampiranUrl = button.getAttribute('data-lampiran');

                // Tentukan kelas badge untuk Prioritas
                let badgeClass = 'secondary';
                if (prioritas.toLowerCase() === 'tinggi') {
                    badgeClass = 'danger';
                } else if (prioritas.toLowerCase() === 'sedang') {
                    badgeClass = 'warning';
                }

                // Update konten modal
                document.getElementById('detailJudul').textContent = judul;
                document.getElementById('detailNomor').textContent = nomor;
                document.getElementById('detailPengirim').textContent = pengirim;
                document.getElementById('detailTanggal').textContent = tanggal;
                document.getElementById('detailPrioritas').innerHTML =
                    `<span class="badge bg-${badgeClass}">${prioritas}</span>`;
                document.getElementById('detailKeterangan').textContent = keterangan;

                const lampiranLink = document.getElementById('detailLampiran');
                const noLampiranText = document.getElementById('noLampiranText');

                if (lampiranUrl) {
                    lampiranLink.href = lampiranUrl;
                    lampiranLink.classList.remove('d-none');
                    noLampiranText.classList.add('d-none');
                } else {
                    lampiranLink.href = '#';
                    lampiranLink.classList.add('d-none');
                    noLampiranText.classList.remove('d-none');
                }
            });
        });
    </script>
@endsection
