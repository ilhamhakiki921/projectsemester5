@extends('layouts.app')

@section('title', 'Diagnosa — ' . $pasien->nama)

@section('style')
<link rel="stylesheet" href="{{ asset('css/icd10.css') }}">

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

    /* =========================
       KEMBALI
    ========================== */

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

    /* =========================
       IDENTITAS PASIEN
    ========================== */

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

    /* =========================
       TABS
    ========================== */

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

        border-radius: 20px;

        font-size: 10px;
        font-weight: 600;

        text-decoration: none;

        color: #1a1a1a;
        background: transparent;
    }

    .tab-item.active {
        background: #7a0000;
        color: #ffffff;
    }

    .tab-item.disabled {
        cursor: not-allowed;
    }

    /* =========================
       FORM CARD
    ========================== */

    .form-card {
        background: #ffffff;

        border: 2px solid #b40000;
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

    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;

        margin-bottom: 16px;
    }

    .field label {
        display: block;
        margin-bottom: 6px;

        font-size: 10px;
        font-weight: 600;
        color: #333;
    }

    .field label .required-mark {
        color: #ed1111;
        margin-left: 2px;
    }

    .field input,
    .field textarea {
        width: 100%;

        padding: 8px 10px;

        border: 1px solid #ccc;
        border-radius: 6px;

        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        color: #222;

        outline: none;
    }

    .field input:focus,
    .field textarea:focus {
        border-color: #b40000;
        box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
    }

    /* =========================
       DIAGNOSIS SEKUNDER & TINDAKAN (bisa ditambah)
    ========================== */

    .repeat-list {
        display: flex;
        flex-direction: column;
        gap: 8px;

        margin-bottom: 10px;
    }

    .repeat-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .repeat-row input {
        flex: 1;
    }

    .btn-hapus-row {
        width: 30px;
        height: 30px;
        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #ed1111;
        border-radius: 6px;

        background: #ffffff;
        color: #ed1111;

        font-size: 16px;
        line-height: 1;
        cursor: pointer;
    }

    .btn-hapus-row:hover {
        background: #fdecea;
    }

    .btn-tambah {
        height: 28px;

        padding: 0 14px;

        border: 1px solid #7a0000;
        border-radius: 6px;

        background: #ffffff;
        color: #7a0000;

        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        font-weight: 600;

        cursor: pointer;
    }

    .btn-tambah:hover {
        background: #7a0000;
        color: #ffffff;
    }

    /* =========================
       SUBMIT
    ========================== */

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

    {{-- SIDEBAR --}}
    <x-sidebar />


    {{-- MAIN --}}
    <main class="main">
        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            <x-popup-alert />

            {{-- KEMBALI --}}
            {{-- IDENTITAS PASIEN --}}
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
                    <label>Tanggal Lahir</label>
                    <div class="value-box">{{ $pasien->tanggal_lahir->format('d-m-Y') }}</div>
                </div>

                <div class="identity-field">
                    <label>Usia</label>
                    <div class="value-box">{{ $pasien->tanggal_lahir->age }} Tahun</div>
                </div>

            </div>


            {{-- TABS --}}
            <div class="tabs">
                <a href="{{ route('pemeriksaan.asesmen', [$poli, $pasien->no_rm]) }}" class="tab-item">Anamnesa</a>
                <a href="{{ route('pemeriksaan.soap', [$poli, $pasien->no_rm]) }}" class="tab-item">SOAP</a>
                <span class="tab-item active">Diagnosa</span>
                <a href="{{ route('pemeriksaan.resep', [$poli, $pasien->no_rm]) }}" class="tab-item">Resep</a>
                <a href="{{ route('pemeriksaan.clinical-pathway', [$poli, $pasien->no_rm]) }}" class="tab-item">Clinical Pathway</a>
                <span class="tab-item disabled">Penunjang</span>
                <span class="tab-item disabled">Rujukan</span>
                <span class="tab-item disabled">Autentikasi</span>
            </div>

            @if ($errors->any())
                <div style="margin-bottom: 16px; padding: 10px 14px; background: #fdecea; color: #b3261e; border-radius: 6px; font-size: 11px; font-weight: 500;">
                    Diagnosis Utama wajib diisi. Silakan lengkapi dulu sebelum menyimpan.
                </div>
            @endif


            {{-- FORM DIAGNOSA --}}
            <div class="form-card">

                <h2 class="form-card-title">Diagnosis</h2>

                @php
                    $utama = old('diagnosis_utama', optional($rekam)->diagnosis_utama);
                    $kodeIcd10 = old('kode_icd10', optional($rekam)->kode_icd10 ?? null);

                    $sekunder = old(
                        'diagnosis_sekunder',
                        optional($rekam)->diagnosis_sekunder ? explode(' | ', $rekam->diagnosis_sekunder) : []
                    );

                    $tindakanList = old(
                        'tindakan',
                        optional($rekam)->tindakan ? explode(', ', $rekam->tindakan) : []
                    );

                    if (count($sekunder) === 0) { $sekunder = [null]; }
                    if (count($tindakanList) === 0) { $tindakanList = [null]; }
                @endphp

                <form method="POST" action="{{ route('pemeriksaan.diagnosa.store', [$poli, $pasien->no_rm]) }}" id="form-diagnosa" data-icd-url="{{ route('icd10.cari') }}">
                    @csrf

                    <div class="field-group">
                        <div class="field">
                            <label for="diagnosis_utama">Diagnosis Utama<span class="required-mark">*</span></label>
                            <input type="text" id="diagnosis_utama" name="diagnosis_utama" data-icd="utama" autocomplete="off"
                                   value="{{ $utama }}"
                                   placeholder="Ketik kode atau nama penyakit (ICD-10), contoh: J06.9 / respiratory" required>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label for="kode_icd10">Kode ICD-10 (opsional)</label>
                            <input type="text" id="kode_icd10" name="kode_icd10"
                                   value="{{ $kodeIcd10 }}"
                                   placeholder="Contoh: J06.9">
                        </div>
                    </div>

                    {{-- Diagnosis Sekunder & Tindakan: masing-masing satu baris penuh (tersusun ke bawah, seperti Diagnosis Utama) --}}
                    <div class="field-group">
                        <div class="field" data-dx-section>
                            <label>Diagnosis Sekunder (opsional)</label>

                            <div class="repeat-list" data-list>
                                @foreach ($sekunder as $nilai)
                                    @include('pemeriksaan.diagnosa-row', [
                                        'name' => 'diagnosis_sekunder',
                                        'icd' => true,
                                        'label' => 'Diagnosis Sekunder',
                                        'placeholder' => 'Isi diagnosis sekunder, boleh dikosongkan jika tidak ada',
                                        'selected' => $nilai,
                                        'removable' => ! $loop->first,
                                    ])
                                @endforeach
                            </div>

                            <button type="button" class="btn-tambah" data-add>+ Tambah Diagnosis Sekunder</button>

                            <template data-template>
                                @include('pemeriksaan.diagnosa-row', [
                                    'name' => 'diagnosis_sekunder',
                                        'icd' => true,
                                    'label' => 'Diagnosis Sekunder',
                                    'placeholder' => 'Isi diagnosis sekunder, boleh dikosongkan jika tidak ada',
                                    'removable' => true,
                                ])
                            </template>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field" data-dx-section>
                            <label>Tindakan (opsional)</label>

                            <div class="repeat-list" data-list>
                                @foreach ($tindakanList as $nilai)
                                    @include('pemeriksaan.diagnosa-row', [
                                        'name' => 'tindakan',
                                        'label' => 'Tindakan',
                                        'placeholder' => 'Isi tindakan, boleh dikosongkan jika tidak ada',
                                        'selected' => $nilai,
                                        'removable' => ! $loop->first,
                                    ])
                                @endforeach
                            </div>

                            <button type="button" class="btn-tambah" data-add>+ Tambah Tindakan</button>

                            <template data-template>
                                @include('pemeriksaan.diagnosa-row', [
                                    'name' => 'tindakan',
                                    'label' => 'Tindakan',
                                    'placeholder' => 'Isi tindakan, boleh dikosongkan jika tidak ada',
                                    'removable' => true,
                                ])
                            </template>
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

        <script>
            // Pop-up peringatan custom (bahasa Indonesia) untuk field yang wajib diisi
            document.querySelectorAll('#form-diagnosa [required]').forEach(function (field) {
                field.addEventListener('invalid', function () {
                    field.setCustomValidity('Kolom ini wajib diisi.');
                });
                field.addEventListener('input', function () {
                    field.setCustomValidity('');
                });
            });

            // "+ Tambah" menambah satu kotak isian baru di bawah baris terakhir;
            // tombol "×" pada baris tambahan menghapus baris tersebut.
            (function () {
                var form = document.getElementById('form-diagnosa');

                form.addEventListener('click', function (e) {
                    var section = e.target.closest('[data-dx-section]');
                    if (!section) return;

                    if (e.target.closest('[data-add]')) {
                        var tpl = section.querySelector('[data-template]');
                        var list = section.querySelector('[data-list]');
                        list.appendChild(tpl.content.cloneNode(true));

                        var inputs = list.querySelectorAll('input');
                        inputs[inputs.length - 1].focus();
                    } else if (e.target.closest('[data-remove]')) {
                        e.target.closest('[data-row]').remove();
                    }
                });
            })();
        </script>

    <script src="{{ asset('js/icd10-autocomplete.js') }}"></script>

    </main>

</div>

@endsection
