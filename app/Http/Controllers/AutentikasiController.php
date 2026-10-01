<?php

namespace App\Http\Controllers;

use App\Models\AutentikasiPemeriksaan;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutentikasiController extends Controller
{
    protected $daftarPoli = [
        'umum'         => 'Poli Umum',
        'gigi'         => 'Poli Gigi',
        'dalam'        => 'Poli Dalam',
        'mikrobiologi' => 'Mikrobiologi',
        'fisioterapi'  => 'Fisioterapi',
    ];

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

        $autentikasi = AutentikasiPemeriksaan::where('kunjungan_id', $kunjungan->id)->first();

        return view('pemeriksaan.autentikasi', [
            'poli'        => $poli,
            'rm'          => $no_rm,
            'pasien'      => $pasien,
            'kunjungan'   => $kunjungan,
            'autentikasi' => $autentikasi,
            'bagianKosong' => $this->bagianKosong($kunjungan),
            'namaDefault' => $autentikasi?->nama_dokter
                ?? (Auth::check() ? Auth::user()->nama_lengkap : $kunjungan->dokter),
            'storeUrl'    => route('autentikasi.store', [$poli, $no_rm]),
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
