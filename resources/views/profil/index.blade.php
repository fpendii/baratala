@extends('layout.app')

@section('title', 'Profil Karyawan')

@section('content')

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-6">
        <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-6">
                <img src="/template-admin/assets/img/avatars/1.png"
                     alt="user-avatar"
                     class="d-block w-px-100 h-px-100 rounded" />

                <div class="button-wrapper">
                    <h5>{{ $user['nama'] ?? 'User' }}</h5>
                    <p class="mb-1">{{ $user['email'] ?? '-' }}</p>
                    <small class="text-muted">
                        Role: {{ $user['role'] ?? 'Karyawan' }}
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="POST" action="{{ route('profil.update', $user['id']) }}">
                @csrf

                <div class="row mt-1 g-5">

                    {{-- NAMA --}}
                    <div class="col-md-6 form-control-validation">
                        <div class="form-floating form-floating-outline">
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   id="nama"
                                   name="nama"
                                   value="{{ old('nama', $user['nama'] ?? '') }}">
                            <label for="nama">Nama</label>
                        </div>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6 form-control-validation">
                        <div class="form-floating form-floating-outline">
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user['email'] ?? '') }}">
                            <label for="email">Email</label>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NO HP --}}
                    <div class="col-md-6 form-control-validation">
                        <div class="form-floating form-floating-outline">
                            <input type="text"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   id="no_hp"
                                   name="no_hp"
                                   value="{{ old('no_hp', $user['no_hp'] ?? '') }}">
                            <label for="no_hp">No HP</label>
                        </div>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-md-6 form-control-validation">
                        <div class="form-floating form-floating-outline">
                            <input type="text"
                                   class="form-control @error('alamat') is-invalid @enderror"
                                   id="alamat"
                                   name="alamat"
                                   value="{{ old('alamat', $user['alamat'] ?? '') }}">
                            <label for="alamat">Alamat</label>
                        </div>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD BARU --}}
                    <div class="col-md-6 form-control-validation">
                        <div class="form-floating form-floating-outline">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password">
                            <label for="password">Password Baru</label>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="col-md-6 form-control-validation">
                        <div class="form-floating form-floating-outline">
                            <input type="password"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation"
                                   name="password_confirmation">
                            <label for="password_confirmation">Konfirmasi Password</label>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <small class="text-muted">
                            Kosongkan password jika tidak ingin mengubahnya
                        </small>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn btn-primary me-3">
                        Simpan
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        Reset
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
