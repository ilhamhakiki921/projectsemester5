<?php
 
namespace App\Http\Controllers;
 
use App\Models\Pasien;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
 
class PasienController extends Controller
{
    // Halaman Data Pasien (tabel list pasien lama)
    public function indexLama()
    {
        $pasiens = Pasien::latest()->paginate(10);
 
        return view('pasien.lama', compact('pasiens'));
    }

    public function pendaftaran()
{
    $pasiens = Pasien::orderByDesc('No_RM')->get();

    return view('pendaftaran.index', compact('pasiens'));
}
 
    // Form tambah pasien baru
    public function create()
    {
        return view('pasien.baru');
    }
 
    // Simpan pasien baru sekaligus kunjungan pertamanya
    public function store(Request $request)
    {
        $request->validate([
            'no_rm'              => 'required|unique:pasien,no_rm',
            'nama'                => 'required',
            'jenis_kelamin'       => 'required',
            'tanggal_lahir'       => 'required|date',
            'nik'                 => 'required|unique:pasien,nik',
            'no_telepon'          => 'nullable',
            'alamat'              => 'required',
            'tanggal_kunjungan'   => 'required|date',
            'dokter'              => 'required',
            'poli'                => 'required',
            'pembayaran'          => 'required',
        ]);
 
        $pasien = Pasien::create([
            'no_rm'         => $request->no_rm,
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nik'           => $request->nik,
            'no_telepon'    => $request->no_telepon,
            'alamat'        => $request->alamat,
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
            ->with('success', 'Pasien baru berhasil didaftarkan.');
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