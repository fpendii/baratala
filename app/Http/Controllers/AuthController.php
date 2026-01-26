<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function attempt(Request $request)
    {
        $key = 'login:' . $request->ip();
        $maxAttempts = 5;
        $decay = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retry = RateLimiter::availableIn($key);
            return back()
                ->with('error', "Terlalu banyak percobaan. Coba lagi dalam {$retry} detik.")
                ->withInput();
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            // ambil semua data user
            $user = Auth::user();

            // simpan ke session
            $request->session()->put([
                'pengguna' => $user->toArray(),
                'role'     => $user->role,
                'nama'     => $user->nama,
                'email'    => $user->email,
                'id'       => $user->id,
            ]);


            return redirect()->intended('dashboard');

            // redirect sesuai role
            $role = strtolower($user->role); // biar aman huruf kecil semua
            return redirect()->intended("/{$role}/dashboard");
        }

        RateLimiter::hit($key, $decay);

        return back()
            ->with('error', 'Email atau password salah.')
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landingpage')->with('success', 'Berhasil logout.');
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            // 🔎 Cek apakah email ada di tabel pengguna
            $user = DB::table('pengguna')->where('email', $request->email)->first();
            if (!$user) {
                Log::warning('Percobaan reset password dengan email tidak terdaftar: ' . $request->email);
                return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami.']);
            }

            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => now()]
            );

            $link = url("/reset-password/$token?email=" . urlencode($request->email));

            // ✅ Tambahkan log sebelum kirim email
            Log::info('Reset password link dibuat untuk: ' . $request->email);
            Log::info('Link reset: ' . $link);

            Mail::raw("Klik link berikut untuk reset password kamu: $link", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Password');
            });

            Log::info('Email reset password dikirim ke: ' . $request->email);

            return back()->with('status', 'Link reset password telah dikirim ke email kamu!');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }


    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        Pengguna::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Password berhasil direset!');
    }
}
