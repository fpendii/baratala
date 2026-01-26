@extends('layout.app')

@section('title', 'Laporan Keuangan')

@section('content')
    {{-- ALERT NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon-base ri ri-check-line me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="icon-base ri ri-error-warning-line me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-6">
        {{-- RINGKASAN SALDO --}}
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Total Uang</h5>
                    <p class="mb-2">Kantor</p>
                    <h4 class="text-primary mb-0">Rp{{ number_format($uangKas->nominal ?? 0, 0, ',', '.') }}</h4>
                </div>
                <img src="/image/icon-uang.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83"
                    alt="kas" />
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Uang Kas</h5>
                    <p class="mb-2">Kantor</p>
                    <h4 class="text-primary mb-0">Rp{{ number_format($uangKas->uang_kas ?? 0, 0, ',', '.') }}</h4>
                </div>
                <img src="/image/icon-uang.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83"
                    alt="kas" />
            </div>
        </div>

        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Uang di Rekening</h5>
                    <p class="mb-2">Saldo Bank</p>
                    <h4 class="text-info mb-0">Rp{{ number_format($uangKas->uang_rekening ?? 0, 0, ',', '.') }}</h4>
                </div>
                <img src="/image/icon-bank.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83"
                    alt="rekening" />
            </div>
        </div>
    </div>

    <div class="row gy-6 mt-3">
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Uang Masuk</h5>
                    <p class="mb-2">Bulan ini</p>
                    <h4 class="text-success mb-0">Rp{{ number_format($uangMasuk ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Uang Keluar</h5>
                    <p class="mb-2">Bulan ini</p>
                    <h4 class="text-danger mb-0">Rp{{ number_format($uangKeluar ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>


    {{-- FILTER DAN TABEL --}}
    <div class="col-12 mt-4">
        <div class="card overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Transaksi</h5>
                <div class="d-flex gap-2">
                    <a href="{{ url('keuangan/export') }}?filter_tanggal={{ request('filter_tanggal') }}&filter_jenis={{ request('filter_jenis') }}&filter_pengguna={{ request('filter_pengguna') }}"
                        class="btn btn-outline-success btn-sm">
                        Export Excel
                    </a>
                    @if (Auth::user()->role != 'direktur')
                        <a href="{{ url('keuangan/pengeluaran/create') }}" class="btn btn-danger btn-sm">
                            + Pengeluaran Kas
                        </a>
                        <a href="{{ url('keuangan/kasbon/create') }}" class="btn btn-warning btn-sm">
                            + Kasbon
                        </a>
                        <a href="{{ url('keuangan/uang-masuk/create') }}" class="btn btn-success btn-sm">
                            + Uang Masuk
                        </a>
                    @endif

                </div>
            </div>

            {{-- FILTER --}}
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form action="{{ url('keuangan') }}" method="GET" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label for="filter_tanggal" class="form-label">Tanggal</label>
                                <input type="month" name="filter_tanggal" id="filter_tanggal" class="form-control"
                                    value="{{ request('filter_tanggal') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="filter_jenis" class="form-label">Jenis</label>
                                <select name="filter_jenis" id="filter_jenis" class="form-select">
                                    <option value="">-- Semua Jenis --</option>
                                    <option value="uang_masuk"
                                        {{ request('filter_jenis') == 'uang_masuk' ? 'selected' : '' }}>Uang Masuk
                                    </option>
                                    <option value="pengeluaran"
                                        {{ request('filter_jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran
                                    </option>
                                    <option value="kasbon" {{ request('filter_jenis') == 'kasbon' ? 'selected' : '' }}>
                                        Kasbon</option>
                                </select>
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="filter_pengguna" class="form-label">Penerima</label>
                                <select name="filter_pengguna" id="filter_pengguna" class="form-select">
                                    <option value="">-- Semua Karyawan --</option>
                                    @foreach ($daftarKaryawan as $karyawan)
                                        <option value="{{ $karyawan->id }}"
                                            {{ request('filter_pengguna') == $karyawan->id ? 'selected' : '' }}>
                                            {{ $karyawan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-md-3 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Penerima</th>
                            <th>Keperluan</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanKeuangan as $laporan)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</td>
                                <td>{{ $laporan->penerima ?? '-' }}</td>
                                <td>{{ $laporan->keperluan }}</td>
                                {{-- <td>
                                    <a href="{{ route('keuangan.preview', $laporan->id) }}" target="_blank"
                                        class="btn btn-warning">
                                        <i class="ri ri-eye-line me-1"></i> Pratinjau Tampilan PDF
                                    </a>
                                </td> --}}
                                <td>
                                    @switch($laporan->jenis)
                                        @case('uang_masuk')
                                            <span class="badge bg-success">Uang Masuk</span>
                                        @break

                                        @case('pengeluaran')
                                            <span class="badge bg-danger">Pengeluaran</span>
                                        @break

                                        @default
                                            <span class="badge bg-warning text-dark">Kasbon</span>
                                    @endswitch
                                </td>
                                <td class="{{ $laporan->tipe == 'pendapatan' ? 'text-success' : 'text-danger' }}">
                                    Rp{{ number_format($laporan->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $status = $laporan->status_persetujuan;
                                        $color = match ($status) {
                                            'menunggu' => 'secondary',
                                            'tanpa persetujuan' => 'info',
                                            'disetujui' => 'success',
                                            'ditolak' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $color }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td>
                                    {{-- KONTEN AKSI SEKARANG DALAM DROPDOWN --}}
                                    <div class="dropdown">
                                        {{-- Tombol Ikon Titik Tiga (Dropdown Toggle) --}}
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown" title="Aksi">
                                            <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                        </button>

                                        {{-- Dropdown Menu --}}
                                        <div class="dropdown-menu">
                                            @php
                                                $finalUrl = '';

                                                if ($laporan->lampiran) {
                                                    $fileName = basename($laporan->lampiran);
                                                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                    $fileUrl = route('keuangan.tampil', $fileName);

                                                    $officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

                                                    if (in_array($extension, $officeExtensions)) {
                                                        $finalUrl =
                                                            'https://docs.google.com/viewer?url=' .
                                                            urlencode($fileUrl) .
                                                            '&embedded=true';
                                                    } else {
                                                        // PDF & gambar → route inline
                                                        $finalUrl = $fileUrl;
                                                    }
                                                }
                                            @endphp

                                            @php
                                                // ===== BUKTI PERSETUJUAN =====
                                                $approvalUrl = '';

                                                if ($laporan->bukti_persetujuan_pdf) {
                                                    $approvalFile = basename($laporan->bukti_persetujuan_pdf);
                                                    $approvalExt = strtolower(
                                                        pathinfo($approvalFile, PATHINFO_EXTENSION),
                                                    );
                                                    $approvalRoute = route('keuangan-aprove.tampil', $approvalFile);

                                                    $officeExtensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

                                                    if (in_array($approvalExt, $officeExtensions)) {
                                                        $approvalUrl =
                                                            'https://docs.google.com/viewer?url=' .
                                                            urlencode($approvalRoute) .
                                                            '&embedded=true';
                                                    } else {
                                                        // PDF & gambar
                                                        $approvalUrl = $approvalRoute;
                                                    }
                                                }
                                            @endphp


                                            {{-- 1. TOMBOL DETAIL (Selalu ada) --}}
                                            <a class="dropdown-item btn-detail-laporan" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                {{-- Memindahkan semua data-* atribut ke dropdown item ini --}} data-id="{{ $laporan->id }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}"
                                                data-pengguna="{{ $laporan->penerima ?? '-' }}"
                                                data-keperluan="{{ $laporan->keperluan }}"
                                                data-nominal="{{ number_format($laporan->nominal, 0, ',', '.') }}"
                                                data-jenis="{{ $laporan->jenis }}"
                                                data-metode="{{ ucfirst($laporan->jenis_uang ?? '-') }}"
                                                data-final-url="{{ $finalUrl }}"
                                                data-status="{{ $laporan->status_persetujuan }}"
                                                data-status-teks="{{ ucfirst($laporan->status_persetujuan) }}"
                                                data-catatan="{{ $laporan->catatan ?? 'Tidak ada catatan.' }}"
                                                {{-- DATA BARU: Path PDF --}} data-approval-url="{{ $approvalUrl }}"
                                                {{-- END DATA BARU --}}
                                                data-perlu-persetujuan="{{ $laporan->persetujuan_direktur ?? 0 }}"
                                                data-current-status="{{ $laporan->status_persetujuan }}">
                                                <i class="ri ri-eye-line me-1"></i> Detail
                                            </a>

                                            @php
                                                // Logika Cek Waktu dan Status
                                                $tanggalTransaksi = \Carbon\Carbon::parse($laporan->created_at);
                                                $isRecent = $tanggalTransaksi->gte(\Carbon\Carbon::now()->subDay());
                                                $canModify =
                                                    $laporan->status_persetujuan != 'disetujui' &&
                                                    $laporan->status_persetujuan != 'ditolak' &&
                                                    $isRecent;
                                            @endphp

                                            {{-- 2. TOMBOL EDIT (Hanya jika memenuhi syarat modifikasi) --}}
                                            @if ($canModify && Auth::user()->role == 'keuangan')
                                                <a href="{{ url('keuangan/' . $laporan->id . '/edit') }}"
                                                    class="dropdown-item text-warning">
                                                    <i class="ri ri-edit-line me-1"></i> Edit
                                                </a>
                                            @endif

                                            {{-- 3. TOMBOL HAPUS (Hanya jika memenuhi syarat modifikasi) --}}
                                            @if ($canModify && Auth::user()->role == 'keuangan')
                                                <a class="dropdown-item text-danger btn-hapus-laporan"
                                                    href="javascript:void(0);" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal" data-id="{{ $laporan->id }}"
                                                    data-keperluan="{{ $laporan->keperluan }}">
                                                    <i class="ri ri-delete-bin-line me-1"></i> Hapus
                                                </a>
                                            @endif

                                            {{-- 4. TOMBOL PROSES PERSETUJUAN (Hanya Direktur & Menunggu) --}}
                                            @if (
                                                $laporan->persetujuan_direktur == 1 &&
                                                    Auth::user()->role == 'direktur' &&
                                                    $laporan->status_persetujuan == 'menunggu')
                                                <a href="{{ url('keuangan/persetujuan', $laporan->id) }}"
                                                    class="dropdown-item text-primary">
                                                    <i class="icon-base ri ri-check-line me-1"></i> Setujui
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data keuangan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="container">
                        <div class="d-flex justify-content-end mt-3">
                            {{ $laporanKeuangan->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="icon-base ri ri-file-list-line me-2"></i> Detail Transaksi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between"><strong>Tanggal:</strong><span
                                    id="detail-tanggal"></span></li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Penerima:</strong><span
                                    id="detail-pengguna"></span></li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Jenis:</strong><span
                                    id="detail-jenis"></span></li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Nominal:</strong><span
                                    id="detail-nominal"></span></li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Metode:</strong><span
                                    id="detail-metode"></span></li>
                            <li class="list-group-item"><strong>Keperluan:</strong>
                                <p id="detail-keperluan" class="mb-0 mt-1"></p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between"><strong>Status:</strong><span
                                    id="detail-status"></span></li>
                        </ul>

                        <div class="mt-3 p-3 border rounded bg-light">
                            <strong>Catatan Direktur:</strong>
                            <p id="detail-catatan" class="mb-0 mt-1 fst-italic"></p>
                        </div>

                        <div class="mt-3 p-3 border rounded">
                            <strong>Bukti Transaksi:</strong>
                            <div id="detail-bukti-container" class="mt-2">
                                <span class="text-muted">Tidak ada bukti</span>
                            </div>
                        </div>
                        <div class="mt-3 p-3 border rounded">
                            <strong>Bukti Persetujuan:</strong>
                            <div id="detail-approval-container" class="mt-2">
                                <span class="text-muted">Belum ada bukti persetujuan</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- TOMBOL PROSES PERSETUJUAN (Untuk dialihkan ke halaman persetujuan direktur) --}}
                        <a href="#" id="btn-proses-persetujuan" class="btn btn-primary d-none">
                            <i class="ri ri-check-line me-1"></i> Proses Persetujuan
                        </a>

                        {{-- TOMBOL DOWNLOAD PDF BUKTI PERSETUJUAN --}}
                        <a href="#" id="btn-generate-pdf" class="btn btn-success d-none">
                            <i class="ri ri-file-pdf-line me-1"></i> Download Bukti PDF
                        </a>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus data transaksi: "<strong id="delete-keperluan"></strong>"?
                            <p class="text-danger small mt-2">Tindakan ini tidak bisa dibatalkan.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @push('scripts')
        <script>
            // BARIS BARU: Definisikan variabel peran dari sisi server (Blade)
            const isDirektur = @json(Auth::user()->role == 'direktur');

            document.addEventListener('DOMContentLoaded', function() {
                const detailModal = document.getElementById('detailModal');
                const deleteModal = document.getElementById('deleteModal');
                const deleteForm = document.getElementById('deleteForm');

                // Logic untuk Modal Detail
                detailModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const data = {
                        tanggal: button.getAttribute('data-tanggal'),
                        pengguna: button.getAttribute('data-pengguna'),
                        keperluan: button.getAttribute('data-keperluan'),
                        nominal: button.getAttribute('data-nominal'),
                        jenis: button.getAttribute('data-jenis'),
                        metode: button.getAttribute('data-metode'),
                        finalUrl: button.getAttribute('data-final-url'),
                        statusTeks: button.getAttribute('data-status-teks'),
                        catatan: button.getAttribute('data-catatan'),
                        id: button.getAttribute('data-id'),
                        approvalUrl: button.getAttribute('data-approval-url'),
                        perluPersetujuan: button.getAttribute('data-perlu-persetujuan'),
                        currentStatus: button.getAttribute('data-current-status'),
                        // BARIS BARU: Ambil path PDF
                        pdfPath: button.getAttribute('data-pdf-path')
                    };

                    // Isi detail
                    document.getElementById('detail-tanggal').textContent = data.tanggal;
                    document.getElementById('detail-pengguna').textContent = data.pengguna;
                    document.getElementById('detail-keperluan').textContent = data.keperluan;
                    document.getElementById('detail-nominal').textContent = 'Rp' + data.nominal;
                    document.getElementById('detail-metode').textContent = data.metode;
                    document.getElementById('detail-catatan').textContent = data.catatan;

                    // Logic untuk Jenis Badge
                    const jenisBadge = document.createElement('span');
                    jenisBadge.className = 'badge bg-' + (data.jenis === 'uang_masuk' ? 'success' :
                        data.jenis === 'pengeluaran' ? 'danger' : 'warning text-dark');
                    jenisBadge.textContent = data.jenis.replace('_', ' ').toUpperCase();
                    document.getElementById('detail-jenis').innerHTML = '';
                    document.getElementById('detail-jenis').appendChild(jenisBadge);

                    // Logic untuk Status Badge
                    const statusBadge = document.createElement('span');
                    statusBadge.className = 'badge bg-' + (data.statusTeks === 'Menunggu' ? 'secondary' : data
                        .statusTeks === 'Disetujui' ? 'success' : 'danger');
                    statusBadge.textContent = data.statusTeks;
                    document.getElementById('detail-status').innerHTML = '';
                    document.getElementById('detail-status').appendChild(statusBadge);

                    const approvalContainer = document.getElementById('detail-approval-container');

if (data.approvalUrl && data.approvalUrl !== '') {
    approvalContainer.innerHTML = `
        <a href="${data.approvalUrl}" target="_blank" class="btn btn-outline-success btn-sm">
            <i class="ri ri-file-check-line me-1"></i> Lihat Bukti Persetujuan
        </a>
    `;
} else {
    approvalContainer.innerHTML =
        '<span class="text-muted">Belum ada bukti persetujuan</span>';
}

                    // Logic untuk Bukti transaksi
                    const buktiContainer = document.getElementById('detail-bukti-container');

                    if (data.finalUrl && data.finalUrl !== '') {
                        buktiContainer.innerHTML = `
        <a href="${data.finalUrl}" target="_blank" class="btn btn-outline-info btn-sm">
            <i class="ri ri-file-2-line me-1"></i> Lihat Dokumen
        </a>
    `;
                    } else {
                        buktiContainer.innerHTML =
                            '<span class="text-muted">Tidak ada bukti</span>';
                    }



                    // === LOGIKA TOMBOL PROSES PERSETUJUAN ===
                    const btnProsesPersetujuan = document.getElementById('btn-proses-persetujuan');

                    const showApprovalButton =
                        // 1. Pengguna harus direktur
                        isDirektur &&
                        // 2. Transaksi memerlukan persetujuan direktur (bernialai 1)
                        data.perluPersetujuan == '1' &&
                        // 3. Status transaksi masih pending
                        data.currentStatus == 'menunggu';

                    if (showApprovalButton) {
                        btnProsesPersetujuan.classList.remove('d-none'); // Tampilkan tombol
                        // Set URL (Asumsi: /keuangan/persetujuan/{id} yang akan menampilkan form persetujuan)
                        btnProsesPersetujuan.href = `/keuangan/persetujuan/${data.id}`;
                    } else {
                        btnProsesPersetujuan.classList.add('d-none'); // Sembunyikan
                    }

                    // === LOGIKA BARU: TOMBOL DOWNLOAD PDF ===
                    const btnGeneratePdf = document.getElementById('btn-generate-pdf');

                    if (data.currentStatus === 'disetujui' && data.pdfPath) {
                        btnGeneratePdf.classList.remove('d-none'); // Tampilkan tombol
                        btnGeneratePdf.href = data.pdfPath; // Set URL ke file PDF (asset('storage/path'))
                        btnGeneratePdf.setAttribute('target', '_blank'); // Buka di tab baru
                    } else {
                        btnGeneratePdf.classList.add('d-none'); // Sembunyikan tombol
                        btnGeneratePdf.removeAttribute('target');
                    }
                });

                // Logic untuk Modal Hapus
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const keperluan = button.getAttribute('data-keperluan');

                    document.getElementById('delete-keperluan').textContent = keperluan;
                    deleteForm.action = `/keuangan/${id}`;
                });
            });
        </script>
    @endpush
