<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hak akses halaman REKAM MEDIS (route ber-nama "rekam-medis.*").
 *
 * Role `rm` (Petugas Rekam Medis):
 *  - Di halaman Rekam Medis : boleh mengubah koding & mencetak surat/formulir.
 *    Riwayat Pemeriksaan & SOAP TIDAK boleh diedit.
 *  - Halaman Pemeriksaan & Farmasi : tidak bisa dibuka (dialihkan ke Rekam Medis).
 *  - Di halaman lain        : hanya boleh MELIHAT (semua simpan/ubah/hapus dan
 *    semua cetak/unduh ditolak).
 *
 * Data Master, Audit Trail & Pengaturan: hanya role `admin`.
 *
 * Role `admin` (Administrator): akses penuh ke semua halaman, termasuk mengubah koding
 *  & mencetak di Rekam Medis (satu-satunya pengecualian: Riwayat SOAP terkunci untuk semua).
 *
 * Role lain (dokter, perawat, dst.):
 *  - Halaman Rekam Medis hanya boleh DILIHAT (tidak boleh mengubah / mencetak).
 *  - Halaman miliknya sendiri tidak dibatasi.
 *
 * Penjagaan ada di sisi server, jadi tidak bisa dilewati dengan mengetik URL
 * atau mengirim form manual.
 */
class BatasiAksesRole
{
    /** Aksi ubah di halaman Rekam Medis yang boleh dilakukan role `rm`. */
    protected array $bolehDiubahRm = [
        'rekam-medis.koding.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $nama     = (string) optional($request->route())->getName();
        $halamanRm = str_starts_with($nama, 'rekam-medis.');
        $ubah      = ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
        $cetak     = (bool) preg_match('/\.(cetak|unduh)$/', $nama);

        // Data Master (Data User, Poli, Diagnosa, Tindakan, Obat, Pasien), Audit Trail
        // dan Pengaturan: khusus admin.
        if ($user->role !== 'admin' && $request->is('data-*', 'audit-log', 'audit-log/*', 'pengaturan', 'pengaturan/*')) {
            return redirect()->route($user->isRm() ? 'rekam-medis.index' : 'dashboard');
        }

        // Riwayat Pemeriksaan & SOAP: hanya Dokter yang boleh mengedit.
        if ($nama === 'rekam-medis.soap.update' && ! $user->isDokter()) {
            return $this->tolak($request, 'Riwayat Pemeriksaan & SOAP hanya dapat diedit oleh Dokter.');
        }

        // Dokter: halaman Pendaftaran hanya boleh dilihat.
        if ($user->isDokter() && $ubah && $request->is('pendaftaran/*')) {
            return $this->tolak($request, 'Halaman Pendaftaran hanya dapat dilihat oleh Dokter.');
        }

        if ($user->isRm()) {
            // Halaman Pemeriksaan (asesmen, SOAP, diagnosa, resep, penunjang, rujukan,
            // surat medis, autentikasi, clinical pathway) tidak bisa dibuka sama sekali.
            // Hasil inputannya dilihat lewat halaman Rekam Medis.
            if ($request->is('pemeriksaan', 'pemeriksaan/*')) {
                return redirect()->route('rekam-medis.index');
            }

            // Halaman Farmasi juga tidak bisa dibuka.
            if ($request->is('farmasi', 'farmasi/*')) {
                return redirect()->route('rekam-medis.index');
            }

            if ($halamanRm) {
                if ($ubah && ! in_array($nama, $this->bolehDiubahRm, true)) {
                    return $this->tolak($request, 'Aksi ini tidak diizinkan untuk Petugas Rekam Medis.');
                }

                return $next($request);
            }

            // Di luar halaman Rekam Medis: hanya lihat.
            if ($ubah || $cetak) {
                return $this->tolak($request, 'Di luar halaman Rekam Medis, akun Rekam Medis hanya dapat melihat data (tidak dapat mengubah atau mencetak).');
            }

            return $next($request);
        }

<<<<<<< HEAD
        // Role selain rm & admin: halaman Rekam Medis hanya boleh dilihat
        // (kecuali Dokter, yang boleh mengedit Pemeriksaan & SOAP).
        // Administrator punya akses penuh (kecuali Riwayat SOAP yang hanya boleh diedit Dokter).
        $dokterEditSoap = $user->isDokter() && $nama === 'rekam-medis.soap.update';

        if ($halamanRm && ($ubah || $cetak) && ! $user->isAdmin() && ! $dokterEditSoap) {
            return $this->tolak($request, 'Halaman Rekam Medis hanya dapat diubah dan dicetak oleh Petugas Rekam Medis.');
        }

        return $next($request);
    }

    protected function tolak(Request $request, string $pesan): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $pesan], 403);
        }

        // Aksi cetak/unduh dibuka di tab baru (tidak ada halaman asal): tampilkan 403.
        if ($request->isMethod('GET')) {
            abort(403, $pesan);
        }

        return redirect()->back()->with('popup', [
            'type'    => 'warning',
            'title'   => 'Akses ditolak',
            'message' => $pesan,
        ]);
    }
}
