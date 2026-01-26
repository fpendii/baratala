@extends('layout.direktur-template')

@section('title', 'Detail Rencana Kerja')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Rencana Kerja /</span> Detail Tugas
    </h4>

    {{-- Notifikasi (Opsional: Tambahkan notifikasi success/error di sini) --}}
    {{-- @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif --}}

    <div class="row">

        {{-- KOLOM KIRI: DETAIL RENCANA KERJA & KOMENTAR --}}
        <div class="col-lg-7 col-md-12 mb-4">
            {{-- CARD 1: DETAIL RENCANA KERJA --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Rencana Kerja</h5>
                    <a href="{{ route('rencana.edit', $tugas->id) }}" class="btn btn-sm btn-warning">
                        <i class="bx bx-edit-alt me-1"></i> Edit Detail
                    </a>
                </div>
                <div class="card-body">
                    <dl class="row detail-list-custom">
                        <dt class="col-sm-4 text-nowrap">Judul Rencana</dt>
                        <dd class="col-sm-8 text-break fw-bold">{{ $tugas->judul_rencana }}</dd>

                        <dt class="col-sm-4 text-nowrap">Deskripsi</dt>
                        <dd class="col-sm-8 text-break">{{ $tugas->deskripsi }}</dd>

                        {{-- <dt class="col-sm-4 text-nowrap">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-label-{{ $tugas->status == 'selesai' ? 'success' : ($tugas->status == 'sedang dikerjakan' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($tugas->status) }}
                            </span>
                        </dd> --}}

                        <dt class="col-sm-4 text-nowrap">Prioritas</dt>
                        <dd class="col-sm-8">{{ $tugas->prioritas ?? '-' }}</dd>

                        <dt class="col-sm-4 text-nowrap">Jenis</dt>
                        <dd class="col-sm-8">{{ ucfirst($tugas->jenis) }}</dd>
                    </dl>

                    <hr class="my-3">

                    <dl class="row detail-list-custom">
                        <dt class="col-sm-4 text-nowrap">Tanggal Mulai</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($tugas->tanggal_mulai)->format('d F Y') }}</dd>

                        <dt class="col-sm-4 text-nowrap">Tanggal Selesai</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($tugas->tanggal_selesai)->format('d F Y') }}</dd>

                        <dt class="col-sm-4 text-nowrap">Lampiran</dt>
                        <dd class="col-sm-8">
                            @if($tugas->lampiran)
                                <a href="{{ asset('storage/' . $tugas->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0">
                                    <i class="bx bx-download me-1"></i> Lihat File
                                </a>
                            @else
                                Tidak ada
                            @endif
                        </dd>
                    </dl>

                    <h6 class="mt-4 mb-2">Catatan Tugas</h6>
                    <p class="border p-3 rounded bg-light">{{ $tugas->catatan ?? 'Tidak ada catatan khusus untuk rencana kerja ini.' }}</p>
                </div>
            </div>

            {{-- CARD 2: LIST KOMENTAR & TAMBAH KOMENTAR (DIREKTUR DAPAT MEMODERASI) --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Diskusi dan Komentar</h5>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#tambahKomentarModal">
                        <i class="bx bx-message-add me-1"></i> Tambah Komentar
                    </button>
                </div>
                <div class="card-body komentar-list-container">
                    @forelse($tugas->komentar as $komentar)
                        <div class="d-flex mb-3 p-3 border rounded">
                            <div class="flex-shrink-0 me-3">
                                {{-- Icon atau Avatar --}}
                                <div class="avatar avatar-sm rounded-circle bg-label-secondary d-flex align-items-center justify-content-center">
                                    <span class="avatar-initial rounded-circle">{{ strtoupper(substr($komentar->pengguna->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $komentar->pengguna->name }}</h6>
                                    <small class="text-muted">{{ $komentar->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="text-muted mb-2 small">{{ $komentar->pengguna->role }}</p>
                                <p class="mb-2">{{ $komentar->isi }}</p>

                                <div class="d-flex flex-wrap align-items-center">
                                    <span class="badge me-2 bg-{{ $komentar->status == 'setuju' ? 'success' : ($komentar->status == 'tolak' ? 'danger' : 'warning') }}">
                                        Status: {{ ucfirst($komentar->status) }}
                                    </span>

                                    {{-- Action buttons for Direktur --}}
                                    @if ($komentar->status != 'setuju')
                                        <form action="{{ route('rencana.komentar.status', $komentar->id) }}" method="POST" class="d-inline me-2">
                                            @csrf
                                            <input type="hidden" name="status" value="setuju">
                                            <button type="submit" class="btn btn-success btn-xs py-0 px-2" title="Setujui Komentar"><i class="bx bx-check"></i> Setujui</button>
                                        </form>
                                    @endif
                                    @if ($komentar->status != 'tolak')
                                        <form action="{{ route('rencana.komentar.status', $komentar->id) }}" method="POST" class="d-inline me-2">
                                            @csrf
                                            <input type="hidden" name="status" value="tolak">
                                            <button type="submit" class="btn btn-danger btn-xs py-0 px-2" title="Tolak Komentar"><i class="bx bx-x"></i> Tolak</button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light mb-0 text-center">
                            Belum ada komentar atau diskusi untuk rencana kerja ini. Klik "Tambah Komentar" untuk memulai.
                        </div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('rencana.index') }}" class="btn btn-secondary mt-3">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Rencana
            </a>
        </div>


        {{-- KOLOM KANAN: PENGGUNA YANG DITUGASKAN (Dapat Diedit) --}}
        <div class="col-lg-5 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Kelola Tim Pelaksana</h5>
                    <small class="text-muted">Tambahkan atau hapus pengguna yang bertanggung jawab.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('rencana.updatePengguna', $tugas->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div id="pengguna-list">
                            @forelse($tugas->pengguna as $user)
                                <div class="row mb-2 pengguna-item align-items-center py-1 border-bottom">
                                    <div class="col-10">
                                        <select name="pengguna[]" class="form-select form-select-sm user-select-field">
                                            <option value="">-- Pilih Pengguna --</option>
                                            @foreach($allUsers as $u)
                                                <option value="{{ $u->id }}" {{ $user->id == $u->id ? 'selected' : '' }}>
                                                    {{ $u->nama }} ({{ $u->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-icon btn-sm remove-user" title="Hapus Pengguna">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                {{-- Placeholder for initial empty state --}}
                                <div class="alert alert-info text-center py-2 mb-2" id="empty-user-list">
                                    Belum ada pengguna yang ditugaskan.
                                </div>
                            @endforelse
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-3" id="add-user">
                            <i class="bx bx-user-plus me-1"></i> Tambah Pengguna Baru
                        </button>

                        <div class="mt-4 pt-2 border-top">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan Tim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


{{-- MODAL UNTUK TAMBAH KOMENTAR --}}
<div class="modal fade" id="tambahKomentarModal" tabindex="-1" aria-labelledby="tambahKomentarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rencana.komentar', $tugas->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKomentarModalLabel">Tulis Komentar & Masukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="komentar_direktur" class="form-label">Komentar Direktur</label>
                        <textarea class="form-control" id="komentar_direktur" name="komentar_direktur" rows="5" placeholder="Berikan masukan, persetujuan, atau arahan..." required></textarea>
                        <small class="text-muted">Komentar Anda akan dicatat atas nama Anda sebagai </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-send me-1"></i> Kirim Komentar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- END MODAL --}}

{{-- JS UNTUK PENGGUNA --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const userOptions = @json($allUsers);
    const penggunaList = document.getElementById('pengguna-list');
    const addUserBtn = document.getElementById('add-user');
    const emptyListAlert = document.getElementById('empty-user-list');

    function getCurrentSelectedIds() {
        return [...document.querySelectorAll('.user-select-field')]
            .map(select => select.value)
            .filter(v => v);
    }

    function updateEmptyState() {
        if (emptyListAlert) {
            if (penggunaList.querySelectorAll('.pengguna-item').length === 0) {
                emptyListAlert.style.display = 'block';
            } else {
                emptyListAlert.style.display = 'none';
            }
        }
    }

    function updateSelectOptions() {
        const allSelected = getCurrentSelectedIds();
        document.querySelectorAll('.user-select-field').forEach(select => {
            const current = select.value;
            // Clear existing options, but keep the placeholder (if any)
            let innerHtmlContent = `<option value="">-- Pilih Pengguna --</option>`;

            userOptions.forEach(user => {
                const userIdString = user.id.toString();
                if (userIdString === current || !allSelected.includes(userIdString)) {
                    const selectedAttr = userIdString === current ? 'selected' : '';
                    innerHtmlContent += `<option value="${user.id}" ${selectedAttr}>${user.nama} (${user.email})</option>`;
                }
            });
            select.innerHTML = innerHtmlContent;
        });
        updateEmptyState();
    }

    function createUserRow() {
        const row = document.createElement('div');
        row.classList.add('row', 'mb-2', 'pengguna-item', 'align-items-center', 'py-1', 'border-bottom');
        row.innerHTML = `
            <div class="col-10">
                <select name="pengguna[]" class="form-select form-select-sm user-select-field">
                    <option value="">-- Pilih Pengguna --</option>
                </select>
            </div>
            <div class="col-2 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger btn-icon btn-sm remove-user" title="Hapus Pengguna">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;
        return row;
    }

    // Add User button handler
    addUserBtn.addEventListener('click', () => {
        const row = createUserRow();
        // Insert new row
        if (emptyListAlert) {
            penggunaList.insertBefore(row, emptyListAlert);
        } else {
             penggunaList.appendChild(row);
        }
        // Force the new select field to update its options
        updateSelectOptions();
    });

    // Remove User handler (Delegation)
    penggunaList.addEventListener('click', e => {
        const removeBtn = e.target.closest('.remove-user');
        if (removeBtn) {
            e.target.closest('.pengguna-item').remove();
            updateSelectOptions();
        }
    });

    // Select change handler (Delegation)
    penggunaList.addEventListener('change', e => {
        if (e.target.classList.contains('user-select-field')) updateSelectOptions();
    });

    // Initial call to ensure correctness after initial load
    updateSelectOptions();
});
</script>
@endsection
