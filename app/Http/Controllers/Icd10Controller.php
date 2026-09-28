<?php

namespace App\Http\Controllers;

use App\Models\Icd10;
use App\Services\WhoIcdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Endpoint pencarian ICD-10 untuk kotak Diagnosa (autocomplete).
 * Dipanggil dari browser ke server kita sendiri; Client ID/Secret WHO
 * tidak pernah dikirim ke browser.
 */
class Icd10Controller extends Controller
{
    public function cari(Request $request, WhoIcdService $icd)
    {
        $q = trim(preg_replace('/[%_\\\\]/', '', (string) $request->query('q', '')));

        if (mb_strlen($q) < 2) {
            return response()->json(['data' => [], 'pesan' => null]);
        }

        $hasil = Icd10::where('kind', 'category')
            ->where(function ($w) use ($q) {
                $w->where('code', 'like', $q . '%')
                  ->orWhere('title', 'like', '%' . $q . '%');
            })
            ->orderByRaw('CASE WHEN code LIKE ? THEN 0 ELSE 1 END', [$q . '%'])
            ->orderBy('code')
            ->limit(15)
            ->get(['code', 'title']);

        // Belum ada di tabel lokal, tapi yang diketik berbentuk kode (mis. J06.9)
        // -> tanya langsung ke WHO, lalu simpan supaya berikutnya cepat.
        if ($hasil->isEmpty() && preg_match('/^[A-Za-z]\d{2}(\.\d{1,2})?$/', $q)) {
            try {
                if ($node = $icd->getCode($q)) {
                    $p = WhoIcdService::parse($node);

                    if ($p['kind'] === 'category') {
                        $row = Icd10::updateOrCreate(
                            ['code' => $p['code']],
                            ['title' => $p['title'], 'kind' => $p['kind'], 'parent_code' => $p['parent_code']]
                        );
                        $hasil = collect([$row->only(['code', 'title'])]);
                    }
                }
            } catch (Throwable $e) {
                Log::warning('ICD-10 lookup ke WHO gagal: ' . $e->getMessage());
            }
        }

        $pesan = null;
        if ($hasil->isEmpty() && ! Icd10::exists()) {
            $pesan = 'Data ICD-10 belum diimpor. Jalankan: php artisan icd10:import';
        }

        return response()->json(['data' => $hasil->values(), 'pesan' => $pesan]);
    }
}
