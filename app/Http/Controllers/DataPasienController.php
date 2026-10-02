<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataPasienController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Pasien::query()->with(['kunjungan' => fn ($k) => $k->latest('tanggal_kunjungan')->latest('id')->limit(5)]);

        if ($q !== '') {
            $like = '%' . addcslashes($q, '%_\\') . '%';
            $query->where(function ($w) use ($like) {
                $w->where('no_rm', 'like', $like)
                  ->orWhere('nik', 'like', $like)
                  ->orWhere('nama', 'like', $like);
            });
        }

        $pasiens = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('data-pasien.index', [
            'pasiens'   => $pasiens,
            'q'         => $q,
            'labelPoli' => Poli::daftar(),
        ]);
    }

    public function update(Request $request, Pasien $pasien)
    {
        // No. RM tidak bisa diubah (identitas tetap). Pasien baru didaftarkan lewat menu Pendaftaran.
        $data = $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'nik'           => ['required', 'digits:16', Rule::unique('pasien', 'nik')->ignore($pasien->id)],
            'no_telepon'    => ['nullable', 'string', 'max:20'],
            'alamat'        => ['required', 'string', 'max:500'],
            'kecamatan'     => ['nullable', 'string', 'max:255'],
        ], [
            'nama.required'          => 'Nama pasien wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.digits'             => 'NIK harus 16 digit angka.',
            'nik.unique'             => 'NIK sudah dipakai pasien lain.',
            'alamat.required'        => 'Alamat wajib diisi.',
        ]);

        $pasien->update($data);

        return redirect()->back()->with('popup', [
            'type'    => 'success',
            'title'   => 'Berhasil',
            'message' => 'Data pasien berhasil diperbarui.',
        ]);
    }
}
