<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenunjangController extends Controller
{
    public function index($no_rm)
    {
        // Jika model pasien Anda bukan App\Models\Pasien, ubah di sini
        $pasien = class_exists(\App\Models\Pasien::class)
            ? \App\Models\Pasien::where('no_rm', $no_rm)->first() : null;

        $riwayat = DB::table('permintaan_penunjang')
            ->where('no_rm', $no_rm)->orderBy('id')->get()
            ->map(fn ($r) => $this->format($r))->values();

        return view('pemeriksaan.penunjang', [
            'pasien'   => $pasien,
            'no_rm'    => $no_rm,
            'baseUrl'  => url("/pemeriksaan/umum/{$no_rm}"),
            'storeUrl' => route('penunjang.store', $no_rm),
            'riwayat'  => $riwayat,
        ]);
    }

    public function store(Request $request, $no_rm)
    {
        $data = $request->validate([
            'pemeriksaan'   => 'required|array|min:1',
            'pemeriksaan.*' => 'string|max:100',
            'hasil_ke'      => 'required|in:dokter,pasien',
            'keterangan'    => 'nullable|string|max:1000',
        ]);

        $id = DB::table('permintaan_penunjang')->insertGetId([
            'no_rm'       => $no_rm,
            'pemeriksaan' => json_encode($data['pemeriksaan']),
            'perlu_puasa' => $request->boolean('perlu_puasa'),
            'hasil_ke'    => $data['hasil_ke'],
            'keterangan'  => $data['keterangan'] ?? null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json($this->format(DB::table('permintaan_penunjang')->find($id)), 201);
    }

    public function destroy($no_rm, $id)
    {
        $q = DB::table('permintaan_penunjang')
            ->where('no_rm', $no_rm)->where('id', $id)->where('status', 'Menunggu');

        abort_unless($q->exists(), 422, 'Permintaan sudah diproses.');
        $q->delete();

        return response()->json(['ok' => true]);
    }

    private function format($r): array
    {
        return [
            'id'        => $r->id,
            'items'     => json_decode($r->pemeriksaan, true),
            'tanggal'   => Carbon::parse($r->created_at)->translatedFormat('d M Y'),
            'status'    => $r->status,
            'hapus_url' => route('penunjang.destroy', [$r->no_rm, $r->id]),
        ];
    }
}
