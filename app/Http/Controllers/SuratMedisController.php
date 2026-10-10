<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\SuratMedis;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratMedisController extends Controller
{
    /** Pasien + kunjungan terbaru di poli ini (sama seperti halaman Pemeriksaan lain). */
    protected function konteks($poli, $no_rm): array
    {
        abort_unless(array_key_exists($poli, Poli::daftar()), 404);

        $pasien = Pasien::where('no_rm', $no_rm)->first();
        abort_unless($pasien, 404, 'Data pasien tidak ditemukan.');

        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)
            ->where('poli', $poli)->latest('id')->first();
        abort_unless($kunjungan, 404, 'Data kunjungan pasien tidak ditemukan di poli ini.');

        return [$pasien, $kunjungan];
    }

    /** Surat medis milik pasien ini (semua kunjungannya). */
    protected function suratPasien(Pasien $pasien)
    {
        return SuratMedis::whereIn('kunjungan_id', Kunjungan::where('pasien_id', $pasien->id)->pluck('id'));
    }

    /** Halaman Surat Medis (tab setelah Rujukan, sebelum Autentikasi) untuk semua poli. */
    public function index($poli, $no_rm)
    {
        [$pasien, $kunjungan] = $this->konteks($poli, $no_rm);

        $dokter = $kunjungan->dokter ?: (Auth::check() ? Auth::user()->nama_lengkap : '');

        // Keterangan fisik terisi otomatis dari tanda vital di Asesmen (kalau ada), tetap bisa diubah
        $rm = $kunjungan->rekamMedis;
        $angka = fn ($v) => preg_match('/\d+(?:[.,]\d+)?/', (string) $v, $m) ? str_replace(',', '.', $m[0]) : '';
        $tensi = preg_match('/(\d{2,3})\s*\/\s*(\d{2,3})/', (string) ($rm?->td), $m) ? $m[1] . '/' . $m[2] : '';

        // Isian awal per jenis: kalau sudah pernah disimpan di kunjungan ini, tampilkan yang tersimpan
        $tersimpan = SuratMedis::where('kunjungan_id', $kunjungan->id)->get()->keyBy('jenis');
        $data = [];
        foreach (['Sakit', 'Sehat'] as $jenis) {
            $s = $tersimpan->get($jenis);
            $data[$jenis] = $s
                ? $this->format($s, $poli, $no_rm)
                : [
                    'id'             => null,
                    'nomor_surat'    => SuratMedis::nomorBerikutnya($jenis, now())[1],
                    'alamat_pasien'  => (string) $pasien->alamat,
                    'lama_istirahat' => '',
                    'mulai_tanggal'  => '',
                    'sampai_tanggal' => '',
                    'keperluan'      => '',
                    'tanggal_pemeriksaan' => $kunjungan->tanggal_kunjungan?->toDateString() ?? now()->toDateString(),
                    'hasil_pemeriksaan'   => '',
                    'berat_badan'    => $angka($rm?->berat_badan),
                    'tinggi_badan'   => $angka($rm?->tb),
                    'tekanan_darah'  => $tensi,
                    'tempat'         => 'Ngawi',
                    'tanggal_surat'  => now()->toDateString(),
                    'dokter'         => $dokter,
                    'ttd_dokter'     => '',
                    'cetak_url'      => null,
                    'unduh_url'      => null,
                ];
        }

        return view('pemeriksaan.surat-medis', [
            'poli'     => $poli,
            'rm'       => $no_rm,
            'namaPoli' => Poli::daftar()[$poli],
            'pasien'   => $pasien,
            'data'     => $data,
            'storeUrl' => route('surat-medis.store', [$poli, $no_rm]),
        ]);
    }

    /** Simpan surat. Satu kunjungan hanya punya satu surat per jenis, jadi simpan ulang = memperbarui. */
    public function store(Request $request, $poli, $no_rm)
    {
        [, $kunjungan] = $this->konteks($poli, $no_rm);

        $sakit = $request->input('jenis') === 'Sakit';

        $rules = [
            'jenis'         => 'required|in:Sakit,Sehat',
            'alamat_pasien' => 'required|string|max:255',
            'tempat'        => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'dokter'        => 'required|string|max:150',
            'ttd_dokter'    => 'nullable|string|max:2000000',
        ];

        if ($sakit) {
            $rules['lama_istirahat'] = 'required|integer|min:1|max:365';
            $rules['mulai_tanggal']  = 'required|date';
        } else {
            $rules['tanggal_pemeriksaan'] = 'required|date';
            $rules['keperluan']           = 'required|string|max:255';
            $rules['hasil_pemeriksaan']   = 'required|string|max:500';
            $rules['berat_badan']         = 'required|numeric|min:1|max:500';
            $rules['tinggi_badan']        = 'required|numeric|min:30|max:250';
            $rules['tekanan_darah']       = ['required', 'regex:/^\d{2,3}\/\d{2,3}$/'];
        }

        $data = $request->validate($rules, [
            'required' => ':attribute wajib diisi.',
            'integer'  => ':attribute harus berupa angka.',
            'numeric'  => ':attribute harus berupa angka.',
            'tekanan_darah.regex' => 'Tekanan darah harus berformat 120/80.',
            'min'      => ':attribute minimal :min.',
            'max'      => ':attribute terlalu besar / panjang.',
        ], [
            'alamat_pasien'  => 'Alamat pasien',
            'lama_istirahat' => 'Lama istirahat',
            'mulai_tanggal'  => 'Mulai tanggal',
            'keperluan'      => 'Dipergunakan untuk',
            'tanggal_pemeriksaan' => 'Tanggal pemeriksaan',
            'hasil_pemeriksaan'   => 'Hasil pemeriksaan',
            'berat_badan'    => 'Berat badan',
            'tinggi_badan'   => 'Tinggi badan',
            'tekanan_darah'  => 'Tekanan darah',
            'tempat'         => 'Tempat',
            'tanggal_surat'  => 'Tanggal surat',
            'dokter'         => 'Dokter yang memeriksa',
        ]);

        $surat = SuratMedis::firstOrNew(['kunjungan_id' => $kunjungan->id, 'jenis' => $data['jenis']]);

        // Nomor dibuat sekali saat surat pertama kali disimpan, tidak berubah saat diperbarui
        if (! $surat->exists) {
            [$surat->no_urut, $surat->nomor_surat] = SuratMedis::nomorBerikutnya($data['jenis'], Carbon::parse($data['tanggal_surat']));
        }

        $surat->fill([
            'alamat_pasien'  => $data['alamat_pasien'],
            'lama_istirahat' => $sakit ? $data['lama_istirahat'] : null,
            'mulai_tanggal'  => $sakit ? $data['mulai_tanggal'] : null,
            'sampai_tanggal' => $sakit ? Carbon::parse($data['mulai_tanggal'])->addDays($data['lama_istirahat'] - 1)->toDateString() : null,
            'keperluan'      => $sakit ? null : $data['keperluan'],
            'tanggal_pemeriksaan' => $sakit ? null : $data['tanggal_pemeriksaan'],
            'hasil_pemeriksaan'   => $sakit ? null : $data['hasil_pemeriksaan'],
            'berat_badan'    => $sakit ? null : $data['berat_badan'],
            'tinggi_badan'   => $sakit ? null : $data['tinggi_badan'],
            'tekanan_darah'  => $sakit ? null : $data['tekanan_darah'],
            'tempat'         => $data['tempat'],
            'tanggal_surat'  => $data['tanggal_surat'],
            'dokter'         => $data['dokter'],
            'ttd_dokter'     => $data['ttd_dokter'] ?: null,
        ])->save();

        return response()->json($this->format($surat, $poli, $no_rm), 201);
    }

    /** Tampilan cetak (browser), ukuran F4. */
    public function cetak($poli, $no_rm, $id)
    {
        return view('pemeriksaan.surat-medis-cetak', $this->dataCetak($poli, $no_rm, $id, false));
    }

    /** Unduh PDF, ukuran F4. */
    public function unduh($poli, $no_rm, $id)
    {
        $data = $this->dataCetak($poli, $no_rm, $id, true);

        $pdf = Pdf::loadView('pemeriksaan.surat-medis-cetak', $data)
            ->setPaper([0, 0, 609.45, 935.43], 'portrait');   // F4 = 215 x 330 mm

        $nama = 'Surat-Keterangan-' . $data['surat']->jenis . '-' . $data['pasien']->no_rm . '.pdf';

        return $pdf->download($nama);
    }

    // ----------------------------------------------------------

    protected function dataCetak($poli, $no_rm, $id, bool $pdf): array
    {
        [$pasien] = $this->konteks($poli, $no_rm);
        $surat = $this->suratPasien($pasien)->findOrFail($id);

        return ['surat' => $surat, 'pasien' => $pasien, 'pdf' => $pdf];
    }

    protected function format(SuratMedis $s, $poli, $no_rm): array
    {
        return [
            'id'             => $s->id,
            'nomor_surat'    => $s->nomor_surat,
            'alamat_pasien'  => $s->alamat_pasien,
            'lama_istirahat' => $s->lama_istirahat ?? '',
            'mulai_tanggal'  => $s->mulai_tanggal?->toDateString() ?? '',
            'sampai_tanggal' => $s->sampai_tanggal?->toDateString() ?? '',
            'keperluan'      => $s->keperluan ?? '',
            'tanggal_pemeriksaan' => $s->tanggal_pemeriksaan?->toDateString() ?? '',
            'hasil_pemeriksaan'   => $s->hasil_pemeriksaan ?? '',
            'berat_badan'    => $s->berat_badan !== null ? (float) $s->berat_badan : '',
            'tinggi_badan'   => $s->tinggi_badan !== null ? (float) $s->tinggi_badan : '',
            'tekanan_darah'  => $s->tekanan_darah ?? '',
            'tempat'         => $s->tempat,
            'tanggal_surat'  => $s->tanggal_surat->toDateString(),
            'dokter'         => $s->dokter,
            'ttd_dokter'     => $s->ttd_dokter ?? '',
            'cetak_url'      => route('surat-medis.cetak', [$poli, $no_rm, $s->id]),
            'unduh_url'      => route('surat-medis.unduh', [$poli, $no_rm, $s->id]),
        ];
    }
}
