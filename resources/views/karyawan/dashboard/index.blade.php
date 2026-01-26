@extends('layout.karyawan-template')

@section('title', 'Dashboard Karyawan')

@section('content')


    {{-- BAGIAN 1: UCAPAN SELAMAT DATANG (Elemen yang Anda minta agar tidak dihapus) --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-lg border-0">
                <div class="card-body p-4 p-md-5 text-center">
                    <h1 class="h3 mb-2">
                        Selamat Datang Kembali,
                        <span class="font-weight-bold">{{ Auth::user()->nama ?? 'Karyawan' }}!</span>
                        👋
                    </h1>
                    <p class="lead mb-0">
                        Ayo mulai hari Anda dengan penuh semangat.
                    </p>
                    <small class="text-white-50 mt-2 d-block">
                        Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: AKSES CEPAT KE FITUR UTAMA --}}
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="h5 mb-3 text-gray-800">Menu Utama</h4>
        </div>

        {{-- Tombol 1: Rencana Kerja --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/karyawan/rencana" class="card shadow-sm border-top-0 border-bottom-primary h-100 py-3 text-decoration-none card-hover-scale">
                <div class="card-body text-center">
                    <i class="ri ri-calendar-check-line ri-3x text-primary mb-3"></i>
                    <h5 class="card-title text-primary font-weight-bold mb-0">Rencana Kerja</h5>
                    <p class="text-muted small mt-1">Kelola dan laporkan pekerjaan harian Anda.</p>
                </div>
            </a>
        </div>

        {{-- Tombol 2: Surat Masuk --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/karyawan/surat-masuk" class="card shadow-sm border-top-0 border-bottom-success h-100 py-3 text-decoration-none card-hover-scale">
                <div class="card-body text-center">
                    <i class="ri ri-mail-line ri-3x text-success mb-3"></i>
                    <h5 class="card-title text-success font-weight-bold mb-0">Surat Masuk</h5>
                    <p class="text-muted small mt-1">Cek pengumuman dan surat resmi terbaru.</p>
                </div>
            </a>
        </div>

        {{-- Tombol 3: Keuangan (Hanya untuk Role 'keuangan') --}}
        @if (auth()->user()->role == 'keuangan')
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/karyawan/keuangan" class="card shadow-sm border-top-0 border-bottom-info h-100 py-3 text-decoration-none card-hover-scale">
                <div class="card-body text-center">
                    <i class="ri ri-wallet-3-line ri-3x text-info mb-3"></i>
                    <h5 class="card-title text-info font-weight-bold mb-0">Keuangan</h5>
                    <p class="text-muted small mt-1">Akses menu khusus untuk laporan keuangan.</p>
                </div>
            </a>
        </div>
        @endif

        {{-- Tombol 4: Laporan Jobdesk --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/karyawan/jobdesk" class="card shadow-sm border-top-0 border-bottom-warning h-100 py-3 text-decoration-none card-hover-scale">
                <div class="card-body text-center">
                    <i class="ri ri-file-list-3-line ri-3x text-warning mb-3"></i>
                    <h5 class="card-title text-warning font-weight-bold mb-0">Laporan Jobdesk</h5>
                    <p class="text-muted small mt-1">Lihat riwayat dan status laporan pekerjaan Anda.</p>
                </div>
            </a>
        </div>

        {{-- Tombol 5: Profil --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="/karyawan/profil" class="card shadow-sm border-top-0 border-bottom-secondary h-100 py-3 text-decoration-none card-hover-scale">
                <div class="card-body text-center">
                    <i class="ri ri-user-line ri-3x text-secondary mb-3"></i>
                    <h5 class="card-title text-secondary font-weight-bold mb-0">Profil</h5>
                    <p class="text-muted small mt-1">Perbarui data pribadi dan informasi akun.</p>
                </div>
            </a>
        </div>

    </div>

{{-- Untuk efek hover, jika menggunakan custom CSS --}}
<style>
.card-hover-scale:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
}
</style>
@endsection
