@extends('layouts.app')

@section('title', 'Dashboard')

@section('style')

<style>

    /* =========================
       RESET
    ========================== */

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }


    /* =========================
       BODY
    ========================== */

    body {
        font-family: 'Poppins', sans-serif;
        background: #f3f4f6;
        color: #222;
    }


    /* =========================
       MAIN CONTENT
    ========================== */

    .main {
        width: calc(100% - 230px);
        min-height: 100vh;
        margin-left: 230px;
        background: #f3f4f6;
    }


    /* =========================
       DASHBOARD HEADER
    ========================== */

    .dashboard-header {
        width: 100%;
        height: 68px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 0 28px;

        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
    }


    /* Judul Dashboard */

    .header-title {
        display: flex;
        align-items: center;
        gap: 11px;

        color: #c90000;
        font-size: 15px;
        font-weight: 600;
    }

    .header-title i {
        font-size: 20px;
    }


    /* Profil Admin */

    .admin-profile {
        display: flex;
        align-items: center;
        gap: 8px;

        color: #c90000;
        font-size: 13px;
        font-weight: 600;
    }

    .admin-profile i {
        font-size: 22px;
    }


    /* =========================
       DASHBOARD CONTENT
    ========================== */

    .dashboard-content {
        width: 100%;
        padding: 25px 28px 30px;
    }

    /* =========================
   STATISTIC CARDS
========================== */

.stat-grid {
    display: grid;
    /* 5 kartu sejajar rapi dalam satu baris di layar desktop */
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    width: 100%;
    margin-bottom: 24px;
}

.stat-card {
    position: relative;
    min-height: 126px;
    padding: 18px 18px;

    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;

    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.035);

    transition: transform 0.2s ease,
                box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 14px rgba(0, 0, 0, 0.07);
}


/* Judul */

.stat-title {
    font-size: 12px;
    line-height: 1.35;
    font-weight: 600;
    color: #777;
    margin-bottom: 5px;

    /* sisakan ruang untuk ikon di pojok kanan, dan samakan tinggi
       judul (2 baris) supaya angka di semua kartu sejajar */
    padding-right: 44px;
    min-height: 32px;
}


/* Angka */

.stat-number {
    font-size: 32px;
    line-height: 1.1;
    font-weight: 700;
    color: #c90000;
    margin-bottom: 5px;
}


/* Keterangan */

.stat-description {
    font-size: 11px;
    line-height: 1.45;
    color: #999;
    font-weight: 500;
}


/* Ikon */

.stat-icon {
    position: absolute;
    top: 18px;
    right: 18px;

    width: 36px;
    height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #fff1f1;
    color: #c90000;

    font-size: 17px;
}


/* Responsive */

/* Layar lebar tapi belum cukup untuk 5 kolom: 3 kolom (baris terakhir
   berisi 2 kartu, tetap rapi tanpa ruang kosong yang aneh) */
@media (max-width: 1300px) {

    .stat-grid {
        grid-template-columns: repeat(3, 1fr);
    }

}

/* Tablet: 2 kolom. Kartu terakhir (ke-5) melebar penuh 1 baris supaya
   tidak menyisakan kolom kosong di sampingnya */
@media (max-width: 820px) {

    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .stat-card:last-child {
        grid-column: 1 / -1;
    }

}

/* HP: 1 kolom, semua kartu bertumpuk rapi ke bawah */
@media (max-width: 480px) {

    .stat-grid {
        grid-template-columns: 1fr;
    }

    .stat-card:last-child {
        grid-column: auto;
    }

}

/* =========================
   MIDDLE DASHBOARD
========================== */

.middle-grid {
    display: grid;
    grid-template-columns: 1.55fr 0.95fr;
    gap: 20px;
    margin-bottom: 24px;
}

.dashboard-box {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.035);
}

.box-header {
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 15px;

    border-bottom: 1px solid #eeeeee;
}

.box-title {
    font-size: 13px;
    font-weight: 600;
    color: #222;
}

.box-filter {
    padding: 6px 13px;

    background: #f1f1f1;
    border-radius: 20px;

    font-size: 10px;
    font-weight: 500;
    color: #555;
}

.chart-area {
    height: 250px;
    padding: 15px;
}

/* Filter kalender kunjungan per poli */

.box-filter-date {
    display: flex;
    align-items: center;
    gap: 6px;

    padding: 5px 12px;

    background: #f1f1f1;
    border-radius: 20px;
}

.box-filter-date i {
    font-size: 11px;
    color: #c90000;
}

.box-filter-date input[type="date"] {
    border: none;
    background: transparent;
    outline: none;

    font-family: 'Poppins', sans-serif;
    font-size: 10px;
    font-weight: 500;
    color: #555;

    cursor: pointer;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 100%;
}


/* =========================
   AKTIVITAS TERBARU
========================== */

.activity-area {
    height: 250px;
    padding: 8px 15px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;

    padding: 12px 0;

    border-bottom: 1px solid #f0f0f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    flex-shrink: 0;

    width: 32px;
    height: 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #fff1f1;
    color: #c90000;

    font-size: 14px;
}

.activity-content {
    flex: 1;
}

.activity-text {
    font-size: 11px;
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
}

.activity-name {
    font-size: 10px;
    color: #888;
}

.activity-time {
    font-size: 9px;
    color: #aaa;
}

/* =========================
   10 BESAR PENYAKIT
========================= */

.disease-box {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.035);
}

.disease-header {
    min-height: 58px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 15px;
    border-bottom: 1px solid #eeeeee;
}

.disease-title {
    font-size: 13px;
    font-weight: 600;
    color: #222;
}

.disease-filter {
    display: flex;
    align-items: center;
    gap: 7px;
}

.disease-filter label {
    font-size: 10px;
    color: #777;
    font-weight: 500;
}

.disease-filter select {
    height: 30px;
    padding: 0 28px 0 10px;
    border: 1px solid #dddddd;
    border-radius: 6px;
    background: #ffffff;
    color: #555;
    font-family: 'Poppins', sans-serif;
    font-size: 10px;
    outline: none;
    cursor: pointer;
}

.disease-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.disease-table {
    width: 100%;
    border-collapse: collapse;
}

.disease-table th {
    padding: 11px 15px;
    background: #fafafa;
    border-bottom: 1px solid #eeeeee;
    color: #777;
    font-size: 10px;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
}

.disease-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    color: #444;
    font-size: 10px;
    font-weight: 500;
}

.disease-table tbody tr:last-child td {
    border-bottom: none;
}

.disease-table tbody tr:hover {
    background: #fffafa;
}

.disease-code {
    color: #c90000 !important;
    font-weight: 600 !important;
}

.disease-count {
    font-weight: 600 !important;
    color: #333 !important;
}

.disease-rank {
    width: 45px;
    color: #999 !important;
    font-weight: 600 !important;
}


/* =========================
   RESPONSIVE
========================== */

@media (max-width: 1100px) {

    .middle-grid {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 600px) {

    .middle-grid {
        grid-template-columns: 1fr;
    }

}

    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 850px) {

        .main {
            width: calc(100% - 200px);
            margin-left: 200px;
        }

        .dashboard-content {
            padding: 20px;
        }

    }


    @media (max-width: 600px) {

        .sidebar {
            display: none;
        }

        .main {
            width: 100%;
            margin-left: 0;
        }

        .dashboard-header {
            padding: 0 18px;
        }

        .dashboard-content {
            padding: 18px;
        }

    }
    /* =========================
   TOPBAR
========================= */

.topbar {
    width: 100%;
    height: 68px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 28px;

    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
}

.topbar-title {
    display: flex;
    align-items: center;
    gap: 10px;

    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.topbar-title i {
    font-size: 18px;
}

.topbar-admin {
    display: flex;
    align-items: center;
    gap: 8px;

    font-size: 15px;
    font-weight: 500;
    color: #374151;
}

.topbar-admin i {
    font-size: 20px;
}

/* =========================
   STATISTIK PASIEN PER KECAMATAN
========================== */

.kec-area {
    height: 250px;
    padding: 8px 15px 10px;

    display: flex;
    flex-direction: column;
}

.kec-list {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
}

.kec-row {
    display: grid;
    grid-template-columns: 20px 1fr auto;
    align-items: center;
    gap: 10px;

    padding: 7px 0;

    border-bottom: 1px solid #f0f0f0;
}

.kec-row:last-child {
    border-bottom: none;
}

.kec-rank {
    font-size: 11px;
    font-weight: 600;
    color: #999;
}

.kec-row:first-child .kec-rank {
    color: #c90000;
}

.kec-name {
    margin-bottom: 4px;

    font-size: 12px;
    font-weight: 500;
    color: #333;
}

.kec-bar {
    height: 5px;

    background: #f1f1f1;
    border-radius: 4px;
    overflow: hidden;
}

.kec-bar span {
    display: block;
    height: 100%;

    background: #c90000;
    border-radius: 4px;
}

.kec-count {
    font-size: 12px;
    font-weight: 600;
    color: #222;
    white-space: nowrap;
}

.kec-count small {
    margin-left: 3px;

    font-size: 10px;
    font-weight: 400;
    color: #888;
}

.kec-note {
    padding-top: 6px;

    font-size: 10px;
    color: #888;
}

.kec-kosong {
    margin: auto;

    font-size: 12px;
    color: #888;
    text-align: center;
}

</style>

@endsection


@section('content')

<div class="dashboard">

    {{-- =========================
         SIDEBAR
    ========================== --}}

    <x-sidebar />


    {{-- =========================
         MAIN
    ========================== --}}

    <main class="main">


        {{-- =========================
             HEADER
        ========================== --}}

        <x-topbar title="Dashboard" icon="house-fill" />


        {{-- =========================
             CONTENT
        ========================== --}}

        <section class="dashboard-content">

    {{-- =========================
         4 KARTU STATISTIK
    ========================== --}}

    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div class="stat-title">Pendaftaran Hari Ini</div>
            <div class="stat-number">{{ $registrasiHariIni['total'] }}</div>
            <div class="stat-description">
                @if ($registrasiHariIni['total'] > 0)
                    {{ $registrasiHariIni['lama'] }} pasien lama, {{ $registrasiHariIni['baru'] }} pasien baru
                @else
                    Belum ada pendaftaran hari ini
                @endif
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>

            <div class="stat-title">Total Pasien Terdaftar</div>
            <div class="stat-number">{{ $totalPasien }}</div>
            <div class="stat-description">
                +{{ $pasienBaruBulanIni }} pasien bulan ini
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-person-lines-fill"></i>
            </div>

            <div class="stat-title">Sedang Dalam Antrean</div>
            <div class="stat-number">{{ $antrean['jumlah'] }}</div>
            <div class="stat-description">
                @if ($antrean['jumlah'] > 0)
                    Tunggu terlama {{ $antrean['tunggu_terlama'] }} menit
                @else
                    Tidak ada antrean saat ini
                @endif
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-capsule"></i>
            </div>

            <div class="stat-title">Resep Menunggu</div>
            <div class="stat-number">{{ $resepMenunggu['jumlah'] }}</div>
            <div class="stat-description">
                @if ($resepMenunggu['jumlah'] > 0)
                    {{ $resepMenunggu['hari_ini'] }} dari kunjungan hari ini
                @else
                    Tidak ada resep menunggu
                @endif
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-clipboard2-check"></i>
            </div>

            <div class="stat-title">Pasien Selesai</div>
            <div class="stat-number">{{ $pasienSelesaiHariIni }}</div>
            <div class="stat-description">
                dari {{ $totalKunjunganHariIni }} kunjungan hari ini, rekam medis lengkap
            </div>
        </div>

    </div>


    {{-- =========================
         GRAFIK + AKTIVITAS
    ========================== --}}

    <div class="middle-grid">

        {{-- KUNJUNGAN PASIEN PER POLI --}}

        <div class="dashboard-box">

            <div class="box-header">

                <div class="box-title">
                    Kunjungan Pasien Per Poli
                </div>

                <div class="box-filter-date">
                    <i class="bi bi-calendar3"></i>
                    <input
                        type="date"
                        id="filterTanggalPoli"
                        value="{{ $tanggalPoliDefault }}"
                        max="{{ now()->toDateString() }}"
                    >
                </div>

            </div>

            <div class="chart-area">

                <div class="chart-container">
                    <canvas id="kunjunganPoliChart"></canvas>
                </div>

            </div>

        </div>


        {{-- AKTIVITAS TERBARU --}}

        <div class="dashboard-box">

            <div class="box-header">

                <div class="box-title">
                    Aktivitas Terbaru
                </div>

            </div>

            <div class="activity-area">

                <div class="activity-item">

                    <div class="activity-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>

                    <div class="activity-content">

                        <div class="activity-text">
                            Pasien baru terdaftar
                        </div>

                        <div class="activity-name">
                            Ahmad Fauzi
                        </div>

                    </div>

                    <div class="activity-time">
                        10:32
                    </div>

                </div>


                <div class="activity-item">

                    <div class="activity-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                    <div class="activity-content">

                        <div class="activity-text">
                            Pendaftaran selesai
                        </div>

                        <div class="activity-name">
                            Siti Aminah
                        </div>

                    </div>

                    <div class="activity-time">
                        10:15
                    </div>

                </div>


                <div class="activity-item">

                    <div class="activity-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>

                    <div class="activity-content">

                        <div class="activity-text">
                            Pemeriksaan selesai
                        </div>

                        <div class="activity-name">
                            Budi Santoso
                        </div>

                    </div>

                    <div class="activity-time">
                        09:48
                    </div>

                </div>


                <div class="activity-item">

                    <div class="activity-icon">
                        <i class="bi bi-capsule"></i>
                    </div>

                    <div class="activity-content">

                        <div class="activity-text">
                            Resep diproses
                        </div>

                        <div class="activity-name">
                            Rina Wulandari
                        </div>

                    </div>

                    <div class="activity-time">
                        09:30
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =========================
         STATISTIK PASIEN PER KECAMATAN
    ========================== --}}

    <div class="middle-grid">

        {{-- GRAFIK PASIEN PER KECAMATAN --}}

        <div class="dashboard-box">

            <div class="box-header">

                <div class="box-title">
                    Statistik Pasien Per Kecamatan
                </div>

                <div class="box-filter" id="kecamatanTotal">
                    -
                </div>

            </div>

            <div class="chart-area">

                <div class="chart-container">
                    <canvas id="kecamatanChart"></canvas>
                </div>

            </div>

        </div>


        {{-- PERINGKAT KECAMATAN --}}

        <div class="dashboard-box">

            <div class="box-header">

                <div class="box-title">
                    Peringkat Kecamatan
                </div>

                <div class="box-filter" id="kecamatanTerbanyak">
                    -
                </div>

            </div>

            <div class="kec-area">

                <div class="kec-list" id="kecamatanList"></div>

                <div class="kec-note" id="kecamatanNote"></div>

            </div>

        </div>

    </div>


    {{-- =========================
     10 BESAR PENYAKIT
========================== --}}

<div class="disease-box">

    <div class="disease-header">

        <div class="disease-title">
            10 Besar Penyakit
        </div>

        <div class="disease-filter">

            <label for="bulanPenyakit">
                Periode
            </label>

            <select id="bulanPenyakit" onchange="ubahPeriodePenyakit()">
                @foreach ($daftarBulan ?? [] as $angkaBulan => $namaBulan)
                    <option value="{{ $angkaBulan }}" {{ (int) ($bulanTerpilih ?? 0) === $angkaBulan ? 'selected' : '' }}>
                        {{ $namaBulan }}
                    </option>
                @endforeach
            </select>

            <select id="tahunPenyakit" onchange="ubahPeriodePenyakit()">
                @foreach ($daftarTahun ?? [] as $angkaTahun)
                    <option value="{{ $angkaTahun }}" {{ (int) ($tahunTerpilih ?? 0) === $angkaTahun ? 'selected' : '' }}>
                        {{ $angkaTahun }}
                    </option>
                @endforeach
            </select>

        </div>

    </div>


    <div class="disease-table-wrapper">

        <table class="disease-table">

            <thead>
                <tr>
                    <th class="disease-rank">No.</th>
                    <th>Kode ICD-10</th>
                    <th>Diagnosa</th>
                    <th>Poli</th>
                    <th>Kasus</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($topPenyakit ?? [] as $penyakit)
                    <tr>
                        <td class="disease-rank">{{ $loop->iteration }}</td>
                        <td class="disease-code">{{ $penyakit->kode_icd10 ?: '-' }}</td>
                        <td>{{ $penyakit->diagnosa }}</td>
                        <td>{{ $penyakit->nama_poli }}</td>
                        <td class="disease-count">{{ $penyakit->jumlah }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#999; padding:20px 15px;">
                            Belum ada data diagnosis untuk periode ini.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

</section>


    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function ubahPeriodePenyakit() {
        const bulan = document.getElementById('bulanPenyakit').value;
        const tahun = document.getElementById('tahunPenyakit').value;

        const url = new URL(window.location.href);

        url.searchParams.set('bulan', bulan);
        url.searchParams.set('tahun', tahun);

        window.location.href = url.toString();
    }
</script>

<script>
    const kunjunganPoliAwal = @json($kunjunganPoliChart);
    const urlKunjunganPerPoli = "{{ route('dashboard.kunjungan-per-poli') }}";

    const ctx = document.getElementById('kunjunganPoliChart');

    const kunjunganPoliChart = new Chart(ctx, {
        type: 'bar',

        data: {
            labels: kunjunganPoliAwal.labels,

            datasets: [{
                label: 'Jumlah Kunjungan',

                data: kunjunganPoliAwal.data,

                backgroundColor: '#c90000',

                borderWidth: 0,

                borderRadius: 5,

                barThickness: 28
            }]
        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            },

            scales: {

                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0,
                        font: {
                            family: 'Poppins',
                            size: 10
                        }
                    },

                    grid: {
                        color: '#eeeeee'
                    }
                },

                x: {
                    ticks: {
                        font: {
                            family: 'Poppins',
                            size: 10
                        }
                    },

                    grid: {
                        display: false
                    }
                }

            }
        }
    });

    // ------------------------------------------------------------------
    // Sumber data grafik: tabel "Data Pasien" di halaman Pendaftaran
    // (kolom "Tgl Kunjungan" dan "Poli Tujuan"). Jadi grafik selalu sama
    // dengan data pasien yang tampil di web, tanpa perlu database.
    // Kalau halaman itu gagal dibaca, dipakai data dari server (database).
    // ------------------------------------------------------------------
    const urlPendaftaran = "{{ route('pendaftaran') }}";
    const labelPoliTetap = kunjunganPoliAwal.labels.slice();   // urutan batang
    const inputTanggalPoli = document.getElementById('filterTanggalPoli');

    const bulanIndo = {
        jan: 1, feb: 2, mar: 3, apr: 4, mei: 5, may: 5, jun: 6, jul: 7,
        agu: 8, agt: 8, aug: 8, sep: 9, okt: 10, oct: 10, nov: 11, des: 12, dec: 12
    };

    // "18 Sep 2026" / "18 September 2026" / "2026-09-18" / "18/09/2026" -> "2026-09-18"
    function tanggalKeISO(teks) {
        teks = (teks || '').trim().toLowerCase();

        let m = teks.match(/^(\d{4})-(\d{2})-(\d{2})/);
        if (m) return m[1] + '-' + m[2] + '-' + m[3];

        m = teks.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
        if (m) return m[3] + '-' + m[2].padStart(2, '0') + '-' + m[1].padStart(2, '0');

        m = teks.match(/^(\d{1,2})\s+([a-z]+)\.?\s+(\d{4})$/);
        if (m) {
            const bln = bulanIndo[m[2].substring(0, 3)];
            if (bln) return m[3] + '-' + String(bln).padStart(2, '0') + '-' + m[1].padStart(2, '0');
        }

        return null;
    }

    // "Poli Umum" -> "umum", "Poli Penyakit Dalam" -> "dalam"
    function kunciPoli(teks) {
        let k = (teks || '').toLowerCase().replace(/\s+/g, ' ').trim().replace(/^poli\s+/, '');
        if (k === 'penyakit dalam') k = 'dalam';
        return k;
    }

    // Kecamatan diambil dari atribut data-kecamatan pada baris tabel.
    // Kalau tidak ada, dicoba dari alamat lengkap (data-alamat) dengan format:
    // "Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi".
    function ambilKecamatan(tr) {
        let k = (tr.getAttribute('data-kecamatan') || '').trim();

        if (!k) {
            const el = tr.querySelector('[data-alamat]');
            if (el) {
                const bagian = el.getAttribute('data-alamat')
                    .split(',').map(x => x.trim()).filter(Boolean);
                if (bagian.length >= 4) {
                    k = bagian[bagian.length - 3];
                }
            }
        }

        return k.replace(/^kec(amatan)?\.?\s+/i, '').trim();
    }

    let janjiTabelPasien = null;   // di-cache: halaman pendaftaran cukup dibaca sekali

    function bacaTabelPasien() {
        if (janjiTabelPasien) {
            return janjiTabelPasien;
        }

        janjiTabelPasien = fetch(urlPendaftaran, { headers: { 'Accept': 'text/html' } })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Halaman pendaftaran tidak bisa dibaca.');
                }
                return response.text();
            })
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const kepala = Array.from(doc.querySelectorAll('#tabelPasien thead th'));
                const idxRm = kepala.findIndex(th => th.classList.contains('col-rm'));
                const idxTanggal = kepala.findIndex(th => th.classList.contains('col-tanggal'));
                const idxPoli = kepala.findIndex(th => th.classList.contains('col-poli'));

                if (idxTanggal < 0 || idxPoli < 0) {
                    throw new Error('Kolom tanggal / poli tidak ditemukan pada tabel pasien.');
                }

                const hasil = [];
                doc.querySelectorAll('#tabelPasienBody > tr').forEach(tr => {
                    const sel = tr.children;
                    if (sel.length <= Math.max(idxTanggal, idxPoli)) return;

                    hasil.push({
                        rm: idxRm >= 0 ? sel[idxRm].textContent.trim() : '',
                        tanggal: tanggalKeISO(sel[idxTanggal].textContent),
                        poli: sel[idxPoli].textContent.replace(/\s+/g, ' ').trim(),
                        kecamatan: ambilKecamatan(tr)
                    });
                });

                return hasil;
            });

        // kalau gagal, izinkan dicoba lagi lain kali
        janjiTabelPasien.catch(() => { janjiTabelPasien = null; });

        return janjiTabelPasien;
    }

    function hitungPerPoliDariTabel(baris, tanggal) {
        const jumlah = {};   // key poli -> jumlah
        const namaAsli = {}; // key poli -> teks asli (untuk poli di luar daftar tetap)

        baris.filter(b => b.tanggal === tanggal && b.poli).forEach(b => {
            const k = kunciPoli(b.poli);
            jumlah[k] = (jumlah[k] || 0) + 1;
            namaAsli[k] = b.poli;
        });

        const labels = labelPoliTetap.slice();
        const data = labelPoliTetap.map(label => jumlah[kunciPoli(label)] || 0);

        // Poli lain yang ada di tabel tapi tidak ada di daftar tetap tetap ditampilkan
        const kunciTetap = labelPoliTetap.map(kunciPoli);
        Object.keys(jumlah).forEach(k => {
            if (kunciTetap.indexOf(k) === -1) {
                labels.push(namaAsli[k]);
                data.push(jumlah[k]);
            }
        });

        return { labels: labels, data: data };
    }

    function tampilkanGrafik(hasil) {
        kunjunganPoliChart.data.labels = hasil.labels;
        kunjunganPoliChart.data.datasets[0].data = hasil.data;
        kunjunganPoliChart.update();
    }

    // Cadangan: ambil dari server (database) kalau tabel tidak bisa dibaca
    function muatDariServer(tanggal) {
        return fetch(`${urlKunjunganPerPoli}?tanggal=${tanggal}`, {
            headers: { 'Accept': 'application/json' }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data kunjungan per poli.');
                }
                return response.json();
            })
            .then(tampilkanGrafik);
    }

    function muatGrafikPoli(tanggal) {
        if (!tanggal) {
            return;
        }

        bacaTabelPasien()
            .then(baris => tampilkanGrafik(hitungPerPoliDariTabel(baris, tanggal)))
            .catch(errorTabel => {
                console.warn(errorTabel);
                muatDariServer(tanggal).catch(error => console.error(error));
            });
    }

    // Saat tanggal pada kalender filter diganti, hitung ulang grafik
    inputTanggalPoli.addEventListener('change', function () {
        muatGrafikPoli(this.value);
    });

    // Tampilkan juga untuk tanggal yang sedang terpilih saat halaman dibuka
    muatGrafikPoli(inputTanggalPoli.value);


    // ==================================================================
    // STATISTIK PASIEN PER KECAMATAN
    // Dibaca dari tabel "Data Pasien" yang sama (atribut data-kecamatan).
    // ==================================================================
    const MAKS_BATANG_KECAMATAN = 8;   // sisanya digabung jadi "Lainnya"
    const MAKS_PERINGKAT_KECAMATAN = 6;

    const kecamatanChart = new Chart(document.getElementById('kecamatanChart'), {
        type: 'bar',

        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Pasien',
                data: [],
                backgroundColor: '#c90000',
                borderWidth: 0,
                borderRadius: 5,
                barThickness: 16
            }]
        },

        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,

            plugins: { legend: { display: false } },

            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { family: 'Poppins', size: 10 } },
                    grid: { color: '#eeeeee' }
                },
                y: {
                    ticks: { font: { family: 'Poppins', size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });

    function hitungPerKecamatan(baris) {
        const sudahAda = new Set();
        const jumlah = {};
        const nama = {};
        let total = 0;
        let tanpaKecamatan = 0;

        baris.forEach(b => {
            // satu No. RM dihitung satu pasien
            if (b.rm) {
                if (sudahAda.has(b.rm)) return;
                sudahAda.add(b.rm);
            }

            total++;

            if (!b.kecamatan) {
                tanpaKecamatan++;
                return;
            }

            const k = b.kecamatan.toLowerCase();
            jumlah[k] = (jumlah[k] || 0) + 1;
            nama[k] = nama[k] || b.kecamatan;
        });

        const urut = Object.keys(jumlah)
            .map(k => ({ nama: nama[k], jumlah: jumlah[k] }))
            .sort((a, b) => b.jumlah - a.jumlah || a.nama.localeCompare(b.nama, 'id'));

        return {
            urut: urut,
            total: total,
            tanpaKecamatan: tanpaKecamatan,
            denganKecamatan: total - tanpaKecamatan
        };
    }

    function buatElemen(tag, kelas, teks) {
        const el = document.createElement(tag);
        if (kelas) el.className = kelas;
        if (teks !== undefined) el.textContent = teks;
        return el;
    }

    function tampilkanKecamatan(stat) {
        const elList = document.getElementById('kecamatanList');
        const elNote = document.getElementById('kecamatanNote');

        document.getElementById('kecamatanTotal').textContent = stat.total + ' pasien';
        elList.innerHTML = '';
        elNote.textContent = '';

        if (stat.urut.length === 0) {
            document.getElementById('kecamatanTerbanyak').textContent = '-';
            kecamatanChart.data.labels = [];
            kecamatanChart.data.datasets[0].data = [];
            kecamatanChart.update();
            elList.appendChild(buatElemen('div', 'kec-kosong', 'Belum ada data kecamatan pasien.'));
            return;
        }

        // ---- grafik batang (terbanyak di atas) ----
        let batang = stat.urut.slice(0, MAKS_BATANG_KECAMATAN);
        if (stat.urut.length > MAKS_BATANG_KECAMATAN) {
            const sisa = stat.urut.slice(MAKS_BATANG_KECAMATAN)
                .reduce((jml, k) => jml + k.jumlah, 0);
            batang.push({ nama: 'Lainnya', jumlah: sisa });
        }
        kecamatanChart.data.labels = batang.map(k => k.nama);
        kecamatanChart.data.datasets[0].data = batang.map(k => k.jumlah);
        kecamatanChart.update();

        // ---- peringkat ----
        const teratas = stat.urut[0];
        document.getElementById('kecamatanTerbanyak').textContent = 'Terbanyak: ' + teratas.nama;

        stat.urut.slice(0, MAKS_PERINGKAT_KECAMATAN).forEach((k, i) => {
            const persen = Math.round(k.jumlah / stat.denganKecamatan * 100);

            const baris = buatElemen('div', 'kec-row');
            baris.appendChild(buatElemen('span', 'kec-rank', i + 1));

            const info = buatElemen('div', 'kec-info');
            info.appendChild(buatElemen('div', 'kec-name', k.nama));
            const bar = buatElemen('div', 'kec-bar');
            const isi = document.createElement('span');
            isi.style.width = (k.jumlah / teratas.jumlah * 100) + '%';
            bar.appendChild(isi);
            info.appendChild(bar);
            baris.appendChild(info);

            const hitung = buatElemen('div', 'kec-count', k.jumlah);
            hitung.appendChild(buatElemen('small', '', '(' + persen + '%)'));
            baris.appendChild(hitung);

            elList.appendChild(baris);
        });

        // ---- catatan kecil ----
        const catatan = [];
        if (stat.urut.length > MAKS_PERINGKAT_KECAMATAN) {
            catatan.push('+' + (stat.urut.length - MAKS_PERINGKAT_KECAMATAN) + ' kecamatan lainnya');
        }
        if (stat.tanpaKecamatan > 0) {
            catatan.push(stat.tanpaKecamatan + ' pasien belum diisi kecamatannya');
        }
        elNote.textContent = catatan.join(' \u2022 ');
    }

    bacaTabelPasien()
        .then(baris => tampilkanKecamatan(hitungPerKecamatan(baris)))
        .catch(error => {
            console.warn(error);
            const elList = document.getElementById('kecamatanList');
            elList.innerHTML = '';
            elList.appendChild(buatElemen('div', 'kec-kosong', 'Data pasien tidak dapat dibaca.'));
        });
</script>

@endsection