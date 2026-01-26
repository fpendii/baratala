@extends('layout.app')

@section('title', 'Daftar Surat Masuk')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Surat Masuk</h4>

        <div class="d-flex align-items-center gap-2">

            {{-- 🔔 NOTIF DISPOSISI --}}
            @if (Auth::user()->role !== 'direktur')
                <a href="{{ url('disposisi/saya') }}" class="btn btn-outline-danger position-relative">
                    <i class="ri ri-notification-3-line me-1"></i> Disposisi Saya

                    @if ($notifDisposisi > 0)
                        <span class="position-absolute badge rounded-pill bg-danger"
                            style="
                  top: -6px;
                  right: -6px;
                  font-size: 10px;
                  padding: 4px 6px;
              ">
                            {{ $notifDisposisi }}
                        </span>
                    @endif
                </a>

            @endif

            {{-- 🔥 KHUSUS DIREKTUR --}}
            @if (Auth::user()->role === 'direktur')
                <a href="{{ route('disposisi.index') }}" class="btn btn-outline-info ms-auto">
                    <i class="ri ri-share-forward-line me-1"></i> Surat Didisposisikan
                </a>
            @endif

            <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
                <i class="ri ri-add-line me-1"></i> Tambah
            </a>

        </div>


    </div>


    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h5 class="card-header">Daftar Surat Masuk</h5>



        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul & Nomor</th>
                        <th>Pengirim</th>
                        <th>Tanggal</th>
                        <th>Prioritas</th>
                        <th>Status Disposisi</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($suratMasuk as $index => $item)
                        {{-- ================= FINAL URL LOGIC ================= --}}
                        @php
                            $finalUrl = null;

                            if ($item->lampiran) {
                                $fileName = basename($item->lampiran);
                                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                                $fileUrl = route('surat-masuk.tampil', $fileName);

                                $officeExt = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

                                if (in_array($extension, $officeExt)) {
                                    $finalUrl =
                                        'https://docs.google.com/viewer?url=' . urlencode($fileUrl) . '&embedded=true';
                                } else {
                                    $finalUrl = $fileUrl;
                                }
                            }
                        @endphp
                        {{-- =================================================== --}}

                        <tr>
                            <td>{{ $suratMasuk->firstItem() + $index }}</td>

                            <td>
                                <a href="{{ route('surat-masuk.show', $item->id) }}" class="fw-bold text-primary">
                                    {{ $item->judul }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $item->nomor_surat ?? '-' }}</small>

                                @if ($finalUrl)
                                    <a href="{{ $finalUrl }}" target="_blank" class="ms-1 text-info">
                                        <i class="bx bx-paperclip"></i>
                                    </a>
                                @endif
                            </td>

                            <td>
                                {{ $item->pengirim }}
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit($item->keterangan, 30) }}
                                </small>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}
                            </td>

                            <td>
                                @php
                                    $map = ['tinggi' => 'danger', 'sedang' => 'warning', 'rendah' => 'secondary'];
                                    $p = strtolower($item->prioritas ?? 'rendah');
                                @endphp
                                <span class="badge bg-{{ $map[$p] }}">{{ ucfirst($p) }}</span>
                            </td>

                            <td>
                                @if ($item->status == 'menunggu')
                                    <span class="badge bg-warning">Menunggu</span>
                                @else
                                    <span class="badge bg-info">Didisposisi</span>
                                @endif
                            </td>

                            <td>
                                @if ($finalUrl)
                                    <a href="{{ $finalUrl }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="ri ri-file-2-line"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0" data-bs-toggle="dropdown">
                                        <i class="ri ri-more-2-line"></i>
                                    </button>

                                    <div class="dropdown-menu">

                                        {{-- DETAIL --}}
                                        <a href="javascript:void(0)" class="dropdown-item btn-detail-surat"
                                            data-bs-toggle="modal" data-bs-target="#detailSuratModal"
                                            data-judul="{{ $item->judul }}" data-nomor="{{ $item->nomor_surat }}"
                                            data-pengirim="{{ $item->pengirim }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d F Y') }}"
                                            data-prioritas="{{ ucfirst($p) }}" data-keterangan="{{ $item->keterangan }}"
                                            data-final-url="{{ $finalUrl ?? '' }}">
                                            <i class="ri ri-eye-line me-1"></i> Detail
                                        </a>

                                        {{-- ================= DISPOSISI (KHUSUS DIREKTUR) ================= --}}
                                        @if (Auth::user()->role === 'direktur')
                                            <a href="{{ route('disposisi.create', $item->id) }}"
                                                class="dropdown-item text-primary">
                                                <i class="ri ri-send-plane-line me-1"></i> Disposisi
                                            </a>
                                        @endif
                                        {{-- =============================================================== --}}

                                        {{-- EDIT & DELETE --}}
                                        @if ($item->id_pengguna == Auth::id())
                                            <a class="dropdown-item" href="{{ url('surat-masuk/edit/' . $item->id) }}">
                                                <i class="ri ri-pencil-line me-1"></i> Edit
                                            </a>

                                            <form action="{{ url('surat-masuk/delete/' . $item->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus data?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger">
                                                    <i class="ri ri-delete-bin-line me-1"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada data surat masuk
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{ $suratMasuk->links() }}
    </div>

    {{-- ================= MODAL DETAIL ================= --}}
    <div class="modal fade" id="detailSuratModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Surat Masuk</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <h5 id="detailJudul"></h5>
                    <p id="detailKeterangan"></p>

                    <ul class="list-group mb-3">
                        <li class="list-group-item">Nomor: <span id="detailNomor"></span></li>
                        <li class="list-group-item">Pengirim: <span id="detailPengirim"></span></li>
                        <li class="list-group-item">Tanggal: <span id="detailTanggal"></span></li>
                        <li class="list-group-item">Prioritas: <span id="detailPrioritas"></span></li>
                    </ul>

                    <a href="#" target="_blank" id="detailLampiran" class="btn btn-outline-info d-none">
                        <i class="bx bx-paperclip"></i> Lihat Dokumen
                    </a>

                    <span id="detailLampiranEmpty" class="text-muted d-none">
                        Tidak ada lampiran
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= JS ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('detailSuratModal');

            modal.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;

                document.getElementById('detailJudul').textContent = btn.dataset.judul;
                document.getElementById('detailNomor').textContent = btn.dataset.nomor;
                document.getElementById('detailPengirim').textContent = btn.dataset.pengirim;
                document.getElementById('detailTanggal').textContent = btn.dataset.tanggal;
                document.getElementById('detailPrioritas').textContent = btn.dataset.prioritas;
                document.getElementById('detailKeterangan').textContent = btn.dataset.keterangan;

                const finalUrl = btn.dataset.finalUrl;
                const lampiran = document.getElementById('detailLampiran');
                const empty = document.getElementById('detailLampiranEmpty');

                if (finalUrl) {
                    lampiran.href = finalUrl;
                    lampiran.classList.remove('d-none');
                    empty.classList.add('d-none');
                } else {
                    lampiran.classList.add('d-none');
                    empty.classList.remove('d-none');
                }
            });
        });
    </script>

@endsection
