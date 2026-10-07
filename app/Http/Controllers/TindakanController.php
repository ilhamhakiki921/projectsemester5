<?php

namespace App\Http\Controllers;

use App\Models\Tindakan;
use App\Services\TabelReader;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

/**
 * Data Master > Data Tindakan (kode ICD-9 CM / tindakan medis).
 */
class TindakanController extends Controller
{
    /** Contoh valid: 89, 89.7, 99.21, 03.09 */
    private const POLA_KODE = '/^[0-9]{2}(\.[0-9]{1,2})?$/';

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Tindakan::query();

        if ($q !== '') {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $query->where(function ($w) use ($like) {
                $w->where('kode', 'like', $like)
                  ->orWhere('nama', 'like', $like);
            });
        }

        return view('tindakan.index', [
            'tindakans' => $query->orderBy('kode')->paginate(10)->withQueryString(),
            'q'         => $q,
        ]);
    }

    public function store(Request $request)
    {
        $this->normalisasi($request);

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'regex:' . self::POLA_KODE, 'unique:tindakan,kode'],
            'nama' => ['required', 'string', 'max:500'],
        ], $this->pesan());

        Tindakan::create($data);

        return $this->kembali('Tindakan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Tindakan $tindakan)
    {
        $this->normalisasi($request);

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'regex:' . self::POLA_KODE, Rule::unique('tindakan', 'kode')->ignore($tindakan->id)],
            'nama' => ['required', 'string', 'max:500'],
        ], $this->pesan());

        $tindakan->update($data);

        return $this->kembali('Data tindakan berhasil diperbarui.');
    }

    public function destroy(Tindakan $tindakan)
    {
        $tindakan->delete();

        return $this->kembali('Tindakan berhasil dihapus.');
    }

    /**
     * Import dari Excel (.xlsx) atau CSV.
     * Kolom A = Kode ICD-9, kolom B = Nama Tindakan. Baris judul boleh ada.
     * Kode yang sudah ada diperbarui namanya, yang belum ada ditambahkan.
     */
    public function import(Request $request)
    {
        $request->validateWithBag('import', [
            'file' => ['required', 'file', 'max:5120', 'extensions:xlsx,csv'],
        ], [
            'file.required'   => 'Pilih file Excel (.xlsx) atau CSV terlebih dahulu.',
            'file.file'       => 'File tidak valid.',
            'file.max'        => 'Ukuran file maksimal 5 MB.',
            'file.extensions' => 'Format file harus .xlsx atau .csv.',
        ]);

        set_time_limit(300);

        $file = $request->file('file');

        try {
            $rows = TabelReader::baca($file->getRealPath(), $file->getClientOriginalExtension());
        } catch (RuntimeException $e) {
            return back()->withErrors(['file' => $e->getMessage()], 'import');
        }

        $valid = [];      // kode => nama (kode kembar di file: yang terakhir dipakai)
        $dilewati = 0;
        $pertama = true;

        foreach ($rows as $r) {
            $kode = strtoupper(trim($r[0] ?? ''));
            $nama = trim($r[1] ?? '');

            if ($kode === '' && $nama === '') {
                continue;
            }

            // Baris judul (mis. "Kode ICD")
            if ($pertama) {
                $pertama = false;
                if (preg_match('/^kode/i', $kode)) {
                    continue;
                }
            }

            if (! preg_match(self::POLA_KODE, $kode) || $nama === '') {
                $dilewati++;
                continue;
            }

            $valid[$kode] = mb_substr($nama, 0, 500);
        }

        $baru = 0;
        $diperbarui = 0;
        $now = now();

        foreach (array_chunk($valid, 500, true) as $chunk) {
            $ada = Tindakan::whereIn('kode', array_map('strval', array_keys($chunk)))->pluck('kode')->all();

            $data = [];
            foreach ($chunk as $kode => $nama) {
                $data[] = [
                    'kode'       => (string) $kode,
                    'nama'       => $nama,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Tindakan::upsert($data, ['kode'], ['nama', 'updated_at']);

            $diperbarui += count($ada);
            $baru += count($chunk) - count($ada);
        }

        if ($baru + $diperbarui === 0) {
            return $this->kembali(
                'Tidak ada data yang diimpor. Pastikan kolom A berisi kode ICD-9 (mis. 89.7) dan kolom B berisi nama tindakan.',
                'warning'
            );
        }

        $pesan = "Import selesai: {$baru} tindakan baru, {$diperbarui} diperbarui";
        if ($dilewati > 0) {
            $pesan .= ", {$dilewati} baris dilewati (kode/nama tidak valid)";
        }

        return $this->kembali($pesan . '.');
    }

    /** Kode disimpan tanpa spasi. */
    private function normalisasi(Request $request): void
    {
        $request->merge([
            'kode' => strtoupper(trim((string) $request->input('kode'))),
            'nama' => trim((string) $request->input('nama')),
        ]);
    }

    private function kembali(string $pesan, string $type = 'success')
    {
        return redirect()->route('tindakan.index')->with('popup', [
            'type'    => $type,
            'title'   => $type === 'success' ? 'Berhasil' : 'Perhatian',
            'message' => $pesan,
        ]);
    }

    private function pesan(): array
    {
        return [
            'kode.required' => 'Kode ICD-9 wajib diisi.',
            'kode.unique'   => 'Kode ICD-9 sudah ada.',
            'kode.regex'    => 'Format kode ICD-9 tidak valid. Contoh: 89.7 atau 99.21.',
            'nama.required' => 'Nama tindakan wajib diisi.',
            'nama.max'      => 'Nama tindakan maksimal 500 karakter.',
        ];
    }
}
