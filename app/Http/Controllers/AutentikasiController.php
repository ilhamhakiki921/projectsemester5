<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\AutentikasiPemeriksaan;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutentikasiController extends Controller
{
    protected $daftarPoli = [];

    public function __construct()
    {
        $this->daftarPoli = Poli::daftar();
    }

    /** Pasien + kunjungan terbaru di poli ini (sama seperti tab Pemeriksaan lain). */
    protected function konteks($poli, $no_rm): array
    {
        abort_unless(array_key_exists($poli, $this->daftarPoli), 404);

        $pasien = Pasien::where('no_rm', $no_rm)->first();
        abort_unless($pasien, 404, 'Data pasien tidak ditemukan.');

        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)
            ->where('poli', $poli)->latest('id')->first();
        abort_unless($kunjungan, 404, 'Data kunjungan pasien tidak ditemukan di poli ini.');

        return [$pasien, $kunjungan];
    }

    /** Bagian wajib yang belum diisi (Asesmen, SOAP, Diagnosa). */
    protected function bagianKosong(Kunjungan $kunjungan): array
    {
        $rm = $kunjungan->rekamMedis;
        $kosong = [];

        if (! filled($rm?->keluhan_utama)) {
            $kosong[] = 'Asesmen';
        }
        if (! filled($rm?->subjective) && ! filled($rm?->assessment)) {
            $kosong[] = 'SOAP';
        }
        if (! filled($rm?->diagnosis_utama)) {
            $kosong[] = 'Diagnosa';
        }

        return $kosong;
    }

    public function index($poli, $no_rm)
    {
        [$pasien, $kunjungan] = $this->konteks($poli, $no_rm);
        $kunjungan->load('rekamMedis');

        $tersimpan   = AutentikasiPemeriksaan::where('kunjungan_id', $kunjungan->id)->first();
        $bagianKosong = $this->bagianKosong($kunjungan);

        // Tampilan "Terautentikasi" hanya untuk kunjungan yang benar-benar sudah dilayani:
        // data Asesmen/SOAP/Diagnosa terisi dan status kunjungan sudah Selesai.
        // Selain itu (belum diisi / belum dilayani) tetap tampil form + tanda tangan.
        $autentikasi = ($tersimpan && ! $bagianKosong && $kunjungan->status === 'Selesai')
            ? $tersimpan
            : null;

        return view('pemeriksaan.autentikasi', [
            'poli'        => $poli,
            'rm'          => $no_rm,
            'pasien'      => $pasien,
            'kunjungan'   => $kunjungan,
            'autentikasi' => $autentikasi,
            'bagianKosong' => $bagianKosong,
            // Nama dokter diambil dari dokter yang diinput saat pendaftaran kunjungan
            'namaDefault' => $tersimpan?->nama_dokter
                ?: ($kunjungan->dokter_tampil ?: (Auth::check() ? Auth::user()->nama_lengkap : '')),
            // Jabatan mengikuti poli tempat dokter bertugas (config/dokter.php)
            'jabatanDefault' => $tersimpan?->jabatan
                ?: config('dokter.jabatan.' . $poli, 'Dokter ' . ucfirst($poli)),
            'storeUrl'    => route('autentikasi.store', [$poli, $no_rm]),
        ]);
    }

    /** Lembar autentikasi siap cetak (dengan kop surat klinik). */
    public function cetak($poli, $no_rm)
    {
        [$pasien, $kunjungan] = $this->konteks($poli, $no_rm);
        $kunjungan->load('rekamMedis');

        $autentikasi = AutentikasiPemeriksaan::where('kunjungan_id', $kunjungan->id)->first();

        abort_unless(
            $autentikasi && ! $this->bagianKosong($kunjungan) && $kunjungan->status === 'Selesai',
            404,
            'Pemeriksaan ini belum diautentikasi.'
        );

        return view('pemeriksaan.autentikasi-cetak', [
            'pasien'      => $pasien,
            'kunjungan'   => $kunjungan,
            'autentikasi' => $autentikasi,
            'namaPoli'    => $this->daftarPoli[$poli] ?? ucfirst($poli),
        ]);
    }

    public function store(Request $request, $poli, $no_rm)
    {
        [$pasien, $kunjungan] = $this->konteks($poli, $no_rm);
        $kunjungan->load('rekamMedis');

        $kosong = $this->bagianKosong($kunjungan);
        if ($kosong) {
            return response()->json([
                'message' => 'Lengkapi dulu data ' . implode(', ', $kosong) . ' sebelum autentikasi.',
            ], 422);
        }

        $data = $request->validate([
            'nama_dokter'  => 'required|string|max:150',
            'jabatan'      => 'required|string|max:100',
            'tanda_tangan' => [
                'required', 'string', 'max:700000',
                function ($attribute, $value, $fail) {
                    if (! str_starts_with($value, 'data:image/png;base64,')) {
                        $fail('Tanda tangan tidak valid.');
                    }
                },
            ],
        ], [
            'required'           => ':attribute wajib diisi.',
            'tanda_tangan.max'   => 'Gambar tanda tangan terlalu besar.',
        ], [
            'nama_dokter'  => 'Nama dokter/petugas',
            'jabatan'      => 'Jabatan',
            'tanda_tangan' => 'Tanda tangan digital',
        ]);

        DB::transaction(function () use ($data, $kunjungan) {
            AutentikasiPemeriksaan::updateOrCreate(
                ['kunjungan_id' => $kunjungan->id],
                [
                    'nama_dokter'       => $data['nama_dokter'],
                    'jabatan'           => $data['jabatan'],
                    'tanda_tangan'      => $data['tanda_tangan'],
                    'waktu_autentikasi' => now(),
                ]
            );

            // Autentikasi = langkah terakhir pemeriksaan -> kunjungan selesai
            $kunjungan->update(['status' => 'Selesai']);
        });

        return response()->json([
            'ok'      => true,
            'message' => 'Pemeriksaan berhasil diautentikasi. Status pasien sekarang "Selesai".',
            'waktu'   => now()->format('d-m-Y H:i'),
        ]);
    }
}
