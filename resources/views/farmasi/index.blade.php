@extends('layouts.app')

@section('title', 'Farmasi')

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

    .fm-card {
        background: #ffffff;

        border: 1px solid #e2e2e2;
        border-radius: 10px;

        padding: 20px 22px;

        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .fm-title {
        margin-bottom: 4px;

        font-size: 15px;
        font-weight: 700;
        color: #222;
    }

    .fm-subtitle {
        margin-bottom: 16px;

        font-size: 10px;
        color: #777;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .fm-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fm-table th {
        padding: 10px 8px;

        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #888;
        text-align: left;

        border-bottom: 1px solid #e2e2e2;
        white-space: nowrap;
    }

    .fm-table td {
        padding: 12px 8px;

        font-size: 10px;
        color: #333;

        border-bottom: 1px solid #f0f0f0;
        white-space: nowrap;
    }

    .fm-table tbody tr:hover td {
        background: #fafafa;
    }

    .empty-row td {
        padding: 30px 8px;

        text-align: center;
        color: #888;
        font-size: 10px;
        white-space: normal;
    }

    .status-badge {
        display: inline-block;

        padding: 3px 10px;

        border-radius: 20px;

        font-size: 9px;
        font-weight: 600;
    }

    .status-menunggu-diambil {
        background: #fff4e5;
        color: #b06a00;
    }

    .status-sudah-diserahkan {
        background: #e6f4ea;
        color: #1a7f37;
    }

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Farmasi" icon="capsule" />

        <section class="content">

            <div class="fm-card">

                <h1 class="fm-title">Antrian Resep — Farmasi</h1>
                <p class="fm-subtitle">{{ $daftarResep->count() }} resep tercatat dari seluruh poli</p>

                <div class="table-wrapper">
                    <table class="fm-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tgl. Resep</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Nama Obat</th>
                                <th>Sediaan</th>
                                <th>Jumlah</th>
                                <th>Dosis</th>
                                <th>Frekuensi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftarResep as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->tanggal_penulisan->format('d-m-Y') }}</td>
                                    <td>{{ $item->kunjungan->pasien->no_rm }}</td>
                                    <td>{{ $item->kunjungan->pasien->nama }}</td>
                                    <td>{{ ucfirst($item->kunjungan->poli) }}</td>
                                    <td>{{ $item->obat->nama_obat }}</td>
                                    <td>{{ $item->obat->sediaan }}</td>
                                    <td>{{ $item->jumlah_obat }}</td>
                                    <td>{{ $item->dosis_obat }}</td>
                                    <td>{{ $item->frekuensi }}</td>
                                    <td>
                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($item->status_farmasi)) }}">
                                            {{ $item->status_farmasi }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="11">Belum ada resep obat yang masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

    </main>

</div>

@endsection
