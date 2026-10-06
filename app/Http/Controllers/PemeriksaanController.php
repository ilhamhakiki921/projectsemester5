<?php

namespace App\Http\Controllers;

use App\Models\Poli;
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
    protected $daftarPoli = [];

    public function __construct()
    {
        $this->daftarPoli = Poli::daftar();
    }

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
            $poli = array_key_exists('umum', $this->daftarPoli) ? 'umum' : array_key_first($this->daftarPoli);
            abort_if($poli === null, 404, 'Belum ada poli. Tambahkan dulu di Data Poli.');
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
            'respiratory_rate'           => 'nullable|string',
            'berat_badan'                => 'required|string',
            'detak_jantung'              => 'required|string',
            'saturasi'                   => 'nullable|string',
            'imt'                        => 'required|string',
            'suhu_badan'                 => 'required|string',
            'gcs'                        => 'nullable|string',
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
            'butuh_informed_consent' => 'nullable|in:ya,tidak',
        ], $this->pesanValidasi());

        // Informed Consent hanya ada di Poli Gigi
        $butuhIc = RekamMedis::poliPunyaInformedConsent($poli)
            && ($data['butuh_informed_consent'] ?? 'tidak') === 'ya';
        $data['butuh_informed_consent'] = $butuhIc;
        RekamMedis::tandaiSession($kunjungan->id, $butuhIc);

        RekamMedis::updateOrCreate(
            ['kunjungan_id' => $kunjungan->id],
            $data
        );

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'SOAP Tersimpan!',
            'message' => $butuhIc
                ? 'Data SOAP berhasil masuk ke rekam medis pasien. Lanjutkan ke tab Informed Consent.'
                : 'Data SOAP berhasil masuk ke rekam medis pasien. Lanjutkan ke tab Diagnosa.',
        ]);

        return $butuhIc
            ? redirect()->route('pemeriksaan.informed-consent', [$poli, $no_rm])
            : redirect()->route('pemeriksaan.diagnosa', [$poli, $no_rm]);
    }

    // ==========================================================
    // INFORMED CONSENT (tab muncul di antara SOAP dan Diagnosa
    // kalau di halaman SOAP dipilih "Ya")
    // ==========================================================

    /**
     * Dipanggil otomatis (AJAX) saat pilihan Ya/Tidak Informed Consent di halaman SOAP diklik,
     * supaya tab Informed Consent langsung bisa dibuka tanpa menunggu tombol Simpan SOAP.
     */
    public function setStatusInformedConsent(Request $request, $poli, $no_rm)
    {
        abort_unless(RekamMedis::poliPunyaInformedConsent($poli), 404);

        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        $data = $request->validate([
            'butuh_informed_consent' => 'required|in:ya,tidak',
        ]);

        $butuh = $data['butuh_informed_consent'] === 'ya';

        // Simpan di session dulu supaya tab langsung bisa dipakai
        RekamMedis::tandaiSession($kunjungan->id, $butuh);

        // Simpan permanen di database (dilewati kalau kolom belum ada)
        $tersimpan = true;
        try {
            RekamMedis::updateOrCreate(
                ['kunjungan_id' => $kunjungan->id],
                ['butuh_informed_consent' => $butuh]
            );
        } catch (\Throwable $e) {
            $tersimpan = false;
            report($e);
        }

        return response()->json(['ok' => true, 'tersimpan_db' => $tersimpan]);
    }

    public function informedConsent($poli, $no_rm)
    {
        // Informed Consent baru ada di Poli Gigi; poli lain kembali ke SOAP
        if (! RekamMedis::poliPunyaInformedConsent($poli)) {
            return redirect()->route('pemeriksaan.soap', [$poli, $no_rm]);
        }

        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        // Tab Informed Consent hanya tampil kalau di SOAP dipilih "Ya", jadi halaman ini
        // selalu boleh dibuka dari tab tersebut (tidak lagi bergantung pada isi database).
        return view('pemeriksaan.informed-consent', [
            'poli'      => $poli,
            'rm'        => $no_rm,
            'namaPoli'  => $this->daftarPoli[$poli],
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rekam'     => $kunjungan->rekamMedis,
        ]);
    }

    // ==========================================================
    // DIAGNOSA
    // ==========================================================

    public function diagnosa($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

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
            'diagnosis_utama'      => 'required|string|max:255',
            'kode_icd10'           => 'nullable|string|max:20',
            'diagnosis_sekunder'   => 'required|array|min:1',
            'diagnosis_sekunder.*' => 'nullable|string|max:255',
            'tindakan'             => 'required|array|min:1',
            'tindakan.*'           => 'nullable|string|max:255',
        ], $this->pesanValidasi());

        // Beberapa kotak isian (Diagnosis Sekunder & Tindakan) dikirim sebagai
        // array (boleh ditambah lebih dari satu lewat tombol "+ Tambah"),
        // sedangkan kolom di tabel rekam_medis cuma satu string per pasien.
        // Baris kosong dibuang, sisanya digabung dengan ", ".
        // Pemisah ' | ' (bukan ', ') karena judul ICD-10 sering mengandung koma.
        $data['diagnosis_sekunder'] = implode(' | ', array_filter(
            array_map('trim', $data['diagnosis_sekunder'])
        ));

        $data['tindakan'] = implode(', ', array_filter(
            array_map('trim', $data['tindakan'])
        ));

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

        $request->validate([
            'nama_obat'         => 'required|string|max:255',
            'sediaan'           => 'required|string|max:255',
            'jumlah_obat'       => 'required|integer|min:1',
            'dosis_obat'        => 'required|string|max:255',
            'frekuensi'         => 'required|string|max:255',
            'tanggal_penulisan' => 'required|date',
        ], $this->pesanValidasi());

        // Obat dicari berdasarkan nama + dosis + sediaan (tanpa peduli huruf besar/kecil),
        // karena satu nama obat bisa punya beberapa dosis ("Paracetamol" 500 mg dan 650 mg).
        // Kalau belum ada, otomatis dibuat di Data Obat. Nama disimpan TANPA dosis;
        // kalau dosis terlanjur diketik di nama ("Paracetamol 500mg"), dipisahkan otomatis.
        $namaObat = trim($request->nama_obat);
        $sediaan  = trim($request->sediaan);
        $dosis    = Obat::rapikanDosis($request->dosis_obat);

        [$namaBersih, $dosisDariNama] = Obat::pisahDosis($namaObat);
        $namaObat = $namaBersih;
        $dosis    = $dosis ?? $dosisDariNama;

        $obat = Obat::whereRaw('LOWER(nama_obat) = ?', [mb_strtolower($namaObat)])
            ->whereRaw('LOWER(sediaan) = ?', [mb_strtolower($sediaan)])
            ->where(fn ($q) => $dosis === null ? $q->whereNull('dosis') : $q->where('dosis', $dosis))
            ->first();

        if (! $obat) {
            $obat = Obat::create([
                'nama_obat' => $namaObat,
                'dosis'     => $dosis,
                'sediaan'   => $sediaan,
            ]);
        }

        $data = $request->only(['jumlah_obat', 'dosis_obat', 'frekuensi', 'tanggal_penulisan']);
        $data['kunjungan_id'] = $kunjungan->id;
        $data['obat_id']      = $obat->id;

        Resep::create($data);

        session()->flash('popup', [
            'type'    => 'success',
            'title'   => 'Resep Tersimpan!',
            'message' => 'Resep obat berhasil disimpan dan sudah masuk ke antrian Farmasi.',
        ]);

        return redirect()->route('pemeriksaan.resep', [$poli, $no_rm]);
    }

    // ==========================================================
    // CLINICAL PATHWAY
    // Data yang sudah disimpan dimuat kembali supaya form terisi lagi
    // ketika tab Clinical Pathway dibuka ulang (tidak perlu input ulang).
    // Penyimpanan (POST) ditangani ClinicalPathwayController@store.
    // ==========================================================

    public function clinicalPathway($poli, $no_rm)
    {
        $kunjungan = $this->carikanKunjungan($poli, $no_rm);

        $pathway = \App\Models\ClinicalPathway::with('kunjungan')
            ->where('no_rm', $no_rm)
            ->where('poli', $poli)
            ->first();

        $riwayat = \App\Models\ClinicalPathway::withCount('kunjungan as terisi')
            ->latest('updated_at')
            ->get();

        $daftarDokter = \App\Models\User::where('role', 'dokter')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->pluck('nama_lengkap');

        // ------------------------------------------------------------------
        // Data otomatis dari tab Diagnosa & Resep (dipakai sebagai isian awal
        // selama Clinical Pathway belum pernah disimpan / kolomnya masih kosong)
        // ------------------------------------------------------------------
        $kunjungan->load(['resep.obat']);
        $rekam = $kunjungan->rekamMedis;
        $potong = fn ($teks) => mb_substr((string) $teks, 0, 255);

        $autoHeader = [
            'diagnosis'  => $potong(optional($rekam)->diagnosis_utama),
            'kode_icd10' => $potong(optional($rekam)->kode_icd10),
        ];

        $namaObat = [];
        $ketObat  = [];
        foreach ($kunjungan->resep as $r) {
            $nama = trim(optional($r->obat)->nama_obat . ' ' . optional($r->obat)->dosis);
            $namaObat[] = $nama;
            $ketObat[]  = $r->dosis_obat . ', ' . $r->frekuensi . ', jumlah ' . $r->jumlah_obat;
        }
        if (count($ketObat) > 1) {
            foreach ($ketObat as $n => $ket) {
                $ketObat[$n] = $namaObat[$n] . ': ' . $ket;
            }
        }

        $autoKunjungan = [
            'diagnosis_utama'    => $potong(optional($rekam)->diagnosis_utama),
            'kode_utama'         => $potong(optional($rekam)->kode_icd10),
            'diagnosis_penyerta' => $potong(str_replace(' | ', '; ', (string) optional($rekam)->diagnosis_sekunder)),
            'tindakan_nama'      => $potong(optional($rekam)->tindakan),
            'obat_nama'          => $potong(implode(', ', $namaObat)),
            'obat_keterangan'    => $potong(implode(' | ', $ketObat)),
        ];

        return view('pemeriksaan.clinical-pathway', [
            'poli'      => $poli,
            'namaPoli'  => $this->daftarPoli[$poli],
            'kunjungan' => $kunjungan,
            'pasien'    => $kunjungan->pasien,
            'rm'        => $no_rm,
            'pathway'   => $pathway,
            'riwayat'   => $riwayat,
            'daftarDokter' => $daftarDokter,
            'autoHeader'   => $autoHeader,
            'autoKunjungan' => $autoKunjungan,
        ]);
    }
}
