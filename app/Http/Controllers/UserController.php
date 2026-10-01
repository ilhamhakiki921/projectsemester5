<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Daftar role (nilai enum di tabel `users`) beserta label tampilannya.
     */
    public const ROLES = [
        'admin'               => 'Administrator',
        'petugas_pendaftaran' => 'Petugas Pendaftaran',
        'dokter'              => 'Dokter',
        'perawat'             => 'Perawat',
        'penunjang'           => 'Petugas Penunjang',
        'farmasi'             => 'Petugas Farmasi',
        'rm'                  => 'Petugas Rekam Medis',
        'kepala_klinik'       => 'Kepala Klinik',
    ];

    public function index()
    {
        return view('user.index', [
            'users' => User::orderBy('id')->get(),
            'roles' => self::ROLES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|regex:/^[A-Za-z0-9._-]+$/|unique:users,username',
            'password'     => 'required|string|min:6|confirmed',
            'role'         => ['required', Rule::in(array_keys(self::ROLES))],
            'status'       => ['required', Rule::in(['aktif', 'nonaktif'])],
        ] + $this->aturanEmail(), $this->pesan());

        // Password di-hash otomatis lewat cast 'hashed' pada model User.
        User::create($data);

        return $this->kembali('User baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'password'     => 'nullable|string|min:6|confirmed',
            'role'         => ['required', Rule::in(array_keys(self::ROLES))],
            'status'       => ['required', Rule::in(['aktif', 'nonaktif'])],
        ] + $this->aturanEmail($user->id), $this->pesan());

        // Password kosong = tidak diganti.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Jangan sampai akun yang sedang dipakai menonaktifkan / menurunkan dirinya sendiri.
        if ($this->akunSendiri($user)) {
            $data['status'] = 'aktif';
            $data['role']   = $user->role;
        }

        $user->update($data);

        return $this->kembali('Data user berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        if ($this->akunSendiri($user)) {
            return $this->kembali('Akun yang sedang dipakai tidak bisa dinonaktifkan.', 'warning');
        }

        $user->update(['status' => $user->status === 'aktif' ? 'nonaktif' : 'aktif']);

        return $this->kembali($user->status === 'aktif'
            ? 'User berhasil diaktifkan.'
            : 'User berhasil dinonaktifkan.');
    }

    public function destroy(User $user)
    {
        if ($this->akunSendiri($user)) {
            return $this->kembali('Akun yang sedang dipakai tidak bisa dihapus.', 'warning');
        }

        $user->delete();

        return $this->kembali('User berhasil dihapus.');
    }

    /**
     * Aturan validasi email. Kalau kolom `email` belum ada di tabel users
     * (migration belum dijalankan), aturan dilewati supaya simpan data tetap jalan.
     */
    private function aturanEmail(?int $abaikanId = null): array
    {
        if (! Schema::hasColumn('users', 'email')) {
            return [];
        }

        $unik = Rule::unique('users', 'email');
        if ($abaikanId) {
            $unik->ignore($abaikanId);
        }

        return ['email' => ['nullable', 'email', 'max:100', $unik]];
    }

    private function akunSendiri(User $user): bool
    {
        return Auth::check() && Auth::id() === $user->id;
    }

    private function kembali(string $pesan, string $type = 'success')
    {
        return redirect()->route('user.index')->with('popup', [
            'type'    => $type,
            'title'   => $type === 'success' ? 'Berhasil' : 'Perhatian',
            'message' => $pesan,
        ]);
    }

    private function pesan(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah dipakai.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah dipakai.',
            'username.regex'        => 'Username hanya boleh huruf, angka, titik, strip (-) dan underscore (_), tanpa spasi.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak sama.',
            'role.required'         => 'Role wajib dipilih.',
        ];
    }
}
