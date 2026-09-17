@extends('layouts.app')

@section('title', 'Pasien Baru')

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
        background: #ffffff;
    }

    .content {
        width: 100%;

        padding: 20px 22px;

        background: #ffffff;
    }

    .page-title {
        margin-bottom: 15px;

        font-size: 21px;
        font-weight: 700;

        text-transform: uppercase;

        color: #222;
    }

    .form-section {
        position: relative;

        width: 100%;

        padding: 24px 15px 15px;

        margin-bottom: 18px;

        border: 1px solid #888;

        border-radius: 11px;

        background: #ffffff;
    }

    .section-title {
        position: absolute;

        top: -10px;
        left: 18px;

        padding: 0 9px;

        background: #ffffff;

        font-size: 12px;
        font-weight: 600;

        color: #333;
    }

    .form-grid {
        display: grid;

        grid-template-columns: 1fr 1fr;

        column-gap: 35px;
        row-gap: 10px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 4px;

        font-size: 10px;
        font-weight: 500;

        color: #333;
    }

    .required {
        color: red;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        height: 35px;

        padding: 0 10px;

        border: 1px solid #999;
        border-radius: 5px;

        outline: none;

        background: #ffffff;

        font-family: 'Poppins', sans-serif;

        font-size: 10px;

        color: #555;
    }

    .form-group input::placeholder {
        color: #aaa;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #555;
    }

    .radio-wrapper {
        height: 35px;

        display: flex;
        align-items: center;

        gap: 18px;
    }

    .radio-item {
        display: flex;

        align-items: center;

        gap: 5px;

        font-size: 10px;

        white-space: nowrap;
    }

    .radio-item input {
        width: 13px;
        height: 13px;

        margin: 0;
    }

    .form-buttons {
        display: flex;

        justify-content: flex-end;

        gap: 15px;

        margin-top: 3px;
    }

    .btn {
        width: 87px;
        height: 36px;

        border: none;

        border-radius: 5px;

        color: #ffffff;

        font-family: 'Poppins', sans-serif;

        font-size: 11px;

        cursor: pointer;

        transition: 0.2s;
    }

    .btn-cancel {
        background: #e10b0b;
    }

    .btn-cancel:hover {
        background: #bd0000;
    }

    .btn-submit {
        background: #1010c9;
    }

    .btn-submit:hover {
        background: #08089e;
    }

    /* Pesan error validasi */
    .error-text {
        color: red;
        font-size: 9px;
        margin-top: 3px;
    }

    @media (max-width: 850px) {

        .sidebar {
            width: 200px;
        }

        .main {
            width: calc(100% - 200px);
            margin-left: 200px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {

        .sidebar {
            display: none;
        }

        .main {
            width: 100%;
            margin-left: 0;
        }

        .content {
            padding: 15px;
        }
    }
</style>

@endsection


@section('content')

<div class="dashboard">

    {{-- SIDEBAR --}}
    <x-sidebar />


    {{-- MAIN --}}
    <main class="main">

        {{-- CONTENT --}}
        <section class="content">

            <h1 class="page-title">
                Pendaftaran Pasien Baru
            </h1>

            {{-- Tampilkan error validasi kalau ada --}}
            @if($errors->any())
                <div style="background:#ffe0e0; color:#a00000; padding:10px 15px; border-radius:6px; margin-bottom:15px; font-size:12px;">
                    <strong>Periksa kembali data yang diisi:</strong>
                    <ul style="margin:6px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pasien.baru.store') }}" method="POST">

                @csrf

                {{-- =========================
                     DATA IDENTITAS PASIEN
                ========================== --}}

                <div class="form-section">

                    <div class="section-title">
                        Data Identitas Pasien
                    </div>


                    <div class="form-grid">

                        {{-- No RM --}}
                        <div class="form-group">

                            <label>
                                No. RM <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="no_rm"
                                value="{{ old('no_rm') }}"
                                placeholder="No. RM"
                                required
                            >

                        </div>


                        {{-- Jenis Kelamin --}}
                        <div class="form-group">

                            <label>
                                Jenis Kelamin <span class="required">*</span>
                            </label>

                            <div class="radio-wrapper">

                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="Laki-laki"
                                        {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}
                                        required
                                    >

                                    Laki-laki

                                </label>


                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="Perempuan"
                                        {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}
                                    >

                                    Perempuan

                                </label>

                            </div>

                        </div>


                        {{-- Nama --}}
                        <div class="form-group">

                            <label>
                                Nama Lengkap Pasien
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                placeholder="Nama lengkap pasien"
                                required
                            >

                        </div>


                        {{-- Tanggal Lahir --}}
                        <div class="form-group">

                            <label>
                                Tanggal Lahir
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}"
                                required
                            >

                        </div>


                        {{-- NIK --}}
                        <div class="form-group">

                            <label>
                                NIK <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nik"
                                value="{{ old('nik') }}"
                                placeholder="NIK"
                                required
                            >

                        </div>


                        {{-- Nomor Telepon --}}
                        <div class="form-group">

                            <label>
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                name="no_telepon"
                                value="{{ old('no_telepon') }}"
                                placeholder="Nomor telepon"
                            >

                        </div>


                        {{-- Alamat --}}
                        <div class="form-group">

                            <label>
                                Alamat <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="alamat"
                                value="{{ old('alamat') }}"
                                placeholder="Alamat lengkap"
                                required
                            >

                        </div>

                    </div>

                </div>


                {{-- =========================
                     DATA KUNJUNGAN
                ========================== --}}

                <div class="form-section">

                    <div class="section-title">
                        Data Kunjungan Pasien
                    </div>


                    <div class="form-grid">

                        {{-- Tanggal Kunjungan --}}
                        <div class="form-group">

                            <label>
                                Tanggal Kunjungan
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_kunjungan"
                                value="{{ old('tanggal_kunjungan') }}"
                                required
                            >

                        </div>


                        {{-- Dokter --}}
                        <div class="form-group">

                            <label>
                                Nama Dokter
                                <span class="required">*</span>
                            </label>

                            <select name="dokter" required>

                                <option value="">
                                    Pilih nama dokter yang bertugas
                                </option>

                                <option value="dokter1" {{ old('dokter') == 'dokter1' ? 'selected' : '' }}>
                                    Dr. Ahmad
                                </option>

                                <option value="dokter2" {{ old('dokter') == 'dokter2' ? 'selected' : '' }}>
                                    Dr. Budi
                                </option>

                                <option value="dokter3" {{ old('dokter') == 'dokter3' ? 'selected' : '' }}>
                                    Dr. Citra
                                </option>

                            </select>

                        </div>


                        {{-- Poli --}}
                        <div class="form-group">

                            <label>
                                Poli Tujuan
                                <span class="required">*</span>
                            </label>

                            <select name="poli" required>

                                <option value="">
                                    Pilih poli
                                </option>

                                <option value="umum" {{ old('poli') == 'umum' ? 'selected' : '' }}>
                                    Poli Umum
                                </option>

                                <option value="gigi" {{ old('poli') == 'gigi' ? 'selected' : '' }}>
                                    Poli Gigi
                                </option>

                                <option value="anak" {{ old('poli') == 'anak' ? 'selected' : '' }}>
                                    Poli Anak
                                </option>

                            </select>

                        </div>


                        {{-- Pembayaran --}}
                        <div class="form-group">

                            <label>
                                Cara Pembayaran
                                <span class="required">*</span>
                            </label>

                            <select name="pembayaran" required>

                                <option value="">
                                    Pilih cara pembayaran
                                </option>

                                <option value="bpjs" {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>
                                    BPJS
                                </option>

                                <option value="umum" {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>
                                    Umum
                                </option>

                                <option value="asuransi" {{ old('pembayaran') == 'asuransi' ? 'selected' : '' }}>
                                    Asuransi
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =========================
                     BUTTON
                ========================== --}}

                <div class="form-buttons">

                    <button
                        type="button"
                        class="btn btn-cancel"
                        onclick="history.back()"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        class="btn btn-submit"
                    >
                        Daftar
                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

@endsection