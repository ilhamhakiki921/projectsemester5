@extends('layouts.app')

@section('title', 'Data User')

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

    /* garis bawah topbar sedikit lebih tebal, seperti desain */
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
        border: 1px solid #cfd4dc;
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

    .u-tools { display: flex; align-items: center; gap: 10px; }

    .u-filter {
        height: 40px;
        padding: 0 12px;
        border: 1px solid #cfd4dc;
        border-radius: 10px;
        background: #fff;
        font-family: inherit;
        font-size: 11.5px;
        color: #374151;
        outline: none;
        cursor: pointer;
    }
    .u-filter:focus { border-color: #b40000; }

    .btn-tambah {
        height: 28px;
        display: inline-flex; align-items: center; gap: 5px;
        padding: 0 14px;
        margin-bottom: -8px;
        border: none; border-radius: 4px;
        background: #2a1fb8;
        color: #fff;
        font-family: inherit; font-size: 11px; font-weight: 600;
        cursor: pointer;
        transition: background .15s, transform .1s;
    }
    .btn-tambah:hover { background: #1f1791; }
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
        background: linear-gradient(90deg, #7a0606 0%, #a31313 100%);
        color: #fff;
        font-size: 12px; font-weight: 600;
        text-align: left;
        white-space: nowrap;
    }

    .u-table td {
        padding: 11px 14px;
        font-size: 12px;
        color: #1f2937;
        border-bottom: 1px solid #e2e2e2;
        vertical-align: middle;
    }

    .u-table tbody tr { transition: background .12s; }
    .u-table tbody tr:hover td { background: #fdf6f6; }
    .u-table tbody tr:last-child td { border-bottom: none; }

    .col-no { width: 54px; text-align: center; }
    .u-table thead th.col-no { text-align: center; }

    .u-name { display: flex; align-items: center; gap: 10px; font-weight: 500; }

    .u-avatar {
        width: 30px; height: 30px; flex-shrink: 0;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #fde8e8; color: #b40000;
        font-size: 11px; font-weight: 700;
    }

    .u-username { color: #4b5563; font-family: 'Poppins', sans-serif; }

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
    .act { display: flex; align-items: center; gap: 14px; }

    .btn-act {
        height: 24px;
        display: inline-flex; align-items: center; justify-content: center; gap: 4px;
        padding: 0 10px;
        border-radius: 5px;
        border: 1px solid transparent;
        font-family: inherit; font-size: 11px; font-weight: 500;
        cursor: pointer;
        transition: filter .12s, background .12s, transform .1s;
    }
    .btn-act:active { transform: scale(.94); }

    .btn-view   { width: 46px; background: #7da4ec; color: #0f1f3d; }
    .btn-view:hover { filter: brightness(.92); }

    .btn-edit   { width: 46px; background: #fff; border-color: #6b7280; color: #111; }
    .btn-edit:hover { background: #f3f4f6; }

    .btn-toggle { min-width: 84px; border-radius: 4px; background: #fff; border-color: #6b7280; color: #111; }
    .btn-toggle:hover { background: #f3f4f6; }
    .btn-toggle.is-off { border-color: #157347; color: #157347; }
    .btn-toggle.is-off:hover { background: #ecfaf1; }

    .btn-del    { width: 32px; padding: 0; border-radius: 4px; background: #c40000; color: #fff; }
    .btn-del:hover { background: #a00000; }

    .btn-act:disabled { opacity: .35; cursor: not-allowed; }

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
        width: 100%; max-width: 460px; max-height: 92vh; overflow-y: auto;
        background: #fff; border-radius: 14px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, .3);
        animation: mPop .18s ease;
    }
    .m-box.sm { max-width: 380px; }

    .m-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    background: #d00000;
        color: #fff; border-radius: 14px 14px 0 0;
    }
    .m-head h3 { font-size: 14px; font-weight: 600; }
    .m-close { border: none; background: none; color: #fff; font-size: 20px; cursor: pointer; line-height: 1; opacity: .85; }
    .m-close:hover { opacity: 1; }

    .m-body { padding: 18px 20px 6px; }
    .m-foot { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 20px 18px; }

    .f-group { margin-bottom: 13px; }
    .f-group label { display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; color: #374151; }
    .f-group label small { font-weight: 400; color: #9ca3af; }

    .f-input {
        width: 100%; height: 38px; padding: 0 12px;
        border: 1px solid #cfd4dc; border-radius: 8px;
        font-family: inherit; font-size: 12px; color: #111; background: #fff;
        outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .f-input:focus { border-color: #b40000; box-shadow: 0 0 0 3px rgba(180, 0, 0, .12); }
    .f-input.is-invalid { border-color: #d10000; }

    .f-pass { position: relative; }
    .f-pass .f-input { padding-right: 38px; }
    .f-pass button {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        border: none; background: none; color: #6b7280; cursor: pointer; font-size: 14px;
    }

    .f-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .f-error { margin-top: 4px; font-size: 10.5px; color: #d10000; }

    .m-alert {
        margin-bottom: 12px; padding: 9px 12px;
        border-radius: 8px; background: #fdecec; color: #a80000;
        font-size: 11px;
    }

    .btn-m {
        height: 36px; padding: 0 18px; border-radius: 8px; border: 1px solid transparent;
        font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .btn-m-cancel { background: #fff; border-color: #cfd4dc; color: #374151; }
    .btn-m-cancel:hover { background: #f3f4f6; }
    .btn-m-save { background: #b40000; color: #fff; }
    .btn-m-save:hover { background: #8f0000; }
    .btn-m-danger { background: #d10000; color: #fff; }
    .btn-m-danger:hover { background: #a80000; }

    .m-confirm { padding: 26px 22px 6px; text-align: center; }
    .m-confirm .ic {
        width: 54px; height: 54px; margin: 0 auto 12px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 24px;
    }
    .ic-danger { background: #fdecec; color: #d10000; }
    .ic-warn   { background: #fff4e0; color: #d97706; }
    .m-confirm h4 { font-size: 14px; margin-bottom: 6px; }
    .m-confirm p  { font-size: 11.5px; color: #6b7280; line-height: 1.6; }
    .m-confirm b  { color: #111; }


    /* ===== Modal Tambah / Edit User (khusus #modalForm) ===== */
    #modalForm .m-box { max-width: 880px; border-radius: 24px; }
    #modalForm .m-head {
        display: block;
        position: relative;
        padding: 22px 32px 18px;
        background: linear-gradient(90deg, #b81f1f 0%, #6e0f0f 55%, #470000 100%);
        border-radius: 24px 24px 0 0;
    }
    #modalForm .m-head h3 { font-size: 19px; font-weight: 600; line-height: 1.2; }
    #modalForm .m-head p  { margin-top: 3px; font-size: 10px; font-weight: 500; opacity: .95; }
    #modalForm .m-close { position: absolute; top: 14px; right: 20px; }

    #modalForm .m-body { padding: 30px 40px 4px; }

    #modalForm .f-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 60px;
        row-gap: 14px;
    }
    #modalForm .f-full { grid-column: 1 / -1; }
    #modalForm .f-group { margin-bottom: 0; }

    #modalForm .f-group label { margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #111; }
    #modalForm .f-group label small { font-size: 10.5px; }

    #modalForm .f-input {
        height: 38px; padding: 0 14px;
        border: 1px solid #555; border-radius: 10px;
        font-size: 12.5px;
    }
    #modalForm .f-input:focus { border-color: #b40000; box-shadow: 0 0 0 3px rgba(180, 0, 0, .12); }
    #modalForm .f-input.is-invalid { border-color: #d10000; }

    #modalForm select.f-input {
        appearance: none; -webkit-appearance: none; -moz-appearance: none;
        padding-right: 40px; cursor: pointer;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 16 16' fill='none' stroke='%23111' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M2 5l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 16px center;
    }

    #modalForm .f-input::placeholder { color: #9ca3af; font-weight: 400; }
    #modalForm .f-pass { position: relative; }
    #modalForm .f-pass .f-input { padding-right: 44px; }
    #modalForm .btn-eye {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        width: 26px; height: 26px; padding: 0;
        display: flex; align-items: center; justify-content: center;
        border: none; background: none; color: #374151; font-size: 16px; cursor: pointer;
    }
    #modalForm .btn-eye:hover { color: #b40000; }

    #modalForm .m-foot { gap: 22px; padding: 30px 40px 28px; }
    #modalForm .btn-m { height: 32px; min-width: 130px; padding: 0 18px; border-radius: 6px; font-size: 12px; font-weight: 600; }
    #modalForm .btn-m-cancel { border-color: #555; color: #111; }
    #modalForm .btn-m-save { background: #12c933; color: #fff; }
    #modalForm .btn-m-save:hover { background: #0ea828; }

    @media (max-width: 640px) {
        #modalForm .f-grid { grid-template-columns: 1fr; row-gap: 12px; }
        #modalForm .m-body { padding: 22px 20px 4px; }
        #modalForm .m-foot { padding: 22px 20px 22px; gap: 12px; }
    }

    /* detail */
    .d-top { text-align: center; padding: 6px 0 14px; }
    .d-top .u-avatar { width: 60px; height: 60px; font-size: 20px; margin: 0 auto 8px; }
    .d-top strong { display: block; font-size: 14px; }
    .d-row { display: flex; justify-content: space-between; gap: 12px; padding: 9px 0; border-top: 1px solid #f0f0f0; font-size: 12px; }
    .d-row span:first-child { color: #6b7280; }
    .d-row span:last-child  { font-weight: 500; text-align: right; }

    @media (max-width: 640px) {
        .content { padding: 18px 14px; }
        .u-search { max-width: none; }
        .btn-tambah { margin-bottom: 0; }
        .f-row { grid-template-columns: 1fr; }
    }
</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="DATA USER" icon="house-fill" />

        <x-popup-alert />

        <section class="content">

            {{-- ====== Toolbar ====== --}}
            <div class="u-toolbar">

                <div class="u-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="cariUser" placeholder="Cari nama atau username" autocomplete="off">
                    <button type="button" class="u-clear" id="clearCari" aria-label="Hapus pencarian">&times;</button>
                </div>

                <div class="u-tools">
                    <button type="button" class="btn-tambah" id="btnTambah">
                        <i class="bi bi-plus-lg"></i> Tambah User
                    </button>
                </div>

            </div>

            {{-- ====== Tabel ====== --}}
            <div class="u-card">
                <div class="u-wrap">
                    <table class="u-table">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th>Nama Pengguna</th>
                                <th>Username</th>
                                <th>Role / Jabatan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="userBody">
                            @foreach ($users as $u)
                                @php
                                    $inisial = collect(explode(' ', preg_replace('/^(dr\.|drg\.)\s*/i', '', $u->nama_lengkap)))
                                        ->filter()->take(2)->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))->implode('');
                                    $sendiri = Auth::check() && Auth::id() === $u->id;
                                @endphp
                                <tr class="user-row"
                                    data-search="{{ strtolower($u->nama_lengkap . ' ' . $u->username) }}"
                                    data-role="{{ $u->role }}"
                                    data-status="{{ $u->status }}">

                                    <td class="col-no row-no">{{ $loop->iteration }}</td>

                                    <td>{{ $u->nama_lengkap }}</td>

                                    <td class="u-username">{{ $u->username }}</td>
                                    <td>{{ $roles[$u->role] ?? $u->role }}</td>

                                    <td>
                                        <span class="badge badge-{{ $u->status }}">{{ ucfirst($u->status) }}</span>
                                    </td>

                                    <td>
                                        <div class="act">

                                            <button type="button" class="btn-act btn-view" title="Lihat detail"
                                                data-nama="{{ $u->nama_lengkap }}"
                                                data-username="{{ $u->username }}"
                                                data-email="{{ $u->email ?: '-' }}"
                                                data-role="{{ $roles[$u->role] ?? $u->role }}"
                                                data-status="{{ ucfirst($u->status) }}"
                                                data-dibuat="{{ optional($u->created_at)->format('d-m-Y H:i') ?? '-' }}"
                                                data-inisial="{{ $inisial }}">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            <button type="button" class="btn-act btn-edit" title="Edit"
                                                data-id="{{ $u->id }}"
                                                data-nama="{{ $u->nama_lengkap }}"
                                                data-username="{{ $u->username }}"
                                                data-email="{{ $u->email }}"
                                                data-role="{{ $u->role }}"
                                                data-status="{{ $u->status }}"
                                                data-sendiri="{{ $sendiri ? 1 : 0 }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button type="button"
                                                class="btn-act btn-toggle {{ $u->status === 'aktif' ? '' : 'is-off' }}"
                                                data-id="{{ $u->id }}"
                                                data-nama="{{ $u->nama_lengkap }}"
                                                data-aktif="{{ $u->status === 'aktif' ? 1 : 0 }}"
                                                @if ($sendiri) disabled title="Akun yang sedang dipakai" @endif>
                                                {{ $u->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>

                                            <button type="button" class="btn-act btn-del" title="Hapus"
                                                data-id="{{ $u->id }}"
                                                data-nama="{{ $u->nama_lengkap }}"
                                                @if ($sendiri) disabled title="Akun yang sedang dipakai" @endif>
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="u-empty {{ $users->isEmpty() ? 'show' : '' }}" id="userEmpty">
                        <i class="bi bi-people"></i>
                        <span id="emptyText">{{ $users->isEmpty() ? 'Belum ada data user.' : 'Tidak ada user yang cocok.' }}</span>
                    </div>
                </div>
            </div>

        </section>
    </main>

</div>


{{-- ====== MODAL: Tambah / Edit ====== --}}
@php
    $mode = old('_mode');            // 'tambah' | 'edit' (dipakai kalau validasi gagal)
    $idEdit = old('_id');
@endphp

<div class="m-overlay {{ $errors->any() ? 'show' : '' }}" id="modalForm">
    <div class="m-box">
        <div class="m-head">
            <h3 id="formJudul">{{ $mode === 'edit' ? 'Edit User' : 'Tambah User' }}</h3>
            <p id="formSub">{{ $mode === 'edit' ? 'Perbarui akun pengguna SIP-REK' : 'Kelola akun pengguna SIP-REK' }}</p>
        </div>

        <form method="POST" id="formUser"
              action="{{ $mode === 'edit' && $idEdit ? route('user.update', $idEdit) : route('user.store') }}"
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
                        <label for="fNama">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="fNama" class="f-input @error('nama_lengkap') is-invalid @enderror"
                               value="{{ old('nama_lengkap') }}" maxlength="100" placeholder="Contoh: dr. Bagus Prasetyo">
                        @error('nama_lengkap') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fUsername">Username</label>
                        <input type="text" name="username" id="fUsername" class="f-input @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" maxlength="50" placeholder="Contoh: dr.bagus">
                        @error('username') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fEmail">Email</label>
                        <input type="email" name="email" id="fEmail" class="f-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" maxlength="100" placeholder="Contoh: nama@klinik.com">
                        @error('email') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fRole">Role</label>
                        <select name="role" id="fRole" class="f-input @error('role') is-invalid @enderror">
                            <option value="">Pilih role / jabatan</option>
                            @foreach ($roles as $val => $label)
                                <option value="{{ $val }}" {{ old('role') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fPass">Password <small id="hintPass">{{ $mode === 'edit' ? '(kosongkan jika tidak diganti)' : '' }}</small></label>
                        <div class="f-pass">
                            <input type="password" name="password" id="fPass" class="f-input @error('password') is-invalid @enderror"
                                   placeholder="Minimal 6 karakter" autocomplete="new-password">
                            <button type="button" class="btn-eye" data-target="fPass" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password') <div class="f-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="f-group">
                        <label for="fPass2">Konfirmasi Password</label>
                        <div class="f-pass">
                            <input type="password" name="password_confirmation" id="fPass2" class="f-input"
                                   placeholder="Ulangi password" autocomplete="new-password">
                            <button type="button" class="btn-eye" data-target="fPass2" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>

                    <div class="f-group f-full">
                        <label for="fStatus">Status</label>
                        <select name="status" id="fStatus" class="f-input">
                            <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                </div>

            </div>

            <div class="m-foot">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-save">Simpan User</button>
            </div>
        </form>
    </div>
</div>


{{-- ====== MODAL: Detail ====== --}}
<div class="m-overlay" id="modalDetail">
    <div class="m-box sm">
        <div class="m-head">
            <h3>Detail User</h3>
            <button type="button" class="m-close" data-tutup>&times;</button>
        </div>
        <div class="m-body">
            <div class="d-top">
                <div class="u-avatar" id="dInisial"></div>
                <strong id="dNama"></strong>
            </div>
            <div class="d-row"><span>Username</span><span id="dUsername"></span></div>
            <div class="d-row"><span>Email</span><span id="dEmail"></span></div>
            <div class="d-row"><span>Role / Jabatan</span><span id="dRole"></span></div>
            <div class="d-row"><span>Status</span><span id="dStatus"></span></div>
            <div class="d-row"><span>Dibuat</span><span id="dDibuat"></span></div>
        </div>
        <div class="m-foot">
            <button type="button" class="btn-m btn-m-cancel" data-tutup>Tutup</button>
        </div>
    </div>
</div>


{{-- ====== MODAL: Konfirmasi Nonaktifkan / Aktifkan ====== --}}
<div class="m-overlay" id="modalToggle">
    <div class="m-box sm">
        <form method="POST" id="formToggle">
            @csrf
            @method('PATCH')
            <div class="m-confirm">
                <div class="ic ic-warn"><i class="bi bi-person-lock"></i></div>
                <h4 id="tJudul"></h4>
                <p id="tTeks"></p>
            </div>
            <div class="m-foot" style="justify-content:center">
                <button type="button" class="btn-m btn-m-cancel" data-tutup>Batal</button>
                <button type="submit" class="btn-m btn-m-save" id="tOk">Ya</button>
            </div>
        </form>
    </div>
</div>


{{-- ====== MODAL: Konfirmasi Hapus ====== --}}
<div class="m-overlay" id="modalHapus">
    <div class="m-box sm">
        <form method="POST" id="formHapus">
            @csrf
            @method('DELETE')
            <div class="m-confirm">
                <div class="ic ic-danger"><i class="bi bi-trash3-fill"></i></div>
                <h4>Hapus user ini?</h4>
                <p>Akun <b id="hNama"></b> akan dihapus permanen dan tidak bisa dikembalikan.</p>
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
    const BASE = @json(url('data-user'));
    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];

    /* ---------- Modal helper ---------- */
    const buka  = el => { el.classList.add('show'); };
    const tutup = el => { el.classList.remove('show'); };

    $$('.m-overlay').forEach(ov => {
        if (ov.id !== 'modalForm') {
            ov.addEventListener('mousedown', e => { if (e.target === ov) tutup(ov); });
        }
        $$('[data-tutup]', ov).forEach(b => b.addEventListener('click', () => tutup(ov)));
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') $$('.m-overlay.show').forEach(tutup);
    });

    /* ---------- Cari + filter (langsung, tanpa reload) ---------- */
    const cari = $('#cariUser');
    const rows = $$('.user-row'), clearBtn = $('#clearCari');

    function terapkanFilter() {
        const q = cari.value.trim().toLowerCase();
        let n = 0;

        rows.forEach(tr => {
            const cocok = !q || tr.dataset.search.includes(q);
            tr.style.display = cocok ? '' : 'none';
            if (cocok) { n++; $('.row-no', tr).textContent = n; }
        });

        clearBtn.classList.toggle('show', q.length > 0);

        const kosong = $('#userEmpty');
        if (rows.length) {
            kosong.classList.toggle('show', n === 0);
            $('#emptyText').textContent = 'Tidak ada user yang cocok.';
        }
    }
    cari.addEventListener('input', terapkanFilter);
    clearBtn.addEventListener('click', () => { cari.value = ''; terapkanFilter(); cari.focus(); });

    /* ---------- Form tambah / edit ---------- */
    const modalForm = $('#modalForm'), form = $('#formUser');

    function resetForm() {
        ['fNama', 'fUsername', 'fEmail', 'fPass', 'fPass2'].forEach(id => $('#' + id).value = '');
        $('#fRole').value = '';
        $('#fStatus').value = 'aktif';
        $$('.is-invalid', form).forEach(el => el.classList.remove('is-invalid'));
        $$('.f-error, .m-alert', modalForm).forEach(el => el.remove());
        $('#fStatus').disabled = false;
        $('#fRole').disabled = false;
        ['fPass', 'fPass2'].forEach(id => $('#' + id).type = 'password');
        $$('.btn-eye i', modalForm).forEach(i => i.className = 'bi bi-eye');
    }

    $('#btnTambah').addEventListener('click', () => {
        resetForm();
        $('#formJudul').textContent = 'Tambah User';
        $('#formSub').textContent = 'Kelola akun pengguna SIP-REK';
        form.action = @json(route('user.store'));
        $('#fMethod').value = 'POST';
        $('#fMode').value = 'tambah';
        $('#fId').value = '';
        $('#hintPass').textContent = '';
        buka(modalForm);
        $('#fNama').focus();
    });

    $$('.btn-edit').forEach(btn => btn.addEventListener('click', () => {
        const d = btn.dataset;
        resetForm();
        $('#formJudul').textContent = 'Edit User';
        $('#formSub').textContent = 'Perbarui akun pengguna SIP-REK';
        form.action = BASE + '/' + d.id;
        $('#fMethod').value = 'PUT';
        $('#fMode').value = 'edit';
        $('#fId').value = d.id;
        $('#fNama').value = d.nama;
        $('#fUsername').value = d.username;
        $('#fEmail').value = d.email || '';
        $('#fRole').value = d.role;
        $('#fStatus').value = d.status;
        $('#hintPass').textContent = '(kosongkan jika tidak diganti)';

        // Akun yang sedang login tidak boleh mengubah role/status dirinya sendiri.
        if (d.sendiri === '1') { $('#fRole').disabled = true; $('#fStatus').disabled = true; }

        buka(modalForm);
        $('#fNama').focus();
    }));

    /* ---------- Ikon mata: tampil / sembunyikan password ---------- */
    $$('.btn-eye').forEach(btn => btn.addEventListener('click', () => {
        const input = $('#' + btn.dataset.target), ic = $('i', btn);
        const tampil = input.type === 'password';
        input.type = tampil ? 'text' : 'password';
        ic.className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
        btn.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
    }));

    /* ---------- Detail ---------- */
    $$('.btn-view').forEach(btn => btn.addEventListener('click', () => {
        const d = btn.dataset;
        $('#dInisial').textContent = d.inisial;
        $('#dNama').textContent = d.nama;
        $('#dUsername').textContent = d.username;
        $('#dEmail').textContent = d.email;
        $('#dRole').textContent = d.role;
        $('#dStatus').textContent = d.status;
        $('#dDibuat').textContent = d.dibuat;
        buka($('#modalDetail'));
    }));

    /* ---------- Nonaktifkan / Aktifkan ---------- */
    $$('.btn-toggle').forEach(btn => btn.addEventListener('click', () => {
        const d = btn.dataset, aktif = d.aktif === '1';
        $('#formToggle').action = BASE + '/' + d.id + '/status';
        $('#tJudul').textContent = aktif ? 'Nonaktifkan user ini?' : 'Aktifkan user ini?';
        $('#tTeks').innerHTML = aktif
            ? 'Akun <b></b> tidak akan bisa login sampai diaktifkan kembali.'
            : 'Akun <b></b> akan bisa login kembali.';
        $('#tTeks b').textContent = d.nama;
        $('#tOk').textContent = aktif ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan';
        buka($('#modalToggle'));
    }));

    /* ---------- Hapus ---------- */
    $$('.btn-del').forEach(btn => btn.addEventListener('click', () => {
        $('#formHapus').action = BASE + '/' + btn.dataset.id;
        $('#hNama').textContent = btn.dataset.nama;
        buka($('#modalHapus'));
    }));

    /* ---------- Role/status akun sendiri: aktifkan lagi sebelum kirim (nilai tetap dijaga server) ---------- */
    form.addEventListener('submit', () => {
        $('#fRole').disabled = false;
        $('#fStatus').disabled = false;
    });

    /* ---------- Cegah double submit ---------- */
    $$('form').forEach(f => f.addEventListener('submit', () => {
        const s = f.querySelector('button[type=submit]');
        if (s) setTimeout(() => s.disabled = true, 0);
    }));
})();
</script>

@endsection
