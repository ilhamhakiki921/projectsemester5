<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ObatController extends Controller
{
    public function index()
    {
        return view('obat.index', [
            'daftarObat'  => Obat::orderBy('kode_obat')->get(),
            'kodeBerikut' => Obat::kodeBerikutnya(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        // Kode dibuat otomatis. Kalau kebetulan bentrok (dua orang menyimpan
        // bersamaan), coba lagi dengan nomor berikutnya.
        for ($i = 0; $i < 3; $i++) {
            try {
                Obat::create($data + ['kode_obat' => Obat::kodeBerikutnya()]);
                break;
            } catch (UniqueConstraintViolationException $e) {
                if ($i === 2) {
                    throw $e;
                }
            }
        }

        return $this->kembali('Obat baru berhasil ditambahkan.');
    }

    public function update(Request $request, Obat $obat)
    {
        // Kode obat tidak bisa diubah (identitas tetap).
        $obat->update($this->validasi($request, $obat));

        return $this->kembali('Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat)
    {
        // Tabel resep ikut terhapus (cascade) kalau obat dihapus -- jangan sampai
        // riwayat resep pasien hilang. Obat yang sudah pernah diresepkan ditolak.
        $jumlahResep = $obat->resep()->count();

        if ($jumlahResep > 0) {
            return $this->kembali(
                "Obat ini sudah dipakai di {$jumlahResep} resep sehingga tidak bisa dihapus. "
                . 'Ubah stoknya menjadi 0 jika obat sudah tidak dipakai.',
                'warning'
            );
        }

        $obat->delete();

        return $this->kembali('Obat berhasil dihapus.');
    }

    private function validasi(Request $request, ?Obat $obat = null): array
    {
        // Kalau dosis terlanjur diketik di nama ("Paracetamol 500mg"), pisahkan otomatis.
        $nama  = trim((string) $request->input('nama_obat'));
        $dosis = Obat::rapikanDosis($request->input('dosis'));

        if ($dosis === null) {
            [$nama, $dosis] = Obat::pisahDosis($nama);
        }

        $request->merge([
            'nama_obat' => $nama,
            'dosis'     => $dosis,
            'sediaan'   => trim((string) $request->input('sediaan')),
            'harga'     => $request->filled('harga') ? $request->input('harga') : null,
        ]);

        return $request->validate([
            'nama_obat' => [
                'required', 'string', 'max:150',
                // kombinasi nama + dosis + sediaan tidak boleh kembar
                Rule::unique('obat', 'nama_obat')
                    ->where(fn ($q) => $q->where('dosis', $dosis)->where('sediaan', $request->sediaan))
                    ->ignore($obat?->id),
            ],
            'dosis'   => ['nullable', 'string', 'max:50'],
            'sediaan' => ['required', 'string', 'max:50'],
            'stok'    => ['required', 'integer', 'min:0', 'max:4000000000'],
            'harga'   => ['nullable', 'integer', 'min:0', 'max:4000000000'],
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'nama_obat.unique'   => 'Obat dengan nama, dosis, dan sediaan yang sama sudah ada.',
            'dosis.max'          => 'Dosis maksimal 50 karakter.',
            'sediaan.required'   => 'Sediaan wajib diisi.',
            'stok.required'      => 'Stok wajib diisi.',
            'stok.integer'       => 'Stok harus berupa angka bulat.',
            'stok.min'           => 'Stok tidak boleh kurang dari 0.',
            'harga.integer'      => 'Harga harus berupa angka bulat.',
            'harga.min'          => 'Harga tidak boleh kurang dari 0.',
        ]);
    }

    private function kembali(string $pesan, string $type = 'success')
    {
        return redirect()->route('obat.index')->with('popup', [
            'type'    => $type,
            'title'   => $type === 'success' ? 'Berhasil' : 'Perhatian',
            'message' => $pesan,
        ]);
    }
}
