<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    /**
     * Daftar poli yang tersedia.
     */
    protected $daftarPoli = [
        'umum'         => 'Poli Umum',
        'gigi'         => 'Poli Gigi',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

    protected function pesanValidasi()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
        ];
    }

    /**
     * Cari kunjungan berdasarkan poli + no_rm pasien.
     * Ambil kunjungan TERBARU pasien tsb di poli itu (belum dibatasi tanggal,
     * supaya tetap gampang dicoba selama masa pengembangan).
     */
    protected function carikanKunjungan($poli, $no_rm)
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            abort(404);
        }

        $pasien = Pasien::where('no_rm', $no_rm)->first();

        if (! $pasien) {
            abort(404, 'Data pasien tidak ditemukan.');
        }

        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)
            ->where('poli', $poli)
            ->latest('id')
            ->first();

        if (! $kunjungan) {
            abort(404, 'Data kunjungan pasien tidak ditemukan di poli ini.');
        }

        return $kunjungan;
    }

    public function index($poli = 'umum')
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        $antrian = Kunjungan::with('pasien')
            ->where('poli', $poli)
            ->orderBy('no_antrian')
            ->get();

        return view('pemeriksaan.index', [
            'poli'       => $poli,
            'namaPoli'   => $this->daftarPoli[$poli],
            'daftarPoli' => $this->daftarPoli,
            'antrian'    => $antrian,
        ]);
    }

    // ==========================================================
    // ASESMEN
    // ==========================================================

    public function asesmen($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        return view('pemeriksaan.asesmen', [
            'poli'      => $poli,
            'namaPoli'  => $this->daftarPoli[$poli],
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ]);
    }

    public function simpanAsesmen(Request $request, $poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        $data = $request->validate([
            'keluhan_utama'              => 'required|string',
            'riwayat_penyakit_sekarang'  => 'required|string',
            'riwayat_penyakit_terdahulu' => 'required|string',
            'riwayat_alergi'             => 'required|string',
            'riwayat_pengobatan'         => 'required|string',
            'tb'                         => 'required|string',
            'td'                         => 'required|string',
            'lp'                         => 'required|string',
            'respiratory_rate'           => 'required|string',
            'berat_badan'                => 'required|string',
            'detak_jantung'              => 'required|string',
            'lingkar_dada'               => 'required|string',
            'saturasi'                   => 'required|string',
            'imt'                        => 'required|string',
            'suhu_badan'                 => 'required|string',
            'lingkar_kepala'             => 'required|string',
            'gcs'                        => 'required|string',
        ], $this->pesanValidasi());

        RekamMedis::updateOrCreate(
            ['kunjungan_id' => $kunjungan->id],
            $data
        );

        // Pasien resmi masuk status "Sedang Diperiksa" begitu asesmen pertama diisi
        $kunjungan->update(['status' => 'Sedang Diperiksa']);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Asesmen Tersimpan!',
            'message' => 'Data asesmen berhasil masuk ke rekam medis pasien. Status pasien sekarang "Sedang Diperiksa". Lanjutkan ke tab SOAP.',
        ]);

        return redirect()->route('pemeriksaan.soap', [$poli, $no_rm]);
    }

    // ==========================================================
    // SOAP
    // ==========================================================

    public function soap($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        if (! $kunjungan->rekamMedis || ! $kunjungan->rekamMedis->keluhan_utama) {
            session()->flash('popup', [
                'type'    => 'warning',
                'title'   => 'Asesmen Belum Diisi',
                'message' => 'Lengkapi dan simpan Asesmen Pemeriksaan terlebih dahulu sebelum membuka SOAP.',
            ]);

            return redirect()->route('pemeriksaan.asesmen', [$poli, $no_rm]);
        }

        return view('pemeriksaan.soap', [
            'poli'      => $poli,
            'namaPoli'  => $this->daftarPoli[$poli],
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ]);
    }

    public function simpanSoap(Request $request, $poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        $data = $request->validate([
            'subjective' => 'required|string',
            'objective'  => 'required|string',
            'assessment' => 'required|string',
            'plan'       => 'required|string',
        ], $this->pesanValidasi());

        RekamMedis::updateOrCreate(
            ['kunjungan_id' => $kunjungan->id],
            $data
        );

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'SOAP Tersimpan!',
            'message' => 'Data SOAP berhasil masuk ke rekam medis pasien. Lanjutkan ke tab Diagnosa.',
        ]);

        return redirect()->route('pemeriksaan.diagnosa', [$poli, $no_rm]);
    }

    // ==========================================================
    // DIAGNOSA
    // ==========================================================

    public function diagnosa($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        if (! $kunjungan->rekamMedis || ! $kunjungan->rekamMedis->subjective) {
            session()->flash('popup', [
                'type'    => 'warning',
                'title'   => 'SOAP Belum Diisi',
                'message' => 'Lengkapi dan simpan SOAP terlebih dahulu sebelum membuka Diagnosa.',
            ]);

            return redirect()->route('pemeriksaan.soap', [$poli, $no_rm]);
        }

        return view('pemeriksaan.diagnosa', [
            'poli'      => $poli,
            'namaPoli'  => $this->daftarPoli[$poli],
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ]);
    }

    public function simpanDiagnosa(Request $request, $poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        $data = $request->validate([
            'diagnosa_kerja'   => 'required|string',
            'kode_icd10'       => 'nullable|string',
            'diagnosa_banding' => 'nullable|string',
        ], $this->pesanValidasi());

        RekamMedis::updateOrCreate(
            ['kunjungan_id' => $kunjungan->id],
            $data
        );

        // Diagnosa selesai diisi -> pemeriksaan inti pasien ini dianggap selesai
        $kunjungan->update(['status' => 'Selesai']);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Diagnosa Tersimpan!',
            'message' => 'Data diagnosa berhasil masuk ke rekam medis pasien. Status pasien sekarang "Selesai".',
        ]);

        return redirect()->route('pemeriksaan.index', $poli);
    }
}
