<?php

namespace App\Exports;

use App\Models\LaporanKeuangan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    // Mengimplementasikan FromView
    public function view(): View
    {
        // Logika filtering tetap sama
        $filterTanggal = $this->filters['filter_tanggal'] ?? null;
        $filterJenis = $this->filters['filter_jenis'] ?? null;
        $filterPengguna = $this->filters['filter_pengguna'] ?? null;

        $laporanKeuangan = LaporanKeuangan::with('pengguna')
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        if ($filterTanggal) {
            $date = Carbon::parse($filterTanggal);
            $laporanKeuangan->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month);
        }

        if ($filterJenis) {
            $laporanKeuangan->where('jenis', $filterJenis);
        }

        if ($filterPengguna) {
            // Asumsi: Jika filter_pengguna adalah ID pengguna
            $laporanKeuangan->where('id_pengguna', $filterPengguna);
        }

        // Data dikirim ke view Blade
        return view('exports.laporan-keuangan', [
            'laporanKeuangan' => $laporanKeuangan->get()
        ]);
    }

    // Mengimplementasikan WithTitle
    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    // Mengimplementasikan WithStyles
    // app/Exports/LaporanKeuanganExport.php

    public function styles(Worksheet $sheet)
    {
        // Sisipkan 2 baris kosong di atas
        $sheet->insertNewRowBefore(1, 2);

        // Tulis judul di A1:H1
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');

        // Merge A1:H1
        $sheet->mergeCells('A1:H1');

        // Styling judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Header berada di baris 3
        $headerRow = 3;

        // Styling header
        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // hijau header
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);

        // Data mulai baris 4
        $dataStartRow = 4;
        $lastRow = $sheet->getHighestRow();

        // Format nominal di kolom E
        $sheet->getStyle('E' . $dataStartRow . ':E' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // Border untuk semua data
        $sheet->getStyle('A' . $dataStartRow . ':H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Mengembalikan array kosong karena styling sudah diterapkan manual
        return [];
    }
}
