<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppHelper
{
    /**
     * Kirim pesan WA ke target tertentu (nomor atau group ID).
     */
    public static function send($target, $message)
    {
        $apiKey = env('FONNTE_API_KEY');

        if (!$apiKey || $apiKey === 'dummy_key') {
            Log::info("WhatsApp not sent (no API key). Target: $target, Message: $message");
            return;
        }

        return Http::withHeaders([
            'Authorization' => $apiKey,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,  // bisa nomor WA (628xxxx) atau group ID (1203xxx@g.us)
            'message' => $message,
        ]);
    }

    /**
     * Kirim pesan WA ke grup yang disimpan di .env
     */
    public static function sendToGroup($message)
    {
        $groupId = env('FONNTE_GROUP_ID');

        if (!$groupId) {
            Log::info("WhatsApp group ID not set in .env");
            return;
        }

        return self::send($groupId, $message);
    }

    public static function sendSuratKeluarNotification($target, $request, $pembuat = 'Pengguna')
    {
        try {
            if (!$target) {
                Log::warning('WA NOT SENT: Nomor tujuan kosong');
                return false;
            }

            $token = env('FONNTE_API_KEY');

            if (!$token) {
                Log::error('WA NOT SENT: FONNTE_API_KEY tidak ditemukan');
                return false;
            }

            $tanggalSurat = date('d-m-Y', strtotime($request->tgl_surat));

            $message = "
📄 *Notifikasi Surat Keluar Baru*

Ada surat keluar baru yang dibuat:

📝 *Nomor Surat:* {$request->nomor_surat}
🎯 *Tujuan:* {$request->tujuan}
📖 *Perihal:* {$request->perihal}
📅 *Tanggal:* {$tanggalSurat}
👤 *Dibuat oleh:* {$pembuat}

Silakan cek sistem untuk detailnya.
";

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.fonnte.com/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    "target" => $target,
                    "message" => $message,
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: $token"
                ],
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                Log::error('WA CURL ERROR', ['error' => $error]);
                return false;
            }

            Log::info('WA NOTIFICATION SENT', [
                'target' => $target,
                'response' => $response
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WA NOTIFICATION FAILED', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    public static function sendDisposisiNotification($target, $surat, $catatan, $pengirim)
    {
        try {
            if (!$target) {
                Log::warning('WA NOT SENT: Nomor tujuan kosong (Disposisi)');
                return false;
            }

            $token = env('FONNTE_API_KEY');
            if (!$token) {
                Log::error('WA NOT SENT: FONNTE_API_KEY tidak ditemukan');
                return false;
            }

            $tanggalSurat = date('d-m-Y', strtotime($surat->tanggal_terima));

            $message = "
📩 *Disposisi Surat Masuk*

📄 *Nomor Surat:* {$surat->nomor_surat}
📖 *Perihal:* {$surat->perihal}
📅 *Tanggal Terima:* {$tanggalSurat}
👤 *Dari:* {$pengirim}

📝 *Catatan Disposisi:*
{$catatan}

Silakan login ke sistem untuk menindaklanjuti.
";

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
            ]);

            Log::info('WA DISPOSISI SENT', [
                'target' => $target,
                'response' => $response->body()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WA DISPOSISI FAILED', [
                'message' => $e->getMessage()
            ]);

            return false;
        }
    }
}
