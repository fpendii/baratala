@extends('layout.app')

@section('title', 'Tambah Surat Keluar Baru')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Surat Keluar /</span> Tambah Data
        </h4>
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary">
            <i class="icon-base ri ri-arrow-left-line icon-18px me-1"></i> Kembali
        </a>
    </div>

    {{-- ALERT VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali input Anda.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">Formulir Tambah Surat Keluar</h5>
        <div class="card-body">

            {{-- Tambah Surat --}}
            <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nomor Surat --}}
                <div class="mb-3">
                    <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>

                    <input type="text" id="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror"
                        name="nomor_surat" value="{{ old('nomor_surat', $nomor_surat ?? '') }}" required>


                    @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <input type="hidden" name="jenis_surat" value="{{ old('jenis_surat', $jenis ?? 'umum') }}">

                {{-- Tanggal Surat --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" id="tgl_surat" class="form-control @error('tgl_surat') is-invalid @enderror"
                        name="tgl_surat" value="{{ old('tgl_surat') }}" required>

                    @error('tgl_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tujuan --}}
                <div class="mb-3">
                    <label class="form-label">Tujuan Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tujuan') is-invalid @enderror" name="tujuan"
                        value="{{ old('tujuan') }}" placeholder="Contoh: Direktur Utama PT. Contoh Sejahtera" required>
                    @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Perihal --}}
                <div class="mb-3">
                    <label class="form-label">Perihal Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('perihal') is-invalid @enderror" name="perihal"
                        value="{{ old('perihal') }}" placeholder="Contoh: Permohonan Izin Keramaian" required>
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Metode Pembuatan Surat</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode" id="metode_editor" value="editor"
                            checked>
                        <label class="form-check-label">Tulis Surat di Form</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode" id="metode_upload" value="upload">
                        <label class="form-check-label">Upload Dokumen (PDF / DOC / DOCX)</label>
                    </div>
                </div>

                <div class="mb-3" id="group_upload" style="display:none;">
                    <label class="form-label">Upload Dokumen Surat</label>
                    <input type="file" class="form-control" name="file_surat" accept=".pdf,.doc,.docx">
                </div>



                {{-- KONTEN SURAT (DENGAN TINYMCE) --}}
                <div class="mb-4" id="group_editor">
                    <label class="form-label">Isi Surat Lengkap <span class="text-danger">*</span></label>
                    {{-- GANTI NAMA INPUT DARI 'perihal' MENJADI 'konten_surat' --}}

                    <textarea class="form-control @error('konten_surat') is-invalid @enderror" rows="15" name="konten_surat"
                        id="isi_surat">
        {{-- (Template HTML Anda di sini) --}}
        {{ old(
            'konten_surat',
            '
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

                                                                <td style="width: 15%;">
                                                                    </td>
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
                                                <p >
                                                    (Isi Surat... )
                                                </p>
                                                <br>
                                                Demikian surat permohonan ini disampaikan. Atas perhatian dan kebijaksanaan yang diberikan disampaikan terima kasih.<br><br>

                                                <div style="margin-top: 30px;">
                                                    Pelaihari, 3 Desember 2025<br>
                                                    Hormat kami,<br><br><br><br>
                                                    <p style="margin-bottom: 0;"><strong>H.Ihsanudin SH., MM</strong></p>
                                                    Direktur Utama
                                                </div>
                                                ',
        ) }}
    </textarea>
                    @error('konten_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                </div>

                {{-- Buttons --}}
                <button type="submit" class="btn btn-primary me-2">
                    <i class="icon-base ri ri-save-line icon-18px me-1"></i> Simpan Surat
                </button>

                <button type="reset" class="btn btn-outline-secondary">Reset Formulir</button>

            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

                            // Update tanggal surat
                            let tgl = new Date(tglInput.value);
                            if (!isNaN(tgl)) {
                                const options = {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                };
                                const tglFormatted = tgl.toLocaleDateString('id-ID', options);
                                content = content.replace(/Pelaihari,\s*.*?<br>/,
                                    `Pelaihari, ${tglFormatted}<br>`);
                            }

                            // Update nomor surat
                            const nomorVal = nomorInput.value || '';
                            content = content.replace(/<span id="nomor_surat_text">.*?<\/span>/,
                                `<span id="nomor_surat_text">${nomorVal}</span>`);

                            editor.setContent(content);
                        }

                        // Update saat halaman load
                        updateIsiSurat();

                        // Update saat user mengubah tanggal atau nomor surat
                        tglInput.addEventListener('change', updateIsiSurat);
                        nomorInput.addEventListener('input', updateIsiSurat);
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            const editorGroup = document.getElementById('group_editor');
            const uploadGroup = document.getElementById('group_upload');

            function toggleMetode() {
                if (document.getElementById('metode_upload').checked) {
                    editorGroup.style.display = 'none';
                    uploadGroup.style.display = 'block';
                } else {
                    editorGroup.style.display = 'block';
                    uploadGroup.style.display = 'none';
                }
            }

            document.getElementById('metode_editor').addEventListener('change', toggleMetode);
            document.getElementById('metode_upload').addEventListener('change', toggleMetode);

            toggleMetode();
        });

        function toggleMetode() {
            if (document.getElementById('metode_upload').checked) {
                editorGroup.style.display = 'none';
                uploadGroup.style.display = 'block';

                document.getElementById('isi_surat').removeAttribute('required');
            } else {
                editorGroup.style.display = 'block';
                uploadGroup.style.display = 'none';

                document.getElementById('isi_surat').setAttribute('required', 'required');
            }
        }
    </script>
@endpush
