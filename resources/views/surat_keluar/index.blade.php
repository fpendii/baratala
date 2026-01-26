@extends('layout.app')

@section('title', 'Daftar Surat Keluar')

@section('content')
    <style>
        /* ... CSS yang sudah ada ... */
        .hover-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>


    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Surat Keluar</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPilihJenis">
            <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
        </button>
    </div>

    {{-- ALERT --}}
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

    {{-- =================================================================== --}}
    {{-- CARD FILTER SURAT KELUAR (Kode Baru/Modifikasi) --}}
    <div class="card mb-4">
        <h5 class="card-header">Filter Data Surat Keluar</h5>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">

                {{-- 1. Filter Nomor Surat (BARU) --}}
                <div class="col-md-6 col-lg-3">
                    <label for="filter_nomor_surat" class="form-label">Cari Nomor Surat</label>
                    <input type="text" class="form-control" id="filter_nomor_surat" name="nomor"
                        placeholder="Masukkan Nomor Surat..." value="{{ request('nomor') }}">
                </div>

                {{-- 2. Filter Perihal / Tujuan --}}
                <div class="col-md-6 col-lg-3">
                    <label for="filter_perihal_tujuan" class="form-label">Cari Perihal / Tujuan</label>
                    <input type="text" class="form-control" id="filter_perihal_tujuan" name="cari"
                        placeholder="Perihal atau Tujuan..." value="{{ request('cari') }}">
                </div>

                {{-- 3. Filter Jenis Surat --}}
                <div class="col-md-6 col-lg-2">
                    <label for="filter_jenis" class="form-label">Jenis Surat</label>
                    <select id="filter_jenis" name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="umum" {{ request('jenis') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="operasional" {{ request('jenis') == 'operasional' ? 'selected' : '' }}>Operasional
                        </option>
                        <option value="keuangan" {{ request('jenis') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                    </select>
                </div>

                {{-- 4. Filter Tanggal Mulai --}}
                <div class="col-md-6 col-lg-3">
                    <label for="filter_tanggal_mulai" class="form-label">Dari Tanggal Surat</label>
                    <input type="date" class="form-control" id="filter_tanggal_mulai" name="tanggal_mulai"
                        value="{{ request('tanggal_mulai') }}">
                </div>

                {{-- 5. Filter Tanggal Selesai --}}
                <div class="col-md-6 col-lg-3">
                    <label for="filter_tanggal_selesai" class="form-label">Sampai Tanggal Surat</label>
                    <input type="date" class="form-control" id="filter_tanggal_selesai" name="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}">
                </div>

                {{-- Tombol Aksi --}}
                <div class="col-12 col-md-auto col-lg-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="icon-base ri ri-filter-3-line icon-18px"></i> Filter
                    </button>
                </div>

                {{-- Tombol Reset --}}
                @if (request()->hasAny(['nomor', 'cari', 'jenis', 'tanggal_mulai', 'tanggal_selesai']))
                    <div class="col-12 col-md-auto col-lg-auto">
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100">
                            <i class="icon-base ri ri-refresh-line icon-18px"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
    {{-- END CARD FILTER --}}
    {{-- =================================================================== --}}


    <div class="card">
        <h5 class="card-header">Daftar Surat Keluar</h5>
        <div class="card-body pb-2">

            <div class="table-responsive text-nowrap">

                <table class="table table-hover table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Perihal & Nomor Surat</th>
                            <th style="width: 20%;">Tujuan Surat</th>
                            <th style="width: 15%;">Tanggal Surat</th>
                            <th style="width: 10%;">Jenis Surat</th>
                            <th style="width: 15%;">Dokumen Surat</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        {{-- PASTIKAN DATA SURAT KELUAR MENGGUNAKAN PAGINATE DI CONTROLLER --}}
                        @forelse($surat_keluar as $index => $item)
                            <tr>
                                {{-- Jika menggunakan paginate(), gunakan $surat_keluar->firstItem() + $index --}}
                                <td>{{ $surat_keluar->firstItem() + $index }}</td>
                                <td>
                                    <strong title="{{ $item->nomor_surat }}">
                                        <a class="text-primary">
                                            {{ $item->perihal }}
                                        </a>
                                    </strong>
                                    <br>
                                    <small class="text-muted">No. Surat: {{ $item->nomor_surat }}</small>
                                </td>

                                <td>
                                    <strong class="text-dark">{{ $item->tujuan }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Dibuat oleh: {{ Str::limit($item->pengguna->name ?? 'N/A', 25) }}
                                    </small>
                                </td>

                                <td>
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($item->tgl_surat)->format('d M Y') }}
                                </td>

                                <td>
                                    @php
                                        $jenisClass = [
                                            'keuangan' => 'success',
                                            'operasional' => 'warning',
                                            'umum' => 'info',
                                        ];
                                        $currentJenis = strtolower($item->jenis_surat ?? 'umum');
                                    @endphp
                                    <span class="badge bg-{{ $jenisClass[$currentJenis] ?? 'secondary' }}">
                                        {{ ucfirst($currentJenis) }}
                                    </span>
                                </td>

                                {{-- DOKUMEN SURAT --}}
                                <td>
                                    @if ($item->dok_surat)
                                        @php
                                            // Ambil nama file dan ekstensinya
                                            $fileName = basename($item->dok_surat);
                                            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $fileUrl = route('surat.tampil', $fileName);

                                            // Jika file adalah Word, Excel, atau PPT, gunakan Google Docs Viewer
                                            $officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

                                            if (in_array($extension, $officeExtensions)) {
                                                // URL Google Viewer (Catatan: Website harus sudah Online/Hosting agar ini bekerja)
                                                $finalUrl =
                                                    'https://docs.google.com/viewer?url=' .
                                                    urlencode($fileUrl) .
                                                    '&embedded=true';
                                            } else {
                                                // PDF dan Gambar langsung ke route tampil
                                                $finalUrl = $fileUrl;
                                            }
                                        @endphp

                                        <a href="{{ $finalUrl }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="ri ri-file-2-line"></i> Lihat Dokumen
                                        </a>
                                    @elseif ($item->lampiran)
                                        <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="ri ri-file-2-line"></i> Lihat Lampiran
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>


                                <td>
                                    {{-- ... Dropdown Aksi yang sudah ada ... --}}
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item text-success" href="#" data-bs-toggle="modal"
                                                data-bs-target="#uploadTtdModal" data-id="{{ $item->id }}"
                                                data-nomor="{{ $item->nomor_surat }}">
                                                <i class="icon-base ri ri-upload-cloud-2-line icon-18px me-1"></i> Update
                                                Dokumen
                                            </a>
                                            {{-- DETAIL --}}
                                            <a href="javascript:void(0);" class="dropdown-item btn-detail-surat"
                                                data-bs-toggle="modal" data-bs-target="#detailSuratModal"
                                                data-perihal="{{ $item->perihal }}"
                                                data-nomor="{{ $item->nomor_surat }}" data-tujuan="{{ $item->tujuan }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tgl_surat)->format('d F Y') }}"
                                                data-jenis="{{ ucfirst($currentJenis) }}"
                                                data-final-url="{{ $finalUrl ?? '' }}">
                                                <i class="icon-base ri ri-eye-line icon-18px me-1"></i> Detail
                                            </a>


                                            {{-- EDIT & DELETE --}}
                                            @if ($item->id_pengguna == Auth::id())
                                                <a class="dropdown-item"
                                                    href="{{ route('surat-keluar.edit', $item->id) }}">
                                                    <i class="icon-base ri ri-pencil-line icon-18px me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('surat-keluar.destroy', $item->id) }}"
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
                                <td colspan="7" class="text-center py-4">
                                    <i class="bx bx-envelope-open bx-lg d-block mb-2 text-muted"></i>
                                    <p class="mb-1">Belum ada data Surat Keluar yang tercatat.</p>
                                    <a class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal"
                                        data-bs-target="#modalPilihJenis">
                                        Buat Surat Keluar Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginasi --}}
        @if ($surat_keluar->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{-- appends(request()->query()) agar filter GET tetap terbawa --}}
                {{ $surat_keluar->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    {{-- MODAL PILIH JENIS SURAT & MODAL DETAIL & MODAL UPLOAD TTD (Kode yang sudah ada, tidak diubah) --}}
    {{-- ... (Sisipkan semua kode MODAL di sini) ... --}}

    {{-- MODAL PILIH JENIS SURAT (VERSI ELEGAN) --}}
    <div class="modal fade" id="modalPilihJenis" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary">
                        <i class="bx bx-envelope me-2"></i> Pilih Jenis Surat
                    </h5>
                </div>

                <div class="modal-body pt-2 pb-4 px-4">

                    <p class="text-muted text-center mb-4">
                        Tentukan kategori surat yang akan dibuat
                    </p>

                    <div class="row g-3">

                        {{-- UMUM --}}
                        <div class="col-12">
                            <a href="{{ route('surat-keluar.create', ['jenis' => 'umum']) }}"
                                class="text-decoration-none">
                                <div class="card border-0 shadow-sm hover-card">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div class="icon-circle bg-info text-white">
                                            <i class="ri ri-file-text-line fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-semibold">Surat Umum</h6>
                                            <small class="text-muted">Surat biasa & administrasi</small>
                                        </div>
                                        <i class="ri ri-arrow-right-s-line fs-4 text-muted"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- OPERASIONAL --}}
                        <div class="col-12">
                            <a href="{{ route('surat-keluar.create', ['jenis' => 'operasional']) }}"
                                class="text-decoration-none">
                                <div class="card border-0 shadow-sm hover-card">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div class="icon-circle bg-warning text-white">
                                            <i class="ri ri-briefcase-line fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-semibold">Surat Operasional</h6>
                                            <small class="text-muted">Surat kegiatan & operasional</small>
                                        </div>
                                        <i class="ri ri-arrow-right-s-line fs-4 text-muted"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        {{-- KEUANGAN --}}
                        <div class="col-12">
                            <a href="{{ route('surat-keluar.create', ['jenis' => 'keuangan']) }}"
                                class="text-decoration-none">
                                <div class="card border-0 shadow-sm hover-card">
                                    <div class="card-body d-flex align-items-center gap-3">
                                        <div class="icon-circle bg-success text-white">
                                            <i class="ri ri-money-dollar-circle-line fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-semibold">Surat Keuangan</h6>
                                            <small class="text-muted">Surat transaksi & keuangan</small>
                                        </div>
                                        <i class="ri ri-arrow-right-s-line fs-4 text-muted"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div class="modal fade" id="detailSuratModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-envelope-open me-2 text-primary"></i> Detail Surat Keluar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    <h6 class="text-primary border-bottom pb-2 mb-3">Informasi Surat</h6>

                    <h4 class="fw-bold text-dark" id="detailPerihal"></h4>

                    <ul class="list-group list-group-flush mb-4 border rounded">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Nomor Surat:</strong>
                            <span id="detailNomor"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Tujuan:</strong>
                            <span id="detailTujuan"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Tanggal Surat:</strong>
                            <span id="detailTanggal"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Jenis Surat:</strong>
                            <span id="detailJenis"></span>
                        </li>
                    </ul>

                    <h6 class="text-primary border-bottom pb-2 mb-3">Isi Perihal</h6>
                    <div class="p-3 bg-light rounded mb-4">
                        <p id="detailPerihalContent" style="white-space: pre-wrap;"></p>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3">Dokumen Surat</h6>
                    <div class="mb-4" id="detailLampiranWrapper">
                        <a id="detailLampiran" href="#" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="ri ri-file-2-line me-1"></i> Lihat Dokumen
                        </a>
                        <p id="detailLampiranEmpty" class="text-muted d-none">Tidak ada lampiran.</p>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD DOKUMEN TTD --}}
    <div class="modal fade" id="uploadTtdModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-cloud-upload me-2 text-success"></i> Ganti Dokumen Final (TTD)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('surat-keluar.update-dokumen') }}" method="POST" enctype="multipart/form-data"
                    id="formUploadTtd">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="surat_id" id="uploadTtdSuratId">
                    <div class="modal-body p-4">
                        <p>Unggah file <span style="font-weight: bold">PDF</span> Surat Keluar yang sudah ditandatangani
                            untuk <span style="font-weight: bold">menggantikan dokumen
                                sebelumnya</span>.</p>
                        <h6 class="text-primary mb-3" id="uploadTtdNomorSurat"></h6>

                        <div class="mb-3">
                            <label for="dok_surat" class="form-label">Dokumen Surat Final (PDF) <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" type="file" id="dok_surat" name="dok_surat" accept=".pdf"
                                required>
                            <small class="text-muted">File lama akan dihapus. Pastikan file baru berformat PDF dan sudah
                                ditandatangani.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Upload Dokumen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- SCRIPT YANG SUDAH ADA --}}
    {{-- SCRIPT UNTUK MODAL UPLOAD TTD --}}
    <<script>
        document.addEventListener('DOMContentLoaded', function() {

            const detailModal = document.getElementById('detailSuratModal');

            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                const perihal = button.getAttribute('data-perihal');
                const nomor = button.getAttribute('data-nomor');
                const tujuan = button.getAttribute('data-tujuan');
                const tanggal = button.getAttribute('data-tanggal');
                const jenis = button.getAttribute('data-jenis');
                const finalUrl = button.getAttribute('data-final-url');

                // ISI DATA TEKS
                document.getElementById('detailPerihal').textContent = perihal;
                document.getElementById('detailPerihalContent').textContent = perihal;
                document.getElementById('detailNomor').textContent = nomor;
                document.getElementById('detailTujuan').textContent = tujuan;
                document.getElementById('detailTanggal').textContent = tanggal;

                // BADGE JENIS
                let badgeClass = 'info';
                if (jenis.toLowerCase() === 'keuangan') badgeClass = 'success';
                if (jenis.toLowerCase() === 'operasional') badgeClass = 'warning';

                document.getElementById('detailJenis').innerHTML =
                    `<span class="badge bg-${badgeClass}">${jenis}</span>`;

                // DOKUMEN
                const detailLampiran = document.getElementById('detailLampiran');
                const detailLampiranEmpty = document.getElementById('detailLampiranEmpty');

                if (finalUrl && finalUrl !== '') {
                    detailLampiran.classList.remove('d-none');
                    detailLampiranEmpty.classList.add('d-none');
                    detailLampiran.href = finalUrl;
                    detailLampiran.textContent = 'Lihat Dokumen';
                } else {
                    detailLampiran.classList.add('d-none');
                    detailLampiranEmpty.classList.remove('d-none');
                }
            });

        });
    </script>

@endsection
