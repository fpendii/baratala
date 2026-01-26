@extends('layout.app')

@section('title', 'Jobdesk')

@section('content')
    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri ri-check-line me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri ri-error-warning-line me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="row align-items-center">
            <div class="col-md-6 col-12">
                <h5 class="card-header">Daftar Jobdesk</h5>
            </div>
            <div class="col-md-6 col-12 text-md-end">
                <div class="card-header">
                    {{-- Tombol Bulk Delete (Sembunyi secara default) --}}
                    <button type="button" id="btn-bulk-delete" class="btn btn-danger d-none me-2">
                        <i class="ri ri-delete-bin-line me-1"></i> Hapus Terpilih (<span id="selected-count">0</span>)
                    </button>

                    <a href="{{ url('/data-jobdesk/create') }}" class="btn btn-primary">
                        <i class="ri ri-add-line me-1"></i> Tambah Jobdesk
                    </a>
                </div>
            </div>
        </div>

        <hr class="mt-0">

        {{-- FILTER & SEARCH --}}
        <form method="GET" action="{{ url()->current() }}" class="px-4 pb-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-4 col-12">
                    <label class="form-label fw-medium">Filter Divisi</label>
                    <select name="divisi" class="form-select form-select-sm">
                        <option value="semua">-- Semua Divisi --</option>
                        @foreach ($availableDivisions as $divisi)
                            <option value="{{ $divisi }}" {{ request('divisi') == $divisi ? 'selected' : '' }}>
                                {{ $divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-5 col-md-8 col-12">
                    <label class="form-label fw-medium">Cari Judul Jobdesk</label>
                    <input type="text" name="cari" class="form-control form-control-sm" placeholder="Masukkan kata kunci..." value="{{ request('cari') }}">
                </div>
                <div class="col-lg-4 col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-info btn-sm me-2">
                        <i class="ri ri-filter-line me-1"></i> Filter
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri ri-refresh-line me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- FORM BULK DELETE --}}
        <form id="form-bulk-delete" action="{{ route('data-jobdesk.bulkDelete') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%">
                                <input type="checkbox" id="check-all" class="form-check-input">
                            </th>
                            <th style="width: 5%">No</th>
                            <th>Judul Jobdesk</th>
                            <th style="width: 20%">Divisi</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($jobdesks as $index => $jobdesk)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $jobdesk->id }}" class="form-check-input check-item">
                            </td>
                            <td>{{ $jobdesks->firstItem() + $index }}</td>
                            <td>{{ Str::limit($jobdesk->judul_jobdesk, 50, '...') }}</td>
                            <td>{{ $jobdesk->divisi }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none" data-bs-toggle="dropdown">
                                        <i class="ri ri-more-2-line icon-18px"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item btn-detail" href="javascript:void(0);"
                                           data-bs-toggle="modal" data-bs-target="#detailJobdeskModal"
                                           data-judul="{{ $jobdesk->judul_jobdesk }}"
                                           data-deskripsi="{{ $jobdesk->deskripsi }}"
                                           data-divisi="{{ $jobdesk->divisi }}">
                                            <i class="ri ri-eye-line me-1"></i> Detail
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/jobdesk/edit', $jobdesk->id) }}">
                                            <i class="ri ri-pencil-line me-1"></i> Edit
                                        </a>
                                        <form action="{{ url('/jobdesk/delete/'.$jobdesk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="ri ri-delete-bin-6-line me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada jobdesk yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- @if($jobdesks->hasPages())
            <div class="card-footer">
                {{ $jobdesks->appends(request()->except('page'))->links() }}
            </div>
        @endif --}}
    </div>

    {{-- MODAL DETAIL (Tetap sama seperti sebelumnya) --}}
    <div class="modal fade" id="detailJobdeskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri ri-eye-line me-2"></i> Detail Jobdesk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-semibold">Judul Jobdesk</label>
                        <div class="border p-2 rounded bg-light">
                            <h4 class="mb-0 text-dark" id="modal-judul-jobdesk"></h4>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-semibold">Divisi</label>
                        <p class="border p-2 rounded mb-0 text-dark fs-5" id="modal-divisi">-</p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary mb-2"><i class="ri ri-file-text-line me-1"></i> Deskripsi Jobdesk</label>
                        <div class="border p-3 rounded bg-light">
                            <p id="modal-deskripsi" style="white-space: pre-wrap;" class="mb-0 text-dark">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.check-item');
        const btnBulkDelete = document.getElementById('btn-bulk-delete');
        const selectedCountSpan = document.getElementById('selected-count');
        const formBulkDelete = document.getElementById('form-bulk-delete');

        // Fungsi Update UI Tombol Hapus
        function updateBulkDeleteUI() {
            const checkedCount = document.querySelectorAll('.check-item:checked').length;
            if (checkedCount > 0) {
                btnBulkDelete.classList.remove('d-none');
                selectedCountSpan.textContent = checkedCount;
            } else {
                btnBulkDelete.classList.add('d-none');
            }
        }

        // Event: Master Checkbox
        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteUI();
        });

        // Event: Item Checkbox
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.check-item:checked').length === checkboxes.length;
                checkAll.checked = allChecked;
                updateBulkDeleteUI();
            });
        });

        // Event: Klik Hapus Massal
        btnBulkDelete.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus ' + document.querySelectorAll('.check-item:checked').length + ' data terpilih?')) {
                formBulkDelete.submit();
            }
        });

        // Modal Detail Script
        const detailModal = document.getElementById('detailJobdeskModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                document.getElementById('modal-judul-jobdesk').textContent = button.getAttribute('data-judul') ?? 'N/A';
                document.getElementById('modal-divisi').textContent = button.getAttribute('data-divisi') ?? '-';
                document.getElementById('modal-deskripsi').textContent = button.getAttribute('data-deskripsi') ?? '-';
            });
        }
    });
</script>
@endpush
