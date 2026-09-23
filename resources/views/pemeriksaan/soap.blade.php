@extends('layouts.app')

@section('title', 'SOAP — ' . $pasien['nama_pasien'])

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

    .tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;

        margin-bottom: 16px;
    }

    .tab-item {
        height: 32px;

        display: inline-flex;
        align-items: center;

        padding: 0 16px;

        border-radius: 6px;

        font-size: 10px;
        font-weight: 500;

        text-decoration: none;

        color: #555;
        background: #ffffff;
        border: 1px solid #e2e2e2;
    }

    .tab-item.active {
        background: #ed1111;
        border-color: #ed1111;
        color: #ffffff;
    }

    .tab-item.disabled {
        cursor: not-allowed;
        opacity: 0.55;
    }

    /* =========================
       RINGKASAN ASESMEN
    ========================== */

    .summary-card {
        background: #f7f9fb;

        border: 1px solid #dbe3ea;
        border-radius: 10px;

        padding: 16px 20px;
        margin-bottom: 16px;
    }

    .summary-title {
        display: flex;
        align-items: center;
        gap: 6px;

        margin-bottom: 10px;

        font-size: 11px;
        font-weight: 700;
        color: #1a7f37;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px 20px;
    }

    .summary-item {
        font-size: 9.5px;
        color: #444;
    }

    .summary-item strong {
        display: block;
        margin-bottom: 2px;
        color: #777;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 8.5px;
        letter-spacing: 0.3px;
    }

    /* =========================
       FORM CARD
    ========================== */

    .form-card {
        background: #ffffff;

        border: 2px solid #3b82f6;
        border-radius: 10px;

        padding: 24px 26px;
    }

    .form-card-title {
        margin-bottom: 18px;

        font-size: 15px;
        font-weight: 700;
        color: #ed1111;
    }

    .field-group {
        margin-bottom: 16px;
    }

    .field label {
        display: block;
        margin-bottom: 6px;

        font-size: 10px;
        font-weight: 600;
        color: #333;
    }

    .field textarea {
        width: 100%;

        padding: 8px 10px;

        border: 1px solid #ccc;
        border-radius: 6px;

        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        color: #222;

        outline: none;
        resize: vertical;
    }

    .field textarea:focus {
        border-color: #3b82f6;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;

        margin-top: 8px;
    }

    .btn-simpan {
        height: 34px;

        padding: 0 22px;

        border: none;
        border-radius: 6px;

        background: #16a34a;
        color: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .btn-simpan:hover {
        background: #128a3e;
    }

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            <a href="{{ route('pemeriksaan.asesmen', [$poli, $pasien['no_rm']]) }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Asesmen
            </a>

            <div class="identity-bar">

                <div class="identity-name-block">
                    <div class="identity-label-top">Identitas Pasien</div>
                    <div class="identity-name">{{ strtoupper($pasien['nama_pasien']) }}</div>
                </div>

                <div class="identity-field">
                    <label>No.RM</label>
                    <div class="value-box">{{ $pasien['no_rm'] }}</div>
                </div>

                <div class="identity-field">
                    <label>NIK</label>
                    <div class="value-box">{{ $pasien['nik'] }}</div>
                </div>

                <div class="identity-field">
                    <label>Jenis Kelamin</label>
                    <div class="value-box">{{ $pasien['jenis_kelamin'] }}</div>
                </div>

                <div class="identity-field">
                    <label>Tanggal Lahir</label>
                    <div class="value-box">{{ $pasien['tanggal_lahir'] }}</div>
                </div>

                <div class="identity-field">
                    <label>Usia</label>
                    <div class="value-box">{{ $pasien['usia'] }}</div>
                </div>

            </div>

            <div class="tabs">
                <a href="{{ route('pemeriksaan.asesmen', [$poli, $pasien['no_rm']]) }}" class="tab-item">Asesment</a>
                <span class="tab-item active">SOAP</span>
                <span class="tab-item disabled">Diagnosa</span>
                <span class="tab-item disabled">Resep</span>
                <span class="tab-item disabled">Clinical Pathway</span>
                <span class="tab-item disabled">Penunjang</span>
                <span class="tab-item disabled">Rujukan</span>
                <span class="tab-item disabled">Autentikasi</span>
            </div>

            @if ($errors->any())
                <div style="margin-bottom: 16px; padding: 10px 14px; background: #fdecea; color: #b3261e; border-radius: 6px; font-size: 11px; font-weight: 500;">
                    Semua kolom SOAP wajib diisi. Silakan lengkapi dulu sebelum menyimpan.
                </div>
            @endif

            {{-- RINGKASAN HASIL ASESMEN --}}
            <div class="summary-card">
                <div class="summary-title">
                    <i class="bi bi-check-circle-fill"></i>
                    Hasil Asesmen (tersimpan di rekam medis pasien ini)
                </div>

                <div class="summary-grid">
                    <div class="summary-item"><strong>Keluhan Utama</strong>{{ $asesmen['keluhan_utama'] }}</div>
                    <div class="summary-item"><strong>TD</strong>{{ $asesmen['td'] }}</div>
                    <div class="summary-item"><strong>Suhu Badan</strong>{{ $asesmen['suhu_badan'] }}</div>
                    <div class="summary-item"><strong>Berat Badan</strong>{{ $asesmen['berat_badan'] }}</div>
                    <div class="summary-item"><strong>TB</strong>{{ $asesmen['tb'] }}</div>
                    <div class="summary-item"><strong>Detak Jantung</strong>{{ $asesmen['detak_jantung'] }}</div>
                </div>
            </div>

            {{-- FORM SOAP --}}
            <div class="form-card">

                <h2 class="form-card-title">SOAP</h2>

                <form method="POST" action="{{ route('pemeriksaan.soap.store', [$poli, $pasien['no_rm']]) }}" id="form-soap">
                    @csrf

                    <div class="field-group">
                        <div class="field">
                            <label>Subjective</label>
                            <textarea name="subjective" rows="3" placeholder="Keluhan dari sudut pandang pasien" required>{{ old('subjective') }}</textarea>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label>Objective</label>
                            <textarea name="objective" rows="3" placeholder="Hasil pemeriksaan fisik/penunjang" required>{{ old('objective') }}</textarea>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label>Assessment</label>
                            <textarea name="assessment" rows="3" placeholder="Analisis/diagnosis sementara" required>{{ old('assessment') }}</textarea>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label>Plan</label>
                            <textarea name="plan" rows="3" placeholder="Rencana tindakan/terapi selanjutnya" required>{{ old('plan') }}</textarea>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn-simpan">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

<script>
    document.querySelectorAll('#form-soap [required]').forEach(function (field) {
        field.addEventListener('invalid', function () {
            field.setCustomValidity('Kolom ini wajib diisi.');
        });
        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });
    });
</script>

@endsection
