@extends('layouts.pemeriksaan', ['tabAktif' => 'Informed Consent'])

@push('styles')
<style>
    .ic-card{border:2px solid #b40000;border-radius:10px;padding:20px 22px 24px;margin-bottom:18px;background:#fff}
    .ic-h{margin:0 0 4px;font-size:15px;font-weight:700;color:#ed1111}
    .ic-sub{margin:0 0 16px;font-size:13px;color:#222}
    .ic-kosong{padding:40px 0 48px;text-align:center;font-size:12px;color:#777}
</style>
@endpush

@section('konten')
<section class="ic-card">
    <h2 class="ic-h">Informed Consent</h2>
    <p class="ic-sub">Persetujuan tindakan untuk pasien {{ $pasien->nama ?? '-' }}.</p>

    {{-- TODO: isi formulir Informed Consent sesuai desain yang akan dikirim --}}
    <div class="ic-kosong">Formulir Informed Consent akan ditampilkan di sini.</div>
</section>
@endsection
