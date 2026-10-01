@extends('layouts.app')

@section('title', 'Data Pasien')

@section('style')

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Poppins', sans-serif;
        background: #ffffff;
        color: #222;
    }

    .main {
        min-height: 100vh;
        background: #ffffff;
    }

    .main .topbar { border-bottom: 2px solid #dcdcdc; }

    .content { width: 100%; padding: 30px 28px; }

    /* ===== Toolbar (cari + tambah) ===== */
    .u-toolbar {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .u-search {
        position: relative;
        flex: 1 1 320px;
        max-width: 400px;
    }

    .u-search i {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: #9ca3af;
        pointer-events: none;
    }

    .u-search input {
        width: 100%;
        height: 40px;
        padding: 0 38px 0 38px;
        border: 1px solid #555;
        border-radius: 10px;
        background: #fff;
        font-family: inherit;
        font-size: 12px;
        color: #222;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .u-search input::placeholder { color: #9ca3af; font-weight: 500; }
    .u-search input:focus {
        border-color: #b40000;
        box-shadow: 0 0 0 3px rgba(180, 0, 0, .12);
    }

    .u-search .u-clear {
        position: absolute;
        right: 10px; top: 50%;
        transform: translateY(-50%);
        display: none;
        border: none; background: none;
        color: #9ca3af; cursor: pointer;
        font-size: 14px; line-height: 1;
    }
    .u-search .u-clear.show { display: block; }
    .u-search .u-clear:hover { color: #b40000; }

    .btn-tambah {
        height: 28px;
        display: inline-flex; align-items: center; gap: 5px;
        padding: 0 14px;
        margin-bottom: -8px;
        border: none; border-radius: 4px;
        background: #14089a;
        color: #fff;
        font-family: inherit; font-size: 11px; font-weight: 600;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-tambah:hover { background: #0d0670; }
    .btn-tambah:active { transform: scale(.97); }

    /* ===== Card + tabel ===== */
    .u-card {
        background: #fff;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        overflow: hidden;
    }

    .u-wrap { width: 100%; overflow-x: auto; }

    .u-table { width: 100%; border-collapse: collapse; min-width: 900px; }

    .u-table thead th {
        padding: 16px 14px;
        background: linear-gradient(90deg, #5e0707 0%, #a82020 100%);
        color: #fff;
        font-size: 12px; font-weight: 600;
        text-align: left;
        white-space: nowrap;
    }

    .u-table td {
        padding: 12px 14px;
        font-size: 12px;
        color: #1f2937;
        border-bottom: 1px solid #e2e2e2;
        vertical-align: middle;
    }

    .u-table tbody tr { transition: background .12s; }
    .u-table tbody tr:hover td { background: #fdf6f6; }
    .u-table tbody tr:last-child td { border-bottom: none; }

    .col-no { width: 70px; text-align: center; }
    .col-c  { text-align: center; }
    .u-table thead th.col-no,
    .u-table thead th.col-c { text-align: center; }

    /* ===== Badge ===== */
    .badge {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 10px;
        font-size: 10.5px; font-weight: 700;
    }
    .badge-aktif    { background: #86d3a4; color: #14532d; }
    .badge-nonaktif { background: #777777; color: #111111; }

    /* ===== Tombol aksi ===== */
    .act { display: flex; align-items: center; justify-content: center; gap: 14px; }

    .btn-act {
        height: 24px;
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0;
        border-radius: 5px;
        border: 1px solid transparent;
        font-family: inherit; font-size: 12px;
        cursor: pointer;
        transition: filter .12s, background .12s, transform .1s;
    }
    .btn-act:active { transform: scale(.94); }

    .btn-edit { width: 46px; background: #7ea2ea; border-color: #6b8fd6; color: #0b1b4d; }
    .btn-edit:hover { background: #6b90dc; }

    .btn-detail { width: 46px; background: #fff; border-color: #6b7280; color: #111; }
    .btn-detail:hover { background: #f3f4f6; }

    /* ===== Empty state ===== */
    .u-empty { padding: 40px 10px; text-align: center; color: #6b7280; font-size: 12px; }
    .u-empty i { display: block; font-size: 30px; color: #d1d5db; margin-bottom: 8px; }

    /* ===== Modal ===== */
    .m-overlay {
        position: fixed; inset: 0; z-index: 3000;
        display: none; align-items: center; justify-content: center;
        padding: 16px;
        background: rgba(17, 24, 39, .55);
        backdrop-filter: blur(2px);
    }
    .m-overlay.show { display: flex; animation: mFade .15s ease; }

    @keyframes mFade { from { opacity: 0; } to { opacity: 1; } }
    @keyframes mPop  { from { opacity: 0; transform: translateY(10px) scale(.98); } to { opacity: 1; transform: none; } }

    .m-box {
        width: 100%; max-width: 380px; max-height: 92vh; overflow-y: auto;
        background: #fff; border-radius: 14px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .3);
        animation: mPop .18s ease;
    }

    .m-foot { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 20px 18px; }

    .f-group { margin-bottom: 0; }
    .f-group label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #111; }

    .f-input {
        width: 100%; height: 38px; padding: 0 14px;
        border: 1px solid #555; border-radius: 10px;
        font-family: inherit; font-size: 12.5px; color: #111; background: #fff;
        outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .f-input::placeholder { color: #9ca3af; font-weight: 400; }
    .f-input:focus { border-color: #b40000; box-shadow: 0 0 0 3px rgba(180, 0, 0, .12); }
    .f-input.is-invalid { border-color: #d10000; }

    select.f-input {
        appearance: none; -webkit-appearance: none; -moz-appearance: none;
        padding-right: 40px; cursor: pointer;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 16 16' fill='none' stroke='%23111' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M2 5l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 16px center;
    }

    textarea.f-input { height: auto; min-height: 76px; padding: 10px 14px; resize: vertical; line-height: 1.5; }
    .f-error { margin-top: 4px; font-size: 10.5px; color: #d10000; }

    .m-alert {
        margin-bottom: 12px; padding: 9px 12px;
        border-radius: 8px; background: #fdecec; color: #a80000;
        font-size: 11px;
    }

    .btn-m {
        height: 32px; min-width: 130px; padding: 0 18px; border-radius: 6px; border: 1px solid transparent;
        font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .btn-m-cancel { background: #fff; border-color: #555; color: #111; }
    .btn-m-cancel:hover { background: #f3f4f6; }
    .btn-m-save { background: #12c933; color: #fff; }
    .btn-m-save:hover { background: #0ea828; }
    .btn-m-danger { background: #d10000; color: #fff; }
    .btn-m-danger:hover { background: #a80000; }

    /* ----- modal form tambah/edit ----- */
    #modalForm .m-box { max-width: 720px; border-radius: 24px; }
    #modalForm .m-head {
        position: relative;
        padding: 22px 32px 18px;
        background: linear-gradient(90deg, #b81f1f 0%, #6e0f0f 55%, #470000 100%);
        color: #fff;
        border-radius: 24px 24px 0 0;
    }
    #modalForm .m-head h3 { font-size: 19px; font-weight: 600; line-height: 1.2; }
    #modalForm .m-head p  { margin-top: 3px; font-size: 10px; font-weight: 500; opacity: .95; }

    #modalForm .m-body { padding: 30px 40px 4px; }

    #modalForm .f-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 40px;
        row-gap: 16px;
    }
    #modalForm .f-full { grid-column: 1 / -1; }
    .f-group label small { font-size: 10.5px; font-weight: 400; color: #9ca3af; }
    .f-readonly { background: #f3f4f6; color: #6b7280; cursor: not-allowed; }
    .f-readonly:focus { border-color: #555; box-shadow: none; }

    #modalForm .m-foot { gap: 22px; padding: 30px 40px 28px; }

    /* ----- modal konfirmasi hapus ----- */
    .m-confirm { padding: 26px 22px 6px; text-align: center; }
    .m-confirm .ic {
        width: 54px; height: 54px; margin: 0 auto 12px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 24px;
        background: #fdecec; color: #d10000;
    }
    .m-confirm h4 { font-size: 14px; margin-bottom: 6px; }
    .m-confirm p  { font-size: 11.5px; color: #6b7280; line-height: 1.6; }
    .m-confirm b  { color: #111; }
    #modalHapus .btn-m { min-width: 110px; }

    /* ===== Pagination ===== */
    .pager-bar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        margin-top: 14px;
        font-size: 11px; color: #6b7280;
    }
    .pager { display: flex; align-items: center; gap: 4px; }
    .pager a, .pager span {
        min-width: 28px; height: 28px; padding: 0 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #cfd4dc; border-radius: 6px;
        background: #fff; color: #374151;
        font-size: 11px; font-weight: 500; text-decoration: none;
    }
    .pager a:hover { background: #f3f4f6; }
    .pager .on { background: #a82020; border-color: #a82020; color: #fff; }
    .pager .off { opacity: .4; }
    .pager .dots { border: none; background: none; min-width: 16px; padding: 0; }


    /* ===== Khusus Data Pasien ===== */
    .col-alamat { max-width: 260px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .col-nama { font-weight: 500; }
    .col-nowrap { white-space: nowrap; }
    .u-search { max-width: 380px; }

    /* ----- modal detail ----- */
    #modalDetail .m-box { max-width: 720px; border-radius: 24px; }
    #modalDetail .m-head {
        padding: 22px 32px 18px;
        background: linear-gradient(90deg, #b81f1f 0%, #6e0f0f 55%, #470000 100%);
        color: #fff; border-radius: 24px 24px 0 0;
    }
    #modalDetail .m-head h3 { font-size: 19px; font-weight: 600; line-height: 1.2; }
    #modalDetail .m-head p  { margin-top: 3px; font-size: 10px; font-weight: 500; opacity: .95; }
    #modalDetail .m-body { padding: 26px 40px 4px; }

    .d-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 40px; }
    .d-full { grid-column: 1 / -1; }
    .d-item small { display: block; margin-bottom: 3px; font-size: 10.5px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .3px; }
    .d-item span { font-size: 12.5px; color: #111; line-height: 1.5; word-break: break-word; }

    .d-sub { margin: 22px 0 8px; font-size: 12px; font-weight: 700; color: #a82020; }
    .d-riwayat { width: 100%; border-collapse: collapse; }
    .d-riwayat th { padding: 8px 10px; background: #f3f4f6; font-size: 10.5px; font-weight: 600; text-align: left; color: #4b5563; }
    .d-riwayat td { padding: 8px 10px; font-size: 11.5px; border-bottom: 1px solid #eee; }
    .d-kosong { padding: 14px; text-align: center; font-size: 11.5px; color: #9ca3af; border: 1px dashed #d1d5db; border-radius: 8px; }

    #modalDetail .m-foot { padding: 22px 40px 26px; }

    @media (max-width: 640px) {
        .d-grid { grid-template-columns: 1fr; }
        #modalDetail .m-body { padding: 20px 20px 4px; }
        .content { padding: 18px 14px; }
        .u-search { max-width: none; }
        .btn-tambah { margin-bottom: 0; }
        #modalForm .f-grid { grid-template-columns: 1fr; row-gap: 12px; }
        #modalForm .m-body { padding: 22px 20px 4px; }
        #modalForm .m-foot { padding: 22px 20px 22px; gap: 12px; }
    }
</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="DATA PASIEN" icon="house-fill" />

        <x-popup-alert />

        <section class="content">

            {{-- ====== Toolbar ====== --}}
            <div class="u-toolbar">
                <form class="u-search" id="formCari" method="GET" action="{{ route('data-pasien.index') }}" role="search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" id="cariPasien" value="{{ $q }}" placeholder="Cari No.RM,NIK,atau nama pasien" autocomplete="off">
                    <button type="button" class="u-clear {{ $q !== '' ? 'show' : '' }}" id="clearCari" aria-label="Hapus pencarian">&times;</button>
                </form>
            </div>

            {{-- ====== Tabel (area ini yang diperbarui saat mencari) ====== --}}
            <div id="areaTabel">
                <div class="u-card">
                    <div class="u-wrap">
                        <table class="u-table">
                            <thead>
                                <tr>
                                    <th class="col-c">No RM</th>
                                    <th class="col-c">NIK</th>
                                    <th>Nama Pasien</th>
                                    <th class="col-c">Jenis Kelamin</th>
                                    <th class="col-c">Tgl Lahir</th>
                                    <th>Alamat</th>
                                    <th class="col-c">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pasiens as $p)
                                    @php
                                        $riwayat = $p->kunjungan->map(fn ($k) => [
                                            'tanggal' => $k->tanggal_kunjungan?->format('d-m-Y'),
                                            'poli'    => $labelPoli[$k->poli] ?? ucfirst((string) $k->poli),
                                            'dokter'  => $k->dokter,
                                            'status'  => $k->status,
                                        ])->values();

                                        $info = [
                                            'no_rm'         => $p->no_rm,
                                            'nik'           => $p->nik,
                                            'nama'          => $p->nama,
                                            'jenis_kelamin' => $p->jenis_kelamin,
                                            'tanggal_lahir' => $p->tanggal_lahir?->format('d-m-Y'),
                                            'usia'          => $p->tanggal_lahir ? $p->tanggal_lahir->age . ' Tahun' : '-',
                                            'no_telepon'    => $p->no_telepon ?: '-',
                                            'alamat'        => $p->alamat,
                                            'kecamatan'     => $p->kecamatan ?: '-',
                                            'riwayat'       => $riwayat,
                                        ];
                                    @endphp
                                    <tr>
                                        <td class="col-c col-nowrap">{{ $p->no_rm }}</td>
                                        <td class="col-c col-nowrap">{{ $p->nik }}</td>
                                        <td class="col-nama">{{ $p->nama }}</td>
                                        <td class="col-c col-nowrap">{{ $p->jenis_kelamin }}</td>
                                        <td class="col-c col-nowrap">{{ $p->tanggal_lahir?->format('d-m-Y') }}</td>
                                        <td class="col-alamat" title="{{ $p->alamat }}">{{ $p->alamat }}</td>
                                        <td>
                                            <div class="act">
                                                <button type="button" class="btn-act btn-edit" title="Edit"
                                                    data-id="{{ $p->id }}"
                                                    data-no_rm="{{ $p->no_rm }}"
                                                    data-nama="{{ $p->nama }}"
                                                    data-jk="{{ $p->jenis_kelamin }}"
                                                    data-tgl="{{ $p->tanggal_lahir?->format('Y-m-d') }}"
                                                    data-nik="{{ $p->nik }}"
                                                    data-telp="{{ $p->no_telepon }}"
                                                    data-alamat="{{ $p->alamat }}"
                                                    data-kecamatan="{{ $p->kecamatan }}">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>

                                                <button type="button" class="btn-act btn-detail" title="Lihat detail"
                                                    data-info="{{ json_encode($info) }}">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($pasiens->isEmpty())
                            <div class="u-empty">
                                <i class="bi bi-people"></i>
                                @if ($q !== '')
                                    Tidak ada pasien yang cocok dengan "{{ $q }}".
                                @else
                                    Belum ada data pasien. Pasien baru didaftarkan lewat menu <b>Pendaftaran</b>.
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @if ($pasiens->total() > 0)
                    @php
                        $now  = $pasiens->currentPage();
                        $last = $pasiens->lastPage();
                        $dari = max(1, $now - 2);
                        $ke   = min($last, $now + 2);
                    @endphp
                    <div class="pager-bar">
                        <div>Menampilkan {{ $pasiens->firstItem() }}–{{ $pasiens->lastItem() }} dari {{ number_format($pasiens->total(), 0, ',', '.') }} pasien</div>

                        @if ($last > 1)
                            <nav class="pager" aria-label="Halaman">
                                @if ($pasiens->onFirstPage())
                                    <span class="off"><i class="bi bi-chevron-left"></i></span>
                                @else
                                    <a href="{{ $pasiens->url($now - 1) }}" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></a>
                                @endif

                                @if ($dari > 1)
                                    <a href="{{ $pasiens->url(1) }}">1</a>
                                    @if ($dari > 2) <span class="dots">…</span> @endif
                                @endif

                                @for ($i = $dari; $i <= $ke; $i++)
                                    @if ($i === $now)
                                        <span class="on">{{ $i }}</span>
                                    @else
                                        <a href="{{ $pasiens->url($i) }}">{{ $i }}</a>
                                    @endif
                                @endfor

                                @if ($ke < $last)
                                    @if ($ke < $last - 1) <span class="dots">…</span> @endif
                                    <a href="{{ $pasiens->url($last) }}">{{ $last }}</a>
                                @endif

                                @if ($pasiens->hasMorePages())
                                    <a href="{{ $pasiens->url($now + 1) }}" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></a>
                                @else
                                    <span class="off"><i class="bi bi-chevron-right"></i></span>
                                @endif
                            </nav>
                        @endif
                    </div>
                @endif
            </div>

        </section>
    </main>

</div>


{{-- ====== MODAL: Edit ====== --}}
@php $idEdit = old('_id'); @endphp

<div class="m-overlay {{ $errors->any() && $idEdit ? 'show' : '' }}" id="modalForm">
    <div class="m-box">
        <div class="m-head">
            <h3>Edit Data Pasien</h3>
            <p>Perbarui data pasien SIP-REK</p>
        </div>

        <form method="POST" id="formPasien"
              action="{{ $idEdit ? route('data-pasien.update', $idEdit) : '#' }}"
              autocomplete="off">
            @csrf
            @method('PUT')
            <input type="hidden" name="_id" id="fId" value="{{ $idEdit }}">

            <div class="m-body">

                @if ($errors->any() && $idEdit)
                    <div class="m-alert">Periksa kembali isian yang ditandai merah.</div>
                @endif

                <div class="f-grid">

                    <div class="f-group">
                        <label for="fRm">No. RM <small>(tidak bisa diubah)</small></label>
                        <input type="text" id="fRm" class="f-input f-readonly" readonly tabindex="-1" value="">
                    </div>

                    <div class="f-group">
                        <label for="fNik">NIK</label>
                        <input type="text" name="nik" id="fNik" inputmode="numeric" maxlength="16"
                               class="f-input @error('nik') is-invalid @enderror"
                               value="{{ old('nik') }}" placeholder="16 digit">
                        @error('nik') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group f-full">
                        <label for="fNama">Nama Pasien</label>
                        <input type="text" name="nama" id="fNama" maxlength="255"
                               class="f-input @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}">
                        @error('nama') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fJk">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="fJk" class="f-input @error('jenis_kelamin') is-invalid @enderror">
                            <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fTgl">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="fTgl" max="{{ now()->format('Y-m-d') }}"
                               class="f-input @error('tanggal_lahir') is-invalid @enderror"
                               value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fTelp">No. Telepon <small>(opsional)</small></label>
                        <input type="text" name="no_telepon" id="fTelp" maxlength="20" inputmode="tel"
                               class="f-input @error('no_telepon') is-invalid @enderror"
                               value="{{ old('no_telepon') }}">
                        @error('no_telepon') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fKec">Kecamatan <small>(opsional)</small></label>
                        <input type="text" name="kecamatan" id="fKec" maxlength="255"
                               class="f-input @error('kecamatan') is-invalid @enderror"
                               value="{{ old('kecamatan') }}">
                        @error('kecamatan') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group f-full">
                        <label for="fAlamat">Alamat</label>
                        <textarea name="alamat" id="fAlamat" maxlength="500"
                                  class="f-input @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                </div>

            </div>

            <div class="m-foot">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


{{-- ====== MODAL: Detail ====== --}}
<div class="m-overlay" id="modalDetail">
    <div class="m-box">
        <div class="m-head">
            <h3 id="dNamaJudul">Detail Pasien</h3>
            <p id="dSub">Data identitas dan riwayat kunjungan</p>
        </div>

        <div class="m-body">
            <div class="d-grid">
                <div class="d-item"><small>No. RM</small><span id="dRm"></span></div>
                <div class="d-item"><small>NIK</small><span id="dNik"></span></div>
                <div class="d-item"><small>Jenis Kelamin</small><span id="dJk"></span></div>
                <div class="d-item"><small>Tanggal Lahir / Usia</small><span id="dTgl"></span></div>
                <div class="d-item"><small>No. Telepon</small><span id="dTelp"></span></div>
                <div class="d-item"><small>Kecamatan</small><span id="dKec"></span></div>
                <div class="d-item d-full"><small>Alamat</small><span id="dAlamat"></span></div>
            </div>

            <div class="d-sub">Riwayat Kunjungan <span style="font-weight:400;color:#9ca3af">(5 terakhir)</span></div>
            <div id="dRiwayat"></div>
        </div>

        <div class="m-foot">
            <button type="button" class="btn-m btn-m-cancel" data-tutup>Tutup</button>
        </div>
    </div>
</div>


<script>
(function () {
    const BASE = @json(url('data-pasien'));
    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    /* ---------- Modal helper ---------- */
    const buka  = el => { el.classList.add('show'); };
    const tutup = el => { el.classList.remove('show'); };

    $$('.m-overlay').forEach(ov => {
        // Form edit hanya ditutup lewat tombol Batal / Esc (supaya isian tidak hilang tak sengaja)
        if (ov.id !== 'modalForm') {
            ov.addEventListener('mousedown', e => { if (e.target === ov) tutup(ov); });
        }
        $$('[data-tutup]', ov).forEach(b => b.addEventListener('click', () => tutup(ov)));
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') $$('.m-overlay.show').forEach(tutup);
    });

    /* ---------- Cari (langsung, tanpa reload halaman) ---------- */
    const formCari = $('#formCari'), cari = $('#cariPasien'), clearBtn = $('#clearCari');
    let timer = null, ctl = null;

    async function muat() {
        const q = cari.value.trim();
        const url = new URL(formCari.action, location.origin);
        if (q) url.searchParams.set('q', q);

        clearBtn.classList.toggle('show', q.length > 0);

        if (ctl) ctl.abort();
        ctl = new AbortController();

        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: ctl.signal });
            if (!res.ok) throw new Error(res.status);
            const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
            const baru = doc.querySelector('#areaTabel');
            if (baru) {
                $('#areaTabel').innerHTML = baru.innerHTML;
                history.replaceState(null, '', url);
            }
        } catch (err) {
            if (err.name !== 'AbortError') formCari.submit();   // cadangan: muat ulang biasa
        }
    }

    cari.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(muat, 350); });
    formCari.addEventListener('submit', e => { e.preventDefault(); clearTimeout(timer); muat(); });
    clearBtn.addEventListener('click', () => { cari.value = ''; muat(); cari.focus(); });

    /* ---------- Tombol di dalam tabel (delegasi, karena tabel diganti saat mencari) ---------- */
    const modalForm = $('#modalForm'), form = $('#formPasien');

    function isiForm(d) {
        $$('.is-invalid', form).forEach(el => el.classList.remove('is-invalid'));
        $$('.f-error, .m-alert', modalForm).forEach(el => el.remove());
        form.action = BASE + '/' + d.id;
        $('#fId').value = d.id;
        $('#fRm').value = d.no_rm;
        $('#fNik').value = d.nik;
        $('#fNama').value = d.nama;
        $('#fJk').value = d.jk;
        $('#fTgl').value = d.tgl;
        $('#fTelp').value = d.telp;
        $('#fKec').value = d.kecamatan;
        $('#fAlamat').value = d.alamat;
    }

    function isiDetail(i) {
        $('#dNamaJudul').textContent = i.nama;
        $('#dRm').textContent = i.no_rm;
        $('#dNik').textContent = i.nik;
        $('#dJk').textContent = i.jenis_kelamin;
        $('#dTgl').textContent = i.tanggal_lahir + ' (' + i.usia + ')';
        $('#dTelp').textContent = i.no_telepon;
        $('#dKec').textContent = i.kecamatan;
        $('#dAlamat').textContent = i.alamat;

        $('#dRiwayat').innerHTML = i.riwayat.length
            ? '<table class="d-riwayat"><thead><tr><th>Tanggal</th><th>Poli</th><th>Dokter</th><th>Status</th></tr></thead><tbody>'
              + i.riwayat.map(r => '<tr><td>' + esc(r.tanggal) + '</td><td>' + esc(r.poli) + '</td><td>' + esc(r.dokter) + '</td><td>' + esc(r.status) + '</td></tr>').join('')
              + '</tbody></table>'
            : '<div class="d-kosong">Belum ada riwayat kunjungan.</div>';
    }

    document.addEventListener('click', e => {
        const edit = e.target.closest('.btn-edit');
        if (edit) {
            isiForm(edit.dataset);
            buka(modalForm);
            $('#fNama').focus();
            return;
        }

        const det = e.target.closest('.btn-detail');
        if (det) {
            isiDetail(JSON.parse(det.dataset.info));
            buka($('#modalDetail'));
        }
    });

    // Kalau validasi gagal, modal edit dibuka lagi: isi kolom No. RM dari baris yang bersangkutan
    @if ($errors->any() && $idEdit)
        (function () {
            const btn = $$('.btn-edit').find(b => b.dataset.id === @json((string) $idEdit));
            $('#fRm').value = btn ? btn.dataset.no_rm : '';
        })();
    @endif

    /* ---------- Cegah double submit ---------- */
    form.addEventListener('submit', () => {
        const s = form.querySelector('button[type=submit]');
        if (s) setTimeout(() => s.disabled = true, 0);
    });
})();
</script>

@endsection
