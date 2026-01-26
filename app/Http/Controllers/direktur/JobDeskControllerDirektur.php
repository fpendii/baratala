<?php

namespace App\Http\Controllers\Direktur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jobdesk;

class JobDeskControllerDirektur extends Controller
{
    // Tampilkan semua jobdesk
    public function index()
    {
        $jobdesks = Jobdesk::all();
        return view('direktur.jobdesk.index', compact('jobdesks'));
    }

    // Form create
    public function create()
    {
        return view('direktur.jobdesk.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'judul_jobdesk' => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'divisi'        => 'required|string|max:255',
        ]);
        Jobdesk::create($request->all());

        return redirect()->route('direktur.jobdesk.index')
                         ->with('success', 'Jobdesk berhasil ditambahkan');
    }

    // Form edit
    public function edit($id)
    {
        $jobdesk = Jobdesk::findOrFail($id);
        return view('direktur.jobdesk.edit', compact('jobdesk'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_jobdesk' => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'divisi'        => 'required|string|max:255',
        ]);

        $jobdesk = Jobdesk::findOrFail($id);
        $jobdesk->update($request->all());

        return redirect()->route('direktur.jobdesk.index')
                         ->with('success', 'Jobdesk berhasil diperbarui');
    }

    // Hapus data
    public function destroy($id)
    {
        $jobdesk = Jobdesk::findOrFail($id);
        $jobdesk->delete();

        return redirect()->route('direktur.jobdesk.index')
                         ->with('success', 'Jobdesk berhasil dihapus');
    }
}
