<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenunjangController extends Controller
{
    public function index($poli, $no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->first();

        if (! $pasien) {
            abort(404, 'Data pasien tidak ditemukan.');
        }

        $riwayat = DB::table('permintaan_penunjang')
            ->where('no_rm', $no_rm)->orderBy('id')->get()
            ->map(fn ($r) => $this->format($r, $poli))->values();

        return view('pemeriksaan.penunjang', [
            'poli'     => $poli,
            'rm'       => $no_rm,
            'pasien'   => $pasien,
            'storeUrl' => route('penunjang.store', [$poli, $no_rm]),
            'riwayat'  => $riwayat,
        ]);
    }

    public function store(Request $request, $poli, $no_rm)
    {
        $data = $request->validate([
            'pemeriksaan'   => 'required|array|min:1',
            'pemeriksaan.*' => 'string|max:100',
            'keterangan'    => 'nullable|string|max:1000',
        ], [
            'pemeriksaan.required' => 'Pilih minimal satu pemeriksaan.',
        ]);

        $id = DB::table('permintaan_penunjang')->insertGetId([
            'no_rm'       => $no_rm,
            'pemeriksaan' => json_encode($data['pemeriksaan']),
            'perlu_puasa' => 'Tidak',
            'hasil_ke'    => 'dokter',
            'keterangan'  => $data['keterangan'] ?? null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json($this->format(DB::table('permintaan_penunjang')->find($id), $poli), 201);
    }

    public function update(Request $request, $poli, $no_rm, $id)
    {
        $data = $request->validate([
            'pemeriksaan'   => 'required|array|min:1',
            'pemeriksaan.*' => 'string|max:100',
            'keterangan'    => 'nullable|string|max:1000',
        ], [
            'pemeriksaan.required' => 'Pilih minimal satu pemeriksaan.',
        ]);

        $q = DB::table('permintaan_penunjang')
            ->where('no_rm', $no_rm)->where('id', $id)->where('status', 'Menunggu');

        abort_unless($q->exists(), 422, 'Permintaan sudah diproses, tidak bisa diubah.');

        $q->update([
            'pemeriksaan' => json_encode($data['pemeriksaan']),
            'keterangan'  => $data['keterangan'] ?? null,
            'updated_at'  => now(),
        ]);

        return response()->json($this->format(DB::table('permintaan_penunjang')->find($id), $poli));
    }

    public function destroy($poli, $no_rm, $id)
    {
        $q = DB::table('permintaan_penunjang')
            ->where('no_rm', $no_rm)->where('id', $id)->where('status', 'Menunggu');

        abort_unless($q->exists(), 422, 'Permintaan sudah diproses.');
        $q->delete();

        return response()->json(['ok' => true]);
    }

    private function format($r, $poli): array
    {
        return [
            'id'        => $r->id,
            'items'     => json_decode($r->pemeriksaan, true),
            'tanggal'   => Carbon::parse($r->created_at)->format('d-m-Y'),
            'status'    => $r->status,
            'keterangan' => $r->keterangan,
            'edit_url'  => route('penunjang.update', [$poli, $r->no_rm, $r->id]),
            'hapus_url' => route('penunjang.destroy', [$poli, $r->no_rm, $r->id]),
        ];
    }
}
