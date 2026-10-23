<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Resep;
use Illuminate\Database\Seeder;

/**
 * Data uji coba untuk mencoba akun PERAWAT (perawat tidak bisa mendaftarkan pasien,
 * jadi pasien & kunjungan hari ini dibuatkan di sini).
 *
 * Jalankan:  php artisan db:seed --class=DataUjiPerawatSeeder
 *
 * Isinya:
 *  - 4 pasien dengan kunjungan HARI INI berstatus "Menunggu" (belum ada rekam medis)
 *    -> dipakai untuk mencoba input Anamnesa, SOAP, Clinical Pathway, Rujukan, Autentikasi
 *       dan melihat tab Diagnosa/Resep/Penunjang/Surat Medis yang hanya bisa dilihat.
 *  - 1 pasien dengan kunjungan "Sedang Diperiksa" yang anamnesa + SOAP + diagnosa + resepnya
 *    sudah terisi, belum terverifikasi
 *    -> dipakai untuk mencoba Rekam Medis (Edit SOAP bisa, Koding terkunci).
 *
 * Aman dijalankan berulang kali (tidak menimpa data yang sudah kamu isi / ubah).
 * Nomor RM 000101-000105 sengaja dipisah dari data lain.
 */
class DataUjiPerawatSeeder extends Seeder
{
    public function run(): void
    {
        $hariIni = now()->toDateString();

        $daftar = [
            ['000101', 'Budi Santoso',   'Laki-laki', '1990-04-20', '3372010004900101', 'Jl. Merdeka No. 10, Surakarta',  'umum',  'BPJS',  'Menunggu'],
            ['000102', 'Siti Aminah',    'Perempuan', '1995-08-11', '3372015108950102', 'Jl. Sudirman No. 5, Surakarta',   'umum',  'Umum',  'Menunggu'],
            ['000103', 'Andi Wijaya',    'Laki-laki', '1988-01-02', '3372010201880103', 'Jl. Diponegoro No. 3, Surakarta', 'gigi',  'Umum',  'Menunggu'],
            ['000104', 'Dewi Lestari',   'Perempuan', '1979-12-25', '3372016512790104', 'Jl. Slamet Riyadi No. 21, Surakarta', 'dalam', 'BPJS', 'Menunggu'],
            ['000105', 'Rahmat Hidayat', 'Laki-laki', '1983-06-30', '3372013006830105', 'Jl. Veteran No. 8, Surakarta',   'umum',  'Umum',  'Sedang Diperiksa'],
        ];

        $kunjunganRahmat = null;

        foreach ($daftar as [$noRm, $nama, $jk, $lahir, $nik, $alamat, $poli, $bayar, $status]) {
            $pasien = Pasien::firstOrCreate(
                ['no_rm' => $noRm],
                [
                    'nama'          => $nama,
                    'jenis_kelamin' => $jk,
                    'tanggal_lahir' => $lahir,
                    'nik'           => $nik,
                    'no_telepon'    => '0812' . substr($nik, -8),
                    'alamat'        => $alamat,
                ]
            );

            $kunjungan = Kunjungan::firstOrCreate(
                [
                    'pasien_id'         => $pasien->id,
                    'tanggal_kunjungan' => $hariIni,
                    'poli'              => $poli,
                ],
                [
                    'no_antrian' => Kunjungan::nomorAntrianBerikutnya($poli, $hariIni),
                    'dokter'     => (string) config('dokter.daftar.' . $poli . '.0', 'Dokter Jaga'),
                    'pembayaran' => $bayar,
                    'status'     => $status,
                ]
            );

            if ($noRm === '000105') {
                $kunjunganRahmat = $kunjungan;
            }
        }

        // ---- Pasien 000105: rekam medis sudah terisi (belum terverifikasi) ----
        if ($kunjunganRahmat && ! RekamMedis::where('kunjungan_id', $kunjunganRahmat->id)->exists()) {
            RekamMedis::create([
                'kunjungan_id'               => $kunjunganRahmat->id,

                // Anamnesa
                'keluhan_utama'              => 'Demam dan batuk sejak 3 hari',
                'riwayat_penyakit_sekarang'  => 'Demam naik turun, batuk berdahak, nyeri tenggorokan.',
                'riwayat_penyakit_terdahulu' => 'Tidak ada',
                'riwayat_alergi'             => 'Tidak ada',
                'riwayat_pengobatan'         => 'Paracetamol, belum membaik',
                'tb'                         => 168,
                'berat_badan'                => '65',
                'imt'                        => '23.03',
                'suhu_badan'                 => 38,
                'sistole'                    => 120,
                'diastole'                   => 80,
                'detak_jantung'              => '88',
                'respiratory_rate'           => '20',
                'saturasi'                   => '98',
                'gcs'                        => 15,

                // SOAP
                'subjective'                 => 'Pasien mengeluh demam dan batuk berdahak sejak 3 hari.',
                'objective'                  => 'Suhu 38 C, faring hiperemis, paru vesikuler, tidak ada ronki.',
                'assessment'                 => 'Infeksi saluran pernapasan atas (ISPA).',
                'plan'                       => 'Terapi simtomatik, banyak istirahat dan minum air putih.',

                // Diagnosa (diisi dokter; perawat hanya bisa melihat)
                'diagnosis_utama'            => 'Acute upper respiratory infection',
                'kode_icd10'                 => 'J06.9',
            ]);
        }

        // ---- Resep untuk pasien 000105 (kalau data obat sudah ada) ----
        $obat = Obat::first();

        if ($kunjunganRahmat && $obat && ! Resep::where('kunjungan_id', $kunjunganRahmat->id)->exists()) {
            Resep::create([
                'kunjungan_id'      => $kunjunganRahmat->id,
                'obat_id'           => $obat->id,
                'jumlah_obat'       => 10,
                'dosis_obat'        => $obat->dosis ?: '1 tablet',
                'frekuensi'         => '3x sehari',
                'tanggal_penulisan' => now()->toDateString(),
            ]);
        }
    }
}
