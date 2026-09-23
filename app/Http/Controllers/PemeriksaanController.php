<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    /**
     * Daftar poli yang tersedia.
     * key = slug (dipakai di URL), value = nama tampilan
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
     * Nanti diganti query ke database, contoh:
     * Pasien::whereHas('kunjungan', fn ($q) => $q->where('poli', $poli))->get();
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

    /**
     * Field-field yang wajib diisi di form Asesmen.
     */
    protected function aturanAsesmen()
    {
        return [
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
        ];
    }

    protected function pesanAsesmen()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
        ];
    }

    /**
     * Cari data pasien dari antrian dummy berdasarkan poli + no_rm.
     */
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

    public function index($poli = 'umum')
    {
        if (! array_key_exists($poli, $this->daftarPoli)) {
            $poli = 'umum';
        }

        $antrian = $this->dataDummy()[$poli] ?? [];

        return view('pemeriksaan.index', [
            'poli'       => $poli,
            'namaPoli'   => $this->daftarPoli[$poli],
            'daftarPoli' => $this->daftarPoli,
            'antrian'    => $antrian,
        ]);
    }

    /**
     * Halaman Asesmen Pemeriksaan, dibuka waktu klik tombol "Periksa".
     */
    public function asesmen($poli, $no_rm)
    {
        $pasien = $this->carikanPasien($poli, $no_rm);

        return view('pemeriksaan.asesmen', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
        ]);
    }

    /**
     * Simpan asesmen. Semua field wajib diisi.
     * Sementara disimpan di session (belum ke database), lalu lanjut ke tab SOAP.
     */
    public function simpanAsesmen(Request $request, $poli, $no_rm)
    {
        $pasien = $this->carikanPasien($poli, $no_rm);

        $data = $request->validate($this->aturanAsesmen(), $this->pesanAsesmen());

        // ==================================================
        // NANTI diganti simpan ke tabel rekam_medis, contoh:
        // RekamMedis::updateOrCreate(
        //     ['no_rm' => $no_rm],
        //     ['asesmen' => $data]
        // );
        // ==================================================
        session()->put("rekam_medis.$no_rm.asesmen", $data);

        return redirect()
            ->route('pemeriksaan.soap', [$poli, $no_rm])
            ->with('success', 'Asesmen berhasil disimpan.');
    }

    /**
     * Halaman SOAP, dibuka otomatis setelah Asesmen disimpan.
     */
    public function soap($poli, $no_rm)
    {
        $pasien = $this->carikanPasien($poli, $no_rm);

        // Prototipe: tab SOAP bisa dibuka langsung dari tab manapun,
        // tidak dipaksa harus isi Asesmen dulu. Data asesmen (kalau sudah
        // pernah diisi & disimpan di session) tetap ditampilkan kalau ada.
        return view('pemeriksaan.soap', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
            'asesmen'  => session("rekam_medis.$no_rm.asesmen", []),
        ]);
    }

    /**
     * Simpan SOAP (sementara di session juga, belum ke database).
     */
    public function simpanSoap(Request $request, $poli, $no_rm)
    {
        $this->carikanPasien($poli, $no_rm);

        $data = $request->validate([
            'subjective' => 'required|string',
            'objective'  => 'required|string',
            'assessment' => 'required|string',
            'plan'       => 'required|string',
        ], $this->pesanAsesmen());

        session()->put("rekam_medis.$no_rm.soap", $data);

        return redirect()
            ->route('pemeriksaan.index', $poli)
            ->with('success', 'SOAP berhasil disimpan. Pemeriksaan selesai.');
    }

    /**
     * Halaman Diagnosa. Prototipe: bisa dibuka langsung dari tab manapun,
     * tidak dipaksa harus isi Asesmen/SOAP dulu.
     */
    public function diagnosa($poli, $no_rm)
    {
        $pasien = $this->carikanPasien($poli, $no_rm);

        return view('pemeriksaan.diagnosa', [
            'poli'     => $poli,
            'namaPoli' => $this->daftarPoli[$poli],
            'pasien'   => $pasien,
            'diagnosa' => session("rekam_medis.$no_rm.diagnosa", []),
        ]);
    }

    /**
     * Simpan Diagnosa (sementara di session juga, belum ke database).
     */
    public function simpanDiagnosa(Request $request, $poli, $no_rm)
    {
        $this->carikanPasien($poli, $no_rm);

        $data = $request->validate([
            'diagnosis_utama'    => 'required|string',
            'diagnosis_sekunder' => 'required|string',
            'tindakan'           => 'required|string',
        ], $this->pesanAsesmen());

        // ==================================================
        // NANTI diganti simpan ke tabel rekam_medis, contoh:
        // RekamMedis::updateOrCreate(
        //     ['no_rm' => $no_rm],
        //     ['diagnosa' => $data]
        // );
        // ==================================================
        session()->put("rekam_medis.$no_rm.diagnosa", $data);

        return redirect()
            ->route('pemeriksaan.index', $poli)
            ->with('success', 'Diagnosa berhasil disimpan.');
    }
}