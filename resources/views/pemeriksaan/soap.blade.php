@extends('layouts.app')

@section('title', 'SOAP — ' . $pasien->nama)

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

        border-radius: 20px;

        font-size: 10px;
        font-weight: 600;

        text-decoration: none;

        color: #1a1a1a;
        background: transparent;
    }

    .tab-item[hidden] { display: none; }

    /* Pop-up Informed Consent (muncul saat Simpan SOAP diklik) */
    .ic-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: none; align-items: center; justify-content: center;
        background: rgba(0, 0, 0, .55); padding: 16px;
    }
    .ic-overlay.show { display: flex; }
    .ic-modal {
        width: 100%; max-width: 420px; background: #fff; border-radius: 14px;
        padding: 28px 26px 22px; text-align: center;
        box-shadow: 0 12px 40px rgba(0, 0, 0, .35);
        animation: icMasuk .18s ease-out;
    }
    @keyframes icMasuk { from { opacity: 0; transform: translateY(8px) scale(.97); } to { opacity: 1; transform: none; } }
    .ic-modal h3 { margin: 0 0 8px; font-size: 17px; font-weight: 700; color: #c00000; }
    .ic-modal p { margin: 0 0 22px; font-size: 13px; color: #222; line-height: 1.5; }
    .ic-modal .ic-aksi { display: flex; gap: 12px; justify-content: center; }
    .ic-modal button {
        min-width: 130px; height: 40px; padding: 0 20px; border-radius: 8px;
        font: inherit; font-size: 13px; font-weight: 700; cursor: pointer;
    }
    .ic-modal .ic-ya { border: 0; background: #c00000; color: #fff; }
    .ic-modal .ic-ya:hover { background: #a00000; }
    .ic-modal .ic-tidak { border: 1px solid #bdbdbd; background: #fff; color: #222; }
    .ic-modal .ic-tidak:hover { background: #f1f1f1; }
    .ic-modal button:focus-visible { outline: 2px solid #c00000; outline-offset: 2px; }
    .ic-modal button:disabled { opacity: .6; cursor: wait; }

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
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 18px;

        font-size: 15px;
        font-weight: 700;
        color: #ed1111;
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

    .field-group {
        margin-bottom: 16px;
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
        border-color: #b40000;
        box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
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

            <x-popup-alert />

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

            <div class="tabs">
                <a href="{{ route('pemeriksaan.asesmen', [$poli, $pasien->no_rm]) }}" class="tab-item">Anamnesa</a>
                <span class="tab-item active">SOAP</span>
                {{-- Tab Informed Consent tidak ditampilkan di sini: baru muncul di halaman Informed Consent setelah memilih "Ya" di pop-up Simpan SOAP --}}
                <a href="{{ route('pemeriksaan.diagnosa', [$poli, $pasien->no_rm]) }}" class="tab-item">Diagnosa</a>
                <a href="{{ route('pemeriksaan.resep', [$poli, $pasien->no_rm]) }}" class="tab-item">Resep</a>
                <a href="{{ route('pemeriksaan.clinical-pathway', [$poli, $pasien->no_rm]) }}" class="tab-item">Clinical Pathway</a>
                <a href="{{ route('penunjang.index', [$poli, $pasien->no_rm]) }}" class="tab-item">Penunjang</a>
                <a href="{{ route('rujukan.index', [$poli, $pasien->no_rm]) }}" class="tab-item">Rujukan</a>
                <a href="{{ route('surat-medis.index', [$poli, $pasien->no_rm]) }}" class="tab-item">Surat Medis</a>
                <a href="{{ route('autentikasi.index', [$poli, $pasien->no_rm]) }}" class="tab-item">Autentikasi</a>
            </div>

            @if ($errors->any())
                <div style="margin-bottom: 16px; padding: 10px 14px; background: #fdecea; color: #b3261e; border-radius: 6px; font-size: 11px; font-weight: 500;">
                    Semua kolom SOAP wajib diisi. Silakan lengkapi dulu sebelum menyimpan.
                </div>
            @endif

            {{-- FORM SOAP --}}
            <div class="form-card">

                <h2 class="form-card-title">
                    SOAP
                </h2>

                <form method="POST" action="{{ route('pemeriksaan.soap.store', [$poli, $pasien->no_rm]) }}" id="form-soap">
                    @csrf

                    <div class="field-group">
                        <div class="field">
                            <label>S (subjek)<span class="required-mark">*</span></label>
                            <textarea name="subjective" rows="3" placeholder="Uraian keluhan yang disampaikan pasien" required>{{ old('subjective', optional($rekam)->subjective) }}</textarea>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label>O (object)<span class="required-mark">*</span></label>
                            <textarea name="objective" rows="3" placeholder="Hasil pemeriksaan fisik dan tanda vital" required>{{ old('objective', optional($rekam)->objective) }}</textarea>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label>A (assesmen)<span class="required-mark">*</span></label>
                            <textarea name="assessment" rows="3" placeholder="Diagnosis atau penilaian kondisi pasien" required>{{ old('assessment', optional($rekam)->assessment) }}</textarea>
                        </div>
                    </div>

                    <div class="field-group">
                        <div class="field">
                            <label>P (plan)<span class="required-mark">*</span></label>
                            <textarea name="plan" rows="3" placeholder="Rencana terapi dan tindak lanjut" required>{{ old('plan', optional($rekam)->plan) }}</textarea>
                        </div>
                    </div>

                    @if (\App\Models\RekamMedis::poliPunyaInformedConsent($poli))
                    {{-- Diisi lewat pop-up "Apakah pasien membutuhkan Informed Consent?" saat Simpan SOAP diklik --}}
                    <input type="hidden" name="butuh_informed_consent" id="inputButuhIc" value="tidak">
                    @endif

                    <div class="form-footer">
                        <button type="submit" class="btn-simpan">
                            Simpan SOAP
                        </button>
                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

@if (\App\Models\RekamMedis::poliPunyaInformedConsent($poli))
<div class="ic-overlay" id="icOverlay" role="dialog" aria-modal="true" aria-labelledby="icJudul">
    <div class="ic-modal">
        <h3 id="icJudul">Informed Consent</h3>
        <p>Apakah pasien membutuhkan Informed Consent?</p>
        <div class="ic-aksi">
            <button type="button" class="ic-tidak" id="icTidak">Tidak</button>
            <button type="button" class="ic-ya" id="icYa">Ya</button>
        </div>
    </div>
</div>
@endif

<script>
    document.querySelectorAll('#form-soap [required]').forEach(function (field) {
        field.addEventListener('invalid', function () {
            field.setCustomValidity('Kolom ini wajib diisi.');
        });
        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });
    });

    // Simpan SOAP -> pop-up "butuh Informed Consent?"
    //   Tidak -> pop-up tertutup, SOAP disimpan, lanjut ke Diagnosa
    //   Ya    -> SOAP disimpan, lanjut ke halaman Informed Consent
    (function () {
        var form    = document.getElementById('form-soap');
        var overlay = document.getElementById('icOverlay');
        var input   = document.getElementById('inputButuhIc');
        if (! overlay || ! input) return;          // poli ini tidak punya Informed Consent -> simpan langsung

        var btnYa = document.getElementById('icYa'), btnTidak = document.getElementById('icTidak');
        var terkirim = false;

        function buka()  { overlay.classList.add('show'); btnTidak.focus(); }
        function tutup() { overlay.classList.remove('show'); }

        function pilih(nilai) {
            if (terkirim) return;
            terkirim = true;
            input.value = nilai;
            btnYa.disabled = btnTidak.disabled = true;
            tutup();
            form.submit();
        }

        // Validasi kolom wajib tetap jalan dulu; pop-up baru muncul kalau semua sudah terisi
        form.addEventListener('submit', function (e) {
            if (terkirim) return;
            e.preventDefault();
            buka();
        });

        btnYa.addEventListener('click', function () { pilih('ya'); });
        btnTidak.addEventListener('click', function () { pilih('tidak'); });

        // Klik di luar kotak / tekan Esc = batal (kembali ke form, SOAP belum disimpan)
        overlay.addEventListener('click', function (e) { if (e.target === overlay) tutup(); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('show')) tutup();
        });
    })();

    // Tekan Enter -> pindah ke kolom berikutnya (Shift+Enter tetap bisa bikin baris baru di textarea)
    (function () {
        var form = document.getElementById('form-soap');
        var fields = Array.prototype.slice.call(form.querySelectorAll('textarea'));

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

@endsection
