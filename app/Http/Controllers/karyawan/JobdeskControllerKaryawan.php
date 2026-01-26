<?php

namespace App\Http\Controllers\karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Jobdesk;
use App\Models\LaporanJobdesk;

class JobdeskControllerKaryawan extends Controller
{
    /**
     * Menampilkan daftar semua laporan jobdesk milik karyawan yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the ID of the logged-in user
        $userId = Auth::id();

        // Retrieve all jobdesk reports for the current user,
        // with eager loading for the 'jobdesk' relationship
        $laporanJobdesks = LaporanJobdesk::where('id_pengguna', $userId)
            ->with('jobdesk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('karyawan.jobdesk.index', compact('laporanJobdesks'));
    }

    /**
     * Menampilkan form untuk membuat laporan jobdesk baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all available jobdesks for the employee to choose from
        $jobdesks = Jobdesk::all();
        // dd(123);
        return view('karyawan.jobdesk.create', compact('jobdesks'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'id_jobdesk' => 'required|exists:jobdesk,id',
            'deskripsi' => 'required|string',
            'status' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:2048',
        ]);

        // 2. Proses upload lampiran (jika ada file yang diunggah)
        if ($request->hasFile('lampiran')) {
            // Simpan file di folder 'jobdesk_lampiran' di disk 'public'
            $filePath = $request->file('lampiran')->store('jobdesk_lampiran', 'public');
            $validatedData['lampiran'] = $filePath;
        }

        // Add the user ID of the logged-in user
        $validatedData['id_pengguna'] = Auth::id();
        // $validatedData['id_pengguna'] = 1;

        // Create a new LaporanJobdesk record in the database
        LaporanJobdesk::create($validatedData);

        // Redirect back to the index page with a success message
        return redirect()->route('karyawan.jobdesk.index')->with('success', 'Laporan jobdesk berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail laporan jobdesk.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Find the report by ID and ensure it belongs to the logged-in user
        $laporan = LaporanJobdesk::where('id', $id)
            ->where('id_pengguna', Auth::id())
            ->with('jobdesk')
            ->firstOrFail();

        return view('karyawan.jobdesk.detail', compact('laporan'));
    }

    /**
     * Menampilkan form untuk mengedit laporan jobdesk.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Find the report by ID and ensure it belongs to the logged-in user
        $laporan = LaporanJobdesk::where('id', $id)
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();

        // Get all available jobdesks for the form dropdown
        $jobdesks = Jobdesk::all();

        return view('karyawan.jobdesk.edit', compact('laporan', 'jobdesks'));
    }

    /**
     * Memperbarui laporan jobdesk yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Find the report to be updated and check user ownership
        $laporan = LaporanJobdesk::where('id', $id)
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();

        // Validate the input data
        $validatedData = $request->validate([
            'id_jobdesk' => 'required|exists:jobdesk,id',
            'deskripsi' => 'required|string',
            'status' => 'required|string|in:dikerjakan,tidak-dikerjakan',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('lampiran')) {
            // Delete the old file if it exists
            if ($laporan->lampiran) {
                Storage::delete('public/' . $laporan->lampiran);
            }
            // Store the new file
            $lampiranPath = $request->file('lampiran')->store('public/');
            $validatedData['lampiran'] = str_replace('public/', '', $lampiranPath);
        }

        // Update the report in the database
        $laporan->update($validatedData);

        return redirect()->route('karyawan.jobdesk.index')->with('success', 'Laporan jobdesk berhasil diperbarui.');
    }

    /**
     * Menghapus laporan jobdesk dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the report to be deleted and check user ownership
        $laporan = LaporanJobdesk::where('id', $id)
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();

        // Delete the associated file from storage if it exists
        if ($laporan->lampiran) {
            Storage::delete('public/' . $laporan->lampiran);
        }

        // Delete the report from the database
        $laporan->delete();

        return redirect()->route('karyawan.jobdesk.index')->with('success', 'Laporan jobdesk berhasil dihapus.');
    }
}
