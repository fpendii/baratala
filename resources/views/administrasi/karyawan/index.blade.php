@extends('layout.administrasi-template')

@section('title', 'Daftar Karyawan')

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
    {{-- Card Header: Judul & Tombol Tambah (Fixed for Mobile Layout using Flexbox) --}}
    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
        {{-- Judul --}}
        <h5 class="mb-2 mb-sm-0">Daftar Karyawan</h5>

        {{-- Tombol Tambah Karyawan --}}
        <a href="{{ url('administrasi/karyawan/create') }}" class="btn btn-primary">
            <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
        </a>
    </div>

    <div class="table-responsive text-nowrap">
        {{-- Menggunakan table-hover dan table-dark thead untuk konsistensi --}}
        <table class="table table-hover table-sm align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($karyawan as $user)
                <tr>
                    <td>{{ $loop->iteration + ($karyawan->currentPage() - 1) * $karyawan->perPage() }}</td>
                    <td>
                        <strong class="text-dark">{{ $user->nama }}</strong>
                    </td>
                    <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                    <td>
                        {{-- Dropdown Aksi (Disamakan dengan Jobdesk/Surat Masuk) --}}
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                data-bs-toggle="dropdown">
                                <i class="icon-base ri ri-more-2-line icon-18px"></i>
                            </button>
                            <div class="dropdown-menu">
                                {{-- Tombol Detail yang Memicu Modal --}}
                                <a class="dropdown-item btn-detail" href="javascript:void(0);"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailKaryawanModal"
                                    data-nama="{{ $user->nama }}"
                                    data-email="{{ $user->email }}"
                                    data-nohp="{{ $user->no_hp }}"
                                    data-alamat="{{ $user->alamat }}"
                                    data-role="{{ $user->role }}">
                                     <i class="icon-base ri ri-eye-line icon-18px me-1"></i> Detail
                                </a>
                                {{-- Edit --}}
                                <a class="dropdown-item" href="{{ route('administrasi.karyawan.edit', $user->id) }}">
                                    <i class="icon-base ri ri-pencil-line icon-18px me-1"></i> Edit
                                </a>
                                {{-- Delete --}}
                                <form action="{{ route('administrasi.karyawan.destroy', $user->id) }}" method="POST"
                                    style="display: contents;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan {{ $user->nama }}?');">
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
                    <td colspan="6" class="text-center py-4">
                        <i class="ri ri-user-settings-line ri-3x d-block mb-2 text-muted"></i>
                        <p class="mb-1">Belum ada data karyawan yang terdaftar.</p>
                        <a href="{{ url('administrasi/karyawan/create') }}"
                            class="btn btn-sm btn-outline-primary mt-2">Tambah Karyawan Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="card-footer d-flex justify-content-center">
        {{ $karyawan->links() }}
    </div>
</div>

{{-- MODAL DETAIL KARYAWAN --}}
<div class="modal fade" id="detailKaryawanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="icon-base ri ri-user-line me-2"></i> Detail Karyawan
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body p-4">

                {{-- NAMA KARYAWAN --}}
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                    <div class="border p-2 rounded bg-light">
                        <h4 class="mb-0 text-dark" id="modal-nama-karyawan"></h4>
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
        const detailModal = document.getElementById('detailKaryawanModal');

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
                document.getElementById('modal-nama-karyawan').textContent = nama;
                document.getElementById('modal-email').textContent = email;
                document.getElementById('modal-nohp').textContent = nohp;
                document.getElementById('modal-alamat').textContent = alamat;
                document.getElementById('modal-role').textContent = role;
            });
        }
    });
</script>
@endpush
