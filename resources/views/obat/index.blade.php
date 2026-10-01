@extends('layouts.app')

@section('title', 'Data Obat')

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

    .u-table { width: 100%; border-collapse: collapse; min-width: 860px; }

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

    .btn-edit { width: 46px; background: #f4f4f5; border-color: #6b7280; color: #111; }
    .btn-edit:hover { background: #e5e7eb; }

    .btn-del  { width: 32px; border-radius: 4px; background: #c40000; color: #fff; }
    .btn-del:hover { background: #a00000; }

    /* ===== Empty state ===== */
    .u-empty { display: none; padding: 40px 10px; text-align: center; color: #6b7280; font-size: 12px; }
    .u-empty i { display: block; font-size: 30px; color: #d1d5db; margin-bottom: 8px; }
    .u-empty.show { display: block; }

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


    /* ===== Khusus Data Obat ===== */
    .u-table td.col-nama { font-weight: 500; }
    .u-table td.col-dosis { white-space: nowrap; }
    .dosis-kosong { color: #9ca3af; }

    .col-stok { width: 150px; }
    .stok-num { display: block; margin-bottom: 3px; font-size: 12px; font-weight: 500; line-height: 1; }
    .stok-bar { width: 66px; height: 4px; margin: 0 auto; border-radius: 4px; background: #8a8a8a; overflow: hidden; }
    .stok-bar span { display: block; height: 100%; border-radius: 4px; }
    .stok-aman    .stok-bar span { background: #2ecc40; }
    .stok-menipis .stok-bar span { background: #e69500; }
    .stok-habis   .stok-bar span { background: transparent; }
    .stok-menipis .stok-num { color: #b36b00; }
    .stok-habis   .stok-num { color: #6b7280; }

    .stok-tag {
        display: inline-block; margin-left: 6px; padding: 1px 7px; border-radius: 8px;
        font-size: 9.5px; font-weight: 600; vertical-align: 1px;
    }
    .stok-menipis .stok-tag { background: #fff1d6; color: #9a5b00; }
    .stok-habis   .stok-tag { background: #e5e7eb; color: #4b5563; }

    .col-harga { white-space: nowrap; }

    .f-hint { margin-top: 5px; font-size: 10.5px; color: #9ca3af; line-height: 1.4; }

    .f-prefix { position: relative; }
    .f-prefix > span {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        font-size: 12.5px; color: #6b7280; pointer-events: none;
    }
    .f-prefix .f-input { padding-left: 38px; }

    @media (max-width: 640px) {
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
        <x-topbar title="DATA OBAT" icon="house-fill" />

        <x-popup-alert />

        <section class="content">

            {{-- ====== Toolbar ====== --}}
            <div class="u-toolbar">

                <div class="u-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="cariObat" placeholder="Cari Nama atau Kode Obat" autocomplete="off">
                    <button type="button" class="u-clear" id="clearCari" aria-label="Hapus pencarian">&times;</button>
                </div>

                <button type="button" class="btn-tambah" id="btnTambah">
                    <i class="bi bi-plus-lg"></i> Tambah Obat
                </button>

            </div>

            {{-- ====== Tabel ====== --}}
            <div class="u-card">
                <div class="u-wrap">
                    <table class="u-table">
                        <thead>
                            <tr>
                                <th class="col-c">Kode</th>
                                <th>Nama Obat</th>
                                <th class="col-c">Dosis</th>
                                <th class="col-c">Sediaan</th>
                                <th class="col-c">Stok</th>
                                <th class="col-c">Harga</th>
                                <th class="col-c">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarObat as $o)
                                <tr class="obat-row"
                                    data-search="{{ strtolower($o->kode_obat . ' ' . $o->nama_obat . ' ' . $o->dosis) }}">

                                    <td class="col-c">{{ $o->kode_obat }}</td>
                                    <td class="col-nama">{{ $o->nama_obat }}</td>
                                    <td class="col-c col-dosis">
                                        @if ($o->dosis) {{ $o->dosis }} @else <span class="dosis-kosong">-</span> @endif
                                    </td>
                                    <td class="col-c">{{ $o->sediaan }}</td>
                                    <td class="col-c col-stok stok-{{ $o->status_stok }}">
                                        <span class="stok-num">
                                            {{ number_format($o->stok, 0, ',', '.') }}
                                            @if ($o->status_stok === 'menipis') <span class="stok-tag">Menipis</span> @endif
                                            @if ($o->status_stok === 'habis') <span class="stok-tag">Habis</span> @endif
                                        </span>
                                        <div class="stok-bar"><span style="width: {{ $o->persen_stok }}%"></span></div>
                                    </td>
                                    <td class="col-c col-harga">
                                        @if ($o->harga !== null) Rp {{ number_format($o->harga, 0, ',', '.') }} @else <span class="dosis-kosong">-</span> @endif
                                    </td>
                                    <td>
                                        <div class="act">
                                            <button type="button" class="btn-act btn-edit" title="Edit"
                                                data-id="{{ $o->id }}"
                                                data-kode="{{ $o->kode_obat }}"
                                                data-nama="{{ $o->nama_obat }}"
                                                data-dosis="{{ $o->dosis }}"
                                                data-sediaan="{{ $o->sediaan }}"
                                                data-stok="{{ $o->stok }}"
                                                data-harga="{{ $o->harga }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button type="button" class="btn-act btn-del" title="Hapus"
                                                data-id="{{ $o->id }}"
                                                data-nama="{{ trim($o->nama_obat . ' ' . $o->dosis) }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="u-empty {{ $daftarObat->isEmpty() ? 'show' : '' }}" id="obatEmpty">
                        <i class="bi bi-capsule"></i>
                        <span id="emptyText">{{ $daftarObat->isEmpty() ? 'Belum ada data obat.' : 'Tidak ada obat yang cocok.' }}</span>
                    </div>
                </div>
            </div>

        </section>
    </main>

</div>


{{-- ====== MODAL: Tambah / Edit ====== --}}
@php
    $mode   = old('_mode');   // 'tambah' | 'edit' (dipakai kalau validasi gagal)
    $idEdit = old('_id');
@endphp

<div class="m-overlay {{ $errors->any() ? 'show' : '' }}" id="modalForm">
    <div class="m-box">
        <div class="m-head">
            <h3 id="formJudul">{{ $mode === 'edit' ? 'Edit Obat' : 'Tambah Obat' }}</h3>
            <p id="formSub">{{ $mode === 'edit' ? 'Perbarui data obat SIP-REK' : 'Kelola data obat SIP-REK' }}</p>
        </div>

        <form method="POST" id="formObat"
              action="{{ $mode === 'edit' && $idEdit ? route('obat.update', $idEdit) : route('obat.store') }}"
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
                        <label for="fKode">Kode Obat <small>(otomatis)</small></label>
                        <input type="text" id="fKode" class="f-input f-readonly" readonly tabindex="-1"
                               value="{{ $mode === 'edit' && $idEdit ? optional($daftarObat->firstWhere('id', $idEdit))->kode_obat : $kodeBerikut }}">
                    </div>

                    <div class="f-group">
                        <label for="fSediaan">Sediaan</label>
                        <input type="text" name="sediaan" id="fSediaan" list="listSediaan"
                               class="f-input @error('sediaan') is-invalid @enderror"
                               value="{{ old('sediaan') }}" maxlength="50" placeholder="Contoh: Tablet">
                        <datalist id="listSediaan">
                            @foreach (['Tablet', 'Tablet Kunyah', 'Kapsul', 'Sirup', 'Sachet', 'Salep', 'Tetes', 'Injeksi', 'Inhaler', 'Suppositoria'] as $s)
                                <option value="{{ $s }}">
                            @endforeach
                        </datalist>
                        @error('sediaan') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fNama">Nama Obat</label>
                        <input type="text" name="nama_obat" id="fNama"
                               class="f-input @error('nama_obat') is-invalid @enderror"
                               value="{{ old('nama_obat') }}" maxlength="150" placeholder="Contoh: Paracetamol">
                        @error('nama_obat')
                            <div class="f-error">{{ $message }}</div>
                        @else
                            <div class="f-hint">Tulis nama saja, tanpa dosis.</div>
                        @enderror
                    </div>

                    <div class="f-group">
                        <label for="fDosis">Dosis <small>(opsional)</small></label>
                        <input type="text" name="dosis" id="fDosis"
                               class="f-input @error('dosis') is-invalid @enderror"
                               value="{{ old('dosis') }}" maxlength="50" placeholder="Contoh: 500 mg">
                        @error('dosis')
                            <div class="f-error">{{ $message }}</div>
                        @else
                            <div class="f-hint">Kekuatan obat, mis. 500 mg atau 120 mg/5 ml.</div>
                        @enderror
                    </div>

                    <div class="f-group">
                        <label for="fStok">Stok</label>
                        <input type="number" name="stok" id="fStok" min="0" step="1"
                               class="f-input @error('stok') is-invalid @enderror"
                               value="{{ old('stok', 0) }}" placeholder="0">
                        @error('stok') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fHarga">Harga <small>(per sediaan, opsional)</small></label>
                        <div class="f-prefix">
                            <span>Rp</span>
                            <input type="number" name="harga" id="fHarga" min="0" step="1"
                                   class="f-input @error('harga') is-invalid @enderror"
                                   value="{{ old('harga') }}" placeholder="0">
                        </div>
                        @error('harga') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                </div>

            </div>

            <div class="m-foot">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-save">Simpan Obat</button>
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
                <h4>Hapus obat ini?</h4>
                <p><b id="hNama"></b> akan dihapus permanen dan tidak bisa dikembalikan.</p>
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
    const BASE = @json(url('data-obat'));
    const KODE_BERIKUT = @json($kodeBerikut);
    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];

    /* ---------- Modal helper ---------- */
    const buka  = el => { el.classList.add('show'); };
    const tutup = el => { el.classList.remove('show'); };

    $$('.m-overlay').forEach(ov => {
        // Form tambah/edit hanya ditutup lewat tombol Batal / Esc (supaya isian tidak hilang tak sengaja)
        if (ov.id !== 'modalForm') {
            ov.addEventListener('mousedown', e => { if (e.target === ov) tutup(ov); });
        }
        $$('[data-tutup]', ov).forEach(b => b.addEventListener('click', () => tutup(ov)));
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') $$('.m-overlay.show').forEach(tutup);
    });

    /* ---------- Cari (langsung, tanpa reload) ---------- */
    const cari = $('#cariObat'), clearBtn = $('#clearCari');
    const rows = $$('.obat-row');

    function terapkanFilter() {
        const q = cari.value.trim().toLowerCase();
        let n = 0;

        rows.forEach(tr => {
            const cocok = !q || tr.dataset.search.includes(q);
            tr.style.display = cocok ? '' : 'none';
            if (cocok) n++;
        });

        clearBtn.classList.toggle('show', q.length > 0);

        if (rows.length) {
            $('#obatEmpty').classList.toggle('show', n === 0);
            $('#emptyText').textContent = 'Tidak ada obat yang cocok.';
        }
    }
    cari.addEventListener('input', terapkanFilter);
    clearBtn.addEventListener('click', () => { cari.value = ''; terapkanFilter(); cari.focus(); });

    /* ---------- Form tambah / edit ---------- */
    const modalForm = $('#modalForm'), form = $('#formObat');

    function resetForm() {
        ['fNama', 'fDosis', 'fSediaan', 'fHarga'].forEach(id => { $('#' + id).value = ''; });
        $('#fStok').value = 0;
        $$('.is-invalid', form).forEach(el => el.classList.remove('is-invalid'));
        $$('.f-error, .m-alert', modalForm).forEach(el => el.remove());
    }

    $('#btnTambah').addEventListener('click', () => {
        resetForm();
        $('#formJudul').textContent = 'Tambah Obat';
        $('#formSub').textContent = 'Kelola data obat SIP-REK';
        form.action = @json(route('obat.store'));
        $('#fMethod').value = 'POST';
        $('#fMode').value = 'tambah';
        $('#fId').value = '';
        $('#fKode').value = KODE_BERIKUT;
        buka(modalForm);
        $('#fNama').focus();
    });

    $$('.btn-edit').forEach(btn => btn.addEventListener('click', () => {
        const d = btn.dataset;
        resetForm();
        $('#formJudul').textContent = 'Edit Obat';
        $('#formSub').textContent = 'Perbarui data obat SIP-REK';
        form.action = BASE + '/' + d.id;
        $('#fMethod').value = 'PUT';
        $('#fMode').value = 'edit';
        $('#fId').value = d.id;
        $('#fKode').value = d.kode;
        $('#fNama').value = d.nama;
        $('#fDosis').value = d.dosis;
        $('#fSediaan').value = d.sediaan;
        $('#fStok').value = d.stok;
        $('#fHarga').value = d.harga;
        buka(modalForm);
        $('#fNama').focus();
    }));

    /* ---------- Kalau dosis terlanjur diketik di nama, pindahkan ke kolom Dosis ---------- */
    const POLA_DOSIS = /^(.*?)\s+(\d+(?:[.,]\d+)?\s*(?:mg|mcg|g|ml|iu)(?:\s*\/\s*\d+(?:[.,]\d+)?\s*(?:mg|mcg|g|ml))?)\s*$/i;

    $('#fNama').addEventListener('blur', () => {
        const nama = $('#fNama'), dosis = $('#fDosis');
        const m = nama.value.trim().match(POLA_DOSIS);
        if (m && m[1].trim() && !dosis.value.trim()) {
            nama.value = m[1].trim();
            dosis.value = m[2].trim().replace(/(\d)\s*([a-z])/gi, '$1 $2');
        }
    });

    /* ---------- Hapus ---------- */
    $$('.btn-del').forEach(btn => btn.addEventListener('click', () => {
        $('#formHapus').action = BASE + '/' + btn.dataset.id;
        $('#hNama').textContent = btn.dataset.nama;
        buka($('#modalHapus'));
    }));

    /* ---------- Cegah double submit ---------- */
    $$('form').forEach(f => f.addEventListener('submit', () => {
        const s = f.querySelector('button[type=submit]');
        if (s) setTimeout(() => s.disabled = true, 0);
    }));
})();
</script>

@endsection
