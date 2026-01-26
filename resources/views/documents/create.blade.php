@extends('layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Tambah Arsip Dokumen</h4>
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Laporan Keuangan Q1">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="p-3 bg-light border rounded">
                            <label class="form-label fw-bold">Upload File <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Dokumen</label>
                            <input type="text" name="doc_number" class="form-control @error('doc_number') is-invalid @enderror" value="{{ old('doc_number') }}" placeholder="Kosongkan untuk otomatis">
                            @error('doc_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="category_id" id="category_select" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCat">+</button>
                            </div>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tags</label>
                            <div class="input-group">
                                <select name="tags[]" id="tag_select" class="form-select" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTag">+</button>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_confidential" id="is_confidential" value="1">
                            <label class="form-check-label fw-bold" for="is_confidential">Dokumen Rahasia</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">Simpan Arsip</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modalCat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Kategori Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><input type="text" id="new_cat_name" class="form-control" placeholder="Nama Kategori"></div>
            <div class="modal-footer"><button type="button" class="btn btn-primary" onclick="addCategory()">Simpan</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTag" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Tag Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><input type="text" id="new_tag_name" class="form-control" placeholder="Nama Tag"></div>
            <div class="modal-footer"><button type="button" class="btn btn-primary" onclick="addTag()">Simpan</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function addCategory() {
    let name = $('#new_cat_name').val();
    $.post("{{ route('categories.store_ajax') }}", { _token: "{{ csrf_token() }}", name: name }, function(data) {
        $('#category_select').append(new Option(data.name, data.id, true, true));
        $('#modalCat').modal('hide');
        $('#new_cat_name').val('');
    }).fail(() => alert('Gagal menambah kategori'));
}

function addTag() {
    let name = $('#new_tag_name').val();
    $.post("{{ route('tags.store_ajax') }}", { _token: "{{ csrf_token() }}", name: name }, function(data) {
        $('#tag_select').append(new Option(data.name, data.id, true, true));
        $('#modalTag').modal('hide');
        $('#new_tag_name').val('');
    }).fail(() => alert('Gagal menambah tag'));
}
</script>
@endpush
