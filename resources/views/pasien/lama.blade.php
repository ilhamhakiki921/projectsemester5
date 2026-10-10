@extends('layouts.app')

@section('title', 'Pendaftaran Pasien Lama')

@section('style')

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #e9e9e9;
    color: #333;
}

/* =========================
   LAYOUT
========================= */

.dashboard {
    display: flex;
    min-height: 100vh;
}

/* =========================
   SIDEBAR
========================= */

/* =========================
   SIDEBAR
========================= */

.sidebar {
    width: 230px;
    height: 100vh;

    position: fixed;
    left: 0;
    top: 0;

    display: flex;
    flex-direction: column;

    color: white;

    background: linear-gradient(
        180deg,
        #980000 0%,
        #570000 100%
    );
}


/* =========================
   LOGO
========================= */

.logo {
    height: 75px;

    display: flex;
    align-items: center;

    padding: 12px 20px;

    border-bottom: 1px solid rgba(255,255,255,0.18);
}

.logo-icon {
    width: 38px;
    height: 38px;

    border: 2px solid white;
    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-right: 9px;

    font-size: 17px;
}

.logo-text {
    font-size: 12px;
    font-weight: 600;
}


/* =========================
   MENU
========================= */

.menu {
    padding: 30px 12px;
}

.menu-item,
.menu-dropdown {
    width: 100%;
    height: 40px;

    display: flex;
    align-items: center;

    padding: 0 13px;
    margin-bottom: 6px;

    color: white;
    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 500;

    border: none;
    border-radius: 5px;

    background: transparent;

    cursor: pointer;

    transition: 0.2s;
}

.menu-item i,
.menu-dropdown i {
    width: 28px;
    font-size: 16px;
}

.menu-item:hover,
.menu-dropdown:hover {
    background: rgba(255,255,255,0.12);
}


/* =========================
   MENU PENDAFTARAN
========================= */

.menu-group {
    width: 100%;
}

.menu-dropdown {
    justify-content: space-between;
}

.menu-left {
    display: flex;
    align-items: center;
}

.menu-dropdown .arrow {
    width: auto;

    font-size: 11px;

    transition: transform 0.25s ease;
}


/* Saat Pendaftaran aktif */

.menu-dropdown.active {
    background: #ed1111;
}


/* Panah berputar */

.menu-dropdown .arrow.rotate {
    transform: rotate(180deg);
}


/* =========================
   SUBMENU
========================= */

.submenu {
    max-height: 0;

    overflow: hidden;

    opacity: 0;

    transition:
        max-height 0.3s ease,
        opacity 0.2s ease;

    margin-bottom: 5px;
}

.submenu.show {
    max-height: 150px;

    opacity: 1;
}


/* Item submenu */

.submenu a {
    display: flex;
    align-items: center;

    min-height: 34px;

    margin: 2px 0 2px 28px;
    padding: 7px 10px;

    color: rgba(255,255,255,0.82);

    text-decoration: none;

    font-size: 10px;
    font-weight: 400;

    border-radius: 4px;

    transition: 0.2s;
}

.submenu a:hover {
    color: white;

    background: rgba(255,255,255,0.12);
}


/* Submenu aktif */

.submenu a.submenu-active {
    color: white;

    background: rgba(255,255,255,0.18);

    font-weight: 600;
}


/* =========================
   LOGOUT
========================= */

.logout {
    margin-top: auto;

    padding: 18px 12px;

    border-top: 1px solid rgba(255,255,255,0.2);
}

.logout a {
    height: 40px;

    display: flex;
    align-items: center;

    padding: 0 13px;

    color: white;
    text-decoration: none;

    font-size: 12px;
}

.logout i {
    width: 28px;
    font-size: 17px;
}

/* =========================
   MAIN CONTENT
========================= */

.main {
    width: calc(100% - 230px);
    min-height: 100vh;
    margin-left: 230px;
    background: #ffffff;
}

/* Header */

/* Content */

.content {
    width: 100%;
    min-height: 100vh;
    padding: 25px 22px;
    background: #ffffff;
}
/* =========================
   FORM CONTAINER
========================= */

.form-container {
    width: 100%;
    margin: 0;
    background: #ffffff;
    padding: 0 0 20px 0;
}
/* Title */

.page-title {
    margin-bottom: 15px;
    font-size: 21px;
    font-weight: 700;
    text-transform: uppercase;
    color: #222;
}

/* =========================
   FORM SECTION
========================= */

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

    top: -11px;
    left: 20px;

    background: white;

    padding: 0 10px;

    font-size: 13px;
    font-weight: 600;

    color: #333;
}

/* Form Grid */

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
    font-size: 11px;

    font-weight: 500;

    margin-bottom: 5px;

    color: #333;
}

.required {
    color: red;
}

/* Input */

.form-group input,
.form-group select {
    width: 100%;
    height: 35px;

    padding: 0 10px;

    border: 1px solid #999;
    border-radius: 5px;

    outline: none;

    background: white;

    font-family: 'Poppins', sans-serif;
    font-size: 11px;

    color: #555;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #c40000;

    box-shadow: 0 0 0 2px rgba(196,0,0,0.08);
}

/* Radio */

.radio-container {
    height: 32px;

    display: flex;

    align-items: center;

    gap: 18px;
}

.radio-item {
    display: flex;

    align-items: center;

    gap: 5px;

    font-size: 11px;
}

.radio-item input {
    width: 13px;
    height: 13px;
}

/* =========================
   BUTTON
========================= */

.form-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 2px;
}

.btn {
    width: 87px;
    height: 36px;

    border: none;
    border-radius: 5px;

    color: white;

    font-family: 'Poppins', sans-serif;
    font-size: 11px;

    cursor: pointer;
}

.btn-cancel {
    background: #df1010;
}

.btn-cancel:hover {
    background: #bd0000;
}

.btn-submit {
    background: #1515c9;
}

.btn-submit:hover {
    background: #0d0da5;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

    .sidebar {
        width: 190px;
    }

    .main {
        margin-left: 190px;
        width: calc(100% - 190px);
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .content {
        padding: 20px;
    }

}

@media (max-width: 600px) {

    .sidebar {
        display: none;
    }

    .main {
        margin-left: 0;
        width: 100%;
    }

    .form-container {
        padding: 20px 15px;
    }

}

</style>

@endsection


@section('content')

<div class="dashboard">

    <!-- =========================
         SIDEBAR
    ========================== -->

<x-sidebar />



    <!-- =========================
         MAIN
    ========================== -->

    <main class="main">
        <x-topbar title="Pendaftaran Pasien Lama" icon="person-vcard" />

        <!-- Content -->

        <section class="content">

            <div class="form-container">


                <h1 class="page-title">
                    Pendaftaran Pasien Lama
                </h1>


                <!-- =====================
                     IDENTITAS PASIEN
                ====================== -->

                <div class="form-section">

                    <div class="section-title">
                        Data Identitas Pasien
                    </div>


                    <div class="form-grid">


                        <!-- No RM -->

                        <div class="form-group">

                            <label>
                                No. RM <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="no_rm"
                                placeholder="No. RM"
                            >

                        </div>



                        <!-- Jenis Kelamin -->

                        <div class="form-group">

                            <label>
                                Jenis Kelamin <span class="required">*</span>
                            </label>

                            <div class="radio-container">

                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="Laki-laki"
                                    >

                                    Laki-laki

                                </label>


                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="Perempuan"
                                    >

                                    Perempuan

                                </label>

                            </div>

                        </div>



                        <!-- Nama -->

                        <div class="form-group">

                            <label>
                                Nama Lengkap Pasien
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama"
                                placeholder="Nama lengkap pasien"
                            >

                        </div>



                        <!-- Tanggal Lahir -->

                        <div class="form-group">

                            <label>
                                Tanggal Lahir
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_lahir"
                            >

                        </div>



                        <!-- NIK -->

                        <div class="form-group">

                            <label>
                                NIK <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nik"
                                placeholder="NIK"
                            >

                        </div>



                        <!-- Nomor Telepon -->

                        <div class="form-group">

                            <label>
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                name="no_telepon"
                                placeholder="Nomor telepon"
                            >

                        </div>



                        <!-- Alamat -->

                        <div class="form-group">

                            <label>
                                Alamat <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="alamat"
                                placeholder="Alamat lengkap"
                            >

                        </div>

                    </div>

                </div>



                <!-- =====================
                     DATA KUNJUNGAN
                ====================== -->

                <div class="form-section">

                    <div class="section-title">
                        Data Kunjungan Pasien
                    </div>


                    <div class="form-grid">


                        <!-- Tanggal Kunjungan -->

                        <div class="form-group">

                            <label>
                                Tanggal Kunjungan
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_kunjungan"
                            >

                        </div>



                        <!-- Nama Dokter -->

                        <div class="form-group">

                            <label>
                                Nama Dokter
                                <span class="required">*</span>
                            </label>

                            <select name="dokter">

                                <option value="">
                                    Pilih nama dokter yang bertugas
                                </option>

                                <option value="dokter1">
                                    Dr. Ahmad
                                </option>

                                <option value="dokter2">
                                    Dr. Budi
                                </option>

                                <option value="dokter3">
                                    Dr. Citra
                                </option>

                            </select>

                        </div>



                        <!-- Poli -->

                        <div class="form-group">

                            <label>
                                Poli Tujuan
                                <span class="required">*</span>
                            </label>

                            <select name="poli">

                                <option value="">
                                    Pilih poli
                                </option>

                                @foreach (\App\Models\Poli::daftar(true) as $slugPoli => $namaPoliOpsi)
                                    <option value="{{ $slugPoli }}">
                                        {{ $namaPoliOpsi }}
                                    </option>
                                @endforeach

                            </select>

                        </div>



                        <!-- Pembayaran -->

                        <div class="form-group">

                            <label>
                                Cara Pembayaran
                                <span class="required">*</span>
                            </label>

                            <select name="pembayaran">

                                <option value="">
                                    Pilih cara pembayaran
                                </option>

                                <option value="bpjs">
                                    BPJS
                                </option>

                                <option value="umum">
                                    Umum
                                </option>

                                <option value="asuransi">
                                    Asuransi Lainnya
                                </option>

                            </select>

                        </div>

                    </div>

                </div>



                <!-- =====================
                     BUTTON
                ====================== -->

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


            </div>

        </section>

    </main>

</div>

@endsection