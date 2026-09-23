<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    // Daftar semua kunjungan + status asesmennya
    public function index(Request $request)
    {
        $tanggal = $request->query('tanggal', today()->toDateString());

        $kunjungans = Kunjungan::with(['pasien', 'rekamMedis'])
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->orderByDesc('created_at')
            ->get();

        return view('rekam-medis.index', compact('kunjungans', 'tanggal'));
    }

    // Export hasil asesmen ke PDF
    public function exportPdf($id)
    {
        $antrian = Kunjungan::with(['pasien', 'rekamMedis'])->findOrFail($id);

        abort_if(!$antrian->rekamMedis, 404, 'Asesmen untuk pasien ini belum diisi.');

        $pdf = Pdf::loadView('rekam-medis.pdf', compact('antrian'))
            ->setPaper('a4', 'portrait');

        $namaFile = 'rekam-medis-' . $antrian->pasien->no_rm . '.pdf';

        return $pdf->download($namaFile);
    }
}
