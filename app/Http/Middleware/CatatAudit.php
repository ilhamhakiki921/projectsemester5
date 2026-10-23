<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use App\Models\Pasien;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Mencatat Audit Trail otomatis untuk semua aksi penting (input, simpan,
 * edit, hapus, cetak, ganti password) berdasarkan NAMA ROUTE.
 *
 * Kalau menambah route baru, cukup tambahkan nama route-nya di $peta di bawah.
 * Pencatatan hanya terjadi kalau aksi BERHASIL (validasi lolos, tidak error).
 */
class CatatAudit
{
    /**
     * nama route => [aksi, keterangan, label objek]
     * Aksi: Input | Simpan | Edit | Hapus | Cetak
     */
    protected array $peta = [
        // ---- Pendaftaran ----
        'pasien.baru.store'               => ['Simpan', 'Mendaftarkan pasien baru'],
        'pasien.kunjungan.store'          => ['Simpan', 'Mendaftarkan kunjungan pasien'],
        'pasien.lama.kunjungan.store'     => ['Simpan', 'Mendaftarkan kunjungan pasien lama'],
        'pasien.update'                   => ['Edit',   'Mengubah data pasien'],
        'pasien.lama.destroy'             => ['Hapus',  'Menghapus data pasien'],

        // ---- Pemeriksaan ----
        'pemeriksaan.asesmen.store'            => ['Simpan', 'Menyimpan asesmen'],
        'pemeriksaan.soap.store'               => ['Simpan', 'Menyimpan SOAP'],
        'pemeriksaan.informed-consent.simpan'  => ['Simpan', 'Menyimpan informed consent'],
        'pemeriksaan.informed-consent.status'  => ['Edit',   'Mengubah status informed consent'],
        'pemeriksaan.diagnosa.store'           => ['Simpan', 'Menyimpan diagnosa'],
        'pemeriksaan.resep.store'              => ['Simpan', 'Menyimpan resep'],
        'clinical-pathway.store'               => ['Simpan', 'Menyimpan clinical pathway'],
        'penunjang.store'                      => ['Input',  'Menambah permintaan penunjang'],
        'penunjang.update'                     => ['Edit',   'Mengubah permintaan penunjang'],
        'penunjang.destroy'                    => ['Hapus',  'Menghapus permintaan penunjang'],
        'rujukan.store'                        => ['Simpan', 'Menyimpan surat rujukan'],
        'rujukan.destroy'                      => ['Hapus',  'Menghapus surat rujukan'],
        'rujukan.cetak'                        => ['Cetak',  'Mencetak surat rujukan'],
        'surat-medis.store'                    => ['Simpan', 'Menyimpan surat medis'],
        'surat-medis.cetak'                    => ['Cetak',  'Mencetak surat medis'],
        'surat-medis.unduh'                    => ['Cetak',  'Mengunduh surat medis'],
        'autentikasi.store'                    => ['Simpan', 'Menyimpan autentikasi pemeriksaan'],
        'autentikasi.cetak'                    => ['Cetak',  'Mencetak autentikasi pemeriksaan'],

        // ---- Rekam medis ----
        'rekam-medis.consent.cetak'            => ['Cetak',  'Mencetak general consent'],
        'rekam-medis.koding.update'            => ['Edit',   'Mengubah koding diagnosa/tindakan'],
        'rekam-medis.soap.update'              => ['Edit',   'Mengubah SOAP rekam medis'],
        'rekam-medis.informed-consent.cetak'   => ['Cetak',  'Mencetak informed consent'],
        'rekam-medis.autentikasi.cetak'        => ['Cetak',  'Mencetak autentikasi'],
        'rekam-medis.resume.cetak'             => ['Cetak',  'Mencetak resume medis'],

        // ---- Data master ----
        'user.store'        => ['Input',  'Menambah user'],
        'user.update'       => ['Edit',   'Mengubah user'],
        'user.status'       => ['Edit',   'Mengubah status user'],
        'user.destroy'      => ['Hapus',  'Menghapus user'],
        'poli.store'        => ['Input',  'Menambah poli'],
        'poli.update'       => ['Edit',   'Mengubah poli'],
        'poli.destroy'      => ['Hapus',  'Menghapus poli'],
        'diagnosa.store'    => ['Input',  'Menambah diagnosa/penyakit'],
        'diagnosa.import'   => ['Input',  'Import data diagnosa/penyakit'],
        'diagnosa.update'   => ['Edit',   'Mengubah diagnosa/penyakit'],
        'diagnosa.destroy'  => ['Hapus',  'Menghapus diagnosa/penyakit'],
        'tindakan.store'    => ['Input',  'Menambah tindakan'],
        'tindakan.import'   => ['Input',  'Import data tindakan'],
        'tindakan.template' => ['Cetak',  'Mengunduh template import tindakan'],
        'tindakan.update'   => ['Edit',   'Mengubah tindakan'],
        'tindakan.destroy'  => ['Hapus',  'Menghapus tindakan'],
        'obat.store'        => ['Input',  'Menambah obat'],
        'obat.update'       => ['Edit',   'Mengubah obat'],
        'obat.destroy'      => ['Hapus',  'Menghapus obat'],
        'data-pasien.update'=> ['Edit',   'Mengubah data pasien'],
    ];

    /** Kolom yang tidak pernah ditampilkan / disimpan di log. */
    protected array $rahasia = [
        'password', 'password_confirmation', 'remember_token', '_token', '_method',
        'tanda_tangan', 'ttd', 'signature',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $nama = $request->route()?->getName();

        if (! $nama || ! isset($this->peta[$nama])) {
            return $next($request);
        }

        // Informed consent tersimpan otomatis (draft) tiap beberapa detik;
        // hanya Simpan final (lengkap = true) yang dicatat ke audit trail.
        if ($nama === 'pemeriksaan.informed-consent.simpan' && ! $request->boolean('lengkap')) {
            return $next($request);
        }

        // Snapshot data SEBELUM diproses (untuk membandingkan lama vs baru).
        $sebelum = null;
        $subjek  = null;
        try {
            [$subjek, $sebelum] = $this->ambilSubjek($request);
        } catch (Throwable $e) {
            // abaikan, audit tidak boleh mengganggu proses utama
        }

        $response = $next($request);

        try {
            if ($this->berhasil($request, $response)) {
                $this->catat($request, $nama, $subjek, $sebelum);
            }
        } catch (Throwable $e) {
            report($e);
        }

        return $response;
    }

    /* ------------------------------------------------------------------ */

    protected function berhasil(Request $request, Response $response): bool
    {
        if ($response->getStatusCode() >= 400) {
            return false;
        }

        // Redirect balik karena validasi gagal / ada peringatan.
        if ($request->hasSession()) {
            $s = $request->session();
            if ($s->has('errors') && $s->get('errors')->any()) {
                return false;
            }
            if (($s->get('popup')['type'] ?? null) === 'warning') {
                return false;
            }
        }

        return true;
    }

    /**
     * Cari objek yang sedang diproses: model dari route-model-binding,
     * atau Pasien dari parameter no_rm / rm.
     *
     * @return array{0: ?Model, 1: ?array}
     */
    protected function ambilSubjek(Request $request): array
    {
        $route = $request->route();

        foreach ($route->parameters() as $nilai) {
            if ($nilai instanceof Model) {
                return [$nilai, $nilai->getAttributes()];
            }
        }

        $noRm = $route->parameter('no_rm') ?? $route->parameter('rm');
        if ($noRm) {
            $pasien = Pasien::where('no_rm', $noRm)->first();
            if ($pasien) {
                return [$pasien, $pasien->getAttributes()];
            }
        }

        return [null, null];
    }

    protected function catat(Request $request, string $nama, ?Model $subjek, ?array $sebelum): void
    {
        [$aksi, $judul] = $this->peta[$nama];

        $setelah = $subjek ? $this->segarkan($subjek) : null;
        $tabel   = $subjek?->getTable();
        $dataId  = $subjek?->getKey();
        $noRm    = $this->cariNoRm($request, $subjek);
        $label   = $this->labelObjek($request, $subjek, $sebelum);

        $keterangan = $judul . ($label ? ' ' . $label : '');

        $lama = $baru = null;

        if ($aksi === 'Edit' && $sebelum && $setelah) {
            [$lama, $baru] = $this->selisih($sebelum, $setelah);
        } elseif ($aksi === 'Hapus') {
            $lama = $this->bersihkan($sebelum ?? []);
        } elseif (in_array($aksi, ['Input', 'Simpan'], true)) {
            $baru = $this->ringkasInput($request, $subjek);
        } elseif ($aksi === 'Cetak') {
            $baru = array_filter(['no rm' => $noRm, 'nama' => $this->namaPasien($request, $subjek)]);
        }

        $baru = $baru ?: null;
        $lama = $lama ?: null;

        ActivityLog::catat($aksi, $tabel, $dataId ? (int) $dataId : null, $keterangan, $lama, $baru, $noRm);

        // Ganti password lewat Data User -> catat sebagai aksi tersendiri.
        if ($nama === 'user.update' && $request->filled('password')) {
            ActivityLog::catat(
                'Password',
                'users',
                $dataId ? (int) $dataId : null,
                'Mengganti password user ' . ($setelah['username'] ?? $sebelum['username'] ?? ''),
                null,
                ['username' => $setelah['username'] ?? $sebelum['username'] ?? null]
            );
        }
    }

    protected function segarkan(Model $m): array
    {
        try {
            // $m biasanya sudah ter-update oleh controller; fresh() memastikan nilai terbaru.
            $baru = $m->exists ? $m->newQuery()->find($m->getKey()) : null;
            return ($baru ?: $m)->getAttributes();
        } catch (Throwable $e) {
            return $m->getAttributes();
        }
    }

    /** @return array{0: array, 1: array} hanya kolom yang berubah */
    protected function selisih(array $lama, array $baru): array
    {
        $a = $b = [];
        foreach ($baru as $k => $v) {
            if (in_array($k, $this->rahasia, true) || in_array($k, ['updated_at', 'created_at'], true)) {
                continue;
            }
            $v0 = $lama[$k] ?? null;
            if ((string) $v0 !== (string) $v) {
                $a[$this->label($k)] = $v0;
                $b[$this->label($k)] = $v;
            }
        }
        return [$a, $b];
    }

    protected function ringkasInput(Request $request, ?Model $subjek): array
    {
        $hasil = [];

        if ($subjek instanceof Pasien) {
            $hasil['no rm'] = $subjek->no_rm;
            $hasil['nama']  = $subjek->nama;
        }

        // Pasien baru dibuat oleh store(): cari berdasarkan nama/NIK terbaru
        if (! $subjek && $request->routeIs('pasien.baru.store')) {
            $p = Pasien::latest('id')->first();
            if ($p) {
                $hasil = ['patient id' => $p->id, 'no rm' => $p->no_rm, 'nama' => $p->nama];
            }
        }

        // Ringkas beberapa isian penting yang dikirim user.
        foreach (['nama', 'nama_lengkap', 'username', 'role', 'status', 'kode_poli', 'nama_poli',
                  'kode_obat', 'nama_obat', 'kode', 'code', 'title', 'poli', 'dokter'] as $k) {
            if ($request->filled($k) && is_scalar($request->input($k)) && ! isset($hasil[$this->label($k)])) {
                $hasil[$this->label($k)] = $request->input($k);
            }
        }

        return $this->bersihkan($hasil);
    }

    protected function bersihkan(array $data): array
    {
        $out = [];
        foreach ($data as $k => $v) {
            if (in_array($k, $this->rahasia, true) || is_array($v) || is_object($v)) {
                continue;
            }
            if (in_array($k, ['id', 'created_at', 'updated_at'], true)) {
                continue;
            }
            if ($v === null || $v === '') {
                continue;
            }
            $out[$this->label($k)] = is_string($v) ? mb_strimwidth($v, 0, 120, '…') : $v;
        }
        return $out;
    }

    protected function label(string $k): string
    {
        return str_replace('_', ' ', $k);
    }

    protected function cariNoRm(Request $request, ?Model $subjek): ?string
    {
        $route = $request->route();

        if ($subjek instanceof Pasien) {
            return $subjek->no_rm;
        }
        if ($subjek && isset($subjek->no_rm)) {
            return $subjek->no_rm;
        }
        if ($subjek && isset($subjek->pasien_id)) {
            return Pasien::find($subjek->pasien_id)?->no_rm;
        }

        return $route->parameter('no_rm') ?? $route->parameter('rm');
    }

    protected function namaPasien(Request $request, ?Model $subjek): ?string
    {
        if ($subjek instanceof Pasien) {
            return $subjek->nama;
        }
        if ($subjek && isset($subjek->pasien_id)) {
            return Pasien::find($subjek->pasien_id)?->nama;
        }
        $rm = $this->cariNoRm($request, $subjek);

        return $rm ? Pasien::where('no_rm', $rm)->value('nama') : null;
    }

    /** Teks nama objek untuk keterangan, mis. "Dinisa Putri (RM-000002)". */
    protected function labelObjek(Request $request, ?Model $subjek, ?array $sebelum): string
    {
        $pasienNama = $this->namaPasien($request, $subjek);
        $rm         = $this->cariNoRm($request, $subjek);

        if ($pasienNama) {
            return $pasienNama . ($rm ? " ($rm)" : '');
        }

        $a = $sebelum ?? ($subjek?->getAttributes() ?? []);
        foreach (['nama_lengkap', 'nama_poli', 'nama_obat', 'nama', 'title'] as $k) {
            if (! empty($a[$k])) {
                return (string) $a[$k];
            }
        }

        // Master data baru (store): ambil dari isian form.
        foreach (['nama_lengkap', 'nama_poli', 'nama_obat', 'nama', 'title'] as $k) {
            if ($request->filled($k) && is_string($request->input($k))) {
                return (string) $request->input($k);
            }
        }

        return $rm ? "($rm)" : '';
    }
}
