@extends('layout.app')

@section('title', 'Edit Surat Keluar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold py-3 mb-0">
        <span class="text-muted fw-light">Surat Keluar /</span> Edit Data
    </h4>
    <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary">
        <i class="icon-base ri ri-arrow-left-line icon-18px me-1"></i> Kembali
    </a>
</div>

{{-- ALERT VALIDASI --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <strong>Terjadi Kesalahan!</strong> Mohon periksa input Anda.
    <ul class="mt-2 mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <h5 class="card-header">Formulir Edit Surat Keluar</h5>
    <div class="card-body">
        <form action="{{ route('surat-keluar.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nomor Surat --}}
            <div class="mb-3">
                <label class="form-label">Nomor Surat</label>
                <input type="text" id="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror"
                    name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required>
                @error('nomor_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jenis Surat --}}
            <input type="hidden" name="jenis_surat" value="{{ $surat->jenis_surat }}">

            {{-- Tanggal Surat --}}
            <div class="mb-3">
                <label class="form-label">Tanggal Surat</label>
                <input type="date" id="tgl_surat" class="form-control @error('tgl_surat') is-invalid @enderror"
                    name="tgl_surat"
                    value="{{ old('tgl_surat', \Carbon\Carbon::parse($surat->tgl_surat)->format('Y-m-d')) }}" required>
                @error('tgl_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tujuan --}}
            <div class="mb-3">
                <label class="form-label">Tujuan Surat</label>
                <input type="text" class="form-control @error('tujuan') is-invalid @enderror" name="tujuan"
                    value="{{ old('tujuan', $surat->tujuan) }}" required>
                @error('tujuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Perihal --}}
            <div class="mb-3">
                <label class="form-label">Perihal Surat</label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" name="perihal"
                    value="{{ old('perihal', $surat->perihal) }}" required>
                @error('perihal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Metode Pembuatan Surat --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Metode Pembuatan Surat</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metode" id="metode_editor" value="form"
                        {{ (old('konten_surat', $surat->konten_surat) != '' || old('metode') == 'form') ? 'checked' : '' }}
                        onclick="toggleMetode()">
                    <label class="form-check-label" for="metode_editor">Tulis Surat di Form</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metode" id="metode_upload" value="upload"
                        {{ (old('konten_surat', $surat->konten_surat) == '' && old('dok_surat', $surat->dok_surat) == null) || old('metode') == 'upload' ? 'checked' : '' }}
                        onclick="toggleMetode()">
                    <label class="form-check-label" for="metode_upload">Upload Dokumen (PDF / DOC / DOCX)</label>
                </div>
            </div>

            {{-- Isi Surat Lengkap --}}
            <div class="mb-4" id="isiSuratContainer"
                style="display: {{ ($surat->konten_surat != '' || old('konten_surat') != '') ? 'block' : 'none' }};">
                <label class="form-label">Isi Surat Lengkap <span class="text-danger">*</span></label>
                <textarea class="form-control @error('konten_surat') is-invalid @enderror" rows="20" name="konten_surat"
                    id="isi_surat" required>{{ old(
                        'konten_surat',
                        !empty($surat->konten_surat)
                            ? $surat->konten_surat
                            : '
<table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
<tr>
<td style="width: 15%; text-align: left; vertical-align: middle;">
<img src="../image/logo.png" alt="Logo Perusahaan" style="width: 80px; height: auto;">
</td>
<td style="width: 70%; text-align: center; vertical-align: middle; line-height: 1.2;">
<p style="margin: 0; font-weight: bold; font-size: 14pt;">
PERUSAHAAN DAERAH BARATALA TUNTUNG PANDANG
</p>
<p style="margin: 0; font-size: 10pt;">
Jl. Abadi No. 03 Pelaihari 70811 Telp/Fax. 0512-23445
</p>
<p style="margin: 0; font-size: 10pt;">
e-mail : perusda_baratala@yahoo.com
</p>
</td>
<td style="width: 15%;"></td>
</tr>
<tr>
<td colspan="3" style="padding-top: 10px;">
<hr style="border: none; border-top: 2px solid black; margin: 0;">
</td>
</tr>
</table>

<div style="text-align: center; margin-bottom: 20px;">
<strong>SURAT PERMOHONAN</strong><br>
Nomor : <span id="nomor_surat_text"></span>
</div>

Kepada Yth.<br>
<strong>Direktorat Jendral Minerba</strong><br>
<strong>Kementerian Energi dan Sumber Daya Mineral</strong><br>
<strong>Republik Indonesia</strong><br>
Di –<br>
&nbsp; &nbsp; &nbsp; &nbsp; Tempat<br>
<br>

Dengan hormat,<br><br>
<p>(Isi Surat... )</p>
<br>
Demikian surat permohonan ini disampaikan. Atas perhatian dan kebijaksanaan yang diberikan disampaikan terima kasih.<br><br>

<div style="margin-top: 30px;">
Pelaihari, 3 Desember 2025<br>
Hormat kami,<br><br><br><br>
<p style="margin-bottom: 0;"><strong>H.Ihsanudin SH., MM</strong></p>
Direktur Utama
</div>
',
                    ) }}</textarea>
                @error('konten_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Upload Dokumen --}}
            <div class="mb-4" id="uploadDokumenContainer"
                style="display: {{ ($surat->konten_surat == '' && $surat->dok_surat == null) ? 'block' : 'none' }};">
                <label class="form-label">Upload Dokumen Surat (PDF / DOC / DOCX)</label>
                <input type="file" class="form-control" name="lampiran" accept=".pdf,.doc,.docx">
                @if ($surat->dok_surat)
                    <small class="text-muted">File saat ini: <a href="{{ asset('storage/' . $surat->dok_surat) }}" target="_blank">Lihat Dokumen</a></small>
                @endif
            </div>

            {{-- Buttons --}}
            <button type="submit" class="btn btn-primary me-2">
                <i class="ri ri-save-line me-1"></i> Update Surat
            </button>
            <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // === Toggle Metode Input ===
    window.toggleMetode = function() {
        const metode = document.querySelector('input[name="metode"]:checked').value;
        document.getElementById('isiSuratContainer').style.display = metode === 'form' ? 'block' : 'none';
        document.getElementById('uploadDokumenContainer').style.display = metode === 'upload' ? 'block' : 'none';
    }

    // Jalankan toggleMetode() saat load untuk set tampilan default
    toggleMetode();

    // === TinyMCE Setup ===
    tinymce.init({
        selector: '#isi_surat',
        plugins: 'advlist autolink lists link image charmap print preview anchor code paste',
        toolbar_mode: 'floating',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist | code',
        height: 600,
        menubar: false,
        content_style: 'body { font-family:Times New Roman,Times,serif; font-size:12pt; line-height: 1.5; margin: 30px; }',
        extended_valid_elements: "div[style],p[style],hr[style],strong[style],span[id]",
        setup: function(editor) {
            editor.on('init', function() {
                const tglInput = document.getElementById('tgl_surat');
                const nomorInput = document.getElementById('nomor_surat');

                function updateIsiSurat() {
                    let content = editor.getContent();
                    let tgl = new Date(tglInput.value);
                    if (!isNaN(tgl)) {
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        const tglFormatted = tgl.toLocaleDateString('id-ID', options);
                        content = content.replace(/Pelaihari,\s*.*?<br>/, `Pelaihari, ${tglFormatted}<br>`);
                    }
                    const nomorVal = nomorInput.value || '';
                    content = content.replace(/<span id="nomor_surat_text">.*?<\/span>/, `<span id="nomor_surat_text">${nomorVal}</span>`);
                    editor.setContent(content);
                }

                updateIsiSurat();
                tglInput.addEventListener('change', updateIsiSurat);
                nomorInput.addEventListener('input', updateIsiSurat);
            });
        }
    });
});
</script>
@endpush
