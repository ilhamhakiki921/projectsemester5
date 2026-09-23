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
    color: #222;
}

/* =========================
   MAIN
========================= */

.main {
    width: calc(100% - 230px);
    min-height: 100vh;
    margin-left: 230px;
    background: #f3f4f6;
}

.content {
    width: 100%;
    padding: 25px 22px;
    background: #f3f4f6;
}

/* =========================
   FILTER POLI
========================= */

.poli-filter {
    display: flex;
    align-items: center;
    gap: 10px;

    margin-bottom: 18px;
}

.poli-filter label {
    font-size: 12px;
    font-weight: 600;

    color: #222;
}

.poli-select {
    height: 36px;

    padding: 0 12px;

    border: 1px solid #d0d3d8;
    border-radius: 8px;

    outline: none;

    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 500;

    color: #222;

    background: #ffffff;

    cursor: pointer;
}

.poli-select:focus {
    border-color: #0f3d33;
}

/* =========================
   CARD
========================= */

.card {
    background: #ffffff;

    border-radius: 14px;

    padding: 22px 24px 8px;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.card-title {
    font-size: 17px;
    font-weight: 700;

    color: #1a1a1a;
}

.card-subtitle {
    margin-top: 3px;
    margin-bottom: 16px;

    font-size: 11px;
    color: #8a8f98;
}

/* =========================
   TABLE
========================= */

.patient-table {
    width: 100%;

    border-collapse: collapse;
}

.patient-table th {
    padding: 10px 12px;

    border-bottom: 1px solid #ececec;

    font-size: 10px;
    font-weight: 700;

    text-align: left;
    text-transform: uppercase;

    color: #8a8f98;

    letter-spacing: 0.3px;
}

.patient-table td {
    padding: 14px 12px;

    border-bottom: 1px solid #f0f0f0;

    font-size: 12px;

    color: #333;

    vertical-align: middle;
}

.patient-table tbody tr:last-child td {
    border-bottom: none;
}

.col-no {
    width: 5%;
}

.col-antrian {
    width: 12%;
}

.col-rm {
    width: 11%;
}

.col-nama {
    width: 18%;
}

.col-alamat {
    width: 22%;
}

.col-status {
    width: 17%;
}

.col-action {
    width: 10%;
}

.nama-pasien {
    font-weight: 600;
    color: #1a1a1a;
}

.empty-state {
    padding: 22px 12px;

    text-align: center;

    font-size: 11px;
    color: #8a8f98;
}

/* =========================
   NOMOR ANTRIAN
========================= */

.antrian-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 34px;
    height: 30px;

    padding: 0 6px;

    border-radius: 8px;

    background: #0f3d33;
    color: #ffffff;

    font-size: 12px;
    font-weight: 700;
}

/* =========================
   STATUS BADGE
========================= */

.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 5px 11px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 600;
}

.badge::before {
    content: "";

    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}

.badge-menunggu {
    background: #fdecd2;
    color: #b3691a;
}

.badge-diperiksa {
    background: #dbe9fe;
    color: #1d5fd6;
}

.badge-selesai {
    background: #d7f2e3;
    color: #158a4c;
}

/* =========================
   ACTION
========================= */

.btn-periksa {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 8px 18px;

    border: none;
    border-radius: 8px;

    background: #0f3d33;
    color: #ffffff;

    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 11px;
    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;
}

.btn-periksa:hover {
    background: #0a2b24;
}

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">

        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            {{-- FILTER POLI --}}
            <div class="poli-filter">

                <label for="poli-select">Pilih Poli:</label>

                <select
                    id="poli-select"
                    class="poli-select"
                    onchange="window.location.href = this.value"
                >

                    @foreach ($daftarPoli as $kode => $nama)

                        <option
                            value="{{ route('pemeriksaan.index', ['poli' => $kode]) }}"
                            {{ $poli === $kode ? 'selected' : '' }}
                        >
                            {{ $nama }}
                        </option>

                    @endforeach

                </select>

            </div>

            {{-- CARD ANTRIAN --}}
            <div class="card">

                <div class="card-title">
                    Antrian Pemeriksaan — {{ $daftarPoli[$poli] }}
                </div>

                <div class="card-subtitle">
                    {{ $antrians->count() }} pasien dalam antrian
                </div>

                <table class="patient-table">

                    <thead>

                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-antrian">No. Antrian</th>
                            <th class="col-rm">No. RM</th>
                            <th class="col-nama">Nama Pasien</th>
                            <th class="col-alamat">Alamat</th>
                            <th class="col-status">Status</th>
                            <th class="col-action">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($antrians as $index => $antrian)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <span class="antrian-badge">
                                        {{ str_pad($antrian->no_antrian, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $antrian->pasien->no_rm }}
                                </td>

                                <td class="nama-pasien">
                                    {{ $antrian->pasien->nama }}
                                </td>

                                <td>
                                    {{ $antrian->pasien->alamat }}
                                </td>

                                <td>

                                    @if ($antrian->status === 'Menunggu')
                                        <span class="badge badge-menunggu">Menunggu</span>
                                    @elseif ($antrian->status === 'Sedang Diperiksa')
                                        <span class="badge badge-diperiksa">Sedang Diperiksa</span>
                                    @else
                                        <span class="badge badge-selesai">Selesai</span>
                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('pemeriksaan.show', $antrian->id) }}"
                                        class="btn-periksa"
                                    >
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
