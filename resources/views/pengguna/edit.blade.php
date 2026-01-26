@extends('layout.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Edit Data Pengguna</h5>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        value="{{ old('nama', $pengguna->nama) }}" required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $pengguna->email) }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="no_hp" class="form-label">No. HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp"
                        value="{{ old('no_hp', $pengguna->no_hp) }}">
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $pengguna->alamat) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="kepala_teknik_tambang"
                            {{ old('role', $pengguna->role) == 'kepala_teknik_tambang' ? 'selected' : '' }}>
                            Kepala Teknik Tambang
                        </option>
                        <option value="keuangan" {{ old('role', $pengguna->role) == 'keuangan' ? 'selected' : '' }}>
                            Keuangan
                        </option>
                        <option value="produksi" {{ old('role', $pengguna->role) == 'produksi' ? 'selected' : '' }}>
                            Produksi
                        </option>
                        <option value="IT" {{ old('role', $pengguna->role) == 'IT' ? 'selected' : '' }}>
                            IT
                        </option>
                        <option value="umum" {{ old('role', $pengguna->role) == 'umum' ? 'selected' : '' }}>
                            Umum
                        </option>
                        <option value="direktur" {{ old('role', $pengguna->role) == 'direktur' ? 'selected' : '' }}>
                            Direktur
                        </option>
                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
