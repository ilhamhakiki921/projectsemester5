<?php

namespace App\Http\Controllers;

use App\Models\AutentikasiPemeriksaan;
use App\Models\GeneralConsent;
use App\Models\InformedConsent;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\SuratMedis;
use App\Models\SuratRujukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class RekamMedisController extends Controller
{
    public function index()
    {
        // Satu baris = satu pasien (berkas rekam medis), bukan satu kunjungan.
        $pasienList = Pasien::with([
                'generalConsent',
                'kunjungan' => fn ($q) => $q->orderByDesc('tanggal_kunjungan')->orderByDesc('id'),
            ])
            ->withCount('kunjungan')
            ->orderBy('no_rm')
            ->get();

        // Tab "Koding & Verifikasi": daftar kunjungan beserta diagnosa & kode ICD-10
        $daftarKoding = Kunjungan::with(['pasien', 'rekamMedis'])
            ->latest('tanggal_kunjungan')
            ->latest('id')
            ->get();

        return view('rekam-medis.index', [
            'pasienList'   => $pasienList,
            'daftarKoding' => $daftarKoding,
            'namaPoli'     => Poli::daftar(),
        ]);
    }

    /**
     * Berkas rekam medis lengkap satu pasien (tombol "Buka Berkas").
     */
    public function berkas(Pasien $pasien)
    {
        $pasien->load('generalConsent');

        $kunjungan = Kunjungan::with(['rekamMedis', 'resep.obat'])
            ->where('pasien_id', $pasien->id)
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('id')
            ->get();

        // Semua resep dari seluruh kunjungan, terbaru di atas
        $resep = $kunjungan->flatMap(function ($k) {
            return $k->resep->map(function ($r) use ($k) {
                $r->setRelation('kunjungan', $k);
                return $r;
            });
        })->values();

        // Permintaan & hasil penunjang (laboratorium) pasien ini
        try {
            $penunjang = DB::table('permintaan_penunjang')
                ->where('no_rm', $pasien->no_rm)
                ->orderByDesc('id')
                ->get()
                ->map(function ($r) {
                    $r->daftar = json_decode($r->pemeriksaan, true) ?: [];
                    return $r;
                });
        } catch (\Throwable $e) {
            $penunjang = collect();
        }

        // Informed Consent dari seluruh kunjungan pasien (poli apa pun: Gigi, Umum, dst.)
        try {
            $informedConsent = InformedConsent::whereIn('kunjungan_id', $kunjungan->pluck('id'))
                ->get()
                ->keyBy('kunjungan_id');
        } catch (\Throwable $e) {
            report($e);   // tabel informed_consent belum dibuat (migrasi belum dijalankan)
            $informedConsent = collect();
        }

        // Surat rujukan (internal & eksternal) dari seluruh kunjungan pasien, terbaru di atas
        try {
            $rujukan = SuratRujukan::whereIn('kunjungan_id', $kunjungan->pluck('id'))
                ->orderByDesc('tanggal_surat')
                ->orderByDesc('id')
                ->get();
        } catch (\Throwable $e) {
            report($e);   // tabel surat_rujukan belum dibuat (migrasi belum dijalankan)
            $rujukan = collect();
        }

        // Autentikasi (tanda tangan dokter) per kunjungan
        try {
            $autentikasi = AutentikasiPemeriksaan::whereIn('kunjungan_id', $kunjungan->pluck('id'))
                ->get()
                ->keyBy('kunjungan_id');
        } catch (\Throwable $e) {
            report($e);   // tabel autentikasi_kunjungan belum dibuat (migrasi belum dijalankan)
            $autentikasi = collect();
        }

        // Surat Medis (Surat Keterangan Sakit & Sehat) dari seluruh kunjungan pasien, terbaru di atas
        try {
            $suratMedis = SuratMedis::whereIn('kunjungan_id', $kunjungan->pluck('id'))
                ->orderByDesc('tanggal_surat')
                ->orderByDesc('id')
                ->get();
        } catch (\Throwable $e) {
            report($e);   // tabel surat_medis belum dibuat (migrasi belum dijalankan)
            $suratMedis = collect();
        }

        return view('rekam-medis.berkas', [
            'autentikasi'     => $autentikasi,
            'rujukan'         => $rujukan,
            'pasien'          => $pasien,
            'kunjungan'       => $kunjungan,
            'resep'           => $resep,
            'penunjang'       => $penunjang,
            'informedConsent' => $informedConsent,
            'suratMedis'      => $suratMedis,
            'namaPoli'  => Poli::daftar(),
        ]);
    }

    /**
     * Perbarui (atau buat) General Consent pasien dari halaman berkas.
     */
    public function consentUpdate(Request $request, Pasien $pasien)
    {
        $jumlahPernyataan = count(GeneralConsent::PERNYATAAN);

        $data = $request->validate([
            'nama_penyetuju'   => 'required|string|max:255',
            'hubungan'         => 'required|string|max:100',
            'saksi'            => 'nullable|string|max:255',
            'petugas_pencatat' => 'nullable|string|max:255',
            'pendamping'       => 'nullable|string|max:255',
            'pernyataan'       => 'nullable|array',
            'pernyataan.*'     => 'integer|between:0,' . ($jumlahPernyataan - 1),
        ], [
            'nama_penyetuju.required' => 'Nama penandatangan wajib diisi.',
            'hubungan.required'       => 'Hubungan dengan pasien wajib dipilih.',
        ]);

        $dipilih = array_values(array_unique(array_map('intval', $data['pernyataan'] ?? [])));

        // Pernyataan bersifat Wajib harus dicentang semua; yang Opsional boleh tidak
        if (array_diff(GeneralConsent::indeksWajib(), $dipilih)) {
            return back()
                ->withInput()
                ->withErrors(['pernyataan' => 'Pernyataan yang bersifat Wajib harus dicentang semua.']);
        }

        $pasienSendiri = $data['hubungan'] === 'Pasien sendiri';

        // Tanggal & jam persetujuan otomatis = saat formulir disimpan
        $waktu = now();

        $isi = [
            'pemberi_persetujuan' => $pasienSendiri ? 'Pasien' : 'Wali',
            'nama_penyetuju'      => $data['nama_penyetuju'],
            'hubungan'            => $pasienSendiri ? null : $data['hubungan'],
            'setuju'              => true,
            'disetujui_pada'      => $waktu,
        ];

        // Kolom tambahan hanya diisi kalau migrasinya sudah dijalankan
        $tambahan = [
            'pendamping'        => $data['pendamping'] ?? null,
            'saksi'             => $data['saksi'] ?? null,
            'pernyataan_setuju' => $dipilih,
            'petugas_pencatat'  => ! empty($data['petugas_pencatat'])
                                        ? $data['petugas_pencatat']
                                        : (Auth::check()
                                            ? (Auth::user()->nama_lengkap ?? Auth::user()->name ?? null)
                                            : null),
        ];

        foreach ($tambahan as $kolom => $nilai) {
            if (Schema::hasColumn('general_consent', $kolom)) {
                $isi[$kolom] = $nilai;
            }
        }

        GeneralConsent::updateOrCreate(['pasien_id' => $pasien->id], $isi);

        return redirect()
            ->to(route('rekam-medis.berkas', $pasien->id) . '#consent')
            ->with('sukses_consent', 'General Consent berhasil diperbarui.');
    }

    /**
     * Formulir General Consent siap cetak (membuka dialog print otomatis).
     */
    public function consentCetak(Pasien $pasien)
    {
        $pasien->load('generalConsent');

        return view('rekam-medis.consent-cetak', [
            'pasien'  => $pasien,
            'consent' => $pasien->generalConsent,
        ]);
    }

    /**
     * Simpan kode ICD-10 hasil koding dan tandai Terverifikasi.
     */
    public function kodingUpdate(Request $request, Kunjungan $kunjungan)
    {
        $data = $request->validate([
            'kode_icd10' => 'required|string|max:20',
        ], [
            'kode_icd10.required' => 'Kode ICD-10 wajib diisi.',
        ]);

        $rekam = $kunjungan->rekamMedis;

        if (! $rekam) {
            abort(404, 'Rekam medis untuk kunjungan ini belum ada.');
        }

        $rekam->kode_icd10 = strtoupper(trim($data['kode_icd10']));

        if (Schema::hasColumn('rekam_medis', 'status_koding')) {
            $rekam->status_koding = 'Terverifikasi';
        }

        $rekam->save();

        return redirect()
            ->to(route('rekam-medis.berkas', $kunjungan->pasien_id) . '#soap')
            ->with('sukses_koding', 'Koding berhasil disimpan dan diverifikasi.')
            ->with('koding_kunjungan_id', $kunjungan->id);
    }

    /**
     * Edit data pemeriksaan (tanda vital + SOAP) dari tab "Riwayat Pemeriksaan & SOAP".
     * Hanya boleh selama belum Terverifikasi; kalau sudah dikoding & diverifikasi, ditolak.
     */
    public function soapUpdate(Request $request, Kunjungan $kunjungan)
    {
        $rekam = $kunjungan->rekamMedis;

        if (! $rekam) {
            abort(404, 'Rekam medis untuk kunjungan ini belum ada.');
        }

        $kembali = route('rekam-medis.berkas', $kunjungan->pasien_id) . '#soap';

        // Penjagaan di sisi server (tombol di tampilan bisa saja dilewati)
        if ($rekam->sudahTerverifikasi()) {
            return redirect()->to($kembali)
                ->with('gagal_soap', 'Rekam medis ini sudah terverifikasi sehingga tidak dapat diedit lagi.')
                ->with('koding_kunjungan_id', $kunjungan->id);
        }

        $validator = Validator::make($request->all(), [
            'tb'               => 'required|string|max:50',
            'berat_badan'      => 'required|string|max:50',
            'imt'              => 'nullable|string|max:50',
            'suhu_badan'       => 'required|string|max:50',
            'td'               => 'required|string|max:50',
            'detak_jantung'    => 'required|string|max:50',
            'respiratory_rate' => 'nullable|string|max:50',
            'saturasi'         => 'nullable|string|max:50',
            'gcs'              => 'nullable|string|max:50',
            'subjective'       => 'required|string',
            'objective'        => 'required|string',
            'assessment'       => 'required|string',
            'plan'             => 'required|string',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->to($kembali)
                ->withErrors($validator, 'editSoap')
                ->withInput()
                ->with('edit_soap_kunjungan_id', $kunjungan->id);
        }

        $data = $validator->validated();

        // IMT dihitung ulang di server: BB (kg) / (TB (m))^2, TB dalam cm
        $tb = (float) str_replace(',', '.', $data['tb']);
        $bb = (float) str_replace(',', '.', $data['berat_badan']);
        if ($tb > 0 && $bb > 0) {
            $data['imt'] = number_format($bb / (($tb / 100) ** 2), 2, '.', '');
        }

        $rekam->fill($data)->save();

        return redirect()->to($kembali)
            ->with('sukses_soap', 'Data pemeriksaan & SOAP berhasil diperbarui.')
            ->with('koding_kunjungan_id', $kunjungan->id);
    }

    /**
     * Lembar autentikasi siap cetak untuk kunjungan tertentu (dari tab Autentikasi di berkas).
     * Syarat sama dengan halaman Pemeriksaan: data terisi, status Selesai, dan sudah ditandatangani.
     */
    public function autentikasiCetak(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien', 'rekamMedis');
        $rm = $kunjungan->rekamMedis;

        $autentikasi = AutentikasiPemeriksaan::where('kunjungan_id', $kunjungan->id)->first();

        $lengkap = $rm
            && filled($rm->keluhan_utama)
            && (filled($rm->subjective) || filled($rm->assessment))
            && filled($rm->diagnosis_utama);

        abort_unless(
            $autentikasi && $lengkap && $kunjungan->status === 'Selesai',
            404,
            'Pemeriksaan ini belum diautentikasi.'
        );

        return view('pemeriksaan.autentikasi-cetak', [
            'pasien'      => $kunjungan->pasien,
            'kunjungan'   => $kunjungan,
            'autentikasi' => $autentikasi,
            'namaPoli'    => Poli::daftar()[$kunjungan->poli] ?? ucfirst($kunjungan->poli),
        ]);
    }

    /**
     * Cetak Informed Consent yang sudah diisi di menu Pemeriksaan (Poli Umum & Poli Gigi).
     */
    public function informedConsentCetak(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien');

        $ic = InformedConsent::where('kunjungan_id', $kunjungan->id)->first();

        abort_unless($ic && ! empty($ic->data), 404, 'Informed Consent untuk kunjungan ini belum diisi.');

        // Isian tersimpan berbentuk daftar pasangan [nama_field, nilai] -> peta nama => [nilai, ...]
        $peta = [];
        foreach ($ic->data as $baris) {
            if (! is_array($baris) || ! isset($baris[0])) {
                continue;
            }
            $peta[$baris[0]][] = $baris[1] ?? '';
        }

        return view('rekam-medis.informed-consent-cetak', [
            'pasien'    => $kunjungan->pasien,
            'kunjungan' => $kunjungan,
            'ic'        => $ic,
            'd'         => $peta,
            'namaPoli'  => Poli::daftar()[$kunjungan->poli] ?? 'Poli ' . ucfirst($kunjungan->poli),
        ]);
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load('pasien', 'rekamMedis', 'resep.obat');

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
