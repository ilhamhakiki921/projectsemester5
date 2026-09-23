@extends('layouts.app')

@section('title', 'Pemeriksaan')

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

/* =========================
   MAIN
========================= */

.main {
    width: calc(100% - 230px);
    min-height: 100vh;
    margin-left: 230px;
    background: #ffffff;
}

.content {
    width: 100%;
    padding: 25px 22px;
    background: #ffffff;
}

.page-title {
    margin-bottom: 15px;

    font-size: 21px;
    font-weight: 700;

    text-transform: uppercase;

    color: #222;
}

/* =========================
   TAB POLI
========================= */

.poli-tabs {
    display: flex;
    gap: 8px;

    margin-bottom: 18px;

    border-bottom: 1px solid #ccc;
}

.poli-tab {
    padding: 9px 16px;

    font-size: 11px;
    font-weight: 500;

    color: #555;

    text-decoration: none;

    border-bottom: 2px solid transparent;

    transition: 0.2s;
}

.poli-tab:hover {
    color: #980000;
}

.poli-tab.active {
    color: #980000;
    font-weight: 700;

    border-bottom: 2px solid #980000;
}

/* =========================
   TABLE
========================= */

.table-wrapper {
    width: 100%;

    overflow-x: auto;

    border: 1px solid #777;
}

.patient-table {
    width: 100%;

    border-collapse: collapse;

    table-layout: fixed;
}

.patient-table th,
.patient-table td {
    border: 1px solid #999;

    padding: 8px 7px;

    font-size: 9px;

    text-align: left;

    vertical-align: middle;
}

.patient-table th {
    height: 34px;

    background: #e5e5e5;

    font-size: 9px;
    font-weight: 600;

    color: #222;

    text-align: center;
}

.patient-table td {
    height: 38px;

    color: #444;

    background: #ffffff;
}

.patient-table tbody tr:hover td {
    background: #f8f8f8;
}

.col-no {
    width: 5%;
}

.col-antrian {
    width: 10%;
}

.col-rm {
    width: 10%;
}

.col-nama {
    width: 20%;
}

.col-alamat {
    width: 25%;
}

.col-status {
    width: 15%;
}

.col-action {
    width: 10%;
}

.text-center {
    text-align: center !important;
}

.empty-state {
    padding: 20px;

    text-align: center;

    font-size: 10px;
    color: #888;
}

/* =========================
   STATUS BADGE
========================= */

.badge {
    display: inline-block;

    padding: 4px 9px;

    border-radius: 20px;

    font-size: 8px;
    font-weight: 600;

    text-transform: uppercase;
}

.badge-menunggu {
    background: #fff3cd;
    color: #8a6300;
}

.badge-diperiksa {
    background: #cfe2ff;
    color: #084298;
}

.badge-selesai {
    background: #d1e7dd;
    color: #0f5132;
}

/* =========================
   ACTION
========================= */

.btn-periksa {
    display: inline-flex;
    align-items: center;
    gap: 4px;

    padding: 5px 10px;

    border: none;
    border-radius: 5px;

    background: #1111b8;
    color: #ffffff;

    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 8px;
    font-weight: 500;

    cursor: pointer;

    transition: 0.2s;
}

.btn-periksa:hover {
    background: #08088f;
}

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">

        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            <h1 class="page-title">
                Antrian Pemeriksaan
            </h1>

            {{-- TAB POLI --}}
            <div class="poli-tabs">

                @foreach ($daftarPoli as $kode => $nama)

                    <a
                        href="{{ route('pemeriksaan.index', ['poli' => $kode]) }}"
                        class="poli-tab {{ $poli === $kode ? 'active' : '' }}"
                    >
                        {{ $nama }}
                    </a>

                @endforeach

            </div>

            {{-- TABLE ANTRIAN --}}
            <div class="table-wrapper">

                <table class="patient-table">

                    <thead>

                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-antrian">No Antrian</th>
                            <th class="col-rm">No RM</th>
                            <th class="col-nama">Nama Pasien</th>
                            <th class="col-alamat">Alamat</th>
                            <th class="col-status">Status</th>
                            <th class="col-action">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($antrians as $index => $antrian)

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td class="text-center">
                                    {{ $antrian->no_antrian }}
                                </td>

                                <td>
                                    {{ $antrian->pasien->no_rm }}
                                </td>

                                <td>
                                    {{ $antrian->pasien->nama }}
                                </td>

                                <td>
                                    {{ $antrian->pasien->alamat }}
                                </td>

                                <td class="text-center">

                                    @if ($antrian->status === 'Menunggu')
                                        <span class="badge badge-menunggu">Menunggu</span>
                                    @elseif ($antrian->status === 'Sedang Diperiksa')
                                        <span class="badge badge-diperiksa">Sedang Diperiksa</span>
                                    @else
                                        <span class="badge badge-selesai">Selesai</span>
                                    @endif

                                </td>

                                <td class="text-center">

                                    <a href="#" class="btn-periksa">
                                        <i class="bi bi-clipboard2-pulse"></i>
                                        Periksa
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="empty-state">
                                    Belum ada pasien di antrian poli ini hari ini.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

@endsection
