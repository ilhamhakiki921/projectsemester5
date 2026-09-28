<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Klien untuk WHO ICD-API (ICD-10).
 *
 * Alur: minta access token (OAuth2 client credentials) -> panggil
 * https://id.who.int/icd/release/10/{rilis}/... dengan header Bearer token.
 *
 * CATATAN: endpoint ICD-10 di WHO hanya untuk "browse" (ambil kode / turunan),
 * TIDAK punya fitur pencarian teks. Fitur cari ada di ICD-11 saja. Karena itu
 * data ICD-10 diambil dari WHO lalu disimpan di tabel lokal (php artisan
 * icd10:import), dan pencarian dilakukan di tabel lokal itu.
 */
class WhoIcdService
{
    public function baseUrl(): string
    {
        return rtrim(config('services.who_icd.base_url'), '/')
            . '/icd/release/10/' . config('services.who_icd.release');
    }

    public function token(): string
    {
        return Cache::remember('who_icd.token', now()->addMinutes(50), function () {
            $id     = config('services.who_icd.client_id');
            $secret = config('services.who_icd.client_secret');

            if (! $id || ! $secret) {
                throw new RuntimeException('ICD_CLIENT_ID / ICD_CLIENT_SECRET belum diisi di file .env');
            }

            try {
                $res = Http::asForm()->timeout(15)->post(config('services.who_icd.token_url'), [
                    'client_id'     => $id,
                    'client_secret' => $secret,
                    'scope'         => 'icdapi_access',
                    'grant_type'    => 'client_credentials',
                ]);
            } catch (ConnectionException $e) {
                throw new RuntimeException('Tidak bisa terhubung ke server token WHO: ' . $e->getMessage());
            }

            if ($res->failed() || ! $res->json('access_token')) {
                throw new RuntimeException('Gagal meminta token WHO (HTTP ' . $res->status() . '). Cek Client ID/Secret.');
            }

            return $res->json('access_token');
        });
    }

    protected function headers(): array
    {
        return [
            'Accept'          => 'application/json',
            'Accept-Language' => config('services.who_icd.language', 'en'),
            'API-Version'     => 'v2',
        ];
    }

    protected function https(string $uri): string
    {
        // Di dalam respons WHO, URI ditulis http://, padahal harus dipanggil via https://
        return str_replace('http://', 'https://', $uri);
    }

    /** Ambil satu entitas (bab / blok / kategori). Return null kalau tidak ditemukan. */
    public function get(string $uri): ?array
    {
        $url = $this->https($uri);

        try {
            $res = Http::withToken($this->token())->withHeaders($this->headers())
                ->timeout(20)->retry(3, 500, throw: false)->get($url);

            if ($res->status() === 401) {          // token kedaluwarsa -> minta ulang sekali
                Cache::forget('who_icd.token');
                $res = Http::withToken($this->token())->withHeaders($this->headers())
                    ->timeout(20)->get($url);
            }
        } catch (ConnectionException $e) {
            throw new RuntimeException('Tidak bisa terhubung ke WHO ICD-API: ' . $e->getMessage());
        }

        if ($res->status() === 404) {
            return null;
        }

        if ($res->failed()) {
            throw new RuntimeException('WHO ICD-API membalas HTTP ' . $res->status() . ' untuk ' . $url);
        }

        return $res->json();
    }

    public function getCode(string $code): ?array
    {
        return $this->get($this->baseUrl() . '/' . rawurlencode(strtoupper($code)));
    }

    /** Ambil banyak entitas sekaligus (paralel). Yang gagal di-pool dicoba lagi satu per satu. */
    public function getMany(array $uris): array
    {
        $uris = array_values($uris);
        if (! $uris) {
            return [];
        }

        $token   = $this->token();
        $headers = $this->headers();

        $responses = Http::pool(fn (Pool $pool) => array_map(
            fn ($uri, $i) => $pool->as((string) $i)->withToken($token)->withHeaders($headers)
                ->timeout(20)->get($this->https($uri)),
            $uris,
            array_keys($uris)
        ));

        $out = [];
        foreach ($uris as $i => $uri) {
            $r = $responses[$i] ?? null;
            $out[] = ($r instanceof Response && $r->successful()) ? $r->json() : $this->get($uri);
        }

        return array_values(array_filter($out));
    }

    /** Rapikan respons WHO jadi array sederhana. */
    public static function parse(array $n): array
    {
        $title = $n['title'] ?? '';
        if (is_array($title)) {
            $title = $title['@value'] ?? '';
        }

        $last = fn (?string $uri) => $uri ? rawurldecode(basename(parse_url($uri, PHP_URL_PATH) ?: '')) : '';

        $code = $n['code'] ?? $last($n['@id'] ?? '');

        $kind = $n['classKind'] ?? null;
        if (! in_array($kind, ['chapter', 'block', 'category'], true)) {
            $kind = str_contains($code, '-') ? 'block'
                : (preg_match('/^[A-Z]\d{2}(\.\d+)?$/', $code) ? 'category' : 'chapter');
        }

        $parents = (array) ($n['parent'] ?? []);

        return [
            'code'        => $code,
            'title'       => trim(strip_tags((string) $title)),
            'kind'        => $kind,
            'parent_code' => $parents ? $last(end($parents)) : null,
            'children'    => array_values((array) ($n['child'] ?? [])),
        ];
    }
}
