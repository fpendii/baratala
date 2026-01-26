<!DOCTYPE html>
<html>

<head>
    <title>Bukti Persetujuan Laporan Keuangan</title>
    <style>
        /* Mengatur font dasar dan margin halaman */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 0.5in;
            line-height: 1.5;
        }

        /* HEADER BARU UNTUK KOP SURAT */
        .letterhead {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .letterhead-content {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .letterhead-logo {
            width: 15%;
            /* Sesuaikan ukuran logo */
            float: left;
            margin-right: 15px;
        }

        .letterhead-logo img {
            width: 80px;
            /* Ukuran gambar logo */
            height: auto;
        }

        .letterhead-details {
            float: left;
            text-align: center;
            width: 80%;
        }

        .letterhead-details h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }

        .letterhead-details h3 {
            margin: 3px 0;
            font-size: 11pt;
            font-weight: normal;
        }

        .letterhead-details p {
            margin: 0;
            font-size: 10pt;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Judul Dokumen Utama */
        .main-title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 14pt;
            font-weight: bold;
        }

        /* Detail Transaksi (Tabel Utama) */
        .transaction-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .transaction-details th,
        .transaction-details td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        .transaction-details th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .transaction-details .total-row td {
            font-weight: bold;
            background-color: #e6f7ff;
            /* Warna terang untuk total */
        }

        /* Data Info (Header atas) */
        .info-data {
            margin-bottom: 20px;
            font-size: 11pt;
        }

        .info-data table {
            width: 100%;
            border: none;
        }

        .info-data td {
            padding: 2px 0;
            border: none;
        }

        .info-data .label {
            width: 30%;
            font-weight: bold;
        }

        /* Bagian Tanda Tangan */
        .ttd-section {
            margin-top: 60px;
            width: 100%;
            border-collapse: collapse;
        }

        .ttd-section td {
            width: 50%;
            text-align: center;
            padding: 10px;
            border: none;
        }

        .ttd-names {
            position: relative;
            height: 100px;
            /* Memberi ruang untuk gambar ttd dan nama */
        }

        /* Style untuk gambar tanda tangan */
        .ttd-signature {
            position: absolute;
            bottom: 30px;
            /* Sesuaikan posisi vertikal ttd */
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .ttd-signature img {
            width: 100px;
            /* Ukuran tanda tangan */
            opacity: 0.8;
            /* Sedikit transparan agar terlihat seperti stempel/ttd asli */
        }

        .ttd-status {
            font-style: italic;
            font-size: 10pt;
        }

        .ttd-divider {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 80%;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 9pt;
            border-top: 1px dotted #ccc;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    {{-- KOP SURAT BARU --}}
    <div class="letterhead clearfix">
        <div class="letterhead-logo">
            <img alt="Logo perusahaan" src="{{ $logoBase64 ?? '' }}" style="width:120px;">

        </div>
        <div class="letterhead-details">
            <h1>PERUSAHAAN DAERAH BARATALA TUNTUNG PANDANG</h1>
            <h3>Jl. Abadi No. 03 Pelaihari 70811 Telp/Fax. 0512-23445</h3>
            <p>e-mail : perusda_baratala@yahoo.com</p>
        </div>
    </div>

    {{-- JUDUL DOKUMEN UTAMA --}}
    <div class="main-title">
        BUKTI PERSETUJUAN KEUANGAN - TRANSAKSI
        {{ strtoupper($laporan->jenis == 'uang_masuk' ? 'PENDAPATAN' : 'PENGELUARAN') }}
    </div>

    {{-- INFORMASI LAPORAN --}}
    <div class="info-data">
        <table>
            <tr>
                <td class="label" style="width: 25%">Nomor Transaksi</td>
                <td style="width: 5%">:</td>
                <td>{{ $laporan->id }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Transaksi</td>
                <td>:</td>
                <td>{{ ucwords(str_replace('_', ' ', $laporan->jenis)) }} (Metode:
                    {{ ucfirst($laporan->jenis_uang ?? '-') }})</td>
            </tr>
            <tr>
                <td class="label">Tanggal Diajukan</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Persetujuan</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($tanggal_persetujuan)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- DETAIL TRANSAKSI --}}
    <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 15px; font-size: 12pt;">Detail
        Transaksi</h3>
    <table class="transaction-details">
        <thead>
            <tr>
                <th>Keperluan / Deskripsi</th>
                <th style="width: 20%;">Penerima / Sumber</th>
                <th style="width: 20%;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $laporan->keperluan }}</td>
                <td>{{ $laporan->penerima ?? '-' }}</td>
                <td style="text-align: right;">{{ number_format($laporan->nominal, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="text-align: right; font-weight: bold;">TOTAL
                    {{ strtoupper($laporan->jenis == 'uang_masuk' ? 'DITERIMA' : 'DIKELUARKAN') }}</td>
                <td style="text-align: right;">Rp. {{ number_format($laporan->nominal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- CATATAN PERSETUJUAN --}}
    {{-- <div style="padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; margin-bottom: 30px;">
        <p style="margin: 0; font-weight: bold;">Catatan / Persetujuan Direktur:</p>
        <p style="margin: 5px 0 0 0; font-style: italic; white-space: pre-wrap;">{{ $laporan->catatan ?? 'Tidak ada catatan khusus.' }}</p>
        <p style="margin-top: 15px; font-weight: bold; color: green;">STATUS: DISETUJUI</p>
    </div> --}}

    {{-- BAGIAN TANDA TANGAN --}}
    <table class="ttd-section">
        <thead>
            <tr>
                <td>Disiapkan oleh,</td>
                <td>Disetujui oleh,</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="ttd-names">
                    <p class="ttd-divider"></p>
                    <p>{{ $laporan->pengguna->nama ?? 'Nama Karyawan' }}</p>
                    <p class="ttd-status">Staf/Bagian Keuangan</p>
                </td>
                <td class="ttd-names">
                    {{-- Tanda Tangan Direktur --}}
                    <div class="ttd-signature">
                        @if (!empty($ttdDirBase64))
                            <img src="{{ $ttdDirBase64 }}" style="width:150px;">
                        @endif

                    </div>

                    <p class="ttd-divider"></p>
                    <p>{{ $direktur }}</p>
                    <p class="ttd-status">Direktur Utama</p>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}
    </div>

</body>

</html>
