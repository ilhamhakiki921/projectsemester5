<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RekamMedis;
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
        'anak'         => 'Poli Anak',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

    /**
     * Nama bulan untuk dropdown "Periode" pada tabel 10 Besar Penyakit.
     */
    protected $daftarBulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function index(Request $request)
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

        // ==========================================================
        // 10 BESAR PENYAKIT
        // Bulan & tahun dipilih lewat 2 dropdown terpisah (bukan 1
        // dropdown gabungan), default ke bulan & tahun berjalan.
        // ==========================================================
        $bulanTerpilih = (int) $request->input('bulan', $hariIni->month);
        $tahunTerpilih = (int) $request->input('tahun', $hariIni->year);

        if ($bulanTerpilih < 1 || $bulanTerpilih > 12) {
            $bulanTerpilih = $hariIni->month;
        }

        $daftarBulan = $this->daftarBulan;
        $daftarTahun = $this->daftarTahunPenyakit($tahunTerpilih);
        $topPenyakit = $this->hitungTopPenyakit($bulanTerpilih, $tahunTerpilih);

        return view('dashboard', compact(
            'totalPasien',
            'totalKunjunganHariIni',
            'pasienSelesaiHariIni',
            'tanggalPoliDefault',
            'kunjunganPoliChart',
            'topPenyakit',
            'bulanTerpilih',
            'tahunTerpilih',
            'daftarBulan',
            'daftarTahun'
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
        $jumlahPerPoli = [];

        try {
            $baris = Kunjungan::whereDate('tanggal_kunjungan', $tanggal)
                ->select('poli', DB::raw('count(*) as jumlah'))
                ->groupBy('poli')
                ->get();

            // Samakan penulisan (huruf besar/kecil & spasi) supaya "Umum",
            // "umum " dan "UMUM" tetap dihitung sebagai satu poli.
            foreach ($baris as $b) {
                $key = strtolower(trim((string) $b->poli));
                $jumlahPerPoli[$key] = ($jumlahPerPoli[$key] ?? 0) + (int) $b->jumlah;
            }
        } catch (QueryException $e) {
            Log::warning('Dashboard: gagal menghitung kunjungan per poli. ' . $e->getMessage());
        }

        $labels = [];
        $data = [];

        foreach ($this->daftarPoli as $key => $label) {
            $labels[] = $label;
            $data[] = (int) ($jumlahPerPoli[$key] ?? 0);
        }

        // Poli lain yang ada kunjungannya tapi belum terdaftar di $daftarPoli
        // tetap ditampilkan, jadi data tidak pernah "hilang" dari grafik.
        foreach ($jumlahPerPoli as $key => $jumlah) {
            if (! array_key_exists($key, $this->daftarPoli)) {
                $labels[] = 'Poli ' . ucfirst($key);
                $data[] = $jumlah;
            }
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    /**
     * 10 diagnosis (penyakit) terbanyak pada bulan & tahun tertentu,
     * dihitung otomatis dari data rekam medis pasien (diagnosis_utama
     * pada tabel rekam_medis, digabung dengan poli & tanggal kunjungan
     * pada tabel kunjungan). Diagnosis yang sama pada poli berbeda
     * dihitung sebagai baris terpisah supaya kolom "Poli" tetap akurat.
     */
    protected function hitungTopPenyakit(int $bulan, int $tahun)
    {
        try {
            return RekamMedis::query()
                ->join('kunjungan', 'kunjungan.id', '=', 'rekam_medis.kunjungan_id')
                ->whereNotNull('rekam_medis.diagnosis_utama')
                ->where('rekam_medis.diagnosis_utama', '!=', '')
                ->whereYear('kunjungan.tanggal_kunjungan', $tahun)
                ->whereMonth('kunjungan.tanggal_kunjungan', $bulan)
                ->select(
                    'rekam_medis.diagnosis_utama as diagnosa',
                    'rekam_medis.kode_icd10 as kode_icd10',
                    'kunjungan.poli as poli',
                    DB::raw('count(*) as jumlah')
                )
                ->groupBy('rekam_medis.diagnosis_utama', 'rekam_medis.kode_icd10', 'kunjungan.poli')
                ->orderByDesc('jumlah')
                ->limit(10)
                ->get()
                ->map(function ($baris) {
                    $baris->nama_poli = $this->daftarPoli[strtolower(trim((string) $baris->poli))]
                        ?? ('Poli ' . ucfirst($baris->poli));

                    return $baris;
                });
        } catch (QueryException $e) {
            Log::warning('Dashboard: gagal menghitung 10 besar penyakit. ' . $e->getMessage());

            return collect();
        }
    }

    /**
     * Pilihan tahun untuk dropdown "Tahun" pada filter 10 Besar
     * Penyakit. Selalu menyediakan beberapa tahun ke belakang (bukan
     * cuma tahun berjalan), lalu diperlebar lagi kalau ternyata ada
     * data kunjungan yang lebih lama, atau kalau tahun yang sedang
     * dipilih ada di luar rentang itu.
     */
    protected function daftarTahunPenyakit(int $tahunTerpilih): array
    {
        $tahunSekarang = (int) Carbon::today()->year;

        // Minimal 6 tahun ke belakang supaya dropdown tidak cuma
        // berisi satu tahun berjalan.
        $tahunAwal = $tahunSekarang - 5;

        try {
            $tahunPalingAwal = Kunjungan::min('tanggal_kunjungan');

            if ($tahunPalingAwal) {
                $tahunAwal = min($tahunAwal, (int) Carbon::parse($tahunPalingAwal)->year);
            }
        } catch (QueryException $e) {
            Log::warning('Dashboard: gagal mengambil tahun kunjungan paling awal. ' . $e->getMessage());
        }

        // Tetap sertakan tahun yang sedang dipilih walau di luar rentang data,
        // supaya dropdown tidak "lompat" ke tahun lain saat halaman dibuka.
        $tahunAwal = min($tahunAwal, $tahunTerpilih);
        $tahunAkhir = max($tahunSekarang, $tahunTerpilih);

        return range($tahunAkhir, $tahunAwal);
    }
}
