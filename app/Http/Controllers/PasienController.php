<?php
 
namespace App\Http\Controllers;
 
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
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
    return view('pasien.baru');
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
            'no_rm'              => 'nullable|unique:pasien,no_rm',
            'nama'                => 'required',
            'jenis_kelamin'       => 'required',
            'tanggal_lahir'       => 'required|date',
            'nik'                 => 'required|digits:16|unique:pasien,nik',
            'no_telepon'          => 'nullable',
            'alamat'              => 'required',
            'kecamatan'           => 'required',
            'tanggal_kunjungan'   => 'required|date',
            'dokter'              => 'nullable',
            'poli'                => 'required',
            'pembayaran'          => 'required',
        ]);

        $noRm = $request->filled('no_rm') ? $request->no_rm : $this->noRmBerikutnya();

        $kunjungan = null;

        // Satu transaksi: kalau simpan kunjungan gagal, data pasien ikut dibatalkan
        // (supaya tidak ada pasien "yatim" tanpa kunjungan).
        DB::transaction(function () use ($request, $noRm, &$kunjungan) {
            $pasien = Pasien::create([
                'no_rm'         => $noRm,
                'nama'          => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nik'           => $request->nik,
                'no_telepon'    => $request->no_telepon,
                'alamat'        => $request->alamat,
                'kecamatan'     => $request->kecamatan,
            ]);
 
            $kunjungan = Kunjungan::create([
                'pasien_id'         => $pasien->id,
                'no_antrian'        => Kunjungan::nomorAntrianBerikutnya($request->poli, $request->tanggal_kunjungan),
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'dokter'            => $request->filled('dokter') ? $request->dokter : 'Dokter Jaga',
                'poli'              => $request->poli,
                'pembayaran'        => $request->pembayaran,
                'status'            => 'Menunggu',
            ]);
        });

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
            ->with('success', 'Pasien baru berhasil didaftarkan.');
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

    // No. RM otomatis: nomor terbesar yang sudah ada di database + 1.
    protected function noRmBerikutnya(): string
    {
        $mulaiDari = 123456; // No. RM awal yang dipakai untuk pasien baru

        $terbesar = Pasien::query()
            ->selectRaw('MAX(CAST(no_rm AS UNSIGNED)) as terbesar')
            ->value('terbesar');

        $terbesar = max((int) $terbesar, $mulaiDari - 1);

        return (string) ($terbesar + 1);
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
 
    // Hapus pasien
    public function destroy($id)
    {
        Pasien::findOrFail($id)->delete();
 
        return redirect()
            ->route('pasien.lama')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}