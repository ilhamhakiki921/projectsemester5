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
 *  - Di halaman lain        : hanya boleh MELIHAT (semua simpan/ubah/hapus dan
 *    semua cetak/unduh ditolak).
 *
 * Role lain (dokter, perawat, admin, dst.):
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

                // Riwayat Pemeriksaan & SOAP: hanya Dokter yang boleh mengedit.
        if ($nama === 'rekam-medis.soap.update' && ! $user->isDokter()) {
            return $this->tolak($request, 'Riwayat Pemeriksaan & SOAP hanya dapat diedit oleh Dokter.');
        }

        // Dokter: halaman Pendaftaran hanya boleh dilihat.
        if ($user->isDokter() && $ubah && $request->is('pendaftaran/*')) {
            return $this->tolak($request, 'Halaman Pendaftaran hanya dapat dilihat oleh Dokter.');
        }

        if ($user->isRm()) {
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

               // Role selain rm: halaman Rekam Medis hanya boleh dilihat
        // (kecuali Dokter, yang boleh mengedit Pemeriksaan & SOAP).
        $dokterEditSoap = $user->isDokter() && $nama === 'rekam-medis.soap.update';

        if ($halamanRm && ($ubah || $cetak) && ! $dokterEditSoap) {
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
