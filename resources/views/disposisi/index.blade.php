@extends('layout.app')

@section('title', 'Surat Sedang Didisposisikan')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Surat Sedang Didisposisikan</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">
                <i class="ri ri-arrow-left-line me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h5 class="card-header">Daftar Surat Didisposisikan</h5>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul Surat</th>
                        <th>Pengirim</th>
                        <th>Tanggal Terima</th>
                        <th>Didisposisikan Ke</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($suratMasuk as $index => $item)
                        <tr>
                            <td>{{ $suratMasuk->firstItem() + $index }}</td>

                            <td>
                                <strong>{{ $item->judul }}</strong><br>
                                <small class="text-muted">
                                    {{ $item->nomor_surat ?? '-' }}
                                </small>
                            </td>

                            <td>{{ $item->pengirim }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}
                            </td>

                            {{-- USER TUJUAN --}}
                            <td>
                                @foreach ($item->disposisi as $d)
                                    <span
                                        class="badge
        {{ $d->status_baca === 'sudah' ? 'bg-success' : 'bg-danger' }}
        mb-1">
                                        {{ $d->userTujuan->nama }}

                                        @if ($d->status_baca === 'sudah')
                                            <i class="ri ri-check-line ms-1"></i>
                                        @else
                                            <i class="ri ri-time-line ms-1"></i>
                                        @endif
                                    </span>
                                @endforeach

                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge bg-warning">
                                    Didisposisikan
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <a href="{{ route('disposisi.create', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ri ri-send-plane-line"></i> Disposisi Ulang
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada surat yang sedang didisposisikan
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{ $suratMasuk->links() }}
    </div>

@endsection
