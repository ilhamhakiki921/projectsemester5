<?php
 
namespace App\Http\Controllers;
 
use App\Models\GeneralConsent;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Kunjungan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
 
class PasienController extends Controller
{
    protected $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    // Halaman Data Pasien (tabel list pasien lama)
    public function indexLama()
    {
        $pasiens = Pasien::latest()->paginate(10);
 
        return view('pasien.lama', compact('pasiens'));
    }

    // Halaman Pendaftaran: daftar pasien beneran dari database (bukan dummy),
    // lengkap dengan info kunjungan terakhirnya (tanggal & poli tujuan).
    public function pendaftaran()
    {
        $pasiens = Pasien::with(['kunjungan' => function ($query) {
                $query->latest('id');
            }])
            ->orderByDesc('no_rm')
            ->get()
            ->map(function ($p) {
                $kunjungan = $p->kunjungan->first();

                $p->alamat_jalan = trim(explode(',', $p->alamat)[0]);
                $p->lahir_text   = $this->tanggalKeTeks($p->tanggal_lahir);

                $p->tgl_kunjungan_text = $kunjungan
                    ? $this->tanggalKeTeks($kunjungan->tanggal_kunjungan)
                    : '-';

                $p->poli_label = $kunjungan
                    ? (Poli::daftar()[$kunjungan->poli] ?? ucfirst($kunjungan->poli))
                    : '-';

                return $p;
            });

        return view('pendaftaran.index', compact('pasiens'));
    }

    // "2026-09-27" (atau objek Carbon) -> "27 Sep 2026"
    protected function tanggalKeTeks($tanggal): string
    {
        if (! $tanggal) {
            return '-';
        }

        $tanggal = \Carbon\Carbon::parse($tanggal);

        return $tanggal->day . ' ' . $this->namaBulan[$tanggal->month - 1] . ' ' . $tanggal->year;
    }
 
    // Form tambah pasien baru
public function create()
{
    return view('pasien.baru', [
        'noRmBerikutnya' => $this->noRmBerikutnya(),
    ]);
}
 
    // Simpan pasien baru sekaligus kunjungan pertamanya
    //
    // Dipakai oleh dua tempat: form lama (pasien/baru.blade.php, submit
    // biasa -> redirect) dan modal "Daftarkan Pasien Baru" di halaman
    // Pendaftaran (dipanggil lewat fetch/AJAX -> balas JSON). No. RM dan
    // nama dokter boleh tidak dikirim: kalau kosong, akan dibuatkan
    // otomatis supaya pasien tetap benar-benar tersimpan ke database
    // (bukan cuma data dummy di JS) dan langsung muncul di antrian
    // Pemeriksaan poli tujuannya.
    public function store(Request $request)
    {
        $request->validate([
            'nama'                => 'required',
            'jenis_kelamin'       => 'required',
            'tanggal_lahir'       => 'required|date',
            'nik'                 => 'required|digits:16|unique:pasien,nik',
            'no_telepon'          => 'nullable',
            'alamat'              => 'required',
            'kecamatan'           => 'required',
            'title'               => 'nullable|string|max:50',
            'tempat_lahir'        => 'nullable|string|max:255',
            'agama'               => 'nullable|string|max:255',
            'status_pernikahan'   => 'nullable|string|max:100',
            'provinsi'            => 'nullable|string|max:255',
            'kabupaten'           => 'nullable|string|max:255',
            'kelurahan'           => 'nullable|string|max:255',
            'negara'              => 'nullable|string|max:255',
            'pendidikan'          => 'nullable|string|max:100',
            'pekerjaan'           => 'nullable|string|max:255',
            'data_lain'           => 'nullable|array',
            'keluarga'            => 'nullable|array',
            'tanggal_kunjungan'   => 'required|date',
            'dokter'              => 'nullable',
            'poli'                => 'required',
            'pembayaran'          => 'required',
            'general_consent'           => 'nullable|array',
            'general_consent.pemberi'   => 'nullable|in:Pasien,Wali',
            'general_consent.nama'      => 'nullable|string|max:255',
            'general_consent.hubungan'  => 'nullable|string|max:100',
            'general_consent.waktu'     => 'nullable|date',
            'general_consent.alamat'              => 'nullable|string|max:500',
            'general_consent.telepon'             => 'nullable|string|max:50',
            'general_consent.alergi'              => 'nullable|string|max:1000',
            'general_consent.riwayat_penyakit'    => 'nullable|string|max:1000',
            'general_consent.kerabat'             => 'nullable|array|max:3',
            'general_consent.kerabat.*.nama'      => 'nullable|string|max:255',
            'general_consent.kerabat.*.hubungan'  => 'nullable|string|max:100',
            'general_consent.izin_kunjungan'      => 'nullable|in:Mengizinkan,Tidak mengizinkan',
            'general_consent.privasi_khusus'      => 'nullable|in:Menginginkan privasi khusus,Tidak menginginkan',
            'general_consent.keterangan_privasi'  => 'nullable|string|max:1000',
            'general_consent.ttd_petugas'         => 'nullable|string|max:700000',
            'general_consent.ttd_penanggung_jawab'=> 'nullable|string|max:700000',
            'general_consent.ttd_saksi'           => 'nullable|string|max:700000',
            'general_consent.nama_petugas'        => 'nullable|string|max:255',
            'general_consent.nama_saksi'          => 'nullable|string|max:255',
        ]);

        // No. RM SELALU dibuat otomatis oleh sistem (6 digit, berurutan).
        // Input 'no_rm' dari form/klien sengaja diabaikan supaya tidak bisa
        // diisi manual atau dipalsukan.
        $noRm = null;
        $kunjungan = null;

        // Satu transaksi: kalau simpan kunjungan gagal, data pasien ikut dibatalkan
        // (supaya tidak ada pasien "yatim" tanpa kunjungan).
        //
        // Dicoba ulang maksimal 5x kalau ada 2 pendaftaran yang kebetulan
        // mendapat No. RM yang sama pada waktu bersamaan (bentrok unique).
        $percobaan = 0;
        while (true) {
            try {
                DB::transaction(function () use ($request, &$noRm, &$kunjungan) {
                    $noRm = $this->noRmBerikutnya();
                    $this->simpanPasienBaru($request, $noRm, $kunjungan);
                });
                break;
            } catch (QueryException $e) {
                $percobaan++;
                $bentrokRm = ($e->errorInfo[0] ?? null) === '23000'
                    && str_contains($e->getMessage(), 'no_rm');

                if (!$bentrokRm || $percobaan >= 5) {
                    throw $e;
                }
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'no_rm'      => $noRm,
                'no_antrian' => $kunjungan->no_antrian,
                'poli'       => $kunjungan->poli,
                'message'    => 'Pasien baru berhasil didaftarkan.',
            ]);
        }

        return redirect()
            ->route('pasien.lama')
            ->with('success', 'Pasien baru berhasil didaftarkan dengan No. RM ' . $noRm . '.');
    }

    // Isi transaksi simpan pasien baru + general consent + kunjungan pertama.
    protected function simpanPasienBaru(Request $request, string $noRm, &$kunjungan): void
    {
        {
            $pasien = Pasien::create([
                'no_rm'         => $noRm,
                'nama'          => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nik'           => $request->nik,
                'no_telepon'    => $request->no_telepon,
                'alamat'        => $request->alamat,
                'kecamatan'     => $request->kecamatan,

                // Data lain dari form Pendaftaran
                'title'             => $request->title,
                'tempat_lahir'      => $request->tempat_lahir,
                'agama'             => $request->agama,
                'status_pernikahan' => $request->status_pernikahan,
                'provinsi'          => $request->provinsi,
                'kabupaten'         => $request->kabupaten,
                'kelurahan'         => $request->kelurahan,
                'negara'            => $request->negara,
                'pendidikan'        => $request->pendidikan,
                'pekerjaan'         => $request->pekerjaan,

                'no_kk'           => $request->input('data_lain.no_kk'),
                'domisili'        => $request->input('data_lain.domisili'),
                'nip'             => $request->input('data_lain.nip'),
                'jabatan'         => $request->input('data_lain.jabatan'),
                'divisi'          => $request->input('data_lain.divisi'),
                'departemen'      => $request->input('data_lain.departemen'),
                'sub_departemen'  => $request->input('data_lain.sub_departemen'),
                'perusahaan'      => $request->input('data_lain.perusahaan'),
                'suku'            => $request->input('data_lain.suku'),
                'bahasa'          => $request->input('data_lain.bahasa'),
                'kewarganegaraan' => $request->input('data_lain.kewarganegaraan'),

                'keluarga_nama'     => $request->input('keluarga.nama'),
                'keluarga_jk'       => $request->input('keluarga.jk'),
                'keluarga_hubungan' => $request->input('keluarga.hubungan'),
                'keluarga_telepon'  => $request->input('keluarga.telepon'),
            ]);
 
            // General Consent (diisi setelah Identitas Pasien di form pendaftaran)
            $gc = $request->input('general_consent');

            if (is_array($gc) && ! empty($gc['nama']) && Schema::hasTable('general_consent')) {
                $dataConsent = [
                    'pasien_id'           => $pasien->id,
                    'pemberi_persetujuan' => $gc['pemberi'] ?? 'Pasien',
                    'nama_penyetuju'      => $gc['nama'],
                    'hubungan'            => $gc['hubungan'] ?? null,
                    'setuju'              => true,
                    'disetujui_pada'      => ! empty($gc['waktu']) ? \Carbon\Carbon::parse($gc['waktu']) : now(),
                ];

                // Kolom desain General Consent lengkap (perlu migrasi 2026_10_12)
                if (Schema::hasColumn('general_consent', 'data_form')) {
                    $dataConsent['data_form'] = [
                        'alamat_penanda_tangan' => $gc['alamat'] ?? null,
                        'telepon_penanda_tangan'=> $gc['telepon'] ?? null,
                        'riwayat_alergi'        => $gc['alergi'] ?? null,
                        'riwayat_penyakit'      => $gc['riwayat_penyakit'] ?? null,
                        'kerabat'               => array_values(array_filter(
                            $gc['kerabat'] ?? [],
                            fn ($k) => ! empty($k['nama'])
                        )),
                        'izin_kunjungan'        => $gc['izin_kunjungan'] ?? null,
                        'privasi_khusus'        => $gc['privasi_khusus'] ?? null,
                        'keterangan_privasi'    => $gc['keterangan_privasi'] ?? null,
                        'setuju_perawatan'      => true,  // A
                        'setuju_hak_kewajiban'  => true,  // C
                        'setuju_biaya'          => true,  // D
                    ];
                    $dataConsent['ttd_petugas']          = $gc['ttd_petugas'] ?? null;
                    $dataConsent['ttd_penanggung_jawab'] = $gc['ttd_penanggung_jawab'] ?? null;
                    $dataConsent['ttd_saksi']            = $gc['ttd_saksi'] ?? null;
                }

                // Nama yang tercetak di bawah tanda tangan petugas & saksi
                if (Schema::hasColumn('general_consent', 'petugas_pencatat')) {
                    $dataConsent['petugas_pencatat'] = ! empty($gc['nama_petugas'])
                        ? $gc['nama_petugas']
                        : (optional(auth()->user())->nama_lengkap ?? optional(auth()->user())->name);
                }

                if (Schema::hasColumn('general_consent', 'saksi')) {
                    $dataConsent['saksi'] = ! empty($gc['nama_saksi']) ? $gc['nama_saksi'] : null;
                }

                GeneralConsent::create($dataConsent);
            }

            $kunjungan = Kunjungan::create([
                'pasien_id'         => $pasien->id,
                'no_antrian'        => Kunjungan::nomorAntrianBerikutnya($request->poli, $request->tanggal_kunjungan),
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'dokter'            => $request->filled('dokter') ? $request->dokter : $this->dokterBawaan($request->poli),
                'poli'              => $request->poli,
                'pembayaran'        => $request->pembayaran,
                'status'            => 'Menunggu',
            ]);
        }
    }

    // Simpan perubahan data pasien dari modal "Edit Data Pasien" di halaman
    // Pendaftaran. Dicari berdasarkan no_rm (bukan id) karena itu yang
    // tersimpan di baris tabel/tombol edit-nya.
    public function update(Request $request, $no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $request->validate([
            'nama'          => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'nik'           => 'required|digits:16|unique:pasien,nik,' . $pasien->id,
            'no_telepon'    => 'nullable',
            'alamat'        => 'required',
            'kecamatan'     => 'nullable',
        ]);

        $pasien->update([
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->filled('tanggal_lahir') ? $request->tanggal_lahir : $pasien->tanggal_lahir,
            'nik'           => $request->nik,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
            'kecamatan'     => $request->filled('kecamatan') ? $request->kecamatan : $pasien->kecamatan,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pasien berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Data pasien berhasil diperbarui.');
    }

    // Dokter bawaan kalau pendaftaran tidak memilih dokter: dokter poli tujuan
    // (lihat config/dokter.php). Kalau poli belum ada di daftar -> 'Dokter Jaga'.
    protected function dokterBawaan($poli): string
    {
        return config('dokter.daftar.' . $poli . '.0', 'Dokter Jaga');
    }

    // No. RM otomatis: 6 digit, berurutan (000001, 000002, 000003, ...).
    // Diambil dari No. RM numerik terbesar yang sudah ada + 1.
    // Dipanggil juga dari form "Pasien Baru" untuk menampilkan nomor berikutnya.
    protected function noRmBerikutnya(): string
    {
        $terbesar = Pasien::query()
            ->whereRaw("no_rm REGEXP '^[0-9]+$'")
            ->selectRaw('MAX(CAST(no_rm AS UNSIGNED)) as terbesar')
            ->value('terbesar');

        return str_pad((string) ((int) $terbesar + 1), 6, '0', STR_PAD_LEFT);
    }

    public function cari(Request $request)
{
    $query = Pasien::query();

    if ($request->filled('no_rm')) {
        $query->where('no_rm', 'like', '%' . $request->no_rm . '%');
    }

    if ($request->filled('nama')) {
        $query->where('nama', 'like', '%' . $request->nama . '%');
    }

    if ($request->filled('alamat')) {
        $query->where('alamat', 'like', '%' . $request->alamat . '%');
    }

    if ($request->filled('nik')) {
        $query->where('nik', 'like', '%' . $request->nik . '%');
    }

    if ($request->filled('tanggal_lahir')) {
        $query->whereDate('tanggal_lahir', $request->tanggal_lahir);
    }

    $pasiens = $query->latest()->limit(50)->get();

    $data = $pasiens->map(function ($p) {
        return [
            'id'     => $p->id,
            'no_rm'  => $p->no_rm,
            'nik'    => $p->nik,
            'nama'   => $p->nama,
            'alamat' => $p->alamat,
            'usia'   => \Carbon\Carbon::parse($p->tanggal_lahir)->age . ' th',
            'jk'     => $p->jenis_kelamin,
            'lahir'  => \Carbon\Carbon::parse($p->tanggal_lahir)->format('d-m-Y'),
            'wali'   => '-',
        ];
    });

    return response()->json($data);
}
 
    // Form edit / kunjungan pasien lama (data identitas otomatis terisi)
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
 
        return view('pasien.edit-lama', compact('pasien'));
    }
 
    // Simpan data kunjungan pasien lama
    public function storeKunjungan(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);
 
        $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'dokter'            => 'required',
            'poli'              => 'required',
            'pembayaran'        => 'required',
        ]);
 
        Kunjungan::create([
            'pasien_id'         => $pasien->id,
            'no_antrian'        => Kunjungan::nomorAntrianBerikutnya($request->poli, $request->tanggal_kunjungan),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'dokter'            => $request->dokter,
            'poli'              => $request->poli,
            'pembayaran'        => $request->pembayaran,
            'status'            => 'Menunggu',
        ]);
 
        return redirect()
            ->route('pasien.lama')
            ->with('success', 'Kunjungan pasien berhasil didaftarkan.');
    }
 
    // Daftarkan kunjungan pasien lama dari modal di halaman Pendaftaran (AJAX).
    // Kunjungan disimpan ke database sehingga pasien langsung muncul di antrian
    // Pemeriksaan poli yang dituju.
    public function storeKunjunganByRm(Request $request, $no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $request->validate([
            'tanggal_kunjungan' => 'required|date',
            'dokter'            => 'nullable',
            'poli'              => 'required',
            'pembayaran'        => 'required',
        ]);

        abort_unless(array_key_exists($request->poli, Poli::daftar()), 422, 'Poli tujuan tidak valid.');

        $kunjungan = Kunjungan::create([
            'pasien_id'         => $pasien->id,
            'no_antrian'        => Kunjungan::nomorAntrianBerikutnya($request->poli, $request->tanggal_kunjungan),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'dokter'            => $request->filled('dokter') ? $request->dokter : $this->dokterBawaan($request->poli),
            'poli'              => $request->poli,
            'pembayaran'        => $request->pembayaran,
            'status'            => 'Menunggu',
        ]);

        return response()->json([
            'success'     => true,
            'no_antrian'  => $kunjungan->no_antrian,
            'tgl_text'    => $this->tanggalKeTeks($kunjungan->tanggal_kunjungan),
            'poli_label'  => Poli::daftar()[$kunjungan->poli] ?? ucfirst($kunjungan->poli),
            'message'     => 'Kunjungan berhasil didaftarkan.',
        ]);
    }

    // Hapus pasien
    public function destroy($id)
    {
        Pasien::findOrFail($id)->delete();
 
        return redirect()
            ->route('pasien.lama')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}