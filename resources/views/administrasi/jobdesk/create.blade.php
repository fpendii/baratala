@extends('layout.administrasi-template')

@section('title', 'Tambah Jobdesk')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Jobdesk</h5>
        <small class="text-body-secondary float-end">Form Tambah Data Jobdesk</small>
    </div>
    <div class="card-body">
        <form action="{{ url('administrasi/jobdesk/store') }}" method="POST">
            @csrf

            {{-- FIELD: DIVISI (TUNGGAL) --}}
            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Divisi Tujuan</label>
                <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ri ri-group-line"></i></span>
                        <select
                            class="form-select @error('divisi') is-invalid @enderror"
                            name="divisi" {{-- NAMA INI TUNGGAL (BUKAN ARRAY) --}}
                            required
                        >
                            <option value="" disabled {{ old('divisi') == null ? 'selected' : '' }}>Pilih Divisi</option>
                            <option value="direktur" {{ old('divisi') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                            <option value="kepala teknik" {{ old('divisi') == 'kepala teknik' ? 'selected' : '' }}>Kepala Teknik</option>
                            <option value="enginer" {{ old('divisi') == 'enginer' ? 'selected' : '' }}>Enginer</option>
                            <option value="produksi" {{ old('divisi') == 'produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="keuangan" {{ old('divisi') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                        </select>
                        @error('divisi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr/>

            {{-- ---------------------------------------------------- --}}
            {{-- WADAH UTAMA UNTUK INPUT JUDUL JOBDESK DINAMIS --}}
            {{-- ---------------------------------------------------- --}}
            <div id="jobdesk-container">
                {{-- BARIS INPUT PERTAMA (Akan di-clone) --}}
                <div class="jobdesk-item row mb-4">
                    <label class="col-sm-2 col-form-label fw-bold jobdesk-label">Jobdesk 1</label>
                    <div class="col-sm-10">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="icon-base ri ri-task-line"></i></span>
                            <input
                                type="text"
                                class="form-control @error('judul_jobdesk.0') is-invalid @enderror"
                                name="judul_jobdesk[]" {{-- NAMA INI ARRAY --}}
                                placeholder="Masukkan Judul Jobdesk"
                                value="{{ old('judul_jobdesk.0') }}"
                                required
                            />
                            {{-- Tombol Hapus: Hanya muncul di item > 1 --}}
                            <button type="button" class="btn btn-outline-danger remove-jobdesk d-none" title="Hapus Jobdesk"><i class="ri ri-close-line"></i></button>
                        </div>
                        @error('judul_jobdesk.0')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- TOMBOL TAMBAH BARIS --}}
            <div class="row mb-4">
                <div class="col-sm-10 offset-sm-2">
                    <button type="button" id="add-jobdesk" class="btn btn-outline-success btn-sm">
                        <i class="ri ri-add-line"></i> Tambah Jobdesk Lain
                    </button>
                </div>
            </div>
            {{-- ---------------------------------------------------- --}}

            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <a href="{{ url('/administrasi/jobdesk') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Semua Jobdesk</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('jobdesk-container');
    const addButton = document.getElementById('add-jobdesk');

    // Fungsi untuk meng-update label nomor dan menampilkan/menyembunyikan tombol hapus
    function updateLabels() {
        const items = container.querySelectorAll('.jobdesk-item');

        items.forEach((item, index) => {
            // Update label Jobdesk
            item.querySelector('.jobdesk-label').textContent = 'Jobdesk ' + (index + 1);

            // Update tombol hapus: Tampilkan jika item > 1
            const removeButton = item.querySelector('.remove-jobdesk');
            if (removeButton) {
                if (items.length > 1) {
                    removeButton.classList.remove('d-none');
                } else {
                    removeButton.classList.add('d-none');
                }
            }
        });
    }

    // Panggil pertama kali untuk mengkonfigurasi tombol hapus
    updateLabels();


    addButton.addEventListener('click', function () {
        // 1. Kloning item jobdesk pertama
        const firstItem = container.querySelector('.jobdesk-item');
        const newItem = firstItem.cloneNode(true);

        // 2. Bersihkan nilai input pada item baru
        const inputField = newItem.querySelector('input[name="judul_jobdesk[]"]');
        inputField.value = '';
        inputField.classList.remove('is-invalid');

        // Hapus pesan validasi error (jika ada)
        let errorDiv = inputField.closest('.col-sm-10').querySelector('.text-danger');
        if (errorDiv) {
            errorDiv.remove();
        }

        // 3. Pastikan tombol hapus muncul di baris baru
        const removeButton = newItem.querySelector('.remove-jobdesk');
        if (removeButton) {
            removeButton.classList.remove('d-none');
        }

        // 4. Tambahkan item baru ke container
        container.appendChild(newItem);

        // 5. Update label nomor
        updateLabels();
    });

    // Event Delegasi untuk Tombol Hapus
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-jobdesk')) {
            const removeButton = e.target.closest('.remove-jobdesk');
            const itemToRemove = removeButton.closest('.jobdesk-item');

            // Hanya hapus jika lebih dari satu item tersisa
            if (container.querySelectorAll('.jobdesk-item').length > 1) {
                itemToRemove.remove();
                updateLabels(); // Update label setelah penghapusan
            }
        }
    });
});
</script>
@endpush
