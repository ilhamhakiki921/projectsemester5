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

    /** Aksi ubah di halaman Rekam Medis yang tidak boleh dilakukan siapa pun. */
    protected array $dikunciSemua = [
        'rekam-medis.soap.update',   // Riwayat Pemeriksaan & SOAP: baca saja
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

        // Riwayat Pemeriksaan & SOAP: tidak bisa diedit oleh siapa pun.
        if (in_array($nama, $this->dikunciSemua, true)) {
            return $this->tolak($request, 'Riwayat Pemeriksaan & SOAP hanya dapat dilihat, tidak dapat diedit dari halaman Rekam Medis.');
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

        // Role selain rm: halaman Rekam Medis hanya boleh dilihat.
        if ($halamanRm && ($ubah || $cetak)) {
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
