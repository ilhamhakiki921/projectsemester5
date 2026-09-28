<?php

namespace App\Console\Commands;

use App\Models\Icd10;
use App\Services\WhoIcdService;
use Illuminate\Console\Command;
use RuntimeException;

class ImportIcd10 extends Command
{
    protected $signature = 'icd10:import
                            {--kode= : Uji koneksi saja: ambil 1 kode (mis. J06.9) lalu tampilkan}
                            {--chapter= : Hanya impor 1 bab, angka romawi (mis. X = penyakit sistem napas)}';

    protected $description = 'Tarik data ICD-10 dari WHO ICD-API ke tabel lokal icd10';

    protected int $total = 0;

    protected WhoIcdService $icd;

    public function handle(WhoIcdService $icd): int
    {
        $this->icd = $icd;

        try {
            if ($kode = $this->option('kode')) {
                return $this->uji($kode);
            }

            $root = $icd->get($icd->baseUrl());
            if (! $root) {
                $this->error('Rilis ICD-10 tidak ditemukan. Cek ICD_RELEASE di .env (mis. 2019).');
                return self::FAILURE;
            }

            $chapters = (array) ($root['child'] ?? []);

            if ($only = $this->option('chapter')) {
                $chapters = array_values(array_filter(
                    $chapters,
                    fn ($u) => strcasecmp(basename($u), $only) === 0
                ));
            }

            foreach ($chapters as $uri) {
                $node = $icd->get($uri);
                if (! $node) {
                    continue;
                }
                $this->info('Bab ' . basename($uri) . ' ...');
                $this->walk($node);
            }
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->info("Selesai. {$this->total} data tersimpan/diperbarui.");
        return self::SUCCESS;
    }

    protected function uji(string $kode): int
    {
        $node = $this->icd->getCode($kode);

        if (! $node) {
            $this->warn("Kode {$kode} tidak ditemukan di rilis " . config('services.who_icd.release'));
            return self::FAILURE;
        }

        $p = WhoIcdService::parse($node);
        $this->info("Koneksi OK: {$p['code']} - {$p['title']} ({$p['kind']})");

        return self::SUCCESS;
    }

    /** Simpan node, lalu turun ke anak-anaknya (diambil paralel, 10 per kelompok). */
    protected function walk(array $node): void
    {
        $p = WhoIcdService::parse($node);

        if ($p['code'] === '') {
            return;
        }

        Icd10::updateOrCreate(
            ['code' => $p['code']],
            ['title' => $p['title'], 'kind' => $p['kind'], 'parent_code' => $p['parent_code']]
        );

        if (++$this->total % 250 === 0) {
            $this->line("  {$this->total} data ...");
        }

        foreach (array_chunk($p['children'], 10) as $chunk) {
            foreach ($this->icd->getMany($chunk) as $child) {
                $this->walk($child);
            }
        }
    }
}
