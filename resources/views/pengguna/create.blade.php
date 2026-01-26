@extends('layout.app')

@section('title', 'Tambah pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Tambah pengguna Baru</h5>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                        required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        required>
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No. HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="direktur"
                            {{ old('role') == 'direktur' ? 'selected' : '' }}>
                            Direktur
                        </option>
                        <option value="kepala_teknik_tambang"
                            {{ old('role') == 'kepala_teknik_tambang' ? 'selected' : '' }}>
                            Kepala Teknik Tambang
                        </option>
                        <option value="keuangan" {{ old('role') == 'keuangan' ? 'selected' : '' }}>
                            Keuangan
                        </option>
                        <option value="produksi" {{ old('role') == 'produksi' ? 'selected' : '' }}>
                            Produksi
                        </option>
                        <option value="IT" {{ old('role') == 'IT' ? 'selected' : '' }}>
                            IT
                        </option>
                        <option value="umum" {{ old('role') == 'umum' ? 'selected' : '' }}>
                            Umum
                        </option>
                        <option value="direktur" {{ old('role') == 'direktur' ? 'selected' : '' }}>
                            Direktur
                        </option>

                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
