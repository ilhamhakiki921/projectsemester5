<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Barryvdh\DomPDF\Facade\Pdf;

class RekamMedisController extends Controller
{
    public function index()
    {
        $daftar = Kunjungan::with('pasien')
            ->latest('tanggal_kunjungan')
            ->latest('id')
            ->get();

        return view('rekam-medis.index', [
            'daftar' => $daftar,
        ]);
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien', 'rekamMedis');

        return view('rekam-medis.show', [
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ]);
    }

    /**
     * Unduh PDF per bagian: asesmen, soap, atau diagnosa.
     */
    public function unduhPdf(Kunjungan $kunjungan, string $bagian)
    {
        if (! in_array($bagian, ['asesmen', 'soap', 'diagnosa'])) {
            abort(404);
        }

        $kunjungan->load('pasien', 'rekamMedis');

        if (! $kunjungan->rekamMedis) {
            abort(404, 'Rekam medis untuk kunjungan ini belum ada.');
        }

        $pdf = Pdf::loadView("rekam-medis.pdf.$bagian", [
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ])->setPaper('a4', 'portrait');

        $namaFile = ucfirst($bagian) . '-' . $kunjungan->pasien->no_rm . '.pdf';

        return $pdf->download($namaFile);
    }
}
