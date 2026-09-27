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
       CARI PASIEN
    ========================== */

    .search-card {
        background: #ffffff;

        border: 1px solid #e2e2e2;
        border-radius: 10px;

        padding: 18px 22px;

        margin-bottom: 16px;

        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .search-card h2 {
        font-size: 12px;
        font-weight: 700;
        color: #222;

        margin-bottom: 14px;
    }

    .search-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .search-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

        .search-field label {
            font-size: 10px;
            font-weight: 600;
            color: #111827;
        }

    .search-field input {
        height: 34px;

        padding: 0 10px;

        border: 1px solid #999;
        border-radius: 6px;

        background: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        color: #222;

        outline: none;
    }

    .search-field input:focus {
        border-color: #ed1111;
        box-shadow: 0 0 0 2px rgba(237, 17, 17, 0.12);
    }

    .search-field input[type="date"]::-webkit-calendar-picker-indicator {
        width: 18px;
        height: 18px;
        padding: 2px;
        cursor: pointer;
        filter: opacity(0.75);
    }

    .search-field input[type="date"]::-webkit-calendar-picker-indicator:hover {
        filter: opacity(1);
    }

    .search-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;

        margin-top: 14px;
    }

    .btn-search,
    .btn-reset {
        height: 30px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0 16px;

        border: none;
        border-radius: 6px;

        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        font-weight: 500;

        cursor: pointer;
        text-decoration: none;
    }

    .btn-search {
        background: #ed1111;
        color: #ffffff;
    }

    .btn-search:hover {
        background: #c40d0d;
    }

    .btn-reset {
        background: #ffffff;
        color: #777;
        border: 1px solid #ccc;
    }

    .btn-reset:hover {
        background: #f5f5f5;
    }

    @media (max-width: 900px) {
        .search-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .search-grid {
            grid-template-columns: 1fr;
        }
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
            color: #111827;
            text-align: left;

            border-bottom: 1px solid #e2e2e2;
        }

        .antrian-table td {
            padding: 12px 8px;

            font-size: 10px;
            color: #222;
            font-weight: 600;

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

    .status-sedang-diperiksa {
        background: #e8f0fe;
        color: #1967d2;
    }

    .status-selesai {
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

            <x-popup-alert />

            {{-- CARI PASIEN --}}
            <form method="GET" action="{{ route('pemeriksaan.index', $poli) }}" class="search-card">

                <h2>Cari Pasien</h2>

                <div class="search-grid">

                    <div class="search-field">
                        <label for="nama">Nama Pasien</label>
                        <input type="text" id="nama" name="nama" value="{{ request('nama') }}" placeholder="Masukkan nama pasien">
                    </div>

                    <div class="search-field">
                        <label for="no_rm">No. RM</label>
                        <input type="text" id="no_rm" name="no_rm" value="{{ request('no_rm') }}" placeholder="Masukkan nomor rekam medis" inputmode="numeric" maxlength="6" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)">
                    </div>

                    <div class="search-field">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" value="{{ request('nik') }}" placeholder="Masukkan NIK pasien (16 digit)" inputmode="numeric" maxlength="16" oninput="this.value = this.value.replace(/\D/g, '').slice(0, 16)">
                    </div>

                    <div class="search-field">
                        <label for="tanggal_kunjungan">Tanggal Kunjungan</label>
                        <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ request('tanggal_kunjungan') }}">
                    </div>

                </div>

                <div class="search-actions">
                    <a href="{{ route('pemeriksaan.index', $poli) }}" class="btn-reset">Reset</a>
                    <button type="submit" class="btn-search">Cari</button>
                </div>

            </form>


            {{-- CARD ANTRIAN --}}
            <div class="antrian-card">

                <h1 class="antrian-title">
                    Antrian Pemeriksaan &mdash; {{ $namaPoli }}
                </h1>

                <p class="antrian-subtitle">
                    {{ $antrian->count() }} pasien dalam antrian
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
                                <th>NIK</th>
                                <th>Tgl Kunjungan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($antrian as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ str_pad($item->no_antrian, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item->pasien->no_rm }}</td>
                                    <td>{{ $item->pasien->nama }}</td>
                                    <td>{{ $item->pasien->alamat }}</td>
                                    <td>{{ $item->pasien->nik }}</td>
                                    <td>{{ optional($item->tanggal_kunjungan)->format('d-m-Y') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($item->status)) }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('pemeriksaan.asesmen', [$poli, $item->pasien->no_rm]) }}"
                                            class="btn-periksa"
                                        >
                                            Periksa
                                        </a>
                                    </td>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="9">
                                        @if (request()->hasAny(['nama', 'no_rm', 'nik', 'tanggal_kunjungan']))
                                            Tidak ada pasien yang cocok dengan pencarian.
                                        @else
                                            Belum ada pasien di antrian poli ini hari ini.
                                        @endif
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