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
    padding: 25px 22px 50px;
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
    color: #b30000;
}

.card {
    background: #ffffff;

    border-radius: 12px;

    padding: 20px 22px;
    margin-bottom: 16px;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

/* IDENTITAS PASIEN */

.identitas-label {
    margin-bottom: 4px;

    font-size: 13px;
    font-weight: 700;

    color: #222;
}

.pasien-nama {
    margin-bottom: 14px;

    font-size: 19px;
    font-weight: 700;
    letter-spacing: 0.3px;

    color: #1a1a1a;
    text-transform: uppercase;
}

.identitas-grid {
    display: flex;
    flex-wrap: wrap;

    gap: 14px;
}

.identitas-item {
    min-width: 140px;
    flex: 1;
}

.identitas-item label {
    display: block;

    margin-bottom: 5px;

    font-size: 10px;
    font-weight: 600;

    color: #666;
}

.identitas-box {
    height: 34px;

    display: flex;
    align-items: center;

    padding: 0 10px;

    border-radius: 6px;

    background: #eceef0;

    font-size: 11px;
    color: #333;
}

/* TABS */

.tab-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;

    margin-bottom: 16px;

    border-bottom: 1px solid #e5e5e5;

    padding-bottom: 0;
}

.tab-btn {
    padding: 9px 16px;

    border: none;
    border-radius: 8px 8px 0 0;

    background: transparent;

    font-family: 'Poppins', sans-serif;
    font-size: 11px;
    font-weight: 500;

    color: #777;

    cursor: pointer;

    transition: 0.2s;
}

.tab-btn:hover {
    color: #b30000;
}

.tab-btn.active {
    color: #b30000;
    font-weight: 700;

    background: #fbe4e4;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.placeholder-box {
    padding: 40px 20px;

    text-align: center;

    border: 1px dashed #d0d3d8;
    border-radius: 10px;

    font-size: 11px;
    color: #8a8f98;
}

/* FORM ASESMEN */

.section-title {
    margin-bottom: 14px;

    font-size: 14px;
    font-weight: 700;

    color: #1a1a1a;
}

.form-group {
    margin-bottom: 16px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;

    gap: 16px;

    margin-bottom: 16px;
}

.form-row .form-group {
    flex: 1;
    min-width: 220px;

    margin-bottom: 0;
}

.form-row-3 {
    display: flex;
    flex-wrap: wrap;

    gap: 16px;

    margin-bottom: 16px;
}

.form-row-3 .form-group {
    flex: 1;
    min-width: 160px;

    margin-bottom: 0;
}

.form-group label {
    display: block;

    margin-bottom: 6px;

    font-size: 11px;
    font-weight: 600;

    color: #333;
}

.form-group label .required-mark {
    color: #b30000;
    margin-left: 2px;
}

.form-group input[readonly] {
    background: #eceded;
    color: #555;
    cursor: not-allowed;
}

.form-group textarea,
.form-group input {
    width: 100%;

    padding: 9px 11px;

    border: 1px solid #d8dade;
    border-radius: 7px;

    outline: none;

    font-family: 'Poppins', sans-serif;
    font-size: 11px;

    color: #222;

    background: #f7f7f8;

    resize: vertical;
}

.form-group textarea {
    min-height: 70px;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #b30000;
    background: #ffffff;
}

.vital-title {
    margin: 6px 0 14px;

    font-size: 13px;
    font-weight: 700;

    color: #1a1a1a;
}

.btn-simpan {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 11px 24px;

    border: none;
    border-radius: 8px;

    background: #16a34a;
    color: #ffffff;

    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;
}

.btn-simpan:hover {
    background: #128a3e;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 5px 11px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 600;
}

.status-badge::before {
    content: "";

    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}

.status-menunggu {
    background: #fdecd2;
    color: #b3691a;
}

.status-diperiksa {
    background: #dbe9fe;
    color: #1d5fd6;
}

.status-selesai {
    background: #d7f2e3;
    color: #158a4c;
}

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">

        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            {{-- IDENTITAS PASIEN --}}
            <div class="card">

                <div class="identitas-label">
                    Identitas Pasien
                </div>

                <div class="pasien-nama">
                    {{ $antrian->pasien->nama }}
                </div>

                <div class="identitas-grid">

                    <div class="identitas-item">
                        <label>No. RM</label>
                        <div class="identitas-box">{{ $antrian->pasien->no_rm }}</div>
                    </div>

                    <div class="identitas-item">
                        <label>NIK</label>
                        <div class="identitas-box">{{ $antrian->pasien->nik }}</div>
                    </div>

                    <div class="identitas-item">
                        <label>Jenis Kelamin</label>
                        <div class="identitas-box">{{ $antrian->pasien->jenis_kelamin }}</div>
                    </div>

                    <div class="identitas-item">
                        <label>Tanggal Lahir</label>
                        <div class="identitas-box">
                            {{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->translatedFormat('d M Y') }}
                        </div>
                    </div>

                    <div class="identitas-item">
                        <label>Usia</label>
                        <div class="identitas-box">
                            {{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} th
                        </div>
                    </div>

                    <div class="identitas-item">
                        <label>Status</label>
                        <div>

                            @if ($antrian->status === 'Menunggu')
                                <span class="status-badge status-menunggu">Menunggu</span>
                            @elseif ($antrian->status === 'Sedang Diperiksa')
                                <span class="status-badge status-diperiksa">Sedang Diperiksa</span>
                            @else
                                <span class="status-badge status-selesai">Selesai</span>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

            {{-- TAB NAVIGASI --}}
            <div class="card">

                <div class="tab-nav">
                    <button type="button" class="tab-btn active" onclick="bukaTab('assesment', this)">Assesment</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('soap', this)">SOAP</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('diagnosa', this)">Diagnosa</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('resep', this)">Resep</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('clinical', this)">Clinical Pathway</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('penunjang', this)">Penunjang</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('rujukan', this)">Rujukan</button>
                    <button type="button" class="tab-btn" onclick="bukaTab('autentikasi', this)">Autentikasi</button>
                </div>

                {{-- TAB: ASSESMENT --}}
                <div id="tab-assesment" class="tab-panel active">

                    <form action="{{ route('pemeriksaan.simpanAsesmen', $antrian->id) }}" method="POST">
                        @csrf

                        <div class="section-title">Asesmen Pemeriksaan</div>

                        <div class="form-group">
                            <label>Keluhan Utama<span class="required-mark">*</span></label>
                            <textarea name="keluhan_utama" placeholder="Keluhan utama yang dirasakan pasien" required>{{ old('keluhan_utama', $rekamMedis->keluhan_utama ?? '') }}</textarea>
                        </div>

                        <div class="form-row">

                            <div class="form-group">
                                <label>Riwayat Penyakit Sekarang<span class="required-mark">*</span></label>
                                <textarea name="riwayat_penyakit_sekarang" placeholder="Uraian riwayat penyakit yang sedang dialami" required>{{ old('riwayat_penyakit_sekarang', $rekamMedis->riwayat_penyakit_sekarang ?? '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Riwayat Penyakit Terdahulu<span class="required-mark">*</span></label>
                                <textarea name="riwayat_penyakit_terdahulu" placeholder="Riwayat penyakit terdahulu, tulis 'Tidak ada' jika tidak ada" required>{{ old('riwayat_penyakit_terdahulu', $rekamMedis->riwayat_penyakit_terdahulu ?? '') }}</textarea>
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group">
                                <label>Riwayat Alergi<span class="required-mark">*</span></label>
                                <input type="text" name="riwayat_alergi" value="{{ old('riwayat_alergi', $rekamMedis->riwayat_alergi ?? '') }}" placeholder="Riwayat alergi pasien, tulis 'Tidak ada' jika tidak ada" required>
                            </div>

                            <div class="form-group">
                                <label>Riwayat Pengobatan<span class="required-mark">*</span></label>
                                <input type="text" name="riwayat_pengobatan" value="{{ old('riwayat_pengobatan', $rekamMedis->riwayat_pengobatan ?? '') }}" placeholder="Riwayat pengobatan pasien, tulis 'Tidak ada' jika tidak ada" required>
                            </div>

                        </div>

                        <div class="vital-title">Tanda Vital</div>

                        <div class="form-row-3">

                            <div class="form-group">
                                <label>TB (Tinggi Badan)<span class="required-mark">*</span></label>
                                <input type="number" id="tb" name="tb" min="1" max="300" step="1" value="{{ old('tb', optional($rekam)->tb) }}" placeholder="Tinggi badan dalam cm" required>
                            </div>

                            <div class="form-group">
                                <label>BB (Berat Badan)<span class="required-mark">*</span></label>
                                <input type="text" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $rekamMedis->berat_badan ?? '') }}" placeholder="Berat badan dalam kg" required>
                            </div>

                            <div class="form-group">
                                <label>IMT</label>
                                <input type="text" id="imt" name="imt" value="{{ old('imt', $rekamMedis->imt ?? '') }}" placeholder="Terisi otomatis" readonly>
                            </div>

                        </div>

                        <div class="form-row-3">

                            <div class="form-group">
                                <label>Suhu Badan<span class="required-mark">*</span></label>
                                <input type="number" min="1" max="50" step="1" name="suhu_badan" value="{{ old('suhu_badan', $rekamMedis->suhu_badan ?? '') }}" placeholder="Suhu tubuh dalam °C" required>
                            </div>

                                                        <div class="form-group">
                                <label>TD (Tekanan Darah)<span class="required-mark">*</span></label>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <input type="number" name="sistole" min="1" max="300" step="1" style="flex:1;min-width:0" value="{{ old('sistole', $rekamMedis->sistole ?? '') }}" placeholder="Sistole" required>
                                    <span>/</span>
                                    <input type="number" name="diastole" min="1" max="200" step="1" style="flex:1;min-width:0" value="{{ old('diastole', $rekamMedis->diastole ?? '') }}" placeholder="Diastole" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Denyut Nadi<span class="required-mark">*</span></label>
                                <input type="text" name="detak_jantung" value="{{ old('detak_jantung', $rekamMedis->detak_jantung ?? '') }}" placeholder="Denyut nadi dalam x/menit" required>
                            </div>

                        </div>

                        <div class="form-row-3">

                            <div class="form-group">
                                <label>Respiratory Rate<span class="required-mark">*</span></label>
                                <input type="text" name="respiratory_rate" value="{{ old('respiratory_rate', $rekamMedis->respiratory_rate ?? '') }}" placeholder="Laju napas dalam x/menit" required>
                            </div>

                            <div class="form-group">
                                <label>Saturasi<span class="required-mark">*</span></label>
                                <input type="text" name="saturasi" value="{{ old('saturasi', $rekamMedis->saturasi ?? '') }}" placeholder="Saturasi oksigen dalam %" required>
                            </div>

                            <div class="form-group">
                                <label>GCS<span class="required-mark">*</span></label>
                                <input type="number" min="3" max="15" step="1" name="gcs" value="{{ old('gcs', $rekamMedis->gcs ?? '') }}" placeholder="Total skor GCS (3-15)" required>
                            </div>

                        </div>

                        <button type="submit" class="btn-simpan">
                            <i class="bi bi-save2"></i>
                            Simpan Asesmen
                        </button>

                    </form>

                </div>

                {{-- TAB LAIN (BELUM DIBUAT) --}}
                @foreach (['soap' => 'SOAP', 'diagnosa' => 'Diagnosa', 'resep' => 'Resep', 'clinical' => 'Clinical Pathway', 'penunjang' => 'Penunjang', 'rujukan' => 'Rujukan', 'autentikasi' => 'Autentikasi'] as $kode => $label)

                    <div id="tab-{{ $kode }}" class="tab-panel">
                        <div class="placeholder-box">
                            Form {{ $label }} akan dibuat di tahap selanjutnya.
                        </div>
                    </div>

                @endforeach

            </div>

        </section>

    </main>

</div>

<script>

function bukaTab(nama, tombol) {

    document.querySelectorAll('.tab-panel').forEach(function (panel) {
        panel.classList.remove('active');
    });

    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.classList.remove('active');
    });

    document.getElementById('tab-' + nama).classList.add('active');
    tombol.classList.add('active');

}

// Hitung IMT otomatis: BB (kg) / (TB (m))^2, TB diinput dalam cm
(function () {
    var tbInput = document.getElementById('tb');
    var bbInput = document.getElementById('berat_badan');
    var imtInput = document.getElementById('imt');

    if (!tbInput || !bbInput || !imtInput) {
        return;
    }

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
})();

</script>

@endsection
