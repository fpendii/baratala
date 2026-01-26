<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan | BARATALA TUNTUNG PANDANG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">
    <meta name="google-site-verification" content="rYvl0NTI-KizBCuwVeQ2IGDpv2imbjtio-E1FTFzSaA" />

    <link rel="icon" href="image/logo.png" type="image/x-icon">

    <style>
        /* ====================== Root & Basic Styles ====================== */
        :root {
            --primary-color: #0d1a26;
            --secondary-color: #343a40;
            --accent-color: #ffc107;
            --light-bg: #f5f5f5;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        /* ====================== Top Bar ====================== */
        .top-bar {
            background-color: var(--primary-color);
            color: #dee2e6;
            font-size: 0.8rem;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ====================== Navbar ====================== */
        .navbar-custom {
            background-color: rgba(13, 26, 38, 0.98);
            border-bottom: 3px solid var(--accent-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        .navbar-brand {
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-link {
            transition: color 0.3s;
            font-weight: 600;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .cta-button {
            background-color: var(--accent-color);
            color: var(--primary-color);
            font-weight: 700;
            padding: 12px 45px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.5);
        }

        .cta-button:hover {
            background-color: #e0a800;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.7);
        }

        /* ====================== Hero ====================== */
        .hero-title {
            font-size: 4.8rem;
            font-weight: 900 !important;
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 1.0);
        }

        .carousel-inner div.d-block {
            background-blend-mode: multiply;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .carousel-caption {
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* ====================== Sections ====================== */
        .section-title {
            font-weight: 900;
            text-transform: uppercase;
            border-left: 8px solid var(--accent-color);
            padding-left: 20px;
            margin-bottom: 4rem;
            font-size: 2.2rem;
        }

        .vision-section {
            background-color: var(--primary-color);
            color: white;
            padding-bottom: 70px !important;
        }

        .vision-section .lead {
            font-weight: 400;
        }

        /* ====================== Tim ====================== */
        .team-member img {
            filter: grayscale(80%);
            transition: filter 0.3s, transform 0.3s;
            border: 5px solid var(--accent-color);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .team-member:hover img {
            filter: grayscale(0%);
            transform: scale(1.05);
            border-color: white;
        }

        .team-member .text-warning {
            font-weight: 700 !important;
        }

        /* ====================== Statistik Box ====================== */
        .stat-box {
            background-color: white;
            border: none;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            border-top: 5px solid var(--accent-color);
        }

        .stat-box h3 {
            font-size: 2.5rem;
            color: var(--primary-color);
            font-weight: 800;
        }

        /* ====================== List Group ====================== */
        .list-group-item {
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 0 !important;
        }

        .legalitas-list-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1rem;
            color: var(--secondary-color);
        }

        .legalitas-list-item i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: var(--accent-color);
        }

        .legalitas-docs {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* ====================== Footer ====================== */
        .footer-custom {
            background-color: var(--primary-color);
        }

        /* ====================== Responsif ====================== */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 3rem;
            }

            .section-title {
                font-size: 1.75rem;
                margin-bottom: 3rem;
            }
        }

        @media (max-width: 576px) {
            /* Top Bar */
            .top-bar {
                display: block !important;
                font-size: 0.7rem;
                text-align: center;
            }

            /* Navbar */
            .navbar-custom .navbar-brand {
                font-size: 1rem;
            }

            .navbar-nav .nav-link {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }

            .cta-button {
                padding: 10px 25px;
                font-size: 0.8rem;
            }

            /* Hero */
            #hero-slider .carousel-inner {
                height: 70vh;
            }

            .hero-title {
                font-size: 2rem;
                text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
            }

            .carousel-caption p.lead {
                font-size: 0.9rem;
            }

            /* Section */
            .section-title {
                font-size: 1.5rem;
                padding-left: 15px;
                margin-bottom: 2rem;
            }

            .vision-section .row,
            #tentang .row,
            #operasi .row,
            #legalitas .row {
                flex-direction: column;
            }

            .vision-section .col-md-6,
            #tentang .col-lg-6,
            #operasi .col-lg-6,
            #legalitas .col-lg-6 {
                max-width: 100%;
            }

            /* Tim */
            .team-member img {
                width: 120px !important;
                height: 120px !important;
            }

            .team-member h4 {
                font-size: 0.9rem;
            }

            .team-member p {
                font-size: 0.8rem;
            }

            /* List Dokumen */
            #operasi .list-group {
                overflow-x: auto;
            }

            #operasi .list-group-item {
                white-space: nowrap;
            }

            /* Footer */
            .footer-custom .row > div {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="top-bar d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-start">
                    <i class="bi bi-clock me-2"></i> Jam Operasional Kantor: Senin - Jumat, 08:00 - 16:00 WITA
                </div>
                <div class="col-md-6 text-end">
                    <a href="mailto:perusda.baratala@yahoo.com" class="text-white-50 text-decoration-none mx-2"><i
                            class="bi bi-envelope-fill me-1"></i>perusda.baratala@yahoo.com</a>
                    <a href="tel:051223445" class="text-white-50 text-decoration-none mx-2"><i
                            class="bi bi-phone-fill me-1"></i> Telp/Fax. 0512-23445</a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom p-3" style="z-index: 1050;">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="#">
                <img src="{{ asset('image/logo.png') }}" style="width: 40px" alt="Baratala Logo"> BARATALA TUNTUNG
                PANDANG
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#operasi">Operasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#legalitas">Legalitas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tim">Manajemen</a></li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-warning fw-bold ms-lg-3">Masuk</a>
            </div>
        </div>
    </nav>

    <section id="hero-slider" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner" style="height: 90vh;">
            <div class="carousel-item active" data-bs-interval="5000">
                <div class="d-block w-100"
                    style="background-image: url('{{ asset('image/mining-slide-1.jpg') }}'); background-size: cover; background-position: center; height: 90vh;">
                </div>
                <div class="carousel-caption">
                    <div class="col-lg-12">
                        <h1 class="hero-title fw-bolder mb-3 text-white">
                            BARATALA <span class="text-warning">TUNTUNG PANDANG</span>
                        </h1>
                        <p class="lead mb-5 fs-4 text-white-75">PERUSAHAAN DAERAH DENGAN KOMITMEN PENINGKATAN EKONOMI
                            DAERAH,SUMBERDAYA MANUSIA DAN PENGELOLOAAN LINGKUNGAN BERKELANJUTAN</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="5000">
                <div class="d-block w-100"
                    style="background-image: url('{{ asset('image/mining-slide-2.jpg') }}'); background-size: cover; background-position: center; height: 90vh;">
                </div>
                <div class="carousel-caption">
                    <div class="col-lg-12">
                        <h1 class="hero-title fw-bolder mb-3 text-white">FOKUS PADA <span
                                class="text-warning">INOVASI</span> DAN KUALITAS</h1>
                        <p class="lead mb-5 fs-4 text-white-75">PERUSAHAAN DAERAH DENGAN KOMITMEN PENINGKATAN EKONOMI
                            DAERAH,SUMBERDAYA MANUSIA DAN PENGELOLOAAN LINGKUNGAN BERKELANJUTAN</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="5000">
                <div class="d-block w-100"
                    style="background-image: url('{{ asset('image/mining-slide-3.jpg') }}'); background-size: cover; background-position: center; height: 90vh;">
                </div>
                <div class="carousel-caption">
                    <div class="col-lg-12">
                        <h1 class="hero-title fw-bolder mb-3 text-white">PRIORITAS <span class="text-warning">K3L</span>
                            DAN SDM</h1>
                        <p class="lead mb-5 fs-4 text-white-75">PERUSAHAAN DAERAH DENGAN KOMITMEN PENINGKATAN EKONOMI
                            DAERAH,SUMBERDAYA MANUSIA DAN PENGELOLOAAN LINGKUNGAN BERKELANJUTAN</p>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#hero-slider" data-bs-slide="prev"><span
                class="carousel-control-prev-icon" aria-hidden="true"></span><span
                class="visually-hidden">Previous</span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#hero-slider" data-bs-slide="next"><span
                class="carousel-control-next-icon" aria-hidden="true"></span><span
                class="visually-hidden">Next</span></button>
    </section>

    <section id="tentang" class="py-5 bg-white">
        <div class="container">
            <h2 class="section-title text-dark">Profil & Sejarah Perusahaan</h2>
            <div class="row align-items-center">
                <div class="mb-4 mb-lg-0">
                    <h3 class="h3 fw-bold text-dark mb-4">Tentang Perusahaan.</h3>
                    <p class="text-secondary">
                        Perusahaan Daerah PD Baratala Tuntung Pandang Kabupaten Tanah Laut, Provinsi Kalimantan
                        Selatan, didirikan pada tanggal 25 Agustus 2005 berdasarkan Peraturan Daerah Kabupaten Tanah
                        Laut Nomor 6 Tahun 2005 tentang Pembentukan Perusahaan Daerah Baratala Tuntung Pandang. Tujuan
                        didirikannya perusahaan ini adalah untuk mengelola dan memanfaatkan potensi daerah serta menjadi
                        sarana pengembangan perekonomian dalam rangka mendukung pembangunan daerah.
                    </p>

                    <p class="text-secondary">
                        Perusahaan ini memiliki IUP Operasi Produksi (IUP-OP) KW 06,
                        Berdasarkan Keputusan Kepala Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Provinsi
                        Kalimantan Selatan Nomor 503/3-IUP.OP4/DSDPMPTSP/IV/II/2020, PD Baratala Tuntung Pandang
                        ditetapkan sebagai pemegang IUP Operasi Produksi (IUP-OP) KW 06, yang berlokasi di Desa Sungai
                        Bakar dan Pemalongan, Kecamatan Bajuin, Kabupaten Tanah Laut. IUP ini diterbitkan sebagai
                        perpanjangan atas izin yang telah ada sebelumnya.
                    </p>

                    <p class="text-secondary">
                        Selain itu, perusahaan juga memiliki IUP Operasi Produksi (IUP-OP) KW 07, berdasarkan Surat
                        Keputusan Nomor 503/11.5-41/DMPTSP/XII/2020, dengan wilayah izin berada di Desa Tampang dan
                        Sumber Mulya, Kecamatan Pelaihari, Kabupaten Tanah Laut. IUP ini juga diterbitkan sebagai
                        perpanjangan dari izin sebelumnya.
                    </p>

                    <h4 class="h3 fw-bold text-dark mb-4 text-warning">
                        Kami mengintegrasikan teknologi pintar, kepatuhan ketat, dan pemberdayaan komunitas lokal untuk
                        mencapai keberhasilan jangka panjang.
                    </h4>
                    {{-- <a href="#investor" class="btn btn-outline-dark mt-3 fw-bold">Lihat Tata Kelola Perusahaan</a> --}}
                </div>
                {{-- <div class="col-lg-6">
                    <h4 class="fw-bold text-dark mb-3"><i class="bi bi-map-fill text-warning me-2"></i> Peta Wilayah
                        Operasi Utama</h4>
                    <div style="height: 400px;">
                        <img src="/image/map-tambang-baratala.png" alt="Map Tambang Baratala"
                            class="img-fluid rounded-3 shadow-lg"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    {{-- <section id="statistik" class="py-5" style="background-color: var(--secondary-color);">
        <div class="container text-center">
            <h2 class="section-title text-white">Statistik Kunci Kami</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-box">
                        <h3 class="mb-1 text-warning">20 Juta+</h3>
                        <p class="text-dark fw-bold small mb-0">Ton Material Diekstraksi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h3 class="mb-1 text-warning">99.8%</h3>
                        <p class="text-dark fw-bold small mb-0">Tingkat Kepatuhan K3L</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h3 class="mb-1 text-warning">400+</h3>
                        <p class="text-dark fw-bold small mb-0">Hektar Lahan Direklamasi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h3 class="mb-1 text-warning">100%</h3>
                        <p class="text-dark fw-bold small mb-0">Kepemilikan Alat Berat Terbaru</p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <section class="py-5 vision-section">
        <div class="container">
            <h2 class="section-title text-white">Visi & Misi Perusahaan</h2>
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h3 class="text-warning fw-bold mb-3"><i class="bi bi-eye-fill me-2"></i> Visi</h3>
                    <p class="lead text-white-75">melaksanakan Pengelolaan dan Pemanfaatan Potensi Daerah disamping
                        sebagai
                        sarana pengembangan dalam Pembangunan Daerah.</p>
                </div>
                <div class="col-md-6">
                    <h3 class="text-warning fw-bold mb-3"><i class="bi bi-flag-fill me-2"></i> Misi</h3>
                    <ul class="list-unstyled text-white-75">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> Memberikan
                            lapangan
                            pekerjaan.
                        </li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> Memberikan
                            Pendapatan
                            Asli Daerah
                            (PAD)</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i> Memberikan
                            percepatan
                            perkembangan perekonomian daerah bagi masyarakat
                            Tanah Laut umumnya dan masyarakat sekitar tambang, khususnya dengan cara
                            penambangan yang berwawasan lingkungan dan efisiensi yang optimal.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="legalitas" class="py-5 bg-white">
        <div class="container">
            <h2 class="section-title text-dark">Legalitas & Sertifikasi</h2>
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="h4 fw-bold text-dark mb-4 border-bottom border-warning border-3 pb-2">Komitmen Kepatuhan
                    </h3>
                    <p class="text-secondary">
                        PD. Baratala Tuntung Pandang telah resmi mendapatkan **Surat Izin Usaha Perdagangan (SIUP)**
                        dari Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPM & PTSP) Kabupaten Tanah Laut.
                    </p>
                    <p class="text-secondary fw-bold small">
                        SIUP ini diterbitkan dengan Nomor **503/SK/DPMPTSP/IV/II/2020** dan memberikan
                        kewenangan kepada perusahaan untuk menjalankan kegiatan di bidang perdagangan barang dan jasa,
                        dengan ruang lingkup usaha yang mencakup:
                    </p>
                    <ul class="list-unstyled mt-4">
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Kepatuhan &
                                Kepemilikan Resmi</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Kredibilitas &
                                Etika Resmi Usaha</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Lisensi &
                                Pengakuan Legal</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Kelengkapan
                                Dokumen Serta Perpajakan</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Lisensi dan
                                Sertifikasi Profesional</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Jaminan Legal dan
                                Standar Operasi</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Landasan Hukum &
                                Sertifikasi Kompetensi</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Otoritas Resmi &
                                Keandalan Usaha</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Kepatuhan dengan
                                Lingkup</span></li>
                        <li class="legalitas-list-item"><i class="bi bi-check-circle-fill"></i><span>Komitmen
                                Kepatuhan & Standar Hukum</span></li>
                    </ul>
                </div>

                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-dark mb-4 border-bottom border-warning border-3 pb-2">Otoritas
                        Perdagangan</h3>
                    <p class="text-secondary">
                        Dengan adanya SIUP ini, PD. Baratala Tuntung Pandang sah secara hukum untuk melaksanakan
                        kegiatan usaha perdagangan di seluruh wilayah Republik Indonesia, selama perusahaan tetap aktif
                        menjalankan usahanya sesuai ketentuan yang berlaku.
                    </p>

                    <div class="legalitas-docs mt-4">
                        <h5 class="fw-bold mb-3 text-dark">Tinjauan Dokumen Utama</h5>
                        <div class="row g-2 text-center">

                            <div class="col-4">
                                <i class="bi bi-file-earmark-text-fill fs-1 text-dark"></i>
                                <p class="small fw-bold mt-1 mb-0">Akta Pendirian</p>
                                <p class="small text-muted">
                                    <a href="pdf/perda.pdf" download>Perda No. 6 Tahun 2005</a>
                                </p>
                            </div>

                            <div class="col-4">
                                <i class="bi bi-file-earmark-text-fill fs-1 text-dark"></i>
                                <p class="small fw-bold mt-1 mb-0">IUP-OP KW 06</p>
                                <p class="small text-muted">
                                    <a href="/pdf/iup_kw06.pdf" download>SK No.
                                        503/3-IUP.OP4/DSDPMPTSP/IV/II/2020</a>
                                </p>
                            </div>

                            <div class="col-4 mt-2">
                                <i class="bi bi-file-earmark-text-fill fs-1 text-dark"></i>
                                <p class="small fw-bold mt-1 mb-0">IUP-OP KW 07</p>
                                <p class="small text-muted">
                                    <a href="/pdf/iup_kw07.pdf" download>SK No.
                                        503/11.5-41/DMPTSP/XII/2020</a>
                                </p>
                            </div>

                        </div>
                    </div>

                    <p class="small fst-italic text-end text-muted mt-3">*Dokumen legalitas lengkap dapat diakses oleh
                        pihak yang berkepentingan.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="tim" class="py-5 bg-white">

        <div class="container text-center">

            <h2 class="section-title text-dark">Dewan Direksi & Tim Kepemimpinan</h2>

            <div class="row g-4 justify-content-center">


                <div class="col-md-3 col-sm-6">

                    <div class="team-member">

                        <img src="{{ asset('image/bupati.png') }}" class="img-fluid rounded-circle mb-3 shadow"
                            style="width: 150px; height: 150px; object-fit: cover;" alt="Bupati Tanah Laut">

                        <h4 class="h6 fw-bold mb-0 text-dark">H. Rahmat Trianto</h4>

                        <p class="small text-warning mb-2">Bupati Tanah Laut</p>

                    </div>

                </div>

                  <div class="col-md-3 col-sm-6">

                    <div class="team-member">

                        <img src="{{ asset('image/direktur.jpg') }}" class="img-fluid rounded-circle mb-3 shadow"
                            style="width: 150px; height: 150px; object-fit: cover;" alt="Direktur">

                        <h4 class="h6 fw-bold mb-0 text-dark">Ihsanudin</h4>

                        <p class="small text-warning mb-2">Direktur Baratala</p>

                    </div>

                </div>


                <div class="col-md-3 col-sm-6">

                    <div class="team-member">

                        <img src="{{ asset('image/nurhidayati.jpg') }}" class="img-fluid rounded-circle mb-3 shadow"
                            style="width: 150px; height: 150px; object-fit: cover;" alt="Badan Pengawas">

                        <h4 class="h6 fw-bold mb-0 text-dark">Nurhidayati</h4>

                        <p class="small text-warning mb-2">Badan Pengawas</p>

                    </div>

                </div>

            </div>



        </div>

    </section>

    <section id="operasi" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-dark">Kegiatan Operasional Inti</h2>

            <div class="row g-4 mb-5 align-items-center">
                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-dark border-start border-warning border-3 ps-3 mb-3">
                        <i class="bi bi-geo-alt-fill feature-icon me-2"></i> Lokasi Tambang
                    </h3>
                    <p class="text-secondary mb-3">
                        Wilayah tambang PD Baratala Tuntung Pandang terletak di kawasan yang memiliki potensi bijih besi
                        dengan kandungan yang tergolong baik. Bahan galian pada wilayah Izin Usaha Pertambangan Operasi
                        Produksi (IUP-OP) PD Baratala termasuk kategori bijih besi ore body, yang menjadi komoditas
                        utama dalam kegiatan operasi produksi.
                    </p>
                    <h3 class="h4 fw-bold text-dark border-start border-warning border-3 ps-3 mb-3">
                        <i class="bi bi-tools feature-icon me-2"></i>Metode Penambangan (Open Pit)
                    </h3>
                    <p class="text-secondary mb-3">
                        Metode penambangan yang diterapkan pada wilayah prospek tersebut adalah Tambang Terbuka (Open
                        Pit). Pemilihan metode ini disesuaikan dengan karakteristik endapan bijih besi di lokasi,
                        sehingga proses penambangan dapat dilakukan secara efektif, aman, dan memenuhi ketentuan teknis
                        serta standar lingkungan yang berlaku.
                    </p>
                </div>

                <div class="col-lg-6">
                    <h3 class="h4 fw-bold text-dark border-start border-warning border-3 ps-3 mb-3">
                        📄 Daftar Dokumen Lokasi Tambang
                    </h3>

                    <div class="list-group shadow-sm">
                        @php
                            $dokumens = [
                                ['name' => 'Peta Kesampaian Daerah KW 06', 'file' => 'peta_kesampaian_daerah_kw06.pdf'],
                                ['name' => 'Citra 06', 'file' => 'CITRA 06.pdf'],
                                ['name' => 'Peta Geologi', 'file' => 'PETA GEOLOGI.pdf'],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN CITRA SATELIT TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN CITRA SATELIT TAHUN 2026.pdf',
                                ],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN KAWASAN HUTAN TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN KAWASAN HUTAN TAHUN 2026.pdf',
                                ],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN PIT 1&3 KAWASAN HUTAN TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN PIT 1&3 KAWASAN HUTAN TAHUN 2026.pdf',
                                ],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN PIT 3 CITRA Satelit TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN PIT 3 CITRA Satelit TAHUN 2026.pdf',
                                ],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN PIT 3 KAWASAN HUTAN TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN PIT 3 KAWASAN HUTAN TAHUN 2026.pdf',
                                ],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN PIT 3 TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN PIT 3 TAHUN 2026.pdf',
                                ],
                                [
                                    'name' => 'PETA RENCANA PENAMBANGAN TAHUN 2026',
                                    'file' => 'PETA RENCANA PENAMBANGAN TAHUN 2026.pdf',
                                ],
                                ['name' => 'Peta Topografi', 'file' => 'Peta Topografi.pdf'],
                            ];
                        @endphp
                        @foreach ($dokumens as $dok)
                            <a href="{{ asset('peta/' . $dok['file']) }}" target="_blank"
                                class="list-group-item list-group-item-action d-flex align-items-center">

                                <i class="bi bi-filetype-pdf text-danger me-2 fs-5"></i>
                                <span>{{ $dok['name'] }}</span>

                            </a>
                        @endforeach

                    </div>
                </div>



            </div>

            <div class="row g-5">
                <div class="col-md-6">
                    <h3 class="h4 fw-bold text-dark border-bottom border-warning pb-2 mb-4">
                        <i class="bi bi-list-check feature-icon me-2"></i> Kegiatan
                    </h3>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-start mb-3">
                            <i class="bi bi-check-circle-fill text-warning me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Eksplorasi & Penilaian Sumber Daya</h6>
                                <p class="small text-secondary mb-0">Tahap awal penentuan kualitas dan kuantitas
                                    cadangan.
                                </p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="bi bi-check-circle-fill text-warning me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Eksploitasi, Pengolahan dan Pemurnian</h6>
                                <p class="small text-secondary mb-0">Penambangan garuk bebas dan merencanakan metode
                                    blasting serta Ekstraksi material Bijih Besi dan proses pengolahan untuk mencapai
                                    spesifikasi produk.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start mb-3">
                            <i class="bi bi-check-circle-fill text-warning me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">Pengangkutan & Pengapalan</h6>
                                <p class="small text-secondary mb-0">Kegiatan pengangkutan material dari area tambang
                                    menuju terminal pengiriman.</p>
                            </div>

                        </li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h3 class="h4 fw-bold text-dark border-bottom border-warning pb-2 mb-4">
                        <i class="bi bi-tools feature-icon me-2"></i> Fasilitas Pendukung
                    </h3>
                    <ul class="list-group list-group-flush shadow-sm rounded-3 overflow-hidden">
                        <li class="list-group-item bg-light d-flex justify-content-between align-items-center">
                            Jalan Tambang & Akses Logistik
                            <span class="badge bg-warning text-dark"><i class="bi bi-arrow-right-short"></i></span>
                        </li>
                        <li class="list-group-item bg-light d-flex justify-content-between align-items-center">
                            Stockpile penumpukan, Pengolahan dan Pemurnian
                            <span class="badge bg-warning text-dark"><i class="bi bi-arrow-right-short"></i></span>
                        </li>
                        <li class="list-group-item bg-light d-flex justify-content-between align-items-center">
                            Peralatan Berat dan Kendaraan Operasional
                            <span class="badge bg-warning text-dark"><i class="bi bi-arrow-right-short"></i></span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- <div class="text-center mt-5">
                <a href="#bisnis-prospek" class="btn btn-lg cta-button text-decoration-none">
                    Lihat Prospek Bisnis & Produk Kami
                </a>
            </div> --}}
        </div>
    </section>



    <footer class="footer-custom text-white pt-5 pb-3" style="background-color:#0d1a26;">
        <div class="container">
            <div class="row">

                <!-- Tentang Perusahaan -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3 text-warning">BARATALA TUNTUNG PANDANG</h5>
                    <p class="small text-white-50">
                        Perusahaan Daerah yang berkomitmen pada peningkatan ekonomi daerah, pemberdayaan SDM,
                        dan pengelolaan lingkungan yang berkelanjutan.
                    </p>
                </div>

                <!-- Navigasi Cepat -->
                <div class="col-md-2 mb-4">
                    <h6 class="fw-bold mb-3 text-warning">Navigasi</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#tentang" class="text-white-50 text-decoration-none">Tentang</a></li>
                        <li><a href="#operasi" class="text-white-50 text-decoration-none">Operasi</a></li>
                        <li><a href="#legalitas" class="text-white-50 text-decoration-none">Legalitas</a></li>
                        <li><a href="#tim" class="text-white-50 text-decoration-none">Manajemen</a></li>
                    </ul>
                </div>

                <!-- Legal / Informasi Resmi -->
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3 text-warning">Informasi Legal</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li>Perda No. 6 Tahun 2005</li>
                        <li>SIUP Resmi • IUP-OP KW 06 & KW 07</li>
                        <li>Dokumen legal dapat diakses oleh pihak berkepentingan.</li>
                    </ul>
                </div>

                <!-- Kontak Singkat -->
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3 text-warning">Info Perusahaan</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li><i class="bi bi-geo-alt-fill me-2"></i>Tanah Laut, Kalimantan Selatan</li>
                        <li><i class="bi bi-envelope-fill me-2"></i>perusda.baratala@yahoo.com</li>
                        <li><i class="bi bi-telephone-fill me-2"></i>0512-23445</li>
                        <li><i class="bi bi-clock me-2"></i>Senin–Jumat, 08.00–16.00 WITA</li>
                    </ul>
                </div>

                <!-- Media Sosial -->
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3 text-warning">Media Sosial</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li> <div class="col-auto"><a href="https://www.instagram.com/pdbaratala.tuntungpandang/" class="text-white-50"><i
                            class="bi bi-instagram fs-4"></i></a></div></li>
                    </ul>
                </div>

            </div>

            <div class="border-top border-secondary mt-3 pt-3">
                <p class="text-center small text-white-50 mb-0">
                    © {{ date('Y') }} BARATALA TUNTUNG PANDANG. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
