<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Services\SatuSehatService;

class SatuSehatController extends Controller
{
    public function sinkronPasien(Pasien $pasien, SatuSehatService $satusehat)
    {
        try {
            $ihs = $satusehat->cariPasienByNik($pasien->nik);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        if (!$ihs) {
            return back()->with('error', 'NIK tidak ditemukan di SATUSEHAT.');
        }

        $pasien->update(['ihs_number' => $ihs]);

        return back()->with('success', 'IHS Number berhasil disimpan.');
    }
}