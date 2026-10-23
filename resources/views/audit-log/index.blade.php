@extends('layouts.app')

@section('title', 'Audit Trail')

@section('style')

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Poppins', sans-serif;
        background: #ffffff;
        color: #222;
    }

    .main { min-height: 100vh; background: #ffffff; }
    .main .topbar { border-bottom: 2px solid #dcdcdc; }
    .content { width: 100%; padding: 30px 28px; }

    .au-head h2 { font-size: 20px; font-weight: 700; color: #111827; }
    .au-head p  { font-size: 12.5px; color: #6b7280; margin-top: 4px; }

    /* ===== Filter ===== */
    .au-filter {
        display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;
        margin: 18px 0;
    }
    .au-filter .fg { display: flex; flex-direction: column; gap: 4px; }
    .au-filter label { font-size: 11px; font-weight: 600; color: #4b5563; }
    .au-filter input, .au-filter select {
        height: 36px; padding: 0 12px;
        border: 1px solid #555; border-radius: 8px;
        font-family: inherit; font-size: 12px; background: #fff; color: #222;
    }
    .au-filter .fg-q input { min-width: 240px; }

    .au-btn {
        height: 36px; padding: 0 16px; border: none; border-radius: 8px;
        font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px; text-decoration: none;
    }
    .au-btn-cari  { background: #14089a; color: #fff; }
    .au-btn-cari:hover { background: #0d0670; }
    .au-btn-reset { background: #f3f4f6; color: #374151; }
    .au-btn-reset:hover { background: #e5e7eb; }

    /* ===== Tabel ===== */
    .u-card { background: #fff; border: 1px solid #3a3a3a; border-radius: 12px; overflow: hidden; }
    .u-wrap { width: 100%; overflow-x: auto; }
    .u-table { width: 100%; border-collapse: collapse; min-width: 900px; }

    .u-table thead th {
        padding: 16px 14px;
        background: linear-gradient(90deg, #5e0707 0%, #a82020 100%);
        color: #fff; font-size: 12px; font-weight: 600;
        text-align: left; white-space: nowrap;
    }
    .u-table td {
        padding: 12px 14px; font-size: 12px; color: #1f2937;
        border-bottom: 1px solid #e2e2e2; vertical-align: top;
    }
    .u-table tbody tr:hover td { background: #fdf6f6; }
    .u-table tbody tr:last-child td { border-bottom: none; }

    .col-waktu { white-space: nowrap; }
    .au-empty { text-align: center; color: #9ca3af; padding: 36px 14px !important; }

    /* ===== Badge aksi ===== */
    .au-badge {
        display: inline-block; padding: 3px 12px; border-radius: 999px;
        font-size: 11px; font-weight: 600; border: 1px solid; background: #fff;
        white-space: nowrap;
    }
    .au-Login    { color: #1d4ed8; border-color: #1d4ed8; }
    .au-Logout   { color: #4b5563; border-color: #4b5563; }
    .au-Input    { color: #7e22ce; border-color: #7e22ce; }
    .au-Simpan   { color: #15803d; border-color: #15803d; }
    .au-Edit     { color: #b45309; border-color: #b45309; }
    .au-Hapus    { color: #b91c1c; border-color: #b91c1c; }
    .au-Cetak    { color: #0e7490; border-color: #0e7490; }
    .au-Password { color: #c2410c; border-color: #c2410c; }
    .au-TTD      { color: #be185d; border-color: #be185d; }

    /* ===== Detail ===== */
    .au-detail { font-size: 11.5px; line-height: 1.6; color: #6b7280; }
    .au-detail b { color: #111827; font-weight: 600; }
    .au-detail .lama { color: #b91c1c; text-decoration: line-through; }
    .au-detail .baru { color: #15803d; }

    /* ===== Pagination ===== */
    .au-foot {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; margin-top: 16px; font-size: 12px; color: #6b7280;
    }
    .au-pager { display: flex; gap: 6px; }
    .au-pager a, .au-pager span {
        min-width: 34px; height: 32px; padding: 0 10px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 12px; text-decoration: none; color: #374151; background: #fff;
    }
    .au-pager a:hover { background: #f3f4f6; }
    .au-pager .aktif { background: #a82020; border-color: #a82020; color: #fff; font-weight: 600; }
    .au-pager .mati  { color: #d1d5db; }
</style>

@endsection

@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="AUDIT TRAIL" icon="clipboard-data" />

        <section class="content">

            <div class="au-head">
                <h2>Audit trail</h2>
            </div>

            {{-- ====== Filter ====== --}}
            <form method="GET" action="{{ route('audit-log.index') }}" class="au-filter">

                <div class="fg fg-q">
                    <label for="q">Cari</label>
                    <input type="text" id="q" name="q" value="{{ request('q') }}"
                           placeholder="User, keterangan, No RM, IP" autocomplete="off">
                </div>

                <div class="fg">
                    <label for="aksi">Aksi</label>
                    <select id="aksi" name="aksi">
                        <option value="">Semua aksi</option>
                        @foreach ($daftarAksi as $key => $label)
                            <option value="{{ $key }}" @selected(request('aksi') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="fg">
                    <label for="dari">Dari tanggal</label>
                    <input type="date" id="dari" name="dari" value="{{ request('dari') }}">
                </div>

                <div class="fg">
                    <label for="sampai">Sampai tanggal</label>
                    <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}">
                </div>

                <button type="submit" class="au-btn au-btn-cari"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('audit-log.index') }}" class="au-btn au-btn-reset">Reset</a>
            </form>

            {{-- ====== Tabel ====== --}}
            <div class="u-card">
                <div class="u-wrap">
                    <table class="u-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                                <th>Detail</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="col-waktu">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
                                    <td>{{ $log->username ?? '-' }}</td>
                                    <td>
                                        <span class="au-badge au-{{ $log->aksi }}">
                                            {{ $daftarAksi[$log->aksi] ?? $log->aksi }}
                                        </span>
                                    </td>
                                    <td>{{ $log->keterangan }}</td>
                                    <td>
                                        <div class="au-detail">
                                            @if ($log->data_lama && $log->data_baru)
                                                @foreach ($log->data_baru as $k => $v)
                                                    <div>
                                                        <b>{{ $k }}:</b>
                                                        <span class="lama">{{ is_scalar($log->data_lama[$k] ?? null) && ($log->data_lama[$k] ?? '') !== '' ? $log->data_lama[$k] : '(kosong)' }}</span>
                                                        &rarr;
                                                        <span class="baru">{{ is_scalar($v) && $v !== '' ? $v : '(kosong)' }}</span>
                                                    </div>
                                                @endforeach
                                            @elseif ($log->data_baru)
                                                @foreach ($log->data_baru as $k => $v)
                                                    <div><b>{{ $k }}:</b> {{ is_scalar($v) ? $v : json_encode($v) }}</div>
                                                @endforeach
                                            @elseif ($log->data_lama)
                                                @foreach ($log->data_lama as $k => $v)
                                                    <div><b>{{ $k }}:</b> {{ is_scalar($v) ? $v : json_encode($v) }}</div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-waktu">{{ $log->ip_address }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="au-empty">Belum ada riwayat aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ====== Pagination ====== --}}
            @if ($logs->total() > 0)
                <div class="au-foot">
                    <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} data</span>

                    @if ($logs->hasPages())
                        <div class="au-pager">
                            @if ($logs->onFirstPage())
                                <span class="mati">&laquo;</span>
                            @else
                                <a href="{{ $logs->previousPageUrl() }}">&laquo;</a>
                            @endif

                            @php
                                $awal  = max(1, $logs->currentPage() - 2);
                                $akhir = min($logs->lastPage(), $logs->currentPage() + 2);
                            @endphp
                            @for ($i = $awal; $i <= $akhir; $i++)
                                @if ($i === $logs->currentPage())
                                    <span class="aktif">{{ $i }}</span>
                                @else
                                    <a href="{{ $logs->url($i) }}">{{ $i }}</a>
                                @endif
                            @endfor

                            @if ($logs->hasMorePages())
                                <a href="{{ $logs->nextPageUrl() }}">&raquo;</a>
                            @else
                                <span class="mati">&raquo;</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

        </section>
    </main>

</div>

@endsection
