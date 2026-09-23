@extends('layouts.app')

@section('title', 'Rekam Medis — ' . $pasien->nama)

@section('style')

<style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: #ffffff;
        color: #222;
    }

    .main {
        width: calc(100% - 230px);
        min-height: 100vh;
        margin-left: 230px;
        background: #f5f5f5;
    }

    .content {
        width: 100%;
        padding: 25px 22px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        margin-bottom: 14px;

        color: #444;
        text-decoration: none;

        font-size: 11px;
        font-weight: 500;
    }

    .back-link:hover {
        color: #ed1111;
    }

    .identity-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 22px;

        padding: 16px 22px;
        margin-bottom: 16px;

        border-radius: 10px;

        background: linear-gradient(180deg, #980000 0%, #570000 100%);
        color: #ffffff;
    }

    .identity-name-block {
        min-width: 170px;
    }

    .identity-label-top {
        font-size: 9px;
        opacity: 0.8;
        margin-bottom: 3px;
    }

    .identity-name {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .identity-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .identity-field label {
        font-size: 9px;
        opacity: 0.85;
    }

    .identity-field .value-box {
        min-width: 120px;
        height: 28px;

        display: flex;
        align-items: center;

        padding: 0 10px;

        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 5px;

        font-size: 10px;
        font-weight: 500;
    }

    .section-card {
        background: #ffffff;

        border: 1px solid #e2e2e2;
        border-radius: 10px;

        padding: 20px 22px;
        margin-bottom: 16px;
    }

    .section-card-title {
        display: flex;
        align-items: center;
        gap: 8px;

        margin-bottom: 14px;

        font-size: 13px;
        font-weight: 700;
        color: #ed1111;
    }

    .kv-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px 20px;
    }

    .kv-item {
        font-size: 10px;
        color: #333;
    }

    .kv-item strong {
        display: block;
        margin-bottom: 3px;

        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #888;
    }

    .kv-item.full {
        grid-column: 1 / -1;
    }

    .empty-note {
        font-size: 10px;
        color: #999;
        font-style: italic;
    }

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Rekam Medis" icon="folder-fill" />

        <section class="content">

            <a href="{{ route('rekam-medis.index') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Daftar Rekam Medis
            </a>

            <div class="identity-bar">

                <div class="identity-name-block">
                    <div class="identity-label-top">Identitas Pasien</div>
                    <div class="identity-name">{{ strtoupper($pasien->nama) }}</div>
                </div>

                <div class="identity-field">
                    <label>No.RM</label>
                    <div class="value-box">{{ $pasien->no_rm }}</div>
                </div>

                <div class="identity-field">
                    <label>NIK</label>
                    <div class="value-box">{{ $pasien->nik }}</div>
                </div>

                <div class="identity-field">
                    <label>Jenis Kelamin</label>
                    <div class="value-box">{{ $pasien->jenis_kelamin }}</div>
                </div>

                <div class="identity-field">
                    <label>Poli</label>
                    <div class="value-box">{{ ucfirst($kunjungan->poli) }}</div>
                </div>

                <div class="identity-field">
                    <label>Status</label>
                    <div class="value-box">{{ $kunjungan->status }}</div>
                </div>

            </div>

            @if (! $rekam)

                <div class="section-card">
                    <p class="empty-note">Pasien ini belum mulai diperiksa — belum ada data asesmen, SOAP, maupun diagnosa.</p>
                </div>

            @else

                {{-- ASESMEN --}}
                <div class="section-card">
                    <h2 class="section-card-title"><i class="bi bi-clipboard2-pulse-fill"></i> Asesmen Pemeriksaan</h2>

                    @if ($rekam->keluhan_utama)
                        <div class="kv-grid">
                            <div class="kv-item full"><strong>Keluhan Utama</strong>{{ $rekam->keluhan_utama }}</div>
                            <div class="kv-item"><strong>Riwayat Penyakit Sekarang</strong>{{ $rekam->riwayat_penyakit_sekarang }}</div>
                            <div class="kv-item"><strong>Riwayat Penyakit Terdahulu</strong>{{ $rekam->riwayat_penyakit_terdahulu }}</div>
                            <div class="kv-item"><strong>Riwayat Alergi</strong>{{ $rekam->riwayat_alergi }}</div>
                            <div class="kv-item"><strong>Riwayat Pengobatan</strong>{{ $rekam->riwayat_pengobatan }}</div>
                            <div class="kv-item"><strong>TB</strong>{{ $rekam->tb }}</div>
                            <div class="kv-item"><strong>TD</strong>{{ $rekam->td }}</div>
                            <div class="kv-item"><strong>LP</strong>{{ $rekam->lp }}</div>
                            <div class="kv-item"><strong>Respiratory Rate</strong>{{ $rekam->respiratory_rate }}</div>
                            <div class="kv-item"><strong>Berat Badan</strong>{{ $rekam->berat_badan }}</div>
                            <div class="kv-item"><strong>Detak Jantung</strong>{{ $rekam->detak_jantung }}</div>
                            <div class="kv-item"><strong>Lingkar Dada</strong>{{ $rekam->lingkar_dada }}</div>
                            <div class="kv-item"><strong>Saturasi</strong>{{ $rekam->saturasi }}</div>
                            <div class="kv-item"><strong>IMT</strong>{{ $rekam->imt }}</div>
                            <div class="kv-item"><strong>Suhu Badan</strong>{{ $rekam->suhu_badan }}</div>
                            <div class="kv-item"><strong>Lingkar Kepala</strong>{{ $rekam->lingkar_kepala }}</div>
                            <div class="kv-item"><strong>GCS</strong>{{ $rekam->gcs }}</div>
                        </div>
                    @else
                        <p class="empty-note">Belum diisi.</p>
                    @endif
                </div>

                {{-- SOAP --}}
                <div class="section-card">
                    <h2 class="section-card-title"><i class="bi bi-file-earmark-medical-fill"></i> SOAP</h2>

                    @if ($rekam->subjective)
                        <div class="kv-grid">
                            <div class="kv-item full"><strong>S (Subjek)</strong>{{ $rekam->subjective }}</div>
                            <div class="kv-item full"><strong>O (Object)</strong>{{ $rekam->objective }}</div>
                            <div class="kv-item full"><strong>A (Assesmen)</strong>{{ $rekam->assessment }}</div>
                            <div class="kv-item full"><strong>P (Plan)</strong>{{ $rekam->plan }}</div>
                        </div>
                    @else
                        <p class="empty-note">Belum diisi.</p>
                    @endif
                </div>

                {{-- DIAGNOSA --}}
                <div class="section-card">
                    <h2 class="section-card-title"><i class="bi bi-journal-medical"></i> Diagnosa</h2>

                    @if ($rekam->diagnosa_kerja)
                        <div class="kv-grid">
                            <div class="kv-item"><strong>Diagnosa Kerja</strong>{{ $rekam->diagnosa_kerja }}</div>
                            <div class="kv-item"><strong>Kode ICD-10</strong>{{ $rekam->kode_icd10 ?: '-' }}</div>
                            <div class="kv-item full"><strong>Diagnosa Banding</strong>{{ $rekam->diagnosa_banding ?: '-' }}</div>
                        </div>
                    @else
                        <p class="empty-note">Belum diisi.</p>
                    @endif
                </div>

            @endif

        </section>

    </main>

</div>

@endsection
