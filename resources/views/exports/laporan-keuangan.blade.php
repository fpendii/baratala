<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keperluan</th>
            <th>Jenis Transaksi</th>
            <th>Jenis Uang</th>
            <th>Nominal</th>
            <th>Penerima</th>
            <th>Status Persetujuan</th>
            <th>Catatan Direktur</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporanKeuangan as $laporan)
            <tr>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->format('Y-m-d') }}</td>
                <td>{{ $laporan->keperluan }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $laporan->jenis)) }}</td>
                <td>{{ ucfirst($laporan->jenis_uang) }}</td>
                <td>{{ $laporan->nominal }}</td>
                <td>{{ $laporan->penerima ?? $laporan->pengguna->nama ?? '-' }}</td>
                <td>{{ ucfirst($laporan->status_persetujuan) }}</td>
                <td>{{ $laporan->catatan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
