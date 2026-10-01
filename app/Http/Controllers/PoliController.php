<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoliController extends Controller
{
    public function index()
    {
        return view('poli.index', [
            'polis'        => Poli::orderBy('id')->get(),
            'kodeBerikut'  => Poli::kodeBerikutnya(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_poli' => ['required', 'string', 'max:100', 'unique:poli,nama_poli'],
            'status'    => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], $this->pesan());

        // Kode dibuat otomatis oleh sistem. Kalau kebetulan bentrok (dua orang
        // menyimpan bersamaan), coba lagi dengan nomor berikutnya.
        for ($i = 0; $i < 3; $i++) {
            try {
                Poli::create($data + ['kode_poli' => Poli::kodeBerikutnya()]);
                break;
            } catch (UniqueConstraintViolationException $e) {
                if ($i === 2) {
                    throw $e;
                }
            }
        }

        return $this->kembali('Poli baru berhasil ditambahkan.');
    }

    public function update(Request $request, Poli $poli)
    {
        // Kode poli tidak bisa diubah (identitas tetap), hanya nama & status.
        $data = $request->validate([
            'nama_poli' => ['required', 'string', 'max:100', Rule::unique('poli', 'nama_poli')->ignore($poli->id)],
            'status'    => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], $this->pesan());

        $poli->update($data);

        return $this->kembali('Data poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();

        return $this->kembali('Poli berhasil dihapus.');
    }

    private function kembali(string $pesan, string $type = 'success')
    {
        return redirect()->route('poli.index')->with('popup', [
            'type'    => $type,
            'title'   => $type === 'success' ? 'Berhasil' : 'Perhatian',
            'message' => $pesan,
        ]);
    }

    private function pesan(): array
    {
        return [
            'nama_poli.required' => 'Nama poli wajib diisi.',
            'nama_poli.unique'   => 'Nama poli sudah ada.',
            'status.required'    => 'Status wajib dipilih.',
        ];
    }
}
