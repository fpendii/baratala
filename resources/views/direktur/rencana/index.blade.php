@extends('layout.direktur-template')

@section('title', 'Rencana Kerja Saya')

@section('content')


    {{-- Alert Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Daftar Rencana Kerja --}}
    <div class="card">
        <div class="row">
            <div class="col">
                <h5 class="card-header">Rencana Kerja</h5>
            </div>
            <div class="col">
                <div class="card-header text-end">
                    <a href="{{ url('direktur/rencana/create') }}" class="btn btn-primary">
                        <i class="icon-base ri ri-add-line icon-18px me-1"></i> Tambah
                    </a>
                </div>
            </div>
        </div>


        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Rencana Kerja & Detail</th>

                        <th style="width: 5%;">Prioritas</th>
                        <th style="width: 5%;">Status</th>
                        <th style="width: 10%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($tugas as $index => $item)
                        <tr>
                            <td><span class="fw-bold">{{ $index + 1 }}</span></td>
                            <td>
                                <strong class="text-dark">
                                    <a href="{{ route('direktur.rencana.show', $item->id) }}"
                                        class="text-decoration-none text-primary"
                                        title="Lihat Detail: {{ $item->judul_rencana }}">
                                        {{ $item->judul_rencana }}
                                    </a>
                                </strong>


                            </td>


                            <td>
                                @php
                                    $prioritasClass = [
                                        'tinggi' => 'danger',
                                        'sedang' => 'warning',
                                        'rendah' => 'secondary',
                                    ];
                                    $currentPrioritas = strtolower($item->prioritas);
                                @endphp
                                <span class="badge bg-{{ $prioritasClass[$currentPrioritas] ?? 'light' }}">
                                    {{ ucfirst($currentPrioritas) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'selesai' => 'success',
                                        'sedang dikerjakan' => 'primary',
                                        'direview' => 'info',
                                        'ditunda' => 'secondary',
                                        'belum dikerjakan' => 'warning',
                                    ];
                                    $currentStatus = strtolower($item->status);
                                @endphp
                                <span class="badge bg-label-{{ $statusClass[$currentStatus] ?? 'secondary' }}">
                                    {{ ucfirst($currentStatus) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        {{-- Tombol Lihat Detail --}}
                                        <a class="dropdown-item" href="{{ route('direktur.rencana.show', $item->id) }}">
                                            <i class="icon-base ri ri-eye-line icon-18px me-1"></i>
                                            Lihat Detail
                                        </a>
                                        {{-- Tombol Hapus dengan konfirmasi --}}
                                        <form action="{{ route('direktur.rencana.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus rencana kerja: {{ $item->judul_rencana }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bx bx-calendar-plus bx-lg d-block mb-3 text-muted"></i>
                                <h6 class="mb-1 text-dark">Belum ada Rencana Kerja yang Anda buat.</h6>
                                <p class="text-muted">Ayo mulai catat rencana kerja Anda untuk minggu/bulan ini!</p>
                                <a href="{{ route('direktur.rencana.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bx bx-plus me-1"></i> Buat Rencana
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
