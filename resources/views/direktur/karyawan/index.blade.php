@extends('layout.direktur-template')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Daftar Karyawan</h5>
    {{-- Tombol Tambah Karyawan bisa ditambahkan di sini jika ada:
    <a href="{{ route('direktur.karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
    --}}
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
             <thead class="table-dark">
                <tr>
                    {{-- Penyesuaian lebar kolom agar konsisten dengan tabel Surat Masuk --}}
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 5%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawan as $user)
                <tr>
                    <td>{{ $loop->iteration + ($karyawan->currentPage() - 1) * $karyawan->perPage() }}</td>
                    <td>
                        {{-- Menggunakan strong untuk penekanan seperti di contoh surat masuk --}}
                        <strong>{{ $user->nama }}</strong>
                    </td>
                    <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                    <td class="text-center">
                        {{-- START: DROPDOWN Aksi Karyawan (Sudah disamakan sebelumnya) --}}
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                data-bs-toggle="dropdown">
                                <i class="icon-base ri ri-more-2-line icon-18px"></i>
                            </button>
                            <div class="dropdown-menu">

                                {{-- TOMBOL DETAIL BARU (Memicu Modal) --}}
                                <a href="javascript:void(0);" class="dropdown-item btn-detail-karyawan"
                                    data-bs-toggle="modal" data-bs-target="#karyawanDetailModal"
                                    data-karyawan-nama="{{ $user->nama }}"
                                    data-karyawan-email="{{ $user->email }}"
                                    data-karyawan-hp="{{ $user->no_hp }}"
                                    data-karyawan-alamat="{{ $user->alamat }}"
                                    data-karyawan-role="{{ $user->role }}">
                                    <i class="icon-base ri ri-eye-line icon-18px me-1"></i> Detail
                                </a>

                                {{-- Tombol Edit (Contoh) --}}
                                <a class="dropdown-item"
                                    href="{{ route('direktur.karyawan.edit', $user->id) }}">
                                    <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                    Edit
                                </a>

                                {{-- Tombol Hapus (Contoh) --}}
                                {{-- Ganti rute dan sesuaikan dengan fungsi delete di Controller Anda --}}
                                {{-- <form action="{{ route('direktur.karyawan.delete', $user->id) }}"
                                    method="POST" style="display: contents;"
                                    onsubmit="return confirm('Yakin hapus data {{ $user->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                        Hapus
                                    </button>
                                </form> --}}

                            </div>
                        </div>
                        {{-- END: DROPDOWN Aksi Karyawan --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{ $karyawan->links() }}
    </div>
</div>

{{-- Modal Detail Karyawan (Tampilan Diperbarui) --}}
<div class="modal fade" id="karyawanDetailModal" tabindex="-1" aria-labelledby="karyawanDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="karyawanDetailModalLabel">Detail Karyawan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- Nama (Full Width) --}}
                    <div class="col-12 mb-3">
                        <h6>Nama Karyawan:</h6>
                        <p class="fw-bold fs-5" id="detailNama"></p>
                    </div>

                    {{-- Email (Half Width) --}}
                    <div class="col-md-6 mb-3">
                        <h6>Email:</h6>
                        <p id="detailEmail"></p>
                    </div>

                    {{-- No. HP (Half Width) --}}
                    <div class="col-md-6 mb-3">
                        <h6>No. HP:
                        <p id="detailNoHp"></p>
                    </div>

                    {{-- Role (Half Width) --}}
                    <div class="col-md-6 mb-3">
                        <h6>Role:</h6>
                        {{-- Kontainer untuk badge role --}}
                        <p id="detailRole"></p>
                    </div>

                    {{-- Alamat (Full Width, dengan box background) --}}
                    <div class="col-12 mb-3">
                        <h6>Alamat Lengkap:</h6>
                        {{-- Menggunakan div dengan latar belakang seperti contoh untuk alamat panjang --}}
                        <div class="p-3 bg-light rounded" style="white-space: pre-wrap;" id="detailAlamat"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Ambil referensi ke modal
    const karyawanDetailModal = document.getElementById('karyawanDetailModal');

    // Tambahkan event listener saat modal akan ditampilkan
    karyawanDetailModal.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        const button = event.relatedTarget;

        // Ekstrak informasi dari data-* attributes
        const nama = button.getAttribute('data-karyawan-nama');
        const email = button.getAttribute('data-karyawan-email');
        const noHp = button.getAttribute('data-karyawan-hp');
        const alamat = button.getAttribute('data-karyawan-alamat');
        const role = button.getAttribute('data-karyawan-role');

        // Tentukan kelas badge berdasarkan role (Default: primary)
        let badgeClass = 'primary';
        // Logika opsional untuk warna badge berdasarkan role:
        // if (role.toLowerCase() === 'direktur') {
        //     badgeClass = 'danger';
        // } else if (role.toLowerCase() === 'administrasi') {
        //     badgeClass = 'warning';
        // }

        // Update konten modal
        document.getElementById('detailNama').textContent = nama;
        document.getElementById('detailEmail').textContent = email;
        document.getElementById('detailNoHp').textContent = noHp;

        // Alamat dimasukkan ke dalam div berlatar belakang
        document.getElementById('detailAlamat').textContent = alamat;

        // Role dimasukkan sebagai badge ke dalam elemen p#detailRole
        const roleContainer = document.getElementById('detailRole');
        // Pastikan roleContainer ada sebelum mengisi innerHTML
        if (roleContainer) {
            roleContainer.innerHTML = `<span class="badge bg-${badgeClass}">${role}</span>`;
        }

    });
</script>
@endsection
