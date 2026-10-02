@extends('layouts.app')

@section('title', 'Data Diagnosa')

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

    /* ===== Toolbar ===== */
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
    .u-search input:focus { border-color: #b40000; box-shadow: 0 0 0 3px rgba(180, 0, 0, .12); }

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

    .u-tools { display: flex; align-items: center; gap: 10px; margin-bottom: -8px; }

    .btn-top {
        height: 28px;
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0 14px;
        border-radius: 4px;
        font-family: inherit; font-size: 11px; font-weight: 600;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-top:active { transform: scale(.97); }

    .btn-import { background: #fff; border: 1px solid #555; color: #111; font-weight: 500; }
    .btn-import:hover { background: #f3f4f6; }

    .btn-tambah { border: none; background: #14089a; color: #fff; }
    .btn-tambah:hover { background: #0d0670; }

    /* ===== Card + tabel ===== */
    .u-card {
        background: #fff;
        border: 1px solid #3a3a3a;
        border-radius: 12px;
        overflow: hidden;
    }
    .u-wrap { width: 100%; overflow-x: auto; }
    .u-table { width: 100%; border-collapse: collapse; min-width: 640px; }

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
    .u-table tbody tr:hover td { background: #fdf6f6; }
    .u-table tbody tr:last-child td { border-bottom: none; }

    .col-kode { width: 170px; }
    .col-c { text-align: center; width: 190px; }
    .u-table thead th.col-c { text-align: center; }

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
        transition: background .12s, transform .1s;
    }
    .btn-act:active { transform: scale(.94); }
    .btn-edit { width: 46px; background: #f4f4f5; border-color: #6b7280; color: #111; }
    .btn-edit:hover { background: #e5e7eb; }
    .btn-del  { width: 32px; border-radius: 4px; background: #c40000; color: #fff; }
    .btn-del:hover { background: #a00000; }

    /* ===== Empty ===== */
    .u-empty { padding: 40px 10px; text-align: center; color: #6b7280; font-size: 12px; }
    .u-empty i { display: block; font-size: 30px; color: #d1d5db; margin-bottom: 8px; }

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
    .btn-m-save { background: #16a34a; color: #fff; }
    .btn-m-save:hover { background: #128a3e; }
    .btn-m-save:disabled { opacity: .7; cursor: wait; }
    .btn-m-danger { background: #d10000; color: #fff; }
    .btn-m-danger:hover { background: #a80000; }

    /* modal form tambah/edit & import */
    #modalForm .m-box, #modalImport .m-box { max-width: 760px; border-radius: 24px; }
    #modalImport .m-box { max-width: 620px; }

    #modalForm .m-head, #modalImport .m-head {
        padding: 22px 32px 18px;
        background: linear-gradient(90deg, #b81f1f 0%, #6e0f0f 55%, #470000 100%);
        color: #fff;
        border-radius: 24px 24px 0 0;
    }
    .m-head h3 { font-size: 19px; font-weight: 600; line-height: 1.2; }
    .m-head p  { margin-top: 3px; font-size: 10px; font-weight: 500; opacity: .95; }

    #modalForm .m-body, #modalImport .m-body { padding: 30px 40px 4px; }
    #modalForm .f-grid { display: grid; grid-template-columns: 1fr 2fr; column-gap: 40px; row-gap: 16px; }
    #modalForm .m-foot, #modalImport .m-foot { gap: 22px; padding: 30px 40px 28px; }

    /* import */
    .imp-info {
        margin-bottom: 18px; padding: 12px 14px;
        border-radius: 10px; background: #f8f4f4; border: 1px solid #ecdcdc;
        font-size: 11.5px; line-height: 1.7; color: #4b5563;
    }
    .imp-info b { color: #111; }
    .imp-info code { padding: 1px 6px; border-radius: 4px; background: #fff; border: 1px solid #e5e7eb; font-size: 11px; }

    .imp-file {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 16px;
        border: 1.5px dashed #9ca3af; border-radius: 10px;
        cursor: pointer; transition: border-color .15s, background .15s;
    }
    .imp-file:hover { border-color: #b40000; background: #fdf6f6; }
    .imp-file.is-invalid { border-color: #d10000; }
    .imp-file i { font-size: 24px; color: #157347; }
    .imp-file span { font-size: 12px; color: #374151; word-break: break-all; }
    .imp-file input { display: none; }

    /* konfirmasi hapus */
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

    @media (max-width: 640px) {
        .content { padding: 18px 14px; }
        .u-search { max-width: none; }
        .u-tools { margin-bottom: 0; }
        #modalForm .f-grid { grid-template-columns: 1fr; row-gap: 12px; }
        #modalForm .m-body, #modalImport .m-body { padding: 22px 20px 4px; }
        #modalForm .m-foot, #modalImport .m-foot { padding: 22px 20px; gap: 12px; }
    }
</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="DATA DIAGNOSA" icon="house-fill" />

        <x-popup-alert />

        <section class="content">

            {{-- ====== Toolbar ====== --}}
            <div class="u-toolbar">

                <form class="u-search" id="formCari" method="GET" action="{{ route('diagnosa.index') }}" role="search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" id="cariDiagnosa" value="{{ $q }}" placeholder="Cari Kode ICD/Nama Penyakit" autocomplete="off">
                    <button type="button" class="u-clear {{ $q !== '' ? 'show' : '' }}" id="clearCari" aria-label="Hapus pencarian">&times;</button>
                </form>

                <div class="u-tools">
                    <button type="button" class="btn-top btn-import" id="btnImport">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Import Excel
                    </button>
                    <button type="button" class="btn-top btn-tambah" id="btnTambah">
                        <i class="bi bi-plus-lg"></i> Tambah Diagnosis
                    </button>
                </div>

            </div>

            {{-- ====== Tabel (area ini yang diperbarui saat mencari) ====== --}}
            <div id="areaTabel">

                <div class="u-card">
                    <div class="u-wrap">
                        <table class="u-table">
                            <thead>
                                <tr>
                                    <th class="col-kode">Kode ICD</th>
                                    <th>Nama Penyakit</th>
                                    <th class="col-c">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diagnosas as $d)
                                    <tr>
                                        <td>{{ $d->code }}</td>
                                        <td>{{ $d->title }}</td>
                                        <td>
                                            <div class="act">
                                                <button type="button" class="btn-act btn-edit" title="Edit"
                                                    data-id="{{ $d->id }}"
                                                    data-kode="{{ $d->code }}"
                                                    data-nama="{{ $d->title }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn-act btn-del" title="Hapus"
                                                    data-id="{{ $d->id }}"
                                                    data-kode="{{ $d->code }}"
                                                    data-nama="{{ $d->title }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($diagnosas->isEmpty())
                            <div class="u-empty">
                                <i class="bi bi-clipboard2-pulse"></i>
                                @if ($q !== '')
                                    Tidak ada diagnosis yang cocok dengan "{{ $q }}".
                                @else
                                    Belum ada data diagnosis. Gunakan <b>Import Excel</b> atau <b>Tambah Diagnosis</b>.
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @if ($diagnosas->total() > 0)
                    @php
                        $now  = $diagnosas->currentPage();
                        $last = $diagnosas->lastPage();
                        $dari = max(1, $now - 2);
                        $ke   = min($last, $now + 2);
                    @endphp
                    <div class="pager-bar">
                        <div>Menampilkan {{ $diagnosas->firstItem() }}–{{ $diagnosas->lastItem() }} dari {{ number_format($diagnosas->total(), 0, ',', '.') }} data</div>

                        @if ($last > 1)
                            <nav class="pager" aria-label="Halaman">
                                @if ($diagnosas->onFirstPage())
                                    <span class="off"><i class="bi bi-chevron-left"></i></span>
                                @else
                                    <a href="{{ $diagnosas->url($now - 1) }}" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></a>
                                @endif

                                @if ($dari > 1)
                                    <a href="{{ $diagnosas->url(1) }}">1</a>
                                    @if ($dari > 2) <span class="dots">…</span> @endif
                                @endif

                                @for ($i = $dari; $i <= $ke; $i++)
                                    @if ($i === $now)
                                        <span class="on">{{ $i }}</span>
                                    @else
                                        <a href="{{ $diagnosas->url($i) }}">{{ $i }}</a>
                                    @endif
                                @endfor

                                @if ($ke < $last)
                                    @if ($ke < $last - 1) <span class="dots">…</span> @endif
                                    <a href="{{ $diagnosas->url($last) }}">{{ $last }}</a>
                                @endif

                                @if ($diagnosas->hasMorePages())
                                    <a href="{{ $diagnosas->url($now + 1) }}" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></a>
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


{{-- ====== MODAL: Tambah / Edit ====== --}}
@php
    $mode   = old('_mode');   // 'tambah' | 'edit' (dipakai kalau validasi gagal)
    $idEdit = old('_id');
    $errImport = $errors->getBag('import');
@endphp

<div class="m-overlay {{ $errors->any() ? 'show' : '' }}" id="modalForm">
    <div class="m-box">
        <div class="m-head">
            <h3 id="formJudul">{{ $mode === 'edit' ? 'Edit Diagnosis' : 'Tambah Diagnosis' }}</h3>
            <p id="formSub">{{ $mode === 'edit' ? 'Perbarui data diagnosa/penyakit SIP-REK' : 'Kelola data diagnosa/penyakit SIP-REK' }}</p>
        </div>

        <form method="POST" id="formDiagnosa"
              action="{{ $mode === 'edit' && $idEdit ? route('diagnosa.update', $idEdit) : route('diagnosa.store') }}"
              autocomplete="off">
            @csrf
            <input type="hidden" name="_method" id="fMethod" value="{{ $mode === 'edit' ? 'PUT' : 'POST' }}">
            <input type="hidden" name="_mode" id="fMode" value="{{ $mode ?? 'tambah' }}">
            <input type="hidden" name="_id" id="fId" value="{{ $idEdit }}">

            <div class="m-body">

                @if ($errors->any())
                    <div class="m-alert">Periksa kembali isian yang ditandai merah.</div>
                @endif

                <div class="f-grid">

                    <div class="f-group">
                        <label for="fKode">Kode ICD</label>
                        <input type="text" name="code" id="fKode" class="f-input @error('code') is-invalid @enderror"
                               value="{{ old('code') }}" maxlength="20" placeholder="Contoh: J06.9" style="text-transform:uppercase">
                        @error('code') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fNama">Nama Penyakit</label>
                        <input type="text" name="title" id="fNama" class="f-input @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" maxlength="500" placeholder="Contoh: Acute upper respiratory infection, unspecified">
                        @error('title') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                </div>

            </div>

            <div class="m-foot">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-save">Simpan Diagnosis</button>
            </div>
        </form>
    </div>
</div>


{{-- ====== MODAL: Import Excel ====== --}}
<div class="m-overlay {{ $errImport->any() ? 'show' : '' }}" id="modalImport">
    <div class="m-box">
        <div class="m-head">
            <h3>Import Excel</h3>
            <p>Unggah daftar diagnosa/penyakit dari file Excel atau CSV</p>
        </div>

        <form method="POST" action="{{ route('diagnosa.import') }}" enctype="multipart/form-data" id="formImport">
            @csrf
            <div class="m-body">

                <div class="imp-info">
                    Susun file dengan 2 kolom:<br>
                    <b>Kolom A</b>: Kode ICD (contoh <code>J06.9</code>) &nbsp;•&nbsp; <b>Kolom B</b>: Nama Penyakit<br>
                    Baris judul boleh ada. Kode yang sudah ada akan <b>diperbarui</b> namanya, yang belum ada <b>ditambahkan</b>.
                    Format: <b>.xlsx</b> atau <b>.csv</b>, maksimal 5 MB.
                </div>

                <label class="imp-file {{ $errImport->has('file') ? 'is-invalid' : '' }}" id="impFile">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    <span id="impNama">Klik untuk memilih file…</span>
                    <input type="file" name="file" id="impInput" accept=".xlsx,.csv">
                </label>
                @if ($errImport->has('file'))
                    <div class="f-error">{{ $errImport->first('file') }}</div>
                @endif

            </div>

            <div class="m-foot">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-save" id="btnImpOk">Import</button>
            </div>
        </form>
    </div>
</div>


{{-- ====== MODAL: Konfirmasi Hapus ====== --}}
<div class="m-overlay" id="modalHapus">
    <div class="m-box">
        <form method="POST" id="formHapus">
            @csrf
            @method('DELETE')
            <div class="m-confirm">
                <div class="ic"><i class="bi bi-trash3-fill"></i></div>
                <h4>Hapus diagnosis ini?</h4>
                <p><b id="hKode"></b> – <span id="hNama"></span><br>akan dihapus permanen dan tidak bisa dikembalikan.</p>
            </div>
            <div class="m-foot" style="justify-content:center">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-danger">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>


<script>
(function () {
    const BASE = @json(url('data-diagnosa'));
    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];

    /* ---------- Modal helper ---------- */
    const buka  = el => { el.classList.add('show'); };
    const tutup = el => { el.classList.remove('show'); };

    $$('.m-overlay').forEach(ov => {
        // Form tambah/edit & import hanya ditutup lewat Batal / Esc (isian tidak hilang tak sengaja)
        if (ov.id === 'modalHapus') {
            ov.addEventListener('mousedown', e => { if (e.target === ov) tutup(ov); });
        }
        $$('[data-tutup]', ov).forEach(b => b.addEventListener('click', () => tutup(ov)));
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') $$('.m-overlay.show').forEach(tutup);
    });

    /* ---------- Cari (langsung, tanpa reload halaman) ---------- */
    const formCari = $('#formCari'), cari = $('#cariDiagnosa'), clearBtn = $('#clearCari');
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

    /* ---------- Form tambah / edit ---------- */
    const modalForm = $('#modalForm'), form = $('#formDiagnosa');

    function resetForm() {
        ['fKode', 'fNama'].forEach(id => $('#' + id).value = '');
        $$('.is-invalid', form).forEach(el => el.classList.remove('is-invalid'));
        $$('.f-error, .m-alert', modalForm).forEach(el => el.remove());
    }

    $('#btnTambah').addEventListener('click', () => {
        resetForm();
        $('#formJudul').textContent = 'Tambah Diagnosis';
        $('#formSub').textContent = 'Kelola data diagnosa/penyakit SIP-REK';
        form.action = @json(route('diagnosa.store'));
        $('#fMethod').value = 'POST';
        $('#fMode').value = 'tambah';
        $('#fId').value = '';
        buka(modalForm);
        $('#fKode').focus();
    });

    // Tombol edit & hapus berada di area yang diganti saat mencari -> pakai event delegation
    document.addEventListener('click', e => {
        const edit = e.target.closest('.btn-edit');
        if (edit) {
            const d = edit.dataset;
            resetForm();
            $('#formJudul').textContent = 'Edit Diagnosis';
            $('#formSub').textContent = 'Perbarui data diagnosa/penyakit SIP-REK';
            form.action = BASE + '/' + d.id;
            $('#fMethod').value = 'PUT';
            $('#fMode').value = 'edit';
            $('#fId').value = d.id;
            $('#fKode').value = d.kode;
            $('#fNama').value = d.nama;
            buka(modalForm);
            $('#fNama').focus();
            return;
        }

        const del = e.target.closest('.btn-del');
        if (del) {
            $('#formHapus').action = BASE + '/' + del.dataset.id;
            $('#hKode').textContent = del.dataset.kode;
            $('#hNama').textContent = del.dataset.nama;
            buka($('#modalHapus'));
        }
    });

    /* ---------- Import ---------- */
    const modalImport = $('#modalImport'), impInput = $('#impInput');

    $('#btnImport').addEventListener('click', () => {
        impInput.value = '';
        $('#impNama').textContent = 'Klik untuk memilih file…';
        $$('.f-error', modalImport).forEach(el => el.remove());
        $('#impFile').classList.remove('is-invalid');
        buka(modalImport);
    });

    impInput.addEventListener('change', () => {
        $('#impNama').textContent = impInput.files.length ? impInput.files[0].name : 'Klik untuk memilih file…';
    });

    $('#formImport').addEventListener('submit', () => {
        setTimeout(() => { const b = $('#btnImpOk'); b.disabled = true; b.textContent = 'Mengimpor…'; }, 0);
    });

    /* ---------- Cegah double submit ---------- */
    [form, $('#formHapus')].forEach(f => f.addEventListener('submit', () => {
        const s = f.querySelector('button[type=submit]');
        if (s) setTimeout(() => s.disabled = true, 0);
    }));
})();
</script>

@endsection
