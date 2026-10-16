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

    /**
     * Autocomplete ICD-9 di form Diagnosa > Tindakan.
     * Urutan hasil: Data Master Tindakan (lokal, selalu tersedia) lalu layanan ICD-9-CM online.
     * Kalau layanan online gagal (mis. tidak ada internet / sertifikat SSL), hasil lokal tetap tampil.
     */
    public function cari(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json(['data' => [], 'pesan' => null]);
        }

        $hasil = collect();

        // 1. Data Master Tindakan (tabel lokal)
        try {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $hasil = Tindakan::query()
                ->where(function ($w) use ($like) {
                    $w->where('kode', 'like', $like)->orWhere('nama', 'like', $like);
                })
                ->orderBy('kode')
                ->limit(10)
                ->get()
                ->map(fn ($t) => ['code' => $t->kode, 'title' => $t->nama]);
        } catch (\Throwable $e) {
            report($e);   // tabel tindakan belum ada (migrasi belum dijalankan)
        }

        // 2. Layanan ICD-9-CM online (opsional)
        $pesan = null;
        try {
            $res = \Illuminate\Support\Facades\Http::timeout(8)
                ->get('https://clinicaltables.nlm.nih.gov/api/icd9cm_sg/v3/search', [
                    'terms'   => $q,
                    'maxList' => 15,
                ]);

            if (! $res->successful()) {
                throw new \RuntimeException('API ICD-9 gagal');
            }

            $online = collect($res->json()[3] ?? [])
                ->map(fn ($r) => ['code' => $r[0], 'title' => $r[1]]);

            $hasil = $hasil->concat($online)->unique('code');
        } catch (\Throwable $e) {
            if ($hasil->isEmpty()) {
                $pesan = 'Gagal terhubung ke layanan ICD-9 dan belum ada di Data Master Tindakan. Anda tetap bisa mengetik manual.';
            }
        }

        return response()->json([
            'data'  => $hasil->values(),
            'pesan' => $pesan ?? ($hasil->isEmpty() ? 'Tindakan tidak ditemukan. Anda tetap bisa mengetik manual.' : null),
        ]);
    }

    public function store(Request $request)
    {
        $this->normalisasi($request);

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'regex:' . self::POLA_KODE, 'unique:tindakan,kode'],
            'nama' => ['required', 'string', 'max:500'],
        ], $this->pesan());

        $baru = Tindakan::create($data);

        // Data diurutkan menurut kode -> buka halaman tempat data baru ini berada
        $halaman = intdiv(Tindakan::where('kode', '<', $baru->kode)->count(), 10) + 1;

        return $this->kembali('Tindakan baru berhasil ditambahkan.', 'success', ['page' => $halaman])
            ->with('sorot', [$baru->kode]);
    }

    public function update(Request $request, Tindakan $tindakan)
    {
        $this->normalisasi($request);

        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'regex:' . self::POLA_KODE, Rule::unique('tindakan', 'kode')->ignore($tindakan->id)],
            'nama' => ['required', 'string', 'max:500'],
        ], $this->pesan());

        $tindakan->update($data);

        $halaman = intdiv(Tindakan::where('kode', '<', $tindakan->kode)->count(), 10) + 1;

        return $this->kembali('Data tindakan berhasil diperbarui.', 'success', ['page' => $halaman])
            ->with('sorot', [$tindakan->kode]);
    }

    public function destroy(Tindakan $tindakan)
    {
        $tindakan->delete();

        return $this->kembali('Tindakan berhasil dihapus.');
    }

    /**
     * Unduh contoh template (CSV, pemisah titik koma supaya langsung rapi di Excel Indonesia).
     */
    public function template()
    {
        $isi = "\xEF\xBB\xBF"
             . "Kode ICD;Nama Tindakan\r\n"
             . "89.7;Pemeriksaan fisik umum\r\n"
             . "99.21;Injeksi antibiotik\r\n"
             . "86.59;Penjahitan luka kulit\r\n";

        return response($isi, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-data-tindakan.csv"',
        ]);
    }

    /**
     * Import dari Excel (.xlsx) atau CSV.
     * Kolom A = Kode ICD-9, kolom B = Nama Tindakan. Baris pertama (header) dilewati.
     * Kode yang sudah ada diperbarui namanya, yang belum ada ditambahkan.
     * Hasilnya ditampilkan rapi di popup "Hasil Import".
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
        $lewat = [];      // baris yang dilewati + alasannya
        $pertama = true;
        $no = 0;

        foreach ($rows as $r) {
            $no++;
            $kodeAsli = trim((string) ($r[0] ?? ''));
            $namaAsli = trim((string) ($r[1] ?? ''));

            if ($kodeAsli === '' && $namaAsli === '') {
                continue;
            }

            // Baris pertama = header (mis. "Kode ICD")
            if ($pertama) {
                $pertama = false;
                if (preg_match('/^kode/i', $kodeAsli)) {
                    continue;
                }
            }

            $kode = $this->rapikanKode($kodeAsli);
            $nama = $this->rapikanNama($namaAsli);

            if (! preg_match(self::POLA_KODE, $kode)) {
                $lewat[] = ['no' => $no, 'kode' => $kodeAsli, 'nama' => $namaAsli, 'alasan' => 'Format kode tidak valid'];
                continue;
            }

            if ($nama === '') {
                $lewat[] = ['no' => $no, 'kode' => $kodeAsli, 'nama' => $namaAsli, 'alasan' => 'Nama tindakan kosong'];
                continue;
            }

            $valid[$kode] = mb_substr($nama, 0, 500);
        }

        $status = [];     // kode => 'Baru' | 'Diperbarui'
        $now = now();

        foreach (array_chunk($valid, 500, true) as $chunk) {
            $ada = Tindakan::whereIn('kode', array_map('strval', array_keys($chunk)))->pluck('kode')->all();
            $ada = array_flip($ada);

            $data = [];
            foreach ($chunk as $kode => $nama) {
                $data[] = [
                    'kode'       => (string) $kode,
                    'nama'       => $nama,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $status[(string) $kode] = isset($ada[(string) $kode]) ? 'Diperbarui' : 'Baru';
            }

            Tindakan::upsert($data, ['kode'], ['nama', 'updated_at']);
        }

        if (! $valid) {
            return $this->kembali(
                'Tidak ada data yang diimpor. Pastikan kolom A berisi kode ICD-9 (mis. 89.7) dan kolom B berisi nama tindakan.',
                'warning'
            );
        }

        // Daftar hasil import (urut kode) untuk ditampilkan; dibatasi supaya session tetap ringan
        $hasil = [];
        foreach ($valid as $kode => $nama) {
            $hasil[] = ['kode' => (string) $kode, 'nama' => $nama, 'status' => $status[(string) $kode]];
        }
        usort($hasil, fn ($a, $b) => strcmp($a['kode'], $b['kode']));

        $jumlahBaru = count(array_filter($status, fn ($s) => $s === 'Baru'));

        return redirect()->route('tindakan.index')->with('hasil_import', [
            'baru'        => $jumlahBaru,
            'diperbarui'  => count($status) - $jumlahBaru,
            'dilewati'    => count($lewat),
            'rows'        => array_slice($hasil, 0, 300),
            'total_rows'  => count($hasil),
            'lewat'       => array_slice($lewat, 0, 50),
            'file'        => $file->getClientOriginalName(),
        ]);
    }

    /**
     * Rapikan kode dari Excel: koma -> titik, huruf besar, dan angka depan dipadatkan
     * jadi 2 digit (Excel sering mengubah "03.09" menjadi angka 3.09).
     */
    private function rapikanKode(string $kode): string
    {
        $kode = strtoupper(str_replace(',', '.', preg_replace('/\s+/', '', $kode)));

        if (preg_match('/^(\d{1,2})(\.\d{1,2})?$/', $kode, $m)) {
            $kode = str_pad($m[1], 2, '0', STR_PAD_LEFT) . ($m[2] ?? '');
        }

        return $kode;
    }

    /** Spasi ganda/tab/enter di nama dirapikan jadi satu spasi. */
    private function rapikanNama(string $nama): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $nama));
    }

    /** Kode disimpan tanpa spasi. */
    private function normalisasi(Request $request): void
    {
        $request->merge([
            'kode' => strtoupper(trim((string) $request->input('kode'))),
            'nama' => trim((string) $request->input('nama')),
        ]);
    }

    private function kembali(string $pesan, string $type = 'success', array $query = [])
    {
        return redirect()->route('tindakan.index', $query)->with('popup', [
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
