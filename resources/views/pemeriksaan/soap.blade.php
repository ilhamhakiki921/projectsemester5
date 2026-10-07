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

    /* Pilihan Informed Consent */
    .field .ic-tanya { margin: 0 0 8px; font-size: 11px; color: #333; }
    .field .ic-pilihan { display: flex; gap: 10px; }
    .field label.ic-opt { display: inline-block; margin: 0; cursor: pointer; }
    .ic-opt input { position: absolute; opacity: 0; pointer-events: none; }
    .ic-opt span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 72px; height: 34px; padding: 0 18px;
        border: 1px solid #bdbdbd; border-radius: 6px; background: #fff;
        font-size: 11px; font-weight: 600; color: #222;
    }
    .ic-opt input:checked + span { border-color: #c00000; background: #fdecec; color: #c00000; box-shadow: 0 0 0 1px #c00000; }
    .ic-opt input:focus-visible + span { outline: 2px solid #c00000; outline-offset: 2px; }
    .field .ic-catatan { display: block; margin-top: 8px; font-size: 9.5px; color: #777; }

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
                @if (\App\Models\RekamMedis::poliPunyaInformedConsent($poli))
                @php
                    // Pilihan terakhir (Ya/Tidak) dipertahankan saat halaman SOAP dibuka lagi
                    $icYa = old('butuh_informed_consent') !== null
                        ? old('butuh_informed_consent') === 'ya'
                        : \App\Models\RekamMedis::butuhInformedConsent($poli, $pasien->no_rm);
                @endphp
                <a href="{{ route('pemeriksaan.informed-consent', [$poli, $pasien->no_rm]) }}" class="tab-item" id="tabInformedConsent"
                   @unless ($icYa) hidden @endunless>Informed Consent</a>
                @endif
                <a href="{{ route('pemeriksaan.diagnosa', [$poli, $pasien->no_rm]) }}" class="tab-item">Diagnosa</a>
                <a href="{{ route('pemeriksaan.resep', [$poli, $pasien->no_rm]) }}" class="tab-item">Resep</a>
                <a href="{{ route('pemeriksaan.clinical-pathway', [$poli, $pasien->no_rm]) }}" class="tab-item">Clinical Pathway</a>
                <a href="{{ route('penunjang.index', [$poli, $pasien->no_rm]) }}" class="tab-item">Penunjang</a>
                <a href="{{ route('rujukan.index', [$poli, $pasien->no_rm]) }}" class="tab-item">Rujukan</a>
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
                    @php
                        $icAwal = $icYa ? 'ya' : 'tidak';
                    @endphp
                    <div class="field-group">
                        <div class="field">
                            <label>Informed Consent</label>
                            <p class="ic-tanya">Apakah pasien membutuhkan Informed Consent?</p>
                            <div class="ic-pilihan">
                                <label class="ic-opt">
                                    <input type="radio" name="butuh_informed_consent" value="ya" @checked($icAwal === 'ya')>
                                    <span>Ya</span>
                                </label>
                                <label class="ic-opt">
                                    <input type="radio" name="butuh_informed_consent" value="tidak" @checked($icAwal !== 'ya')>
                                    <span>Tidak</span>
                                </label>
                            </div>
                            <small class="ic-catatan">Pilih "Ya" untuk menampilkan tab Informed Consent di antara SOAP dan Diagnosa.</small>
                        </div>
                    </div>
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

<script>
    document.querySelectorAll('#form-soap [required]').forEach(function (field) {
        field.addEventListener('invalid', function () {
            field.setCustomValidity('Kolom ini wajib diisi.');
        });
        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });
    });

    // Pilih "Ya" -> tab Informed Consent langsung muncul (tersimpan permanen setelah Simpan SOAP)
    document.querySelectorAll('input[name="butuh_informed_consent"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            var tab = document.getElementById('tabInformedConsent');
            if (! tab) return;

            var tampil = (this.value === 'ya');
            tab.hidden = ! tampil;

            // Simpan pilihan langsung ke database supaya tab bisa diklik sebelum Simpan SOAP
            fetch(@json(route('pemeriksaan.informed-consent.status', [$poli, $pasien->no_rm])), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('#form-soap input[name="_token"]').value
                },
                body: JSON.stringify({ butuh_informed_consent: this.value })
            }).catch(function () {});

            // Klik "Ya" -> gulir ke atas supaya tab Informed Consent yang baru muncul terlihat
            if (tampil) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                [document.documentElement, document.body, document.querySelector('.main')].forEach(function (el) {
                    if (el && el.scrollTo) el.scrollTo({ top: 0, behavior: 'smooth' });
                });

                // Sorot sebentar supaya jelas tab Informed Consent baru muncul
                tab.animate(
                    [{ boxShadow: '0 0 0 0 rgba(180,0,0,.55)' }, { boxShadow: '0 0 0 10px rgba(180,0,0,0)' }],
                    { duration: 900, iterations: 2 }
                );
            }
        });
    });

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
