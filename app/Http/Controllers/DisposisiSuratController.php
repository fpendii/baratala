<?php

namespace App\Http\Controllers;

use App\Models\DisposisiSurat;
use App\Models\Pengguna;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\WhatsAppHelper;

class DisposisiSuratController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::with(['disposisi.userTujuan'])
            ->where('status', 'didisposisikan')
            ->orderByDesc('tanggal_terima')
            ->paginate(10);

        return view('disposisi.index', compact('suratMasuk'));
    }

    public function create($suratId)
    {
        $surat = SuratMasuk::findOrFail($suratId);

        $users = Pengguna::where('id', '!=', Auth::id())->get();

        // 🔥 Ambil user yang sudah pernah didisposisi
        $checkedUsers = DisposisiSurat::where('surat_masuk_id', $suratId)
            ->pluck('user_tujuan_id')
            ->toArray();

        // 🔥 Ambil catatan terakhir disposisi
        $lastCatatan = DisposisiSurat::where('surat_masuk_id', $suratId)
            ->latest('created_at')
            ->value('catatan');

        return view('disposisi.create', compact(
            'surat',
            'users',
            'checkedUsers',
            'lastCatatan'
        ));
    }


    /**
     * Simpan disposisi (multi user)
     */
    public function store(Request $request, $suratId)
    {
        $request->validate([
            'user_tujuan_id'   => 'required|array|min:1',
            'user_tujuan_id.*' => 'exists:pengguna,id',
            'catatan'          => 'nullable|string',
        ]);

        foreach ($request->user_tujuan_id as $userId) {

            $disposisi = DisposisiSurat::firstOrCreate(
                [
                    'surat_masuk_id' => $suratId,
                    'user_tujuan_id' => $userId,
                ],
                [
                    'direktur_id'       => Auth::id(),
                    'catatan'           => $request->catatan,
                    'tanggal_disposisi' => now()->toDateString(),
                    'status_baca'       => 'belum',
                ]
            );

            // 🔔 KIRIM WA HANYA JIKA DISPOSISI BARU
            if ($disposisi->wasRecentlyCreated) {

                $userTujuan = Pengguna::find($userId);
                $surat      = SuratMasuk::find($suratId);
                $pengirim   = Auth::user()->name ?? 'Direktur';

                WhatsAppHelper::sendDisposisiNotification(
                    $userTujuan->no_hp ?? null,
                    $surat,
                    $request->catatan ?? '-',
                    $pengirim
                );
            }
        }


        // 🔥 UPDATE STATUS SURAT MASUK
        SuratMasuk::where('id', $suratId)->update([
            'status' => 'didisposisikan'
        ]);

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat berhasil didisposisikan');
    }

    public function disposisiSaya()
    {
        $disposisi = DisposisiSurat::with('suratMasuk')
            ->where('user_tujuan_id', Auth::id())
            ->orderBy('tanggal_disposisi', 'desc')
            ->paginate(10);

        // 🔔 AUTO UPDATE STATUS BACA
        DisposisiSurat::where('user_tujuan_id', Auth::id())
            ->where('status_baca', 'belum')
            ->update(['status_baca' => 'sudah']);

        return view('disposisi.saya', compact('disposisi'));
    }


    /**
     * Inbox disposisi untuk user login
     */
    public function inbox()
    {
        $disposisi = DisposisiSurat::with(['suratMasuk', 'direktur'])
            ->where('user_tujuan_id', Auth::id())
            ->orderByDesc('tanggal_disposisi')
            ->paginate(10);

        return view('disposisi.inbox', compact('disposisi'));
    }

    /**
     * Tandai disposisi sudah dibaca
     */
    public function markAsRead($id)
    {
        $disposisi = DisposisiSurat::where('id', $id)
            ->where('user_tujuan_id', Auth::id())
            ->firstOrFail();

        $disposisi->update([
            'status_baca' => 'sudah'
        ]);

        return back()->with('success', 'Disposisi ditandai sudah dibaca');
    }
}
