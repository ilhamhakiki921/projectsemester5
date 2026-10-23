<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pembatasan hak akses berdasarkan role (saat ini: PERAWAT).
 *
 * Aturannya ada di config/akses.php. Role selain perawat tidak terpengaruh.
 *  - Halaman 'tutup' (Data Master, Audit Trail) ditolak total.
 *  - Request tulis (POST/PUT/PATCH/DELETE) hanya lolos kalau nama route-nya
 *    ada di daftar 'tulis_boleh'; sisanya ditolak (default-deny, jadi route
 *    baru yang belum didaftarkan otomatis aman).
 */
class BatasiAkses
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isPetugasPendaftaran()) {
            return $this->batasiPetugasPendaftaran($request, $next);
        }

        if ($user && $user->isRm()) {
            return $this->batasiRekamMedis($request, $next);
        }

        if (! $user || $user->role !== 'perawat') {
            return $next($request);
        }

        $aturan = config('akses.perawat');
        $nama   = (string) optional($request->route())->getName();

        if ($nama !== '' && Str::is($aturan['tutup'], $nama)) {
            return $this->tolak($request, 'Menu ini hanya dapat diakses oleh administrator.');
        }

        if (! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)
            && ! Str::is($aturan['tulis_boleh'], $nama)) {
            return $this->tolak($request, 'Akses perawat pada bagian ini hanya untuk melihat data (tidak dapat menyimpan/mengubah).');
        }

        return $next($request);
    }

    /**
     * Petugas Pendaftaran: whitelist. Hanya route Dashboard, Pendaftaran
     * (pasien lama/baru, kunjungan) dan proxy wilayah yang boleh dibuka.
     * Semua route lain (Pemeriksaan, Penunjang, Farmasi, Rekam Medis, Laporan,
     * Data Master, Audit Trail, Pengaturan, dst.) ditolak, termasuk kalau
     * URL-nya diketik manual. Route baru otomatis tertutup (default-deny).
     */
    private function batasiPetugasPendaftaran(Request $request, Closure $next): Response
    {
        $nama = (string) optional($request->route())->getName();

        $boleh = ['dashboard', 'dashboard.*', 'pendaftaran', 'pasien.*', 'wilayah.*'];

        if ($nama !== '' && Str::is($boleh, $nama)) {
            return $next($request);
        }

        // Tanpa pemberitahuan: cukup dikembalikan ke Dashboard.
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Tidak dapat diakses.'], 403);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Petugas Rekam Medis: di halaman Pendaftaran hanya boleh MELIHAT detail
     * identitas pasien. Daftar pasien baru, daftar kunjungan, edit & hapus pasien
     * ditolak di server (walau URL/form dikirim manual).
     */
    private function batasiRekamMedis(Request $request, Closure $next): Response
    {
        $nama = (string) optional($request->route())->getName();

        // Halaman Farmasi tidak bisa dibuka Petugas Rekam Medis.
        if ($request->is('farmasi', 'farmasi/*')) {
            return redirect()->route('rekam-medis.index');
        }

        $ditutup = [
            'pasien.baru', 'pasien.baru.store', 'pasien.update',
            'pasien.lama.edit', 'pasien.lama.kunjungan.store',
            'pasien.kunjungan.store', 'pasien.lama.destroy',
        ];

        if (in_array($nama, $ditutup, true)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Tidak dapat diakses.'], 403);
            }

            return redirect()->route('pendaftaran');
        }

        // Halaman Pemeriksaan (asesmen, SOAP, diagnosa, resep, penunjang, rujukan,
        // surat medis, autentikasi, clinical pathway, informed consent): hanya lihat.
        // Semua simpan/ubah/hapus dan cetak/unduh ditolak.
        if ($request->is('pemeriksaan', 'pemeriksaan/*')) {
            $ubah  = ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
            $cetak = (bool) preg_match('/\.(cetak|unduh)$/', $nama);

            if ($ubah || $cetak) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Tidak dapat diakses.'], 403);
                }

                return $request->isMethod('GET')
                    ? redirect()->route('rekam-medis.index')
                    : redirect()->back(fallback: route('rekam-medis.index'));
            }
        }

        return $next($request);
    }

    private function tolak(Request $request, string $pesan): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => $pesan], 403);
        }

        // Ditampilkan sebagai toast oleh komponen <x-akses-perawat /> (ada di sidebar,
        // jadi muncul di semua halaman).
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return redirect()->back(fallback: route('dashboard'))->with('akses_ditolak', $pesan);
        }

        return redirect()->route('dashboard')->with('akses_ditolak', $pesan);
    }
}
