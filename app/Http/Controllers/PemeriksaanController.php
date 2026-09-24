<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Resep;
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

    public function index(Request $request, $poli = 'umum')
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        $antrian = Kunjungan::with('pasien')
            ->where('poli', $poli)
            ->when($request->filled('nama'), function ($query) use ($request) {
                $query->whereHas('pasien', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->nama . '%');
                });
            })
            ->when($request->filled('no_rm'), function ($query) use ($request) {
                $query->whereHas('pasien', function ($q) use ($request) {
                    $q->where('no_rm', 'like', '%' . $request->no_rm . '%');
                });
            })
            ->when($request->filled('nik'), function ($query) use ($request) {
                $query->whereHas('pasien', function ($q) use ($request) {
                    $q->where('nik', 'like', '%' . $request->nik . '%');
                });
            })
            ->when($request->filled('tanggal_kunjungan'), function ($query) use ($request) {
                $query->whereDate('tanggal_kunjungan', $request->tanggal_kunjungan);
            })
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
            'diagnosis_utama'    => 'required|string',
            'diagnosis_sekunder' => 'required|string',
            'tindakan'           => 'required|string',
        ], $this->pesanValidasi());

        RekamMedis::updateOrCreate(
            ['kunjungan_id' => $kunjungan->id],
            $data
        );

        // Status tetap "Sedang Diperiksa" -- baru jadi "Selesai" nanti setelah
        // ke-8 form (Asesmen, SOAP, Diagnosa, Resep, Clinical Pathway, Penunjang,
        // Rujukan, Autentikasi) sudah lengkap semua. 4 form terakhir belum dibuat.

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Diagnosa Tersimpan!',
            'message' => 'Data diagnosa berhasil masuk ke rekam medis pasien. Lanjutkan ke tab Resep.',
        ]);

        return redirect()->route('pemeriksaan.resep', [$poli, $no_rm]);
    }

    // ==========================================================
    // RESEP OBAT
    // ==========================================================

    public function resep($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        if (! $kunjungan->rekamMedis || ! $kunjungan->rekamMedis->diagnosis_utama) {
            session()->flash('popup', [
                'type'    => 'warning',
                'title'   => 'Diagnosa Belum Diisi',
                'message' => 'Lengkapi dan simpan Diagnosa terlebih dahulu sebelum membuka Resep.',
            ]);

            return redirect()->route('pemeriksaan.diagnosa', [$poli, $no_rm]);
        }

        $kunjungan->load(['resep.obat']);

        return view('pemeriksaan.resep', [
            'poli'       => $poli,
            'namaPoli'   => $this->daftarPoli[$poli],
            'kunjungan'  => $kunjungan,
            'pasien'     => $kunjungan->pasien,
            'daftarObat' => Obat::orderBy('nama_obat')->get(),
        ]);
    }

    public function simpanResep(Request $request, $poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        $data = $request->validate([
            'obat_id'           => 'required|exists:obat,id',
            'jumlah_obat'       => 'required|integer|min:1',
            'dosis_obat'        => 'required|string',
            'frekuensi'         => 'required|string',
            'tanggal_penulisan' => 'required|date',
        ], $this->pesanValidasi());

        $data['kunjungan_id'] = $kunjungan->id;

        Resep::create($data);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Resep Tersimpan!',
            'message' => 'Resep obat berhasil disimpan dan sudah masuk ke antrian Farmasi.',
        ]);

        return redirect()->route('pemeriksaan.resep', [$poli, $no_rm]);
    }

    // ==========================================================
    // CLINICAL PATHWAY (masih placeholder, menunggu desain)
    // ==========================================================

    public function clinicalPathway($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        return view('pemeriksaan.clinical-pathway', [
            'poli'      => $poli,
            'namaPoli'  => $this->daftarPoli[$poli],
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rm'        => $kunjungan->rekamMedis,
        ]);
    }
}
