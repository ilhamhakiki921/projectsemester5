@extends('layouts.app')

@section('title', 'Rekam Medis')

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
        background: #ffffff;
    }

    .content {
        width: 100%;
        padding: 26px 24px;
    }

    /* =================================
       TAB (Berkas Pasien | Koding & Verifikasi)
    ================================== */

    .rm-tabs {
        display: flex;
        align-items: flex-end;
        gap: 34px;

        height: 42px;
        padding: 0 26px;
        margin-bottom: 18px;

        border-radius: 8px;
        background: linear-gradient(90deg, #6e0018 0%, #a3001f 55%, #c4001f 100%);
        box-shadow: 0 2px 6px rgba(110, 0, 24, 0.25);
    }

    .rm-tab {
        position: relative;

        height: 100%;
        padding: 0;

        border: none;
        background: transparent;
        cursor: pointer;

        color: rgba(255, 255, 255, 0.78);

        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
    }

    .rm-tab:hover {
        color: #ffffff;
    }

    .rm-tab.active {
        color: #ffffff;
    }

    .rm-tab.active::after {
        content: '';

        position: absolute;
        left: 0;
        right: 0;
        bottom: 7px;

        height: 2px;
        border-radius: 2px;
        background: #ffffff;
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
    }

    /* =================================
       PENCARIAN
    ================================== */

    .rm-search {
        position: relative;

        width: 290px;
        margin-bottom: 26px;
    }

    .rm-search i {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);

        font-size: 12px;
        color: #666;
        pointer-events: none;
    }

    .rm-search input {
        width: 100%;
        height: 34px;

        padding: 0 12px 0 32px;

        border: 1px solid #9b9b9b;
        border-radius: 8px;
        background: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        color: #222;
    }

    .rm-search input::placeholder {
        color: #8a8a8a;
    }

    /* =================================
       TABEL
    ================================== */

    .rm-heading {
        margin-bottom: 12px;

        font-size: 15px;
        font-weight: 700;
        color: #111;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .rm-table {
        width: 100%;
        min-width: 980px;

        border-collapse: separate;
        border-spacing: 0;
    }

    .rm-table thead th {
        height: 50px;
        padding: 0 12px;

        background: linear-gradient(90deg, #b01c20 0%, #7a0a0a 55%, #4a0000 100%);
        color: #ffffff;

        font-size: 10.5px;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .rm-table thead th:first-child {
        border-top-left-radius: 14px;
        border-bottom-left-radius: 14px;
    }

    .rm-table thead th:last-child {
        border-top-right-radius: 14px;
        border-bottom-right-radius: 14px;
    }

    .rm-table tbody td {
        padding: 18px 12px;

        font-size: 10.5px;
        color: #222;
        text-align: center;

        border-bottom: 1px solid #f0f0f0;
    }

    .rm-table tbody tr:hover td {
        background: #fafafa;
    }

    .rm-table td.kiri,
    .rm-table th.kiri {
        text-align: left;
    }

    .empty-row td {
        padding: 34px 12px !important;

        color: #888 !important;
        font-size: 11px !important;
        text-align: center;
    }

    /* =================================
       BADGE GENERAL CONSENT
    ================================== */

    .consent-badge {
        display: inline-block;

        min-width: 58px;
        padding: 4px 12px;

        border-radius: 5px;

        font-size: 9px;
        font-weight: 600;
        text-align: center;
    }

    .consent-sudah {
        background: #c6e8c0;
        color: #1c5a14;
    }

    .consent-belum {
        background: #e6e8b4;
        color: #6d6a00;
    }

    /* Badge status kunjungan (tab Koding & Verifikasi) */
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 600;
    }

    .status-menunggu { background: #fff4e5; color: #b06a00; }
    .status-sedang-diperiksa { background: #e8f0fe; color: #1967d2; }
    .status-selesai { background: #e6f4ea; color: #1a7f37; }

    /* =================================
       TOMBOL
    ================================== */

    .btn-berkas {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 62px;
        height: 22px;
        padding: 0 10px;

        border: 1px solid #555;
        border-radius: 4px;
        background: #ffffff;

        color: #222;
        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 8.5px;
        font-weight: 500;

        transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
    }

    .btn-berkas:hover {
        background: #a30000;
        border-color: #a30000;
        color: #ffffff;
    }

    .btn-berkas.disabled {
        opacity: 0.45;
        pointer-events: none;
    }

</style>

@endsection


@section('content')

@php
    $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $tglIndo = fn ($tgl) => $tgl->format('j') . ' ' . $bulan[(int) $tgl->format('n')] . ' ' . $tgl->format('Y');
@endphp

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Rekam Medis" icon="house-fill" />

        <section class="content">

            {{-- TAB --}}
            <div class="rm-tabs">
                <button type="button" class="rm-tab active" data-tab="berkas">Berkas Pasien</button>
                <button type="button" class="rm-tab" data-tab="koding">Koding &amp; Verifikasi</button>
            </div>


            {{-- =========================
                 TAB 1: BERKAS PASIEN
            ========================== --}}
            <div class="tab-panel active" id="panel-berkas">

                <div class="rm-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="cariBerkas" placeholder="Cari No.RM, NIK atau nama pasien" autocomplete="off">
                </div>

                <h2 class="rm-heading">Berkas rekam medis</h2>

                <div class="table-wrapper">
                    <table class="rm-table" id="tabelBerkas">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No.RM</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Umur</th>
                                <th>Kunjungan</th>
                                <th>Terakhir Dilayani</th>
                                <th>General Consent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pasienList as $i => $p)
                                @php
                                    $terakhir = $p->kunjungan->first();
                                    $sudahConsent = $p->generalConsent && $p->generalConsent->setuju;
                                @endphp
                                <tr class="baris-berkas"
                                    data-cari="{{ strtolower($p->no_rm . ' ' . $p->nik . ' ' . $p->nama) }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->no_rm }}</td>
                                    <td>{{ $p->nik ?: '-' }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->jenis_kelamin }}</td>
                                    <td>{{ $p->tanggal_lahir ? $p->tanggal_lahir->age . ' th' : '-' }}</td>
                                    <td>{{ $p->kunjungan_count }}</td>
                                    <td>
                                        @if ($terakhir)
                                            {{ $tglIndo($terakhir->tanggal_kunjungan) }} - {{ $namaPoli[$terakhir->poli] ?? 'Poli ' . ucfirst($terakhir->poli) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sudahConsent)
                                            <span class="consent-badge consent-sudah">Sudah</span>
                                        @else
                                            <span class="consent-badge consent-belum">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rekam-medis.berkas', $p->id) }}" class="btn-berkas">Buka Berkas</a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="10">Belum ada berkas rekam medis.</td>
                                </tr>
                            @endforelse

                            <tr class="empty-row" id="berkasKosong" style="display: none;">
                                <td colspan="10">Pasien tidak ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>


            {{-- =========================
                 TAB 2: KODING & VERIFIKASI
            ========================== --}}
            <div class="tab-panel" id="panel-koding">

                <div class="rm-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="cariKoding" placeholder="Cari No.RM, NIK atau nama pasien" autocomplete="off">
                </div>

                <h2 class="rm-heading">Koding &amp; verifikasi diagnosa</h2>

                <div class="table-wrapper">
                    <table class="rm-table" id="tabelKoding">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No.RM</th>
                                <th>Nama</th>
                                <th>Poli</th>
                                <th>Diagnosa Utama</th>
                                <th>Kode ICD-10</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarKoding as $i => $k)
                                <tr class="baris-koding"
                                    data-cari="{{ strtolower($k->pasien->no_rm . ' ' . $k->pasien->nik . ' ' . $k->pasien->nama) }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $k->tanggal_kunjungan->format('d-m-Y') }}</td>
                                    <td>{{ $k->pasien->no_rm }}</td>
                                    <td>{{ $k->pasien->nama }}</td>
                                    <td>{{ $namaPoli[$k->poli] ?? 'Poli ' . ucfirst($k->poli) }}</td>
                                    <td>{{ optional($k->rekamMedis)->diagnosis_utama ?: '-' }}</td>
                                    <td>{{ optional($k->rekamMedis)->kode_icd10 ?: '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($k->status)) }}">
                                            {{ $k->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('rekam-medis.show', $k->id) }}" class="btn-berkas">Buka Berkas</a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="9">Belum ada data kunjungan.</td>
                                </tr>
                            @endforelse

                            <tr class="empty-row" id="kodingKosong" style="display: none;">
                                <td colspan="9">Data tidak ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

    </main>

</div>

<script>
    (function () {
        // ---- Pindah tab ----
        var tabs   = document.querySelectorAll('.rm-tab');
        var panels = document.querySelectorAll('.tab-panel');

        function bukaTab(nama) {
            tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.tab === nama); });
            panels.forEach(function (p) { p.classList.toggle('active', p.id === 'panel-' + nama); });
        }

        tabs.forEach(function (t) {
            t.addEventListener('click', function () { bukaTab(t.dataset.tab); });
        });

        // ---- Pencarian (No.RM, NIK, nama) ----
        function pasangPencarian(inputId, kelasBaris, idKosong) {
            var input = document.getElementById(inputId);
            var baris = document.querySelectorAll('.' + kelasBaris);
            var kosong = document.getElementById(idKosong);

            input.addEventListener('input', function () {
                var kata = input.value.trim().toLowerCase();
                var tampil = 0;

                baris.forEach(function (tr) {
                    var cocok = tr.dataset.cari.indexOf(kata) !== -1;
                    tr.style.display = cocok ? '' : 'none';
                    if (cocok) tampil++;
                });

                if (kosong) {
                    kosong.style.display = (baris.length > 0 && tampil === 0) ? '' : 'none';
                }
            });
        }

        pasangPencarian('cariBerkas', 'baris-berkas', 'berkasKosong');
        pasangPencarian('cariKoding', 'baris-koding', 'kodingKosong');
    })();
</script>

@endsection
