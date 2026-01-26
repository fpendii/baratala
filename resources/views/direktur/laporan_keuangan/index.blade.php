@extends('layout.direktur-template')

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

    <div class="card">
        {{-- HEADER --}}
        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
            <h5 class="mb-2 mb-sm-0">Laporan Keuangan</h5>
            <a href="{{ url('keuangan/export') }}" class="btn btn-outline-success btn-sm">
                <i class="icon-base ri ri-download-line me-1"></i> Export Excel
            </a>
        </div>

        {{-- RINGKASAN KEUANGAN --}}
        <div class="card-body">
            <div class="row gy-3 mb-4">
                <!-- Ringkasan Uang Kas -->
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Uang Kas</h5>
                            <p class="mb-2">Kantor</p>
                            <h4 class="text-primary mb-0">Rp{{ number_format($uangKas->nominal, 0, ',', '.') }}</h4>
                        </div>
                        <img src="/image/icon-uang.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="83"
                            alt="kas" />
                    </div>
                </div>

                <!-- Ringkasan Uang Masuk -->
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Uang Masuk</h5>
                            <p class="mb-2">Bulan ini</p>
                            <h4 class="text-success mb-0">Rp{{ number_format($uangMasuk, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Uang Keluar -->
                <div class="col-md-12 col-lg-4">
                    <div class="card">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Uang Keluar</h5>
                            <p class="mb-2">Bulan ini</p>
                            <h4 class="text-danger mb-0">Rp{{ number_format($uangKeluar, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <form action="{{ url('direktur/keuangan-laporan') }}" method="GET" class="row g-3 mb-3">
                <div class="col-md-3">
                    <label for="filter_tanggal" class="form-label">Tanggal</label>
                    <input type="month" name="filter_tanggal" id="filter_tanggal" class="form-control"
                        value="{{ request('filter_tanggal') }}">
                </div>
                <div class="col-md-3">
                    <label for="filter_jenis" class="form-label">Jenis</label>
                    <select name="filter_jenis" id="filter_jenis" class="form-select">
                        <option value="">-- Semua Jenis --</option>
                        <option value="uang_masuk" {{ request('filter_jenis') == 'uang_masuk' ? 'selected' : '' }}>Uang
                            Masuk</option>
                        <option value="pengeluaran" {{ request('filter_jenis') == 'pengeluaran' ? 'selected' : '' }}>
                            Pengeluaran</option>
                        <option value="kasbon" {{ request('filter_jenis') == 'kasbon' ? 'selected' : '' }}>Kasbon</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_pengguna" class="form-label">Penerima/Pengaju</label>
                    <select name="filter_pengguna" id="filter_pengguna" class="form-select">
                        <option value="">-- Semua Karyawan --</option>
                        @foreach ($daftarKaryawan as $karyawan)
                            <option value="{{ $karyawan->id }}"
                                {{ request('filter_pengguna') == $karyawan->id ? 'selected' : '' }}>
                                {{ $karyawan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ url('direktur/keuangan-laporan') }}" class="btn btn-outline-secondary"
                        title="Reset Filter">
                        Reset
                    </a>
                </div>
            </form>

            {{-- TABEL --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Peresetujuan</th>
                            <th>Status</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanKeuangan as $laporan)
                            @php
                                $statusPersetujuan = strtolower($laporan->status_persetujuan);
                                $statusClass = [
                                    'menunggu' => 'warning',
                                    'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                ];
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</td>

                                <td>
                                    @if ($laporan->jenis == 'uang_masuk')
                                        <span class="badge bg-success">Uang Masuk</span>
                                    @elseif($laporan->jenis == 'pengeluaran')
                                        <span class="badge bg-danger">Pengeluaran</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Kasbon</span>
                                    @endif
                                </td>
                                <td class="fw-bold {{ $laporan->jenis == 'uang_masuk' ? 'text-success' : 'text-danger' }}">
                                    Rp{{ number_format($laporan->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($laporan->persetujuan_direktur == 1)
                                        <span class="badge bg-warning">Perlu persetujuan</span>
                                    @else
                                        <span class="badge bg-success">Tidak perlu persetujuan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass[$statusPersetujuan] ?? 'secondary' }}">
                                        {{ ucfirst($statusPersetujuan) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn-detail-laporan" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                data-tanggal="{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}"
                                                data-pengguna="{{ $laporan->pengguna->nama ?? '-' }}"
                                                data-keperluan="{{ $laporan->keperluan }}"
                                                data-nominal="{{ number_format($laporan->nominal, 0, ',', '.') }}"
                                                data-jenis="{{ $laporan->jenis }}"
                                                data-metode="{{ ucfirst($laporan->jenis_uang) }}"
                                                data-bukti="{{ $laporan->bukti_transaksi ? asset('storage/' . $laporan->bukti_transaksi) : '' }}"
                                                data-persetujuan="{{ url('direktur/keuangan-laporan/persetujuan', $laporan->id) }}"
                                                data-status-teks="{{ ucfirst($statusPersetujuan) }}"
                                                data-status-class="{{ $statusClass[$statusPersetujuan] ?? 'secondary' }}"
                                                data-catatan="{{ $laporan->catatan ?? 'Tidak ada catatan.' }}">
                                                <i class="icon-base ri ri-eye-line me-1"></i> Detail
                                            </a>
                                            @if ($laporan->persetujuan_direktur == 1)
                                                @if ($statusPersetujuan == 'menunggu')
                                                    <a href="{{ url('direktur/keuangan-laporan/persetujuan', $laporan->id) }}"
                                                        class="dropdown-item text-primary">
                                                        <i class="icon-base ri ri-check-line me-1"></i> Setujui
                                                    </a>
                                                @endif
                                            @endif

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="ri ri-file-list-3-line ri-3x d-block mb-2 text-muted"></i>
                                    <p class="mb-1">Belum ada data laporan keuangan yang tercatat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="card-footer d-flex justify-content-center">
                {{ $laporanKeuangan->links('pagination::bootstrap-5') }}
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
                        <li class="list-group-item d-flex justify-content-between"><strong>Pengaju:</strong><span
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
                </div>
                <div class="modal-footer">
                    <a href="#" id="modal-persetujuan-link" class="btn btn-primary d-none">Proses Persetujuan</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('detailModal');
            const persetujuanLink = document.getElementById('modal-persetujuan-link');

            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                const data = {
                    tanggal: button.getAttribute('data-tanggal'),
                    pengguna: button.getAttribute('data-pengguna'),
                    keperluan: button.getAttribute('data-keperluan'),
                    nominal: button.getAttribute('data-nominal'),
                    jenis: button.getAttribute('data-jenis'),
                    metode: button.getAttribute('data-metode'),
                    bukti: button.getAttribute('data-bukti'),
                    persetujuanUrl: button.getAttribute('data-persetujuan'),
                    statusTeks: button.getAttribute('data-status-teks'),
                    statusClass: button.getAttribute('data-status-class'),
                    catatan: button.getAttribute('data-catatan')
                };

                document.getElementById('detail-tanggal').textContent = data.tanggal;
                document.getElementById('detail-pengguna').textContent = data.pengguna;
                document.getElementById('detail-keperluan').textContent = data.keperluan;
                document.getElementById('detail-nominal').textContent = 'Rp' + data.nominal;
                document.getElementById('detail-metode').textContent = data.metode;
                document.getElementById('detail-catatan').textContent = data.catatan;

                // Jenis badge
                const jenisBadge = document.createElement('span');
                jenisBadge.className = 'badge bg-' + (data.jenis === 'uang_masuk' ? 'success' :
                    data.jenis === 'pengeluaran' ? 'danger' : 'warning text-dark');
                jenisBadge.textContent = data.jenis.replace('_', ' ').toUpperCase();
                document.getElementById('detail-jenis').innerHTML = '';
                document.getElementById('detail-jenis').appendChild(jenisBadge);

                // Status badge
                const statusBadge = document.createElement('span');
                statusBadge.className = 'badge bg-' + data.statusClass;
                statusBadge.textContent = data.statusTeks;
                document.getElementById('detail-status').innerHTML = '';
                document.getElementById('detail-status').appendChild(statusBadge);

                // Bukti transaksi
                const buktiContainer = document.getElementById('detail-bukti-container');
                buktiContainer.innerHTML = data.bukti ?
                    `<a href="${data.bukti}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Bukti</a>` :
                    '<span class="text-muted">Tidak ada bukti</span>';

                // Tombol persetujuan
                if (data.statusTeks.toLowerCase() === 'menunggu') {
                    persetujuanLink.href = data.persetujuanUrl;
                    persetujuanLink.classList.remove('d-none');
                } else {
                    persetujuanLink.classList.add('d-none');
                }
            });
        });
    </script>
@endpush
