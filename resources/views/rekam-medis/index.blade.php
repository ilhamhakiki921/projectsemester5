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
    padding: 25px 22px;
    background: #f3f4f6;
}

.alert-success {
    margin-bottom: 16px;
    padding: 12px 16px;

    border-radius: 8px;

    background: #d7f2e3;
    color: #158a4c;

    font-size: 12px;
    font-weight: 500;
}

.card {
    background: #ffffff;

    border-radius: 14px;

    padding: 22px 24px 8px;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.card-title {
    margin-bottom: 3px;

    font-size: 17px;
    font-weight: 700;

    color: #1a1a1a;
}

.card-subtitle {
    margin-bottom: 16px;

    font-size: 11px;
    color: #8a8f98;
}

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

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 5px 11px;

    border-radius: 20px;

    font-size: 10px;
    font-weight: 600;
}

.status-selesai {
    background: #d7f2e3;
    color: #158a4c;
}

.status-proses {
    background: #dbe9fe;
    color: #1d5fd6;
}

.status-belum {
    background: #eceef0;
    color: #777;
}

.aksi-group {
    display: flex;
    gap: 8px;
}

.btn-aksi {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 7px 12px;

    border: none;
    border-radius: 7px;

    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 10px;
    font-weight: 600;

    cursor: pointer;

    transition: 0.2s;
}

.btn-lihat {
    background: #eceef0;
    color: #333;
}

.btn-lihat:hover {
    background: #dcdfe3;
}

.btn-pdf {
    background: #b30000;
    color: #ffffff;
}

.btn-pdf:hover {
    background: #8a0000;
}

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">

        <x-topbar title="Rekam Medis" icon="folder-fill" />

        <section class="content">

            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">

                <div class="card-title">
                    Rekam Medis Pasien
                </div>

                <div class="card-subtitle">
                    Menampilkan kunjungan tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                </div>

                <table class="patient-table">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No RM</th>
                            <th>Nama Pasien</th>
                            <th>Poli</th>
                            <th>Status Asesmen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($kunjungans as $index => $kunjungan)

                            <tr>

                                <td>{{ $index + 1 }}</td>

                                <td>{{ $kunjungan->pasien->no_rm }}</td>

                                <td class="nama-pasien">{{ $kunjungan->pasien->nama }}</td>

                                <td>{{ ucfirst($kunjungan->poli) }}</td>

                                <td>

                                    @if ($kunjungan->rekamMedis)
                                        <span class="status-badge status-selesai">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Sudah Diisi
                                        </span>
                                    @elseif ($kunjungan->status === 'Sedang Diperiksa')
                                        <span class="status-badge status-proses">
                                            <i class="bi bi-hourglass-split"></i>
                                            Proses
                                        </span>
                                    @else
                                        <span class="status-badge status-belum">
                                            <i class="bi bi-dash-circle"></i>
                                            Belum Diisi
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="aksi-group">

                                        <a href="{{ route('pemeriksaan.show', $kunjungan->id) }}" class="btn-aksi btn-lihat">
                                            <i class="bi bi-eye"></i>
                                            Lihat
                                        </a>

                                        @if ($kunjungan->rekamMedis)
                                            <a href="{{ route('rekam-medis.pdf', $kunjungan->id) }}" class="btn-aksi btn-pdf">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                                PDF
                                            </a>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="empty-state">
                                    Belum ada kunjungan pada tanggal ini.
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
