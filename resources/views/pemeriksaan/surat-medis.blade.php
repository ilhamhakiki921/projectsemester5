@extends('layouts.pemeriksaan', [
    'tabAktif'  => 'Surat Medis',
    'backUrl'   => route('pemeriksaan.index', $poli),
    'backLabel' => 'Kembali ke Antrian',
])

@push('styles')
<style>
    .sm-card{min-height:320px;border:1px solid #6b6b6b;border-radius:14px;padding:22px 20px 26px;background:#fff}
    .sm-title{margin:0;font-size:13px;font-weight:700;color:#c00000}
</style>
@endpush

@section('konten')
<div class="sm-card">
    <h2 class="sm-title">Surat Medis</h2>
    {{-- Isi halaman Surat Medis menyusul (desain belum ada) --}}
</div>
@endsection
