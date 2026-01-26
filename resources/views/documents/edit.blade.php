@extends('layout.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Edit Detail Dokumen</h5>
        <a href="{{ route('documents.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('documents.update', $document->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor Dokumen</label>
                    <input type="text" name="doc_number" class="form-control" value="{{ $document->doc_number }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Judul Dokumen</label>
                    <input type="text" name="title" class="form-control" value="{{ $document->title }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $document->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tags</label>
                    <select name="tags[]" id="edit_tags" class="form-select" multiple>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, $document->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi / Keterangan</label>
                    <textarea name="description" class="form-control" rows="3">{{ $document->description }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#edit_tags').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
@endpush
@endsection
