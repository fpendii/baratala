@extends('layout.app')

@section('title', 'Daftar Pengguna')

@section('content')
{{-- ALERT NOTIFIKASI --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="icon-base ri ri-check-line me-1"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="icon-base ri ri-error-warning-line me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Pengecekan untuk error validasi setelah redirect dari Store/Update --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="icon-base ri ri-error-warning-line me-1"></i>
        Gagal menyimpan data. Mohon periksa kembali formulir Anda.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    {{-- Card Header: Judul & Tombol Tambah --}}
    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
        <h5 class="mb-2 mb-sm-0">Daftar Pengguna</h5>
        <a href="{{ url('pengguna/create') }}" class="btn btn-primary">
            <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
        </a>
    </div>

    <hr class="mt-0 mb-3">

    {{--------------------------------------------------}}
    {{-- START: FORM FILTER DAN PENCARIAN --}}
    {{--------------------------------------------------}}
    <form method="GET" action="{{ url()->current() }}" class="px-4 pb-4">
        <div class="row g-3 align-items-end">

            {{-- Filter Role --}}
            <div class="col-lg-3 col-md-4 col-12">
                <label for="filter_role" class="form-label fw-medium">Filter Role</label>
                <select name="role" id="filter_role" class="form-select form-select-sm">
                    <option value="semua">-- Semua Role --</option>
                    @php
                        $currentRole = request('role', 'semua');
                        // Pastikan $availableRoles dikirim dari Controller
                        $roles = isset($availableRoles) ? $availableRoles : ['admin', 'user', 'manager'];
                    @endphp
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ $currentRole == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Pencarian Teks --}}
            <div class="col-lg-5 col-md-8 col-12">
                <label for="filter_cari" class="form-label fw-medium">Cari Nama/Email</label>
                <input type="text" name="cari" id="filter_cari" class="form-control form-control-sm"
                       placeholder="Cari nama atau email pengguna..."
                       value="{{ request('cari') }}">
            </div>

            {{-- Tombol Aksi --}}
            <div class="col-lg-4 col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-info btn-sm me-2">
                    <i class="icon-base ri ri-filter-line me-1"></i> Filter
                </button>
                {{-- Tombol Reset --}}
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="icon-base ri ri-refresh-line me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>
    {{--------------------------------------------------}}
    {{-- END: FORM FILTER DAN PENCARIAN --}}
    {{--------------------------------------------------}}

    <div class="table-responsive text-nowrap">
        <table class="table table-hover table-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th> {{-- Tambahkan kolom Email agar pencarian terlihat --}}
                    <th>Role</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($pengguna as $user)
                <tr>
                    {{-- Penomoran untuk Pagination --}}
                    <td>{{ $pengguna->firstItem() + $loop->index }}</td>
                    <td>
                        <strong class="text-dark">{{ $user->nama }}</strong>
                    </td>
                    <td>{{ $user->email }}</td> {{-- Tampilkan Email --}}
                    <td>
                        @php
                            $roleClass = [
                                'admin' => 'danger',
                                'direktur' => 'warning',
                                'karyawan' => 'primary',
                                'kepala teknik' => 'info',
                                'enginer' => 'success',
                                'produksi' => 'secondary',
                                'keuangan' => 'dark'
                            ][$user->role] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        {{-- Dropdown Aksi --}}
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                data-bs-toggle="dropdown">
                                <i class="icon-base ri ri-more-2-line icon-18px"></i>
                            </button>
                            <div class="dropdown-menu">
                                {{-- Tombol Detail yang Memicu Modal --}}
                                <a class="dropdown-item btn-detail" href="javascript:void(0);"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailpenggunaModal"
                                    data-nama="{{ $user->nama }}"
                                    data-email="{{ $user->email }}"
                                    data-nohp="{{ $user->no_hp }}"
                                    data-alamat="{{ $user->alamat }}"
                                    data-role="{{ $user->role }}">
                                    <i class="icon-base ri ri-eye-line icon-18px me-1"></i> Detail
                                </a>
                                {{-- Edit --}}
                                <a class="dropdown-item" href="{{ route('pengguna.edit', $user->id) }}">
                                    <i class="icon-base ri ri-pencil-line icon-18px me-1"></i> Edit
                                </a>
                                {{-- Delete --}}
                                <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST"
                                    style="display: contents;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->nama }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <i class="ri ri-user-settings-line ri-3x d-block mb-2 text-muted"></i>
                        <p class="mb-1">Belum ada data pengguna yang terdaftar.</p>
                        <a href="{{ url('pengguna/create') }}"
                            class="btn btn-sm btn-outline-primary mt-2">Tambah Pengguna Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="card-footer d-flex justify-content-center">
        {{ $pengguna->appends(request()->except('page'))->links() }}
    </div>
</div>

{{-- MODAL DETAIL pengguna (tetap sama) --}}
<div class="modal fade" id="detailpenggunaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="icon-base ri ri-user-line me-2"></i> Detail Pengguna
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body p-4">

                {{-- NAMA pengguna --}}
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                    <div class="border p-2 rounded bg-light">
                        <h4 class="mb-0 text-dark" id="modal-nama-pengguna"></h4>
                    </div>
                </div>

                {{-- INFORMASI ROLE & EMAIL --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Role</label>
                        <p class="border p-2 rounded mb-0 text-dark" id="modal-role">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Email</label>
                        <p class="border p-2 rounded mb-0 text-dark" id="modal-email">-</p>
                    </div>
                </div>

                <hr class="mb-4 mt-0">

                {{-- INFORMASI KONTAK --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-primary mb-2">
                            <i class="icon-base ri ri-phone-line me-1"></i> Info Kontak
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">No. HP</label>
                        <p class="border p-2 rounded mb-0 text-dark" id="modal-nohp">-</p>
                    </div>
                </div>

                {{-- ALAMAT --}}
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Alamat</label>
                    <div class="border p-3 rounded bg-light">
                        <p id="modal-alamat" style="white-space: pre-wrap;" class="mb-0 text-dark">-</p>
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
        const detailModal = document.getElementById('detailpenggunaModal');

        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', function (event) {
                // Tombol yang memicu modal
                const button = event.relatedTarget;

                // Ambil data dari atribut data-*
                const nama = button.getAttribute('data-nama') ?? 'N/A';
                const email = button.getAttribute('data-email') ?? 'N/A';
                const nohp = button.getAttribute('data-nohp') ?? '-';
                const alamat = button.getAttribute('data-alamat') ?? 'Belum ada data alamat';
                const role = button.getAttribute('data-role') ?? '-';

                // Update konten modal
                document.getElementById('modal-nama-pengguna').textContent = nama;
                document.getElementById('modal-email').textContent = email;
                document.getElementById('modal-nohp').textContent = nohp;
                document.getElementById('modal-alamat').textContent = alamat;
                document.getElementById('modal-role').textContent = role;
            });
        }
    });
</script>
@endpush
