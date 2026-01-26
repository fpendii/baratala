@extends('layout.direktur-template')

@section('title', 'Daftar Surat Masuk')

@section('content')
    <div class="card">

        {{-- ALERT UNTUK PESAN SUKSES/ERROR --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="icon-base ri ri-check-line me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="icon-base ri ri-error-warning-line me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Card Header: Judul & Tombol Tambah --}}
        <div class="row">
            <div class="col-6">
                <h5 class="card-header">Daftar Surat Masuk</h5>
            </div>
            <div class="col-6">
                <div class="card-header text-end">
                    {{-- Tombol Tambah Surat Masuk --}}
                    <a href="{{ url('direktur/surat-masuk/create') }}" class="btn btn-primary">
                        <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
                    </a>
                </div>
            </div>
        </div>


        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">No</th>
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
                                    {{-- Link ini tetap mengarah ke halaman detail penuh --}}
                                    <a href="{{ url('administrasi/surat-masuk/' . $item->id) }}" class="text-primary">
                                        {{ $item->judul }}
                                    </a>
                                </strong>
                                <br>
                                <small class="text-muted">{{ $item->nomor_surat ?? 'No. Surat: -' }}</small>

                                {{-- Icon Lampiran --}}
                                @if ($item->lampiran)
                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank"
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

                                        {{-- Tombol Edit --}}
                                        <a class="dropdown-item"
                                            href="{{ url('direktur/surat-masuk/edit/' . $item->id) }}">
                                            <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                            Edit
                                        </a>
                                        <form action="{{ url('/direktur/surat-masuk/delete/' . $item->id) }}"
                                            method="POST" style="display: contents;"
                                            onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                                Delete
                                            </button>
                                        </form>



                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bx bx-mail-send bx-lg d-block mb-2 text-muted"></i>
                                <p class="mb-1">Kotak Surat Masuk Anda masih kosong.</p>
                                <a href="{{ url('direktur/surat-masuk/create') }}"
                                    class="btn btn-sm btn-outline-primary mt-2">Input Surat Masuk Pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================================================================= --}}
    {{-- MODAL DETAIL SURAT MASUK --}}
    {{-- ================================================================= --}}
    <div class="modal fade" id="detailSuratModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailTitle">Detail Surat Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6>Judul Surat:</h6>
                            <p class="fw-bold" id="detailJudul"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Nomor Surat:</h6>
                            <p id="detailNomor"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Pengirim:</h6>
                            <p id="detailPengirim"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Tanggal Terima:</h6>
                            <p id="detailTanggal"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6>Prioritas:</h6>
                            <p id="detailPrioritas"></p>
                        </div>
                        <div class="col-12 mb-3">
                            <h6>Keterangan:</h6>
                            <div class="p-3 bg-light rounded" style="white-space: pre-wrap;" id="detailKeterangan"></div>
                        </div>
                        <div class="col-12 mb-3">
                            <h6>Lampiran:</h6>
                            <a href="#" target="_blank" id="detailLampiran"
                                class="btn btn-sm btn-outline-info d-none">
                                <i class="bx bx-paperclip me-1"></i> Lihat Lampiran
                            </a>
                            <span id="noLampiranText" class="text-muted d-none">Tidak ada lampiran.</span>
                        </div>
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
