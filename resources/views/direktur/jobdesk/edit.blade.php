@extends('layout.direktur-template')

@section('title', 'Edit Jobdesk')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Jobdesk</h5>
        <small class="text-body-secondary float-end">Form Edit Data Jobdesk</small>
    </div>
    <div class="card-body">
        <form action="{{ route('direktur.jobdesk.update', $jobdesk->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Jobdesk</label>
                <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ri ri-task-line"></i></span>
                        <input type="text" class="form-control" name="judul_jobdesk"
                            value="{{ $jobdesk->judul_jobdesk }}" required />
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Deskripsi</label>
                <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ri ri-file-text-line"></i></span>
                        <textarea class="form-control" name="deskripsi" required>{{ $jobdesk->deskripsi }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-2 col-form-label">Divisi</label>
                <div class="col-sm-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="icon-base ri ri-group-line"></i></span>
                        <select class="form-select" name="divisi" required>
    <option value="" disabled>Pilih Divisi</option>
    <option value="direktur" {{ $jobdesk->divisi == 'direktur' ? 'selected' : '' }}>Direktur</option>
    <option value="kepala teknik" {{ $jobdesk->divisi == 'kepala teknik' ? 'selected' : '' }}>Kepala Teknik</option>
    <option value="enginer" {{ $jobdesk->divisi == 'enginer' ? 'selected' : '' }}>Enginer</option>
    <option value="produksi" {{ $jobdesk->divisi == 'produksi' ? 'selected' : '' }}>Produksi</option>
    <option value="keuangan" {{ $jobdesk->divisi == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
</select>

                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-sm-10">
                    <a href="{{ url('/direktur/jobdesk') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
