@extends('layouts.app')

@section('title', 'Asesmen Pemeriksaan — ' . $pasien->nama)

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

    .field-row.cols-3 {
        grid-template-columns: 1fr 1fr 1fr;
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

    .field input[readonly] {
        background: #f5f5f5;
        color: #555;
        cursor: not-allowed;
    }

    .field input:focus,
    .field textarea:focus {
        border-color: #b40000;
        box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
    }

    .section-subtitle {
        margin: 6px 0 12px;

        font-size: 11px;
        font-weight: 700;
        color: #222;
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
                <span class="tab-item active">Anamnesa</span>
                <a href="{{ route('pemeriksaan.soap', [$poli, $pasien->no_rm]) }}" class="tab-item">SOAP</a>
                <span class="tab-item disabled">Diagnosa</span>
                <a href="{{ route('pemeriksaan.resep', [$poli, $pasien->no_rm]) }}" class="tab-item">Resep</a>
                <a href="{{ route('pemeriksaan.clinical-pathway', [$poli, $pasien->no_rm]) }}" class="tab-item">Clinical Pathway</a>
                <span class="tab-item disabled">Penunjang</span>
                <span class="tab-item disabled">Rujukan</span>
                <span class="tab-item disabled">Autentikasi</span>
            </div>

            @if ($errors->any())
                <div style="margin-bottom: 16px; padding: 10px 14px; background: #fdecea; color: #b3261e; border-radius: 6px; font-size: 11px; font-weight: 500;">
                    Semua kolom asesmen wajib diisi. Silakan lengkapi dulu sebelum menyimpan.
                </div>
            @endif


            {{-- FORM ASESMEN --}}
            <div class="form-card">

                <h2 class="form-card-title">Asesmen Pemeriksaan</h2>

                <form method="POST" action="{{ route('pemeriksaan.asesmen.store', [$poli, $pasien->no_rm]) }}" id="form-asesmen">
                    @csrf

                    <div class="field-group">
                        <div class="field">
                            <label>Keluhan Utama<span class="required-mark">*</span></label>
                            <input type="text" name="keluhan_utama" value="{{ old('keluhan_utama', optional($rekam)->keluhan_utama) }}" placeholder="Keluhan utama yang dirasakan pasien" required>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label>Riwayat Penyakit Sekarang<span class="required-mark">*</span></label>
                            <input type="text" name="riwayat_penyakit_sekarang" value="{{ old('riwayat_penyakit_sekarang', optional($rekam)->riwayat_penyakit_sekarang) }}" placeholder="Uraian riwayat penyakit yang sedang dialami" required>
                        </div>
                        <div class="field">
                            <label>Riwayat Penyakit Terdahulu<span class="required-mark">*</span></label>
                            <input type="text" name="riwayat_penyakit_terdahulu" value="{{ old('riwayat_penyakit_terdahulu', optional($rekam)->riwayat_penyakit_terdahulu) }}" placeholder="Riwayat penyakit terdahulu, tulis 'Tidak ada' jika tidak ada" required>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label>Riwayat Alergi<span class="required-mark">*</span></label>
                            <input type="text" name="riwayat_alergi" value="{{ old('riwayat_alergi', optional($rekam)->riwayat_alergi) }}" placeholder="Riwayat alergi pasien, tulis 'Tidak ada' jika tidak ada" required>
                        </div>
                        <div class="field">
                            <label>Riwayat Pengobatan<span class="required-mark">*</span></label>
                            <input type="text" name="riwayat_pengobatan" value="{{ old('riwayat_pengobatan', optional($rekam)->riwayat_pengobatan) }}" placeholder="Riwayat pengobatan pasien, tulis 'Tidak ada' jika tidak ada" required>
                        </div>
                    </div>

                    <p class="section-subtitle">Tanda Vital</p>

                    <div class="field-row cols-3">
                        <div class="field">
                            <label>TB (Tinggi Badan)<span class="required-mark">*</span></label>
                            <input type="text" id="tb" name="tb" value="{{ old('tb', optional($rekam)->tb) }}" placeholder="Tinggi badan dalam cm" required>
                        </div>
                        <div class="field">
                            <label>BB (Berat Badan)<span class="required-mark">*</span></label>
                            <input type="text" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', optional($rekam)->berat_badan) }}" placeholder="Berat badan dalam kg" required>
                        </div>
                        <div class="field">
                            <label>IMT</label>
                            <input type="text" id="imt" name="imt" value="{{ old('imt', optional($rekam)->imt) }}" placeholder="Terisi otomatis" readonly>
                        </div>
                    </div>

                    <div class="field-row cols-3">
                        <div class="field">
                            <label>Suhu Badan<span class="required-mark">*</span></label>
                            <input type="text" name="suhu_badan" value="{{ old('suhu_badan', optional($rekam)->suhu_badan) }}" placeholder="Suhu tubuh dalam °C" required>
                        </div>
                        <div class="field">
                            <label>TD (Tekanan Darah)<span class="required-mark">*</span></label>
                            <input type="text" name="td" value="{{ old('td', optional($rekam)->td) }}" placeholder="Tekanan darah, format 120/80" required>
                        </div>
                        <div class="field">
                            <label>Denyut Nadi<span class="required-mark">*</span></label>
                            <input type="text" name="detak_jantung" value="{{ old('detak_jantung', optional($rekam)->detak_jantung) }}" placeholder="Denyut nadi dalam x/menit" required>
                        </div>
                    </div>

                    <div class="field-row cols-3">
                        <div class="field">
                            <label>Respiratory Rate</label>
                            <input type="text" name="respiratory_rate" value="{{ old('respiratory_rate', optional($rekam)->respiratory_rate) }}" placeholder="Laju napas dalam x/menit">
                        </div>
                        <div class="field">
                            <label>Saturasi</label>
                            <input type="text" name="saturasi" value="{{ old('saturasi', optional($rekam)->saturasi) }}" placeholder="Saturasi oksigen dalam %">
                        </div>
                        <div class="field">
                            <label>GCS</label>
                            <input type="text" name="gcs" value="{{ old('gcs', optional($rekam)->gcs) }}" placeholder="Nilai GCS, format E4V5M6">
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
            document.querySelectorAll('#form-asesmen [required]').forEach(function (field) {
                field.addEventListener('invalid', function () {
                    field.setCustomValidity('Kolom ini wajib diisi.');
                });
                field.addEventListener('input', function () {
                    field.setCustomValidity('');
                });
            });

            // Hitung IMT otomatis: BB (kg) / (TB (m))^2, TB diinput dalam cm
            (function () {
                var tbInput = document.getElementById('tb');
                var bbInput = document.getElementById('berat_badan');
                var imtInput = document.getElementById('imt');

                function hitungImt() {
                    var tbCm = parseFloat(tbInput.value);
                    var bbKg = parseFloat(bbInput.value);

                    if (!tbCm || !bbKg || tbCm <= 0) {
                        imtInput.value = '';
                        return;
                    }

                    var tbM = tbCm / 100;
                    var imt = bbKg / (tbM * tbM);

                    imtInput.value = imt.toFixed(2);
                }

                tbInput.addEventListener('input', hitungImt);
                bbInput.addEventListener('input', hitungImt);

                // Hitung saat halaman dimuat jika sudah ada data sebelumnya
                hitungImt();
            })();

            // Tekan Enter -> pindah otomatis ke kolom berikutnya, tidak perlu arahkan kursor manual
            (function () {
                var form = document.getElementById('form-asesmen');
                var fields = Array.prototype.slice.call(
                    form.querySelectorAll('input:not([type="hidden"]):not([readonly]), textarea')
                );

                fields.forEach(function (field, index) {
                    field.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' && ! e.shiftKey) {
                            e.preventDefault();

                            var next = fields[index + 1];
                            if (next) {
                                next.focus();
                            } else {
                                var tombolSimpan = form.querySelector('button[type="submit"]');
                                if (tombolSimpan) tombolSimpan.focus();
                            }
                        }
                    });
                });
            })();
        </script>

    </main>

</div>

@endsection
