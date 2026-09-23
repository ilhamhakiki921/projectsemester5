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
    grid-template-columns: repeat(4, 1fr);
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
    font-weight: 600;
    color: #777;
    margin-bottom: 5px;
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

@media (max-width: 1100px) {

    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .stat-grid {
        grid-template-columns: 1fr;
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
            <div class="stat-number">20</div>
            <div class="stat-description">
                10 pasien lama, 10 pasien baru
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>

            <div class="stat-title">Total Pasien Terdaftar</div>
            <div class="stat-number">{{ $totalPasien }}</div>
            <div class="stat-description">
                +62 bulan ini
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-person-lines-fill"></i>
            </div>

            <div class="stat-title">Sedang Dalam Antrean</div>
            <div class="stat-number">10</div>
            <div class="stat-description">
                Tunggu terlama 24 menit
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-capsule"></i>
            </div>

            <div class="stat-title">Resep Menunggu</div>
            <div class="stat-number">5</div>
            <div class="stat-description">
                1 menunggu stok
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

                <div class="box-filter">
                    Hari Ini
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

            <select id="bulanPenyakit" onchange="ubahPeriodePenyakit(this.value)">
            <option value="2026-09">September 2026</option>
            <option value="2026-08">Agustus 2026</option>
            <option value="2026-07">Juli 2026</option>
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

                <tr>
                    <td class="disease-rank">1</td>
                    <td class="disease-code">J06.9</td>
                    <td>Infeksi saluran pernapasan akut</td>
                    <td>Poli Umum</td>
                    <td class="disease-count">32</td>
                </tr>

                <tr>
                    <td class="disease-rank">2</td>
                    <td class="disease-code">I10</td>
                    <td>Hipertensi</td>
                    <td>Poli Umum</td>
                    <td class="disease-count">27</td>
                </tr>

                <tr>
                    <td class="disease-rank">3</td>
                    <td class="disease-code">K30</td>
                    <td>Dispepsia</td>
                    <td>Poli Penyakit Dalam</td>
                    <td class="disease-count">24</td>
                </tr>

                <tr>
                    <td class="disease-rank">4</td>
                    <td class="disease-code">E11.9</td>
                    <td>Diabetes melitus tipe 2</td>
                    <td>Poli Penyakit Dalam</td>
                    <td class="disease-count">21</td>
                </tr>

                <tr>
                    <td class="disease-rank">5</td>
                    <td class="disease-code">M54.5</td>
                    <td>Nyeri punggung bawah</td>
                    <td>Poli Umum</td>
                    <td class="disease-count">18</td>
                </tr>

                <tr>
                    <td class="disease-rank">6</td>
                    <td class="disease-code">J00</td>
                    <td>Nasofaringitis akut</td>
                    <td>Poli Anak</td>
                    <td class="disease-count">16</td>
                </tr>

                <tr>
                    <td class="disease-rank">7</td>
                    <td class="disease-code">L20.9</td>
                    <td>Dermatitis atopik</td>
                    <td>Poli Umum</td>
                    <td class="disease-count">14</td>
                </tr>

                <tr>
                    <td class="disease-rank">8</td>
                    <td class="disease-code">K29.7</td>
                    <td>Gastritis</td>
                    <td>Poli Penyakit Dalam</td>
                    <td class="disease-count">12</td>
                </tr>

                <tr>
                    <td class="disease-rank">9</td>
                    <td class="disease-code">H10.9</td>
                    <td>Konjungtivitis</td>
                    <td>Poli Mata</td>
                    <td class="disease-count">10</td>
                </tr>

                <tr>
                    <td class="disease-rank">10</td>
                    <td class="disease-code">K04.7</td>
                    <td>Abses periapikal</td>
                    <td>Poli Gigi</td>
                    <td class="disease-count">8</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

</section>


    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function ubahPeriodePenyakit(value) {
        const url = new URL(window.location.href);

        url.searchParams.set('periode', value);

        window.location.href = url.toString();
    }
</script>

<script>
    const ctx = document.getElementById('kunjunganPoliChart');

    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: [
                'Poli Umum',
                'Poli Gigi',
                'Poli Anak',
                'Poli Penyakit Dalam',
                'Poli Mata'
            ],

            datasets: [{
                label: 'Jumlah Kunjungan',

                data: [32, 18, 25, 14, 11],

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
</script>

@endsection