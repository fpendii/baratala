@php
    $finalUrl = null;

    if ($surat->lampiran) {
        $fileName = basename($surat->lampiran);
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $fileUrl = route('surat.tampil', $fileName);

        $officeExt = ['doc','docx','xls','xlsx','ppt','pptx'];

        if (in_array($extension, $officeExt)) {
            $finalUrl = 'https://docs.google.com/viewer?url='
                . urlencode($fileUrl) . '&embedded=true';
        } else {
            $finalUrl = $fileUrl;
        }
    }
@endphp


@extends('layout.app')

@section('title', 'Disposisi Surat')

@section('content')

    <div class="mb-4">
        <h4 class="fw-bold py-3 mb-0">
            Disposisi Surat
        </h4>
    </div>

    {{-- ALERT --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong>Informasi Surat</strong>
        </div>

        <div class="card-body">
            <table class="table table-sm">
                <tr>
                    <th width="150">Judul</th>
                    <td>{{ $surat->judul }}</td>
                </tr>
                <tr>
                    <th>Nomor Surat</th>
                    <td>{{ $surat->nomor_surat ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Pengirim</th>
                    <td>{{ $surat->pengirim }}</td>
                </tr>
                <tr>
                    <th>Tanggal Terima</th>
                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_terima)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Prioritas</th>
                    <td>
                        @php
                            $map = ['tinggi' => 'danger', 'sedang' => 'warning', 'rendah' => 'secondary'];
                            $p = strtolower($surat->prioritas);
                        @endphp
                        <span class="badge bg-{{ $map[$p] }}">{{ ucfirst($p) }}</span>
                    </td>
                </tr>
                <tr>
    <th>Dokumen</th>
    <td>
        @if ($finalUrl)
            <a href="{{ $finalUrl }}" target="_blank" class="btn btn-sm btn-outline-info">
                <i class="ri ri-file-2-line"></i> Lihat Dokumen
            </a>
        @else
            <span class="text-muted">Tidak ada</span>
        @endif
    </td>
</tr>


            </table>

        </div>
    </div>

    {{-- FORM DISPOSISI --}}
    <div class="card mt-4">
        <div class="card-header">
            <strong>Form Disposisi</strong>
        </div>

        <form action="{{ route('disposisi.store', $surat->id) }}" method="POST">
            @csrf

            <div class="card-body">

                {{-- USER TUJUAN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Tujuan Disposisi
                    </label>

                    <div class="row">
                        @foreach ($users as $user)
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="user_tujuan_id[]"
                                        value="{{ $user->id }}" id="user{{ $user->id }}"
                                        {{ in_array($user->id, old('user_tujuan_id', $checkedUsers ?? [])) ? 'checked' : '' }}>

                                    <label class="form-check-label" for="user{{ $user->id }}">
                                        {{ $user->nama }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <small class="text-muted">
                        Pilih satu atau lebih pengguna yang akan menerima disposisi
                    </small>
                </div>


                {{-- CATATAN --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Catatan Disposisi
                    </label>
                    <textarea name="catatan" rows="4" class="form-control"
                        placeholder="Tuliskan instruksi atau catatan untuk penerima disposisi...">{{ old('catatan', $lastCatatan ?? '') }}</textarea>

                </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                    <i class="ri ri-arrow-left-line me-1"></i> Kembali
                </a>

                <button class="btn btn-primary">
                    <i class="ri ri-send-plane-line me-1"></i> Kirim Disposisi
                </button>
            </div>

        </form>
    </div>

@endsection
