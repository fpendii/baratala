<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use App\Models\LaporanJobdesk;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class LaporanJobdeskControllerDirektur extends Controller
{
    public function index()
    {
        // ambil semua laporan jobdesk beserta relasi pengguna & jobdesk
        $laporans = LaporanJobdesk::with(['pengguna', 'jobdesk'])->latest()->get();

        return view('direktur.laporan_jobdesk.index', compact('laporans'));
    }

    public function detail($id)
    {
        $laporan = LaporanJobdesk::with(['pengguna', 'jobdesk'])->findOrFail($id);
        // dd($laporan);
        return view('direktur.laporan_jobdesk.detail', compact('laporan'));
    }

    public function JobdeskKaryawan()
    {
        // Ambil semua pengguna yang punya laporan jobdesk
        $karyawans = Pengguna::whereHas('laporanJobdesks')
            ->withCount('laporanJobdesks') // kalau mau tahu jumlah laporannya juga
            ->get();
        // dd($karyawans);
        return view('direktur.laporan_jobdesk.jobdesk_karyawan', compact('karyawans'));
    }



    public function detailJobdeskKaryawan($id)
    {
        $laporans = LaporanJobdesk::with('jobdesk')
            ->where('id_pengguna', $id)
            ->latest()
            ->get();

        return view('direktur.laporan_jobdesk.detail_jobdesk_karyawan', compact('laporans'));
    }
}
