@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien')

@section('style')

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    color: #222;
}

.main {
    width: calc(100% - 230px);
    min-height: 100vh;
    margin-left: 230px;
    background: #f3f4f6;
}

.content {
    width: 100%;
    padding: 25px 22px;
    background: #f3f4f6;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    margin-bottom: 14px;

    color: #555;
    text-decoration: none;

    font-size: 11px;
    font-weight: 500;
}

.back-link:hover {
    color: #0f3d33;
}

.card {
    background: #ffffff;

    border-radius: 14px;

    padding: 22px 24px;
    margin-bottom: 18px;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.pasien-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    flex-wrap: wrap;

    gap: 14px;
}

.pasien-nama {
    font-size: 18px;
    font-weight: 700;

    color: #1a1a1a;
}

.pasien-meta {
    display: flex;
    flex-wrap: wrap;

    gap: 22px;

    margin-top: 12px;
}

.meta-item label {
    display: block;

    margin-bottom: 3px;

    font-size: 9px;
    font-weight: 600;

    text-transform: uppercase;

    color: #8a8f98;
}

.meta-item span {
    font-size: 12px;
    font-weight: 500;

    color: #222;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 5px 11px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 600;
}

.badge::before {
    content: "";

    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}

.badge-menunggu {
    background: #fdecd2;
    color: #b3691a;
}

.badge-diperiksa {
    background: #dbe9fe;
    color: #1d5fd6;
}

.badge-selesai {
    background: #d7f2e3;
    color: #158a4c;
}

.section-title {
    margin-bottom: 12px;

    font-size: 14px;
    font-weight: 700;

    color: #1a1a1a;
}

.placeholder-box {
    padding: 30px 20px;

    text-align: center;

    border: 1px dashed #d0d3d8;
    border-radius: 10px;

    font-size: 11px;
    color: #8a8f98;
}

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">

        <x-topbar title="Pemeriksaan Pasien" icon="heart-pulse-fill" />

        <section class="content">

            <a href="{{ route('pemeriksaan.index', ['poli' => $antrian->poli]) }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Antrian
            </a>

            {{-- DATA PASIEN --}}
            <div class="card">

                <div class="pasien-header">

                    <div class="pasien-nama">
                        {{ $antrian->pasien->nama }}
                    </div>

                    @if ($antrian->status === 'Menunggu')
                        <span class="badge badge-menunggu">Menunggu</span>
                    @elseif ($antrian->status === 'Sedang Diperiksa')
                        <span class="badge badge-diperiksa">Sedang Diperiksa</span>
                    @else
                        <span class="badge badge-selesai">Selesai</span>
                    @endif

                </div>

                <div class="pasien-meta">

                    <div class="meta-item">
                        <label>No. RM</label>
                        <span>{{ $antrian->pasien->no_rm }}</span>
                    </div>

                    <div class="meta-item">
                        <label>NIK</label>
                        <span>{{ $antrian->pasien->nik }}</span>
                    </div>

                    <div class="meta-item">
                        <label>Jenis Kelamin</label>
                        <span>{{ $antrian->pasien->jenis_kelamin }}</span>
                    </div>

                    <div class="meta-item">
                        <label>Umur</label>
                        <span>{{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} th</span>
                    </div>

                    <div class="meta-item">
                        <label>Alamat</label>
                        <span>{{ $antrian->pasien->alamat }}</span>
                    </div>

                    <div class="meta-item">
                        <label>Poli</label>
                        <span>{{ $antrian->poli }}</span>
                    </div>

                    <div class="meta-item">
                        <label>Tgl Kunjungan</label>
                        <span>{{ \Carbon\Carbon::parse($antrian->tanggal_kunjungan)->translatedFormat('d M Y') }}</span>
                    </div>

                </div>

            </div>

            {{-- FORM PEMERIKSAAN (BELUM DIBUAT) --}}
            <div class="card">

                <div class="section-title">
                    Asesmen Pemeriksaan
                </div>

                <div class="placeholder-box">
                    Form asesmen pemeriksaan (keluhan utama, riwayat penyakit, tanda vital, dll)
                    akan dibuat di tahap selanjutnya.
                </div>

            </div>

        </section>

    </main>

</div>

@endsection
