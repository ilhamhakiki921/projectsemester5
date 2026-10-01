<?php

namespace App\Http\Controllers;

use App\Models\Icd10;
use App\Services\TabelReader;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

/**
 * Data Master > Data Diagnosa/Penyakit.
 * Mengelola tabel `icd10` (baris kind = category), tabel yang sama dengan
 * yang dipakai autocomplete Diagnosa di halaman Pemeriksaan.
 */
class DiagnosaController extends Controller
{
    /** Contoh valid: J06.9, A00, A00.1, S72.01 */
    private const POLA_KODE = '/^[A-Z][0-9]{2}(\.[0-9A-Z]{1,4})?$/';

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Icd10::where('kind', 'category');

        if ($q !== '') {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $query->where(function ($w) use ($like) {
                $w->where('code', 'like', $like)
                  ->orWhere('title', 'like', $like);
            });
        }

        return view('diagnosa.index', [
            'diagnosas' => $query->orderBy('code')->paginate(10)->withQueryString(),
            'q'         => $q,
        ]);
    }

    public function store(Request $request)
    {
        $this->normalisasi($request);

        $data = $request->validate([
            'code'  => ['required', 'string', 'max:20', 'regex:' . self::POLA_KODE, 'unique:icd10,code'],
            'title' => ['required', 'string', 'max:500'],
        ], $this->pesan());

        Icd10::create($data + ['kind' => 'category']);

        return $this->kembali('Diagnosis baru berhasil ditambahkan.');
    }

    public function update(Request $request, Icd10 $icd10)
    {
        abort_unless($icd10->kind === 'category', 404);

        $this->normalisasi($request);

        $data = $request->validate([
            'code'  => ['required', 'string', 'max:20', 'regex:' . self::POLA_KODE, Rule::unique('icd10', 'code')->ignore($icd10->id)],
            'title' => ['required', 'string', 'max:500'],
        ], $this->pesan());

        $icd10->update($data);

        return $this->kembali('Data diagnosis berhasil diperbarui.');
    }

    public function destroy(Icd10 $icd10)
    {
        abort_unless($icd10->kind === 'category', 404);

        $icd10->delete();

        return $this->kembali('Diagnosis berhasil dihapus.');
    }

    /**
     * Import dari Excel (.xlsx) atau CSV.
     * Kolom A = Kode ICD, kolom B = Nama Penyakit. Baris judul boleh ada.
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
            $ada = Icd10::whereIn('code', array_map('strval', array_keys($chunk)))->pluck('code')->all();

            $data = [];
            foreach ($chunk as $kode => $nama) {
                $data[] = [
                    'code'        => (string) $kode,
                    'title'       => $nama,
                    'kind'        => 'category',
                    'parent_code' => null,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            Icd10::upsert($data, ['code'], ['title', 'updated_at']);

            $diperbarui += count($ada);
            $baru += count($chunk) - count($ada);
        }

        if ($baru + $diperbarui === 0) {
            return $this->kembali(
                'Tidak ada data yang diimpor. Pastikan kolom A berisi kode ICD (mis. J06.9) dan kolom B berisi nama penyakit.',
                'warning'
            );
        }

        $pesan = "Import selesai: {$baru} diagnosis baru, {$diperbarui} diperbarui";
        if ($dilewati > 0) {
            $pesan .= ", {$dilewati} baris dilewati (kode/nama tidak valid)";
        }

        return $this->kembali($pesan . '.');
    }

    /** Kode selalu disimpan huruf besar tanpa spasi. */
    private function normalisasi(Request $request): void
    {
        $request->merge([
            'code'  => strtoupper(trim((string) $request->input('code'))),
            'title' => trim((string) $request->input('title')),
        ]);
    }

    private function kembali(string $pesan, string $type = 'success')
    {
        return redirect()->route('diagnosa.index')->with('popup', [
            'type'    => $type,
            'title'   => $type === 'success' ? 'Berhasil' : 'Perhatian',
            'message' => $pesan,
        ]);
    }

    private function pesan(): array
    {
        return [
            'code.required'  => 'Kode ICD wajib diisi.',
            'code.unique'    => 'Kode ICD sudah ada.',
            'code.regex'     => 'Format kode ICD tidak valid. Contoh: J06.9 atau A00.',
            'title.required' => 'Nama penyakit wajib diisi.',
            'title.max'      => 'Nama penyakit maksimal 500 karakter.',
        ];
    }
}
