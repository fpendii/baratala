@extends('layout.app')

@section('title', 'Daftar Arsip Dokumen')

@section('content')
    <style>
        /* Style tambahan agar select2 selaras dengan bootstrap */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 0.375rem;
        }

        .hover-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Arsip Dokumen</h4>
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Tambah Dokumen
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- FILTER CARD --}}
    <div class="card mb-4">
        <h5 class="card-header">Filter Arsip</h5>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cari Judul / Nomor</label>
                    <input type="text" class="form-control" name="search" placeholder="Masukkan kata kunci..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- FILTER TAGS BARU --}}
                <div class="col-md-3">
                    <label class="form-label">Filter Tags</label>
                    <select name="filter_tags[]" id="filter_tags" class="form-select" multiple="multiple">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ is_array(request('filter_tags')) && in_array($tag->id, request('filter_tags')) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="ri-filter-3-line"></i> Filter
                    </button>
                    @if (request()->anyFilled(['search', 'category', 'filter_tags']))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Judul & Nomor</th>
                        <th style="width: 15%;">Kategori</th>
                        <th style="width: 15%;">Tags</th> {{-- KOLOM TAGS BARU --}}
                        <th style="width: 10%;">Versi</th>
                        <th style="width: 15%;">Tgl Update</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $index => $item)
                        <tr>
                            <td>{{ $documents->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $item->title }}</strong><br>
                                <small class="text-muted">{{ $item->doc_number ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">{{ $item->category->name }}</span>
                            </td>
                            <td>
                                {{-- MENAMPILKAN TAGS --}}
                                @forelse($item->tags as $tag)
                                    <span class="badge bg-label-info mb-1">{{ $tag->name }}</span>
                                @empty
                                    <span class="text-muted small">-</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-info">v{{ $item->latestVersion->version_number }}</span>
                            </td>
                            <td>{{ $item->updated_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="dropdown">
                                   <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                        </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li>
                                            <a class="dropdown-item text-primary"
                                                href="{{ route('documents.download', $item->latestVersion->id) }}">
                                                <i class="ri-download-cloud-2-line me-2"></i> Download
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-success" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#modalRevisi"
                                                data-id="{{ $item->id }}" data-title="{{ $item->title }}">
                                                <i class="ri-upload-2-line me-2"></i> Upload Revisi
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-warning" "
                                                href="{{route('documents.edit', $item->id)}}"
                                                data-id="{{ $item->id }}" data-title="{{ $item->title }}">
                                                <i class="ri-upload-2-line me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('documents.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus dokumen ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ri-delete-bin-line me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <p class="text-muted">Tidak ada dokumen ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $documents->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- MODAL UPLOAD REVISI --}}
    <div class="modal fade" id="modalRevisi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Versi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formRevisi" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <p class="mb-1">Anda akan mengunggah revisi untuk:</p>
                            <h6 id="revisiDocTitle" class="fw-bold text-primary"></h6>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih File Revisi <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                            <small class="text-muted mt-2 d-block">Versi akan otomatis naik dari versi saat ini.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Upload Revisi Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD REVISI --}}
    {{-- SCRIPTS --}}
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                // Gunakan selector ID langsung untuk modal
                $('#modalRevisi').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Tombol yang diklik
                    var id = button.data('id');
                    var title = button.data('title');

                    var modal = $(this);
                    modal.find('#revisiDocTitle').text(title);
                    modal.find('#formRevisi').attr('action', '/documents/' + id + '/revision');
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // 1. Inisialisasi Select2 untuk Filter
                $('#filter_tags').select2({
                    placeholder: " Pilih Tags...",
                    allowClear: true,
                    width: '100%'
                });


            });
        </script>
    @endpush
@endsection
