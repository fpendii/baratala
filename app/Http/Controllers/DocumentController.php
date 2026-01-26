<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Pengguna;
use Illuminate\Support\Str;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    // READ: Menampilkan daftar dokumen dengan versi terbarunya
    public function index(Request $request)
    {
        // Mulai query dengan eager loading agar performa cepat
        $query = Document::with(['category', 'tags', 'latestVersion']);

        // Filter berdasarkan Judul atau Nomor Dokumen
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('doc_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // FILTER TAGS (Multiple Select)
        if ($request->filled('filter_tags')) {
            $tags = $request->filter_tags; // Ini berupa array ID
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('tags.id', $tags);
            });
        }

        // Ambil data dengan pagination
        $documents = $query->latest()->paginate(10)->withQueryString();

        // Data untuk dropdown filter
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('documents.index', compact('documents', 'categories', 'tags'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        return view('documents.create', compact('categories', 'tags'));
    }

    // PROSES SIMPAN DATA
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file'        => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:10240',
            'doc_number'  => 'nullable|unique:documents,doc_number',
        ], [
            'title.required'       => 'Judul dokumen wajib diisi.',
            'category_id.required' => 'Pilih kategori dokumen.',
            'file.required'        => 'File dokumen belum dipilih.',
            'file.max'             => 'Ukuran file maksimal 10MB.',
            'doc_number.unique'    => 'Nomor dokumen sudah terdaftar.',
        ]);

        try {
            DB::beginTransaction();

            // 2. Inisialisasi Document
            $document = new Document();
            $document->id = (string) Str::uuid();
            $document->title = $request->title;
            $document->description = $request->description;
            $document->category_id = $request->category_id;
            $document->user_id = Auth::id() ?? 1;
            $document->is_confidential = $request->has('is_confidential') ? 1 : 0;
            $document->status = 'active';

            // 3. Logika Nomor Otomatis
            if ($request->filled('doc_number')) {
                $document->doc_number = $request->doc_number;
            } else {
                $count = Document::whereYear('created_at', date('Y'))->count();
                $document->doc_number = 'PD/' . date('Y/m/') . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            }
            $document->save();

            // 4. Simpan Tags
            if ($request->has('tags')) {
                $document->tags()->sync($request->tags);
            }

            // 5. Handle File
            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            // 6. Simpan ke Tabel Document_versions
            DocumentVersion::create([
                'id'             => (string) Str::uuid(),
                'document_id'    => $document->id,
                'version_number' => 1,
                'file_path'      => $path,
                'file_name'      => $file->getClientOriginalName(),
                'file_extension' => $file->getClientOriginalExtension(),
                'file_size'      => $file->getSize(),
            ]);

            DB::commit();
            $document->load('tags', 'category');
            // 🔔 Kirim Notifikasi WA setelah commit berhasil
            $this->sendWhatsAppNotification($document);

            return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diarsipkan: ' . $document->doc_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function storeRevision(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);

            // 1. Cari nomor versi terakhir
            $lastVersion = DocumentVersion::where('document_id', $id)
                ->max('version_number') ?? 1;

            $newVersionNumber = $lastVersion + 1;

            // 2. Handle File Baru
            $file = $request->file('file');
            // Simpan ke folder v2, v3, dst agar rapi
            $path = $file->store('documents', 'public');

            // 3. Simpan ke tabel document_versions
            DocumentVersion::create([
                'id' => (string) Str::uuid(),
                'document_id' => $document->id,
                'version_number' => $newVersionNumber,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_extension' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ]);

            // 4. Update timestamp di tabel documents agar muncul di urutan teratas (tgl update)
            $document->touch();

            DB::commit();
            return back()->with('success', "Revisi v{$newVersionNumber} berhasil diunggah.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal upload revisi: ' . $e->getMessage());
        }
    }

    private function sendWhatsAppNotification($document)
    {
        // === Salam otomatis ===
        $hour = now()->format('H');
        $salam = match (true) {
            $hour >= 5 && $hour < 12  => 'Selamat Pagi',
            $hour >= 12 && $hour < 17 => 'Selamat Siang',
            $hour >= 17 && $hour < 20 => 'Selamat Sore',
            default                   => 'Selamat Malam',
        };

        // 1. Data Tags & Kategori
        $tagsNames = $document->tags->pluck('name')->implode(', ');
        $displayTags = $tagsNames ?: '-';
        $categoryName = $document->category->name ?? 'Tanpa Kategori';

        // 2. Susun Pesan
        $message =
            "👋 *{$salam}*\n\n" .
            "📁 *PEMBERITAHUAN ARSIP DOKUMEN BARU*\n" .
            "━━━━━━━━━━━━━━━━━━\n\n" .
            "📝 *Judul:* {$document->title}\n" .
            "🔢 *Nomor Dokumen:* {$document->doc_number}\n" .
            "📂 *Kategori:* {$categoryName}\n" .
            "🏷️ *Tags:* {$displayTags}\n" .
            "👤 *Pengunggah:* " . (Auth::user()->nama ?? 'Sistem') . "\n" .
            "📅 *Tanggal:* " . now()->format('d/m/Y H:i') . " WIB\n\n" .
            "Silakan cek sistem Baratala untuk detail lebih lanjut.\n\n" .
            "_Notifikasi otomatis dari Sistem Baratala_";

        try {
            // --- KIRIM KE DIREKTUR (Personal) ---
            $direktur = \App\Models\Pengguna::where('role', 'direktur')->first();
            if ($direktur && $direktur->no_hp) {
                \App\Helpers\WhatsAppHelper::send($direktur->no_hp, $message);
            }

            // --- KIRIM KE GRUP (Group) ---
            // Ganti 'ID_GRUP_ANDA' dengan JID/ID grup dari provider WA Anda (misal: 12036302xxxx@g.us)
            $groupId = env('WA_GROUP_ID');
            \App\Helpers\WhatsAppHelper::send($groupId, $message);
        } catch (\Exception $e) {
            Log::error('Error WA Notification: ' . $e->getMessage());
        }
    }

    public function download($versionId)
    {
        // Cari versi spesifik yang diminta
        $version = DocumentVersion::findOrFail($versionId);

        // Cek keberadaan file di storage/app/public/
        if (!Storage::disk('public')->exists($version->file_path)) {

            return back()->with('error', 'Maaf, file fisik tidak ditemukan di server.');
        }

        $path = storage_path('app/public/' . $version->file_path);

        // Download dengan nama asli saat diupload
        return response()->download($path, $version->file_name);
    }

    // UPDATE: Menambah Versi Baru (Revisi)
    public function uploadRevision(Request $request, Document $document)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,docx,jpg,png|max:10240',
        ]);

        return DB::transaction(function () use ($request, $document) {
            // Cari nomor versi terakhir
            $lastVersion = $document->versions()->max('version_number');

            $file = $request->file('file');
            $path = $file->store('documents');

            $document->versions()->create([
                'version_number' => $lastVersion + 1,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_extension' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ]);

            return redirect()->back()->with('success', 'Versi baru berhasil diupload.');
        });
    }

    // DELETE: Soft Delete (Hanya menandai sebagai terhapus)
    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->back()->with('success', 'Dokumen dipindahkan ke tempat sampah.');
    }

    public function edit($id)
    {
        $document = Document::with('tags')->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all(); // Untuk pilihan di select2

        return view('documents.edit', compact('document', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'doc_number'  => 'required|unique:documents,doc_number,' . $id,
        ]);

        try {
            DB::beginTransaction();

            $document = Document::findOrFail($id);
            $document->title = $request->title;
            $document->description = $request->description;
            $document->category_id = $request->category_id;
            $document->doc_number = $request->doc_number;
            $document->is_confidential = $request->has('is_confidential') ? 1 : 0;
            $document->save();

            // Update Tags
            if ($request->has('tags')) {
                $document->tags()->sync($request->tags);
            } else {
                $document->tags()->detach();
            }

            DB::commit();
            return redirect()->route('documents.index')->with('success', 'Data dokumen berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }
}
