@extends('layout.administrasi-template')

@section('title', 'Profil Karyawan')

@section('content')
<div class="card mb-6">
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-6">
            <img src="/template-admin/assets/img/avatars/1.png" alt="user-avatar"
                class="d-block w-px-100 h-px-100 rounded" />
            <div class="button-wrapper">
                <h5>{{ $user['nama'] ?? 'User' }}</h5>
                <p class="mb-1">{{ $user['email'] ?? '-' }}</p>
                <small class="text-muted">Role: {{ $user['role'] ?? 'Karyawan' }}</small>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <form method="POST" action="#">
            @csrf
            <div class="row mt-1 g-5">
                <div class="col-md-6 form-control-validation">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="nama" name="nama"
                            value="{{ $user['nama'] ?? '' }}" />
                        <label for="nama">Nama</label>
                    </div>
                </div>

                <div class="col-md-6 form-control-validation">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="email" id="email" name="email"
                            value="{{ $user['email'] ?? '' }}" />
                        <label for="email">Email</label>
                    </div>
                </div>

                <div class="col-md-6 form-control-validation">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="no_hp" name="no_hp"
                            value="{{ $user['no_hp'] ?? '' }}" />
                        <label for="no_hp">No HP</label>
                    </div>
                </div>

                <div class="col-md-6 form-control-validation">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="alamat" name="alamat"
                            value="{{ $user['alamat'] ?? '' }}" />
                        <label for="alamat">Alamat</label>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Simpan</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection
