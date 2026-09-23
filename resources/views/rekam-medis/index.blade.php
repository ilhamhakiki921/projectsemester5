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
        background: #f5f5f5;
    }

    .content {
        width: 100%;
        padding: 25px 22px;
    }

    .rm-card {
        background: #ffffff;

        border: 1px solid #e2e2e2;
        border-radius: 10px;

        padding: 20px 22px;

        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .rm-title {
        margin-bottom: 4px;

        font-size: 15px;
        font-weight: 700;
        color: #222;
    }

    .rm-subtitle {
        margin-bottom: 16px;

        font-size: 10px;
        color: #777;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .rm-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rm-table th {
        padding: 10px 8px;

        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #888;
        text-align: left;

        border-bottom: 1px solid #e2e2e2;
    }

    .rm-table td {
        padding: 12px 8px;

        font-size: 10px;
        color: #333;

        border-bottom: 1px solid #f0f0f0;
    }

    .rm-table tbody tr:hover td {
        background: #fafafa;
    }

    .empty-row td {
        padding: 30px 8px;

        text-align: center;
        color: #888;
        font-size: 10px;
    }

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

    .btn-lihat {
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
    }

    .btn-lihat:hover {
        background: #c40d0d;
    }

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Rekam Medis" icon="folder-fill" />

        <section class="content">

            <div class="rm-card">

                <h1 class="rm-title">Rekam Medis Pasien</h1>
                <p class="rm-subtitle">{{ $daftar->count() }} kunjungan tercatat</p>

                <div class="table-wrapper">
                    <table class="rm-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Dokter</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftar as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->tanggal_kunjungan->format('d-m-Y') }}</td>
                                    <td>{{ $item->pasien->no_rm }}</td>
                                    <td>{{ $item->pasien->nama }}</td>
                                    <td>{{ ucfirst($item->poli) }}</td>
                                    <td>{{ $item->dokter }}</td>
                                    <td>
                                        <span class="status-badge status-{{ str_replace(' ', '-', strtolower($item->status)) }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('rekam-medis.show', $item->id) }}" class="btn-lihat">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="8">Belum ada rekam medis tercatat.</td>
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
