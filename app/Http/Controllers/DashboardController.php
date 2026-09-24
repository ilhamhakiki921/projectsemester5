<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Daftar poli yang tersedia, dipakai bersama untuk urutan
     * dan label pada grafik "Kunjungan Pasien Per Poli".
     * Key harus sama persis dengan nilai kolom `kunjungan.poli`.
     */
    protected $daftarPoli = [
        'umum'         => 'Poli Umum',
        'gigi'         => 'Poli Gigi',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

    public function index()
    {
        $hariIni = Carbon::today();

        // Kalau tabel belum ada / database bermasalah, dashboard tetap bisa
        // dibuka (angka 0) dan penyebabnya dicatat di storage/logs/laravel.log.
        try {
            $totalPasien = Pasien::count();
        } catch (QueryException $e) {
            Log::warning('Dashboard: gagal menghitung pasien. ' . $e->getMessage());
            $totalPasien = 0;
        }

        try {
            $totalKunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', $hariIni)->count();

            $pasienSelesaiHariIni = Kunjungan::whereDate('tanggal_kunjungan', $hariIni)
                ->where('status', 'Selesai')
                ->count();
        } catch (QueryException $e) {
            Log::warning('Dashboard: gagal menghitung pasien selesai. ' . $e->getMessage());
            $totalKunjunganHariIni = 0;
            $pasienSelesaiHariIni = 0;
        }

        $tanggalPoliDefault = $hariIni->toDateString();
        $kunjunganPoliChart = $this->hitungKunjunganPerPoli($tanggalPoliDefault);

        return view('dashboard', compact(
            'totalPasien',
            'totalKunjunganHariIni',
            'pasienSelesaiHariIni',
            'tanggalPoliDefault',
            'kunjunganPoliChart'
        ));
    }

    /**
     * Endpoint AJAX untuk grafik "Kunjungan Pasien Per Poli".
     * Dipanggil ketika pengguna mengganti tanggal pada kalender filter.
     */
    public function kunjunganPerPoli(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
        ]);

        $tanggal = $request->input('tanggal') ?: Carbon::today()->toDateString();

        return response()->json(
            array_merge(['tanggal' => $tanggal], $this->hitungKunjunganPerPoli($tanggal))
        );
    }

    /**
     * Hitung jumlah kunjungan per poli pada tanggal tertentu.
     * Poli yang belum ada kunjungannya tetap ditampilkan dengan angka 0
     * supaya urutan & label grafik konsisten setiap hari.
     */
    protected function hitungKunjunganPerPoli(string $tanggal): array
    {
        $jumlahPerPoli = collect();

        try {
            $jumlahPerPoli = Kunjungan::whereDate('tanggal_kunjungan', $tanggal)
                ->select('poli', DB::raw('count(*) as jumlah'))
                ->groupBy('poli')
                ->pluck('jumlah', 'poli');
        } catch (QueryException $e) {
            Log::warning('Dashboard: gagal menghitung kunjungan per poli. ' . $e->getMessage());
        }

        $labels = [];
        $data = [];

        foreach ($this->daftarPoli as $key => $label) {
            $labels[] = $label;
            $data[] = (int) ($jumlahPerPoli[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }
}
