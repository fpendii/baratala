@extends('layout.app')

@section('title', 'Daftar Arsip Dokumen')

@section('content')
    <style>
        /* Desain Card Dokumen */
        .document-card {
            transition: all 0.3s ease-in-out;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background-color: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
            border-color: #696cff;
        }

        /* Menangani Teks Panjang agar Turun ke Bawah */
        .card-title {
            white-space: normal !important;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            line-height: 1.4;
            display: block;
        }

        .card-text {
            white-space: normal !important;
            overflow-wrap: break-word;
            word-wrap: break-word;
            line-height: 1.5;
        }

        /* Badge Styles */
        .tag-badge {
            font-size: 0.7rem;
            padding: 0.4em 0.7em;
            text-transform: uppercase;

            /* Izinkan teks turun ke bawah (wrap) */
            white-space: normal !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
            display: inline-block;

            /* Mengatur agar tinggi baris tidak terlalu renggang */
            line-height: 1.2;
            max-width: 100%;
            /* Agar tidak melebihi lebar card */
            text-align: left;
        }

        /* Styling Select2 agar serasi */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 0.375rem;
        }

        @media (max-width: 768px) {
            .btn-mobile-full {
                width: 100%;
            }
        }
    </style>

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h4 class="fw-bold mb-0">Arsip Dokumen</h4>
        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-mobile-full shadow-sm">
            <i class="ri-add-line me-1"></i> Tambah Dokumen
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="ri-checkbox-circle-line me-2 fs-5"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- FILTER CARD --}}
    <div class="card border shadow-none mb-4">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-uppercase">Cari Judul / Nomor</label>
                    <input type="text" class="form-control" name="search" placeholder="Kata kunci..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-uppercase">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-uppercase">Filter Tags</label>
                    <select name="filter_tags[]" id="filter_tags" class="form-select" multiple="multiple">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ is_array(request('filter_tags')) && in_array($tag->id, request('filter_tags')) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="ri-filter-3-line me-1"></i> Filter
                    </button>
                    @if (request()->anyFilled(['search', 'category', 'filter_tags']))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- CARD GRID SECTION --}}
    <div class="row g-4 mb-4">
        @forelse($documents as $item)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card document-card shadow-sm"
                    onclick="showActions('{{ $item->id }}', '{{ addslashes($item->title) }}', '{{ $item->latestVersion->id }}')"
                    style="cursor: pointer;">

                    <div class="card-body p-4 flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-label-primary rounded-pill">{{ $item->category->name }}</span>
                            <i class="ri-more-2-line text-muted fs-4"></i>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-2">
                            {{ $item->title }}
                        </h5>

                        <p class="text-muted small mb-3">
                            <i class="ri-hashtag me-1"></i>{{ $item->doc_number ?? 'Tanpa Nomor' }}
                        </p>

                        <div class="card-text text-muted small mb-4">
                            {{ $item->description ?? 'Tidak ada deskripsi tersedia.' }}
                        </div>

                        <div class="d-flex flex-wrap gap-1 mb-0">
                            @forelse($item->tags as $index => $tag)
                                @if ($index >= 3)
                                    <span
                                        class="badge bg-label-secondary tag-badge shadow-none">+{{ $item->tags->count() - 3 }}</span>
                                    @break
                                @endif

                                {{-- Badge ini sekarang akan otomatis memanjang ke bawah jika teksnya panjang --}}
                                <span class="badge bg-label-info tag-badge shadow-none">
                                    {{ $tag->name }}
                                </span>
                            @empty
                                <span class="text-muted small italic">-</span>
                            @endforelse
                        </div>
                    </div>

                    <div
                        class="card-footer bg-transparent border-top p-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs me-2">
                                <span
                                    class="avatar-initial rounded-pill bg-info small">v{{ $item->latestVersion->version_number }}</span>
                            </div>
                            <small class="text-muted">{{ $item->updated_at->format('d M Y') }}</small>
                        </div>
                        <i class="ri-file-pdf-fill text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada dokumen yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $documents->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

    {{-- MODAL AKSI UTAMA --}}
    <div class="modal fade" id="modalAksi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom bg-light">
                    <h5 class="modal-title fw-bold text-dark">Opsi Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h6 id="actionDocTitle" class="fw-bold text-primary mb-1 card-title"></h6>
                        <small class="text-muted">Pilih tindakan</small>
                    </div>

                    <div class="d-grid gap-2">
                        <a id="btnDownload" href="#" class="btn btn-outline-primary d-flex align-items-center py-2">
                            <i class="ri-download-cloud-2-line me-3 fs-4"></i> Download Dokumen
                        </a>

                        <button id="btnRev" type="button"
                            class="btn btn-outline-success d-flex align-items-center py-2">
                            <i class="ri-upload-2-line me-3 fs-4"></i> Upload Revisi
                        </button>

                        <a id="btnEdit" href="#" class="btn btn-outline-warning d-flex align-items-center py-2">
                            <i class="ri-edit-box-line me-3 fs-4"></i> Edit Data
                        </a>

                        <hr class="my-3">

                        <form id="formDelete" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger d-flex align-items-center py-2 w-100">
                                <i class="ri-delete-bin-line me-3 fs-4"></i> Hapus Dokumen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD REVISI --}}
    <div class="modal fade" id="modalRevisi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold">Upload Revisi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formRevisi" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <p class="text-muted mb-1">Mengunggah revisi untuk:</p>
                            <h6 id="revisiDocTitle" class="fw-bold text-primary card-title"></h6>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih File Baru <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4">Upload Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#filter_tags').select2({
                    placeholder: " Cari Tags...",
                    allowClear: true,
                    width: '100%'
                });
            });

            function showActions(id, title, versionId) {
                $('#actionDocTitle').text(title);

                // Sesuai Route: Route::get('/documents/{document}/download', ...)
                // Kita gunakan versionId karena biasanya yang didownload adalah file dari versi terbaru
                var downloadUrl = "{{ url('/documents') }}/" + versionId + "/download";

                // Sesuai Route Resource: documents.edit -> /documents/{id}/edit
                var editUrl = "{{ url('/documents') }}/" + id + "/edit";

                // Sesuai Route Resource: documents.destroy -> /documents/{id}
                var deleteUrl = "{{ url('/documents') }}/" + id;

                // Sesuai Route: /documents/{id}/revision
                var revisionUrl = "{{ url('/documents') }}/" + id + "/revision";

                // Pasang URL ke tombol-tombol di dalam modal
                $('#btnDownload').attr('href', downloadUrl);
                $('#btnEdit').attr('href', editUrl);
                $('#formDelete').attr('action', deleteUrl);

                // Handler untuk tombol Revisi
                $('#btnRev').off('click').on('click', function() {
                    // Tutup Modal Aksi dulu
                    var modalAksiElement = document.getElementById('modalAksi');
                    var modalAksiInstance = bootstrap.Modal.getInstance(modalAksiElement);
                    if (modalAksiInstance) modalAksiInstance.hide();

                    // Munculkan Modal Revisi setelah modal aksi tertutup
                    setTimeout(function() {
                        var revModal = new bootstrap.Modal(document.getElementById('modalRevisi'));
                        $('#revisiDocTitle').text(title);
                        $('#formRevisi').attr('action', revisionUrl);
                        revModal.show();
                    }, 400);
                });

                // Tampilkan Modal Aksi Utama
                var myModal = new bootstrap.Modal(document.getElementById('modalAksi'));
                myModal.show();
            }
        </script>
    @endpush
@endsection
