<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Catatan: trait "Illuminate\Foundation\Auth\AuthenticatesUsers" (dari
    | paket laravel/ui) TIDAK ada di project ini, jadi proses login/logout
    | ditulis manual di bawah ini (bukan lewat trait) supaya tidak perlu
    | menambah dependency baru:
    | - showLoginForm() : tampilkan form login (view auth.login)
    | - login()         : validasi input & cek username+password ke database
    | - logout()        : proses logout
    |
    | validateLogin() menolak submit kalau username / password kosong,
    | credentials() mencocokkan username+password (dan status akun) ke
    | tabel `users`, sendFailedLoginResponse() menangani kalau
    | username/password salah.
    |
    */

    /**
     * Ke mana user diarahkan setelah berhasil login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Middleware controller ini (pengganti $this->middleware() di constructor,
     * karena App\Http\Controllers\Controller tidak extends
     * Illuminate\Routing\Controller sehingga method tersebut tidak tersedia).
     *
     * Kalau sudah login, tidak bisa buka halaman login lagi (otomatis
     * dilempar ke $redirectTo di atas), kecuali untuk logout.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }

    /**
     * Login memakai kolom "username" (bukan email), sesuai kolom
     * pada tabel `users` (nama_lengkap, username, password, role, status).
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses submit form login.
     *
     * SEMENTARA DINONAKTIFKAN atas permintaan: klik tombol Login langsung
     * masuk ke dashboard, tanpa mengecek username/password ke database.
     * (Logic pengecekan asli disimpan di bawah, di method
     * loginDenganPengecekan(), tinggal dipanggil lagi kalau mau diaktifkan.)
     */
    public function login(Request $request)
    {
        // Nama user dicatat di session supaya Audit Trail tetap tahu siapa
        // yang login/logout walau pengecekan password sedang dinonaktifkan.
        $username = trim((string) $request->input($this->username())) ?: 'admin';
        $request->session()->put('audit_username', $username);

        ActivityLog::catat('Login', 'users', null, 'Login berhasil', null, null, null, $username);

        return redirect($this->redirectTo);
    }

    /**
     * Logic login yang sebenarnya (validasi + cek username/password ke
     * database). Saat ini tidak dipanggil dari mana pun — biarkan saja di
     * sini supaya gampang diaktifkan lagi nanti (tinggal ganti isi method
     * login() di atas menjadi "return $this->loginDenganPengecekan($request);").
     */
    protected function loginDenganPengecekan(Request $request)
    {
        $this->validateLogin($request);

        // Kalau koneksi database sedang bermasalah (mis. MySQL belum
        // menyala / password di .env tidak cocok), tampilkan pesan yang
        // jelas ke pengguna alih-alih halaman error 500.
        try {
            $berhasilLogin = Auth::attempt($this->credentials($request));
        } catch (QueryException $e) {
            Log::warning('Login: gagal terhubung ke database. ' . $e->getMessage());

            return redirect()->back()
                ->withInput($request->only($this->username()))
                ->withErrors([
                    $this->username() => 'Tidak bisa terhubung ke database. Pastikan server database (MySQL) sudah menyala, lalu coba lagi.',
                ]);
        }

        if ($berhasilLogin) {
            $request->session()->regenerate();
            $request->session()->put('audit_username', Auth::user()->username);

            ActivityLog::catat('Login', 'users', Auth::id(), 'Login berhasil');

            return redirect()->intended($this->redirectTo);
        }

        ActivityLog::catat(
            'Login', 'users', null,
            'Login gagal (username atau password salah)',
            null,
            ['username' => (string) $request->input($this->username())],
            null,
            (string) $request->input($this->username()) ?: 'admin'
        );

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Wajib isi username & password (tidak boleh kosong) sebelum
     * dicek ke database, dengan pesan berbahasa Indonesia.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ], [
            $this->username() . '.required' => 'Username wajib diisi.',
            'password.required'             => 'Password wajib diisi.',
        ]);
    }

    /**
     * Kredensial yang dicocokkan ke database saat Auth::attempt().
     * Akun dengan status "nonaktif" ikut ditolak di sini.
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['status' => 'aktif']
        );
    }

    /**
     * Pesan error kalau username / password salah (atau akun nonaktif).
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username()))
            ->withErrors([
                $this->username() => 'Username atau password salah.',
            ]);
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        // Catat DULU, sebelum session dihapus.
        ActivityLog::catat('Logout', 'users', Auth::id(), 'Logout');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Permintaan "Lupa Password": dicatat ke Audit Trail supaya administrator
     * bisa melihat siapa yang meminta reset, lalu mengganti password lewat
     * menu Data User.
     */
    public function lupaPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
        ], [
            'username.required' => 'Username wajib diisi.',
        ]);

        $username = (string) $request->input('username');

        ActivityLog::catat(
            'Password', 'users', null,
            'Permintaan lupa password',
            null,
            ['username' => $username],
            null,
            $username
        );

        return redirect()->route('password.request')
            ->with('status', 'Permintaan reset password sudah dikirim. Silakan hubungi administrator.');
    }
}
