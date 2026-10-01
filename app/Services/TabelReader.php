<?php

namespace App\Services;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

/**
 * Pembaca tabel sederhana untuk file .xlsx dan .csv (tanpa library tambahan).
 * Hasilnya: daftar baris, tiap baris berupa array teks per kolom (A, B, C, ...).
 */
class TabelReader
{
    public static function baca(string $path, string $ext): array
    {
        return strtolower($ext) === 'xlsx' ? self::xlsx($path) : self::csv($path);
    }

    protected static function csv(string $path): array
    {
        $isi = file_get_contents($path);
        if ($isi === false) {
            throw new RuntimeException('File tidak bisa dibaca.');
        }

        $isi = preg_replace('/^\xEF\xBB\xBF/', '', $isi);
        if (! mb_check_encoding($isi, 'UTF-8')) {
            $isi = mb_convert_encoding($isi, 'UTF-8', 'Windows-1252');
        }

        // Excel versi Indonesia biasanya menyimpan CSV dengan pemisah titik koma.
        $barisPertama = strtok($isi, "\n") ?: '';
        $pemisah = substr_count($barisPertama, ';') > substr_count($barisPertama, ',') ? ';' : ',';

        $h = fopen('php://temp', 'r+');
        fwrite($h, $isi);
        rewind($h);

        $rows = [];
        while (($r = fgetcsv($h, 0, $pemisah, '"', '')) !== false) {
            $r = array_map(fn ($v) => trim((string) $v), $r);
            if (implode('', $r) === '') {
                continue;
            }
            $rows[] = $r;
        }
        fclose($h);

        return $rows;
    }

    protected static function xlsx(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP "zip" belum aktif di server ini. Aktifkan extension=zip di php.ini, atau simpan file sebagai CSV lalu impor.');
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('File Excel tidak bisa dibuka. Pastikan formatnya .xlsx.');
        }

        try {
            // 1) Daftar teks bersama (shared strings)
            $shared = [];
            if (($x = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
                $sx = simplexml_load_string($x, SimpleXMLElement::class, LIBXML_NONET | LIBXML_PARSEHUGE);
                foreach ($sx->si ?? [] as $si) {
                    $shared[] = self::gabungTeks($si);
                }
            }

            // 2) Cari file sheet pertama
            $sheetPath = 'xl/worksheets/sheet1.xml';
            $wbXml  = $zip->getFromName('xl/workbook.xml');
            $relXml = $zip->getFromName('xl/_rels/workbook.xml.rels');
            if ($wbXml !== false && $relXml !== false) {
                $wb  = simplexml_load_string($wbXml, SimpleXMLElement::class, LIBXML_NONET);
                $rel = simplexml_load_string($relXml, SimpleXMLElement::class, LIBXML_NONET);
                $first = $wb->sheets->sheet[0] ?? null;
                if ($first) {
                    $rid = (string) $first->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
                    foreach ($rel->Relationship as $r) {
                        if ((string) $r['Id'] === $rid) {
                            $t = ltrim((string) $r['Target'], '/');
                            $sheetPath = str_starts_with($t, 'xl/') ? $t : 'xl/' . $t;
                            break;
                        }
                    }
                }
            }

            $sheetXml = $zip->getFromName($sheetPath);
            if ($sheetXml === false) {
                throw new RuntimeException('Sheet pertama pada file Excel tidak ditemukan.');
            }
        } finally {
            $zip->close();
        }

        // 3) Baca baris & sel
        $sheet = simplexml_load_string($sheetXml, SimpleXMLElement::class, LIBXML_NONET | LIBXML_PARSEHUGE);
        $rows = [];

        foreach ($sheet->sheetData->row ?? [] as $row) {
            $cells = [];
            $maks = -1;

            foreach ($row->c as $c) {
                $idx = self::indexKolom((string) $c['r']);
                $tipe = (string) $c['t'];

                if ($tipe === 's') {
                    $val = $shared[(int) $c->v] ?? '';
                } elseif ($tipe === 'inlineStr') {
                    $val = self::gabungTeks($c->is);
                } else {
                    $val = (string) $c->v;
                }

                $cells[$idx] = trim($val);
                $maks = max($maks, $idx);
            }

            if ($maks < 0) {
                continue;
            }

            $out = [];
            for ($i = 0; $i <= $maks; $i++) {
                $out[] = $cells[$i] ?? '';
            }
            if (implode('', $out) === '') {
                continue;
            }
            $rows[] = $out;
        }

        return $rows;
    }

    /** Gabungkan semua teks <t> (termasuk rich text), kecuali catatan fonetik <rPh>. */
    protected static function gabungTeks(?SimpleXMLElement $node): string
    {
        if (! $node) {
            return '';
        }

        $teks = '';
        foreach ($node->xpath('.//*[local-name()="t"][not(ancestor::*[local-name()="rPh"])]') ?: [] as $t) {
            $teks .= (string) $t;
        }

        return $teks;
    }

    /** "A1" -> 0, "B7" -> 1, "AA3" -> 26 */
    protected static function indexKolom(string $ref): int
    {
        if (! preg_match('/^([A-Z]+)/i', $ref, $m)) {
            return 0;
        }

        $n = 0;
        foreach (str_split(strtoupper($m[1])) as $ch) {
            $n = $n * 26 + (ord($ch) - 64);
        }

        return $n - 1;
    }
}
