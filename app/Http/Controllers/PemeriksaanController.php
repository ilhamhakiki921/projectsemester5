<?php

namespace App\Http\Controllers;

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

    /**
     * DATA DUMMY SEMENTARA.
     * Nanti diganti query ke database.
     */
    protected function dataDummy()
    {
        return [
            'umum' => [
                [
                    'no_antrian'    => 'A001',
                    'no_rm'         => '00123',
                    'nama_pasien'   => 'Budi Santoso',
                    'alamat'        => 'Jl. Merdeka No. 10',
                    'status'        => 'Menunggu',
                    'nik'           => '3372xxxxxxxxxxxx',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '20-04-1990',
                    'usia'          => '36 Tahun',
                ],
                [
                    'no_antrian'    => 'A002',
                    'no_rm'         => '00124',
                    'nama_pasien'   => 'Siti Aminah',
                    'alamat'        => 'Jl. Sudirman No. 5',
                    'status'        => 'Diperiksa',
                    'nik'           => '3372yyyyyyyyyyyy',
                    'jenis_kelamin' => 'Perempuan',
                    'tanggal_lahir' => '11-08-1995',
                    'usia'          => '31 Tahun',
                ],
            ],
            'gigi' => [
                [
                    'no_antrian'    => 'B001',
                    'no_rm'         => '00201',
                    'nama_pasien'   => 'Andi Wijaya',
                    'alamat'        => 'Jl. Diponegoro No. 3',
                    'status'        => 'Menunggu',
                    'nik'           => '3372zzzzzzzzzzzz',
                    'jenis_kelamin' => 'Laki-laki',
                    'tanggal_lahir' => '02-01-1988',
                    'usia'          => '38 Tahun',
                ],
            ],
            'dalam'        => [],
            'mikrobiologi' => [],
            'fisioterapi'  => [],
        ];
    }

    protected function pesanValidasi()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
        ];
    }

    protected function carikanPasien($poli, $no_rm)
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            abort(404);
        }

        $antrian = $this->dataDummy()[$poli] ?? [];
        $pasien  = collect($antrian)->firstWhere('no_rm', $no_rm);

        if (! $pasien) {
            abort(404, 'Data pasien tidak ditemukan di antrian poli ini.');
        }

        return $pasien;
    }

    /**
     * Tentukan status pasien saat ini berdasarkan progres pengisian
     * rekam medis yang tersimpan di session (sementara, sebelum ada DB).
     *
     * menunggu  -> belum ada apa-apa
     * proses    -> asesmen sudah diisi, SOAP belum
     * selesai   -> asesmen + SOAP sudah diisi (pemeriksaan inti selesai)
     */
    protected function statusPasien($no_rm, $statusDefault)
    {
        if (session()->has("rekam_medis.$no_rm.soap")) {
            return ['kode' => 'selesai', 'label' => 'Diperiksa'];
        }

        if (session()->has("rekam_medis.$no_rm.asesmen")) {
            return ['kode' => 'proses', 'label' => 'Proses Pemeriksaan'];
        }

        $kode = ($statusDefault === 'Diperiksa') ? 'selesai' : 'menunggu';

        return ['kode' => $kode, 'label' => $statusDefault];
    }

    public function index($poli = 'umum')
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        $antrian = $this->dataDummy()[$poli] ?? [];

        foreach ($antrian as &$item) {
            $status = $this->statusPasien($item['no_rm'], $item['status']);
            $item['status']      = $status['label'];
            $item['status_kode'] = $status['kode'];
        }
        unset($item);

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
        $pasien = $this->carikanPasien($poli, $no_rm);

        return view('pemeriksaan.asesmen', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
        ]);
    }

    public function simpanAsesmen(Request $request, $poli, $no_rm)
    {
        $this->carikanPasien($poli, $no_rm);

        $data = $request->validate([
            'keluhan_utama'      => 'required|string',
            'riwayat_sekarang'   => 'required|string',
            'riwayat_terdahulu'  => 'required|string',
            'riwayat_alergi'     => 'required|string',
            'riwayat_pengobatan' => 'required|string',
            'tb'                 => 'required|string',
            'td'                 => 'required|string',
            'lp'                 => 'required|string',
            'respiratory_rate'   => 'required|string',
            'berat_badan'        => 'required|string',
            'detak_jantung'      => 'required|string',
            'lingkar_dada'       => 'required|string',
            'saturasi'           => 'required|string',
            'imt'                => 'required|string',
            'suhu_badan'         => 'required|string',
            'lingkar_kepala'     => 'required|string',
            'gcs'                => 'required|string',
        ], $this->pesanValidasi());

        // NANTI diganti simpan ke tabel rekam_medis
        session()->put("rekam_medis.$no_rm.asesmen", $data);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Asesmen Tersimpan!',
            'message' => 'Data asesmen berhasil masuk ke rekam medis pasien. Status pasien sekarang "Proses Pemeriksaan". Lanjutkan ke tab SOAP.',
        ]);

        return redirect()->route('pemeriksaan.soap', [$poli, $no_rm]);
    }

    // ==========================================================
    // SOAP
    // ==========================================================

    public function soap($poli, $no_rm)
    {
        $pasien = $this->carikanPasien($poli, $no_rm);

        if (! session()->has("rekam_medis.$no_rm.asesmen")) {
            session()->flash('popup', [
                'type'    => 'warning',
                'title'   => 'Asesmen Belum Diisi',
                'message' => 'Lengkapi dan simpan Asesmen Pemeriksaan terlebih dahulu sebelum membuka SOAP.',
            ]);

            return redirect()->route('pemeriksaan.asesmen', [$poli, $no_rm]);
        }

        return view('pemeriksaan.soap', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
            'asesmen'  => session("rekam_medis.$no_rm.asesmen"),
        ]);
    }

    public function simpanSoap(Request $request, $poli, $no_rm)
    {
        $this->carikanPasien($poli, $no_rm);

        $data = $request->validate([
            'subjective' => 'required|string',
            'objective'  => 'required|string',
            'assessment' => 'required|string',
            'plan'       => 'required|string',
        ], $this->pesanValidasi());

        // NANTI diganti simpan ke tabel rekam_medis
        session()->put("rekam_medis.$no_rm.soap", $data);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'SOAP Tersimpan!',
            'message' => 'Data SOAP berhasil masuk ke rekam medis pasien. Status pasien sekarang "Diperiksa". Lanjutkan ke tab Diagnosa.',
        ]);

        return redirect()->route('pemeriksaan.diagnosa', [$poli, $no_rm]);
    }

    // ==========================================================
    // DIAGNOSA
    // ==========================================================

    public function diagnosa($poli, $no_rm)
    {
        $pasien = $this->carikanPasien($poli, $no_rm);

        if (! session()->has("rekam_medis.$no_rm.soap")) {
            session()->flash('popup', [
                'type'    => 'warning',
                'title'   => 'SOAP Belum Diisi',
                'message' => 'Lengkapi dan simpan SOAP terlebih dahulu sebelum membuka Diagnosa.',
            ]);

            return redirect()->route('pemeriksaan.soap', [$poli, $no_rm]);
        }

        return view('pemeriksaan.diagnosa', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
        ]);
    }

    public function simpanDiagnosa(Request $request, $poli, $no_rm)
    {
        $this->carikanPasien($poli, $no_rm);

        $data = $request->validate([
            'diagnosa_kerja'   => 'required|string',
            'diagnosa_banding' => 'nullable|string',
            'kode_icd10'       => 'nullable|string',
        ], $this->pesanValidasi());

        // NANTI diganti simpan ke tabel rekam_medis
        session()->put("rekam_medis.$no_rm.diagnosa", $data);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Diagnosa Tersimpan!',
            'message' => 'Data diagnosa berhasil masuk ke rekam medis pasien.',
        ]);

        return redirect()->route('pemeriksaan.index', $poli);
    }
}
