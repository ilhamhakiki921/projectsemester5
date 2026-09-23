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
       PILIH POLI
    ========================== */

    .poli-select-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 20px;
    }

    .poli-select-wrapper label {
        font-size: 12px;
        font-weight: 600;
        color: #222;
    }

    .poli-select {
        height: 34px;
        min-width: 180px;

        padding: 0 10px;

        border: 1px solid #999;
        border-radius: 6px;

        background: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        color: #222;

        outline: none;
        cursor: pointer;
    }

    /* =========================
       CARD ANTRIAN
    ========================== */

    .antrian-card {
        background: #ffffff;

        border: 1px solid #e2e2e2;
        border-radius: 10px;

        padding: 20px 22px;

        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .antrian-title {
        font-size: 15px;
        font-weight: 700;
        color: #222;
    }

    .antrian-subtitle {
        margin-top: 2px;
        margin-bottom: 16px;

        font-size: 10px;
        color: #777;
    }

    /* =========================
       TABLE
    ========================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .antrian-table {
        width: 100%;
        border-collapse: collapse;
    }

    .antrian-table th {
        padding: 10px 8px;

        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #888;
        text-align: left;

        border-bottom: 1px solid #e2e2e2;
    }

    .antrian-table td {
        padding: 12px 8px;

        font-size: 10px;
        color: #333;

        border-bottom: 1px solid #f0f0f0;
    }

    .antrian-table tbody tr:hover td {
        background: #fafafa;
    }

    .empty-row td {
        padding: 30px 8px;

        text-align: center;
        color: #888;
        font-size: 10px;
    }

    /* =========================
       STATUS BADGE
    ========================== */

    .status-badge {
        display: inline-block;

        padding: 3px 10px;

        border-radius: 20px;

        font-size: 9px;
        font-weight: 600;
    }

    .status-menunggu {
        background: #fff4e5;
        color: #b06a00;
    }

    .status-diperiksa {
        background: #e6f4ea;
        color: #1a7f37;
    }

    /* =========================
       ACTION BUTTON
    ========================== */

    .btn-periksa {
        height: 26px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0 12px;

        border: none;
        border-radius: 5px;

        background: #ed1111;
        color: #ffffff;

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 9px;
        font-weight: 500;

        cursor: pointer;
    }

    .btn-periksa:hover {
        background: #c40d0d;
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

            @if (session('success'))
                <div style="margin-bottom: 16px; padding: 10px 14px; background: #e6f4ea; color: #1a7f37; border-radius: 6px; font-size: 11px; font-weight: 500;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div style="margin-bottom: 16px; padding: 10px 14px; background: #fff4e5; color: #b06a00; border-radius: 6px; font-size: 11px; font-weight: 500;">
                    {{ session('warning') }}
                </div>
            @endif

            {{-- PILIH POLI --}}
            <div class="poli-select-wrapper">

                <label for="poli">Pilih Poli:</label>

                <select
                    id="poli"
                    class="poli-select"
                    onchange="window.location.href = this.value"
                >
                    @foreach ($daftarPoli as $slug => $nama)
                        <option
                            value="{{ route('pemeriksaan.index', $slug) }}"
                            {{ $poli === $slug ? 'selected' : '' }}
                        >
                            {{ $nama }}
                        </option>
                    @endforeach
                </select>

            </div>


            {{-- CARD ANTRIAN --}}
            <div class="antrian-card">

                <h1 class="antrian-title">
                    Antrian Pemeriksaan &mdash; {{ $namaPoli }}
                </h1>

                <p class="antrian-subtitle">
                    {{ count($antrian) }} pasien dalam antrian
                </p>


                <div class="table-wrapper">

                    <table class="antrian-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Antrian</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($antrian as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item['no_antrian'] }}</td>
                                    <td>{{ $item['no_rm'] }}</td>
                                    <td>{{ $item['nama_pasien'] }}</td>
                                    <td>{{ $item['alamat'] }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($item['status']) }}">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('pemeriksaan.asesmen', [$poli, $item['no_rm']]) }}"
                                            class="btn-periksa"
                                        >
                                            Periksa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="7">
                                        Belum ada pasien di antrian poli ini hari ini.
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

@endsection
