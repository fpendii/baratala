<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jobdesk;
use App\Models\LaporanJobdesk;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Contracts\Queue\Job;

use function Laravel\Prompts\select;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSuratKeluarBulanIni = SuratKeluar::whereMonth('created_at', now()->month)->count();
        $totalSuratMasukBulanIni = SuratMasuk::whereMonth('created_at', now()->month)->count();
        $totalPengguna = Pengguna::count();

        $suratKeluarTerbaru = SuratKeluar::latest()->take(5)->get();
        $suratMasukTerbaru  = SuratMasuk::latest()->take(5)->get();
        $LaporanJobdeskTerbaru = LaporanJobdesk::join('jobdesk','laporan_jobdesk.id_jobdesk','=','jobdesk.id')
            ->join('pengguna','laporan_jobdesk.id_pengguna','=','pengguna.id')
            ->select('laporan_jobdesk.id','jobdesk.divisi','pengguna.nama')
            ->orderBy('laporan_jobdesk.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('totalSuratKeluarBulanIni', 'totalSuratMasukBulanIni', 'totalPengguna', 'suratKeluarTerbaru', 'suratMasukTerbaru', 'LaporanJobdeskTerbaru'));
    }
}
