@extends('layout.administrasi-template')

@section('title', 'Edit Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Edit Data Karyawan</h5>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.karyawan.update', $karyawan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $karyawan->nama) }}" required>
                @error('nama')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $karyawan->email) }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">No. HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $karyawan->no_hp) }}">
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin" {{ (old('role', $karyawan->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                    <option value="karyawan" {{ (old('role', $karyawan->role) == 'karyawan') ? 'selected' : '' }}>Karyawan</option>
                    <option value="direktur" {{ (old('role', $karyawan->role) == 'direktur') ? 'selected' : '' }}>Direktur</option>
                    <option value="enginer" {{ (old('role', $karyawan->role) == 'enginer') ? 'selected' : '' }}>Enginer</option>
                    <option value="produksi" {{ (old('role', $karyawan->role) == 'produksi') ? 'selected' : '' }}>Produksi</option>

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
            <a href="{{ route('administrasi.karyawan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
