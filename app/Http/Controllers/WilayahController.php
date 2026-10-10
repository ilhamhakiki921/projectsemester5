<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Proxy data Provinsi/Kabupaten/Kecamatan untuk kombobox di form Pendaftaran
 * Pasien Baru.
 *
 * Kenapa harus lewat backend (bukan fetch() langsung dari browser)?
 * Server sumber datanya (emsifa/api-wilayah-indonesia, dihost di GitHub
 * Pages) tidak mengirim header CORS, jadi kalau dipanggil langsung dari
 * JavaScript di browser akan selalu gagal dengan error
 * "blocked by CORS policy". Request server-ke-server (dari sini) tidak
 * kena aturan CORS sama sekali, jadi amanmalah lebih cepat karena hasilnya
 * di-cache.
 */
class WilayahController extends Controller
{
    protected string $baseUrl = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    // Dataset emsifa belum diperbarui sejak pemekaran Papua (2022), jadi
    // 4 provinsi baru ini ditambahkan manual supaya daftar provinsi lengkap.
    // Catatan: karena bukan bagian dari dataset asli, kabupaten/kota di
    // 4 provinsi ini TIDAK tersedia lewat endpoint kabupaten (lihat method
    // kabupaten()) sampai ada sumber data pengganti yang mencakupnya.
    protected array $provinsiTambahan = [
        ['id' => '95', 'name' => 'PAPUA SELATAN'],
        ['id' => '96', 'name' => 'PAPUA TENGAH'],
        ['id' => '97', 'name' => 'PAPUA PEGUNUNGAN'],
        ['id' => '98', 'name' => 'PAPUA BARAT DAYA'],
    ];

    // Desa/kelurahan yang tidak ada di dataset emsifa. Key = id kecamatan.
protected array $kelurahanTambahan = [
    '3521120' => [ // Kec. Paron, Ngawi
        ['id' => '3521120004', 'name' => 'SEMEN'],
    ],
];

    public function provinsi()
    {
        $data = Cache::remember('wilayah.provinsi', now()->addDays(30), function () {
            $res = Http::timeout(10)->get("{$this->baseUrl}/provinces.json");

            return $res->successful() ? $res->json() : [];
        });

        $data = array_merge($data, $this->provinsiTambahan);

        return response()->json($this->rapikanNama($data));
    }

    public function kabupaten(string $provinsiId)
    {
        $data = Cache::remember("wilayah.kabupaten.{$provinsiId}", now()->addDays(30), function () use ($provinsiId) {
            $res = Http::timeout(10)->get("{$this->baseUrl}/regencies/{$provinsiId}.json");

            return $res->successful() ? $res->json() : [];
        });

        return response()->json($this->rapikanNama($data));
    }

public function kecamatan(string $kabupatenId)
{
    $data = Cache::remember("wilayah.kecamatan.{$kabupatenId}", now()->addDays(30), function () use ($kabupatenId) {
        $res = Http::timeout(10)->get("{$this->baseUrl}/districts/{$kabupatenId}.json");

        return $res->successful() ? $res->json() : [];
    });

    return response()->json($this->rapikanNama($data));
}

public function kelurahan(string $kecamatanId)
{
    $data = Cache::remember("wilayah.kelurahan.{$kecamatanId}", now()->addDays(30), function () use ($kecamatanId) {
        $res = Http::timeout(10)->get("{$this->baseUrl}/villages/{$kecamatanId}.json");

        return $res->successful() ? $res->json() : [];
    });

    $data = array_merge($data, $this->kelurahanTambahan[$kecamatanId] ?? []);

    return response()->json($this->rapikanNama($data));
}
    // Dataset asli semua huruf besar ("JAWA TIMUR") -> dirapikan jadi
    // "Jawa Timur" supaya enak dibaca di form.
    protected function rapikanNama(array $data): array
    {
        return array_values(array_map(function ($item) {
            $item['name'] = Str::title(mb_strtolower($item['name']));

            return $item;
        }, $data));
    }
}