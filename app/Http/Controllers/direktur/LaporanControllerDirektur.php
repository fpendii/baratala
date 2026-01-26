<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanControllerDirektur extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('pengguna')->latest()->get();
        return view('direktur.laporan.index', compact('laporans'));
    }

    public function TampilanTabel()
    {
        $laporans = Laporan::with('pengguna')->latest()->get();
        return view('direktur.laporan.tabel', compact('laporans'));
    }

    public function show($id)
    {
        $laporan = Laporan::with(['pengguna', 'laporanKeuangan'])->findOrFail($id);
        return view('direktur.laporan.detail', compact('laporan'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'keputusan' => 'nullable|string',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->keputusan = $request->keputusan;
        $laporan->save();

        return redirect()->route('direktur.laporan.show', $laporan->id)
            ->with('success', 'Keputusan berhasil diperbarui!');
    }

    
}
