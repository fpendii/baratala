@extends('layout.app')

@section('title', 'Tambah Rencana Kerja')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tambah Rencana Kerja</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('rencana.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="judul_rencana" class="form-label">Judul Rencana</label>
                    <input type="text" id="judul_rencana" name="judul_rencana" class="form-control" placeholder="Masukkan judul rencana" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="5" placeholder="Tuliskan deskripsi rencana kerja"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="belum dikerjakan">Belum Dikerjakan</option>
                            <option value="sedang dikerjakan">Sedang Dikerjakan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label">Prioritas</label>
                        <select id="prioritas" name="prioritas" class="form-select">
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="rendah">Rendah</option>
                            <option value="sedang">Sedang</option>
                            <option value="tinggi">Tinggi</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="jenis" value="rencana" id="">

                <div class="mb-3">
                    <label class="form-label d-block">Pengguna yang Ditugaskan</label>
                    <div id="pengguna-container">
                        {{-- Select pengguna akan dimuat di sini --}}
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" id="add-pengguna" class="btn btn-sm btn-outline-primary mt-2">
                            <i class='bx bx-plus me-1'></i> Tambah Pengguna
                        </button>
                    </div>
                </div>

                <input type="hidden" name="pengguna" id="pengguna-hidden">

                <div class="mb-3">
                    <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
                    <input type="file" id="lampiran" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Rencana</button>
                <a href="{{ route('direktur.rencana.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan $users dikirim dari controller dan tidak null
    const allUsers = @json($users->where('id', '!=', auth()->id()));
    const container = document.getElementById('pengguna-container');
    const hiddenInput = document.getElementById('pengguna-hidden');
    const addButton = document.getElementById('add-pengguna');

    // Fungsi untuk memperbarui nilai input hidden
    function updateHiddenInput() {
        const selectedIds = Array.from(container.querySelectorAll('select')).map(s => s.value).filter(v => v);
        hiddenInput.value = selectedIds.join(',');
        updateAllSelectOptions(); // Panggil ini agar semua select diperbarui
    }

    // Fungsi untuk memperbarui opsi di SEMUA select
    function updateAllSelectOptions() {
        // Ambil semua ID yang sedang dipilih di seluruh select (kecuali yang kosong)
        const allSelectedIds = Array.from(container.querySelectorAll('select')).map(s => s.value).filter(v => v);

        container.querySelectorAll('select').forEach(selectElement => {
            const currentValue = selectElement.value;
            selectElement.innerHTML = `<option value="">-- Pilih Pengguna --</option>`;

            allUsers.forEach(user => {
                const userIdString = user.id.toString();

                // Tampilkan opsi jika user ID sama dengan nilai yang sedang dipilih (agar nilai terpilih tetap ada)
                // ATAU jika user ID belum ada di daftar ID yang dipilih di select lain
                if (userIdString === currentValue || !allSelectedIds.includes(userIdString)) {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.email})`;
                    if (userIdString === currentValue) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                }
            });
        });
    }

    // Fungsi untuk membuat elemen div yang berisi select dan tombol hapus
    function createSelectElement() {
        // Menggunakan input-group untuk tampilan yang compact
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group mb-2';

        const select = document.createElement('select');
        select.className = 'form-select';
        select.addEventListener('change', updateHiddenInput); // Memanggil updateHiddenInput juga akan memicu updateAllSelectOptions

        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-outline-danger';
        deleteButton.innerHTML = `<i class='bx bx-trash'></i>`;

        deleteButton.addEventListener('click', function() {
            wrapper.remove();
            updateHiddenInput(); // Memperbarui input hidden dan opsi select
        });

        wrapper.appendChild(select);
        wrapper.appendChild(deleteButton);

        return wrapper;
    }

    // Fungsi untuk menambahkan elemen select baru
    function addSelectElement() {
        const newWrapper = createSelectElement();
        const newSelect = newWrapper.querySelector('select');

        // Dapatkan semua ID yang sudah dipilih di SELECT yang ADA
        const currentSelectedIds = Array.from(container.querySelectorAll('select'))
            .filter(selectElement => selectElement.value) // Filter hanya yang sudah punya nilai
            .map(selectElement => selectElement.value);

        newSelect.innerHTML = `<option value="">-- Pilih Pengguna --</option>`;

        allUsers.forEach(user => {
            if (!currentSelectedIds.includes(user.id.toString())) {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} (${user.email})`;
                newSelect.appendChild(option);
            }
        });

        container.appendChild(newWrapper);
        updateAllSelectOptions(); // Panggil untuk memastikan select lama juga ikut ter-refresh
    }

    // Event listener untuk tombol "Tambah Pengguna"
    addButton.addEventListener('click', function() {
        addSelectElement();
    });

    // Inisialisasi: Panggil updateHiddenInput untuk memastikan opsi yang benar saat pertama kali dibuka
    updateHiddenInput();
});
</script>
@endsection
