@extends('layouts.app')

@section('title', 'Pendaftaran Pasien')

@section('style')

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f5f7f7;
        color: #222;
    }


    /* =========================
   LAYOUT
========================== */

.dashboard {
    width: 100%;
    min-height: 100vh;
    position: relative;
}



/* =========================
   MAIN
========================== */

.main {
    width: calc(100% - 230px);
    min-height: 100vh;
    margin-left: 230px;
    background: #f5f7f7;
    position: relative;

    transition:
        width 0.25s ease,
        margin-left 0.25s ease;
}

body.sidebar-collapsed .main {
    width: 100%;
    margin-left: 0;
}


    /* =========================
       CONTENT
    ========================== */

    .content {
        width: 100%;
        padding: 28px 32px;
    }


    /* =========================
       PAGE TITLE
    ========================== */

    .page-title {
        margin: 0 0 20px 0;

        font-size: 22px;
        font-weight: 600;

        color: #222;
    }


    /* =========================
       SEARCH & BUTTON
    ========================== */

    .top-action {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 20px;
    }


    .search-box {
        position: relative;
        width: 325px;
    }


    .search-box i {
        position: absolute;

        left: 13px;
        top: 50%;

        transform: translateY(-50%);

        font-size: 14px;
        color: #777;

        pointer-events: none;
    }


    .search-input {
        width: 100%;
        height: 45px;

        padding: 0 14px 0 40px;

        border: 1px solid #d9dede;
        border-radius: 8px;

        outline: none;

        background: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 13px;

        color: #333;

        transition: border-color 0.2s ease,
                    box-shadow 0.2s ease;
    }


    .search-input::placeholder {
        color: #999;
    }


    .search-input:focus {
        border-color: #0f766e;

        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.08);
    }


    /* =========================
       BUTTON PASIEN BARU
    ========================== */

    .btn-new {
    height: 43px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 0 18px;

    border: none;
    border-radius: 8px;

    background: #dc2626;
    color: #ffffff;

    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;

    cursor: pointer;

    transition: background 0.2s ease,
                transform 0.15s ease;
}


.btn-new:hover {
    background: #b91c1c;
    color: #ffffff;
    text-decoration: none;
}


    .btn-new i {
        font-size: 14px;
    }


    /* =========================
       TABLE CARD
    ========================== */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;

        background: #ffffff;

        border: 1px solid #dfe4e4;

        border-radius: 9px;

        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
    }


    /* =========================
       TABLE
    ========================== */

    .patient-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: fixed;
    }


    .patient-table th,
    .patient-table td {
        padding: 13px 12px;

        border-bottom: 1px solid #e4e8e8;

        font-size: 12px;

        vertical-align: middle;
    }


    .patient-table th {
        height: 46px;

        background: #f8faf9;

        color: #555;

        font-size: 11px;
        font-weight: 600;

        text-align: left;

        text-transform: uppercase;
    }


    .patient-table td {
        min-height: 65px;

        background: #ffffff;

        color: #333;
    }


    .patient-table tbody tr:last-child td {
        border-bottom: none;
    }


    .patient-table tbody tr:hover td {
        background: #fafcfc;
    }


    /* =========================
       COLUMN WIDTH
    ========================== */

    .col-no {
    width: 4%;
    text-align: center !important;
}

.col-rm {
    width: 9%;
}

.col-nama {
    width: 13%;
}

.col-alamat {
    width: 14%;
}

.col-jk {
    width: 9%;
}

.col-tanggal {
    width: 11%;
}

.col-poli {
    width: 11%;
}

.col-status {
    width: 12%;
}

.col-action {
    width: 17%;
}


    /* =========================
       DATA PASIEN
    ========================== */

    .patient-name {
        font-weight: 600;
        color: #222;
    }


    .rm-number {
        font-weight: 600;
        color: #333;
    }


    /* =========================
       STATUS BADGE
    ========================== */

    .status-badge {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 11px;
        font-weight: 500;

        white-space: nowrap;
    }


    .status-badge::before {
        content: '';

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: currentColor;
    }


    .status-diperiksa {
        color: #2875ad;
        background: #e5f1fa;
    }


    .status-selesai {
        color: #28885a;
        background: #e4f5eb;
    }


    .status-menunggu {
        color: #d98218;
        background: #fff0d9;
    }


    /* =========================
       ACTION
    ========================== */

    .action {
        display: flex;

        align-items: center;

        gap: 7px;
    }


    .btn-kunjungan,
    .btn-detail {
        min-height: 38px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #d9e1e1;
        border-radius: 7px;

        background: #ffffff;

        color: #222;

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;

        transition: background 0.2s ease,
                    border-color 0.2s ease;
    }


    .btn-kunjungan,
.btn-detail {
    width: 110px;
    height: 42px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    text-align: center;
    line-height: 15px;

    padding: 4px 8px;
}


    .btn-kunjungan:hover,
    .btn-detail:hover {
        background: #f4f7f7;

        border-color: #bfcaca;

        color: #222;

        text-decoration: none;
    }


    /* =========================
       RESPONSIVE
    ========================== */

    @media (max-width: 1100px) {

        .content {
            padding: 24px 20px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .patient-table {
            min-width: 1050px;
        }

    }


    @media (max-width: 700px) {

        .top-action {
            align-items: stretch;
            flex-direction: column;
            gap: 12px;
        }

        .search-box {
            width: 100%;
        }

        .btn-new {
            width: 100%;
        }

    }

    .btn-kunjungan,
.btn-detail {
    width: 95px;
    height: 42px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 4px 8px;

    border: 1px solid #d9e1e1;
    border-radius: 7px;

    background: #ffffff;
    color: #222222;

    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 11px;
    font-weight: 600;

    text-align: center;
    line-height: 15px;

    cursor: pointer;

    transition: background 0.2s ease,
                border-color 0.2s ease;
}

/* =========================
   MODAL KUNJUNGAN
========================== */

.modal-overlay {
    position: fixed;
    inset: 0;

    display: none;
    align-items: center;
    justify-content: center;

    background: rgba(0, 0, 0, 0.48);

    z-index: 9999;

    padding: 20px;
}

.modal-overlay.show {
    display: flex;
}


.modal-kunjungan {
    width: 750px;
    max-width: 100%;

    background: #ffffff;

    border-radius: 14px;

    overflow: hidden;

    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);

    animation: modalMasuk 0.2s ease;
}


@keyframes modalMasuk {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}


/* =========================
   MODAL HEADER
========================== */

.modal-header {
    min-height: 100px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 22px 28px;

    border-bottom: 1px solid #e5e7eb;
}


.modal-header h2 {
    margin: 0 0 4px;

    font-size: 20px;
    font-weight: 600;

    color: #111827;
}


.modal-header p {
    margin: 0;

    font-size: 13px;

    color: #78909c;
}


.modal-close {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;

    background: transparent;

    color: #78909c;

    font-size: 18px;

    cursor: pointer;
}


.modal-close:hover {
    color: #333;
}


/* =========================
   MODAL BODY
========================== */

.modal-body {
    padding: 24px 28px 26px;
}


/* =========================
   IDENTITY
========================== */

.identity-card {
    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 18px 28px;

    padding: 17px;

    background: #f8faf9;

    border: 1px dashed #d5dddd;

    border-radius: 10px;

    margin-bottom: 22px;
}


.identity-item {
    display: flex;
    flex-direction: column;

    gap: 5px;
}


.identity-item span {
    font-size: 11px;

    color: #78909c;

    text-transform: uppercase;
}


.identity-item strong {
    font-size: 13px;

    font-weight: 600;

    color: #111827;
}


/* =========================
   FORM
========================== */

.form-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 20px 18px;
}


.form-group {
    display: flex;
    flex-direction: column;

    gap: 7px;
}


.form-group label {
    font-size: 13px;

    font-weight: 600;

    color: #455a64;
}


.form-group input,
.form-group select {
    width: 100%;
    height: 48px;

    padding: 0 14px;

    border: 1px solid #d9e1e1;

    border-radius: 8px;

    background: #ffffff;

    color: #222;

    font-family: 'Poppins', sans-serif;

    font-size: 13px;

    outline: none;
}


.form-group input:focus,
.form-group select:focus {
    border-color: #0f766e;

    box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.08);
}


.input-icon {
    position: relative;
}


.input-icon input {
    padding-right: 42px;
}


.input-icon i {
    position: absolute;

    right: 14px;
    top: 50%;

    transform: translateY(-50%);

    color: #111827;

    pointer-events: none;
}


/* =========================
   MODAL FOOTER
========================== */

.modal-footer {
    display: flex;

    justify-content: flex-end;
    align-items: center;

    gap: 12px;

    padding: 20px 28px;

    border-top: 1px solid #e5e7eb;
}


.btn-batal,
.btn-daftarkan {
    height: 43px;

    padding: 0 18px;

    border-radius: 8px;

    font-family: 'Poppins', sans-serif;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;
}


.btn-batal {
    border: 1px solid #d9e1e1;

    background: #ffffff;

    color: #222;
}


.btn-daftarkan {
    border: none;

    background: #0f766e;

    color: #ffffff;
}


.btn-batal:hover {
    background: #f5f7f7;
}


.btn-daftarkan:hover {
    background: #0b5f59;
}


/* =========================
   RESPONSIVE MODAL
========================== */

@media (max-width: 700px) {

    .identity-card {
        grid-template-columns: 1fr 1fr;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

}
/* =========================
   MODAL DETAIL PASIEN
========================= */

.detail-overlay {
    position: fixed;
    inset: 0;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(0, 0, 0, 0.48);

    z-index: 10000;
}

.detail-overlay.show {
    display: flex;
}

.detail-modal {
    width: 750px;
    max-width: 100%;

    background: #ffffff;

    border-radius: 14px;

    overflow: hidden;

    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);

    animation: detailMasuk 0.2s ease;
}

@keyframes detailMasuk {
    from {
        opacity: 0;
        transform: translateY(-10px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}


/* HEADER */

.detail-header {
    min-height: 98px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 22px 28px;

    border-bottom: 1px solid #e5e7eb;
}

.detail-header h2 {
    margin: 0 0 4px;

    font-size: 20px;
    font-weight: 600;

    color: #111827;
}

.detail-header p {
    margin: 0;

    font-size: 13px;

    color: #78909c;
}

.detail-close {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;

    background: transparent;

    color: #78909c;

    font-size: 18px;

    cursor: pointer;
}

.detail-close:hover {
    color: #333333;
}


/* BODY */

.detail-body {
    padding: 26px 28px 25px;
}

.detail-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    column-gap: 70px;

    row-gap: 18px;
}

.detail-item {
    display: flex;
    flex-direction: column;

    gap: 6px;
}

.detail-item span {
    font-size: 11px;

    color: #78909c;

    text-transform: uppercase;
}

.detail-item strong {
    font-size: 14px;

    font-weight: 400;

    color: #111827;

    line-height: 1.5;
}

.detail-alamat {
    grid-column: 1 / -1;
}


/* FOOTER */

.detail-footer {
    display: flex;

    justify-content: flex-end;

    padding: 20px 28px;

    border-top: 1px solid #e5e7eb;
}

.btn-detail-tutup {
    height: 43px;

    padding: 0 20px;

    border: none;

    border-radius: 8px;

    background: #0f766e;

    color: #ffffff;

    font-family: 'Poppins', sans-serif;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;
}

.btn-detail-tutup:hover {
    background: #0b5f59;
}


/* RESPONSIVE */

@media (max-width: 700px) {

    .detail-grid {
        grid-template-columns: 1fr;

        column-gap: 0;
    }

    .detail-alamat {
        grid-column: auto;
    }

}
/* =========================
   MODAL PASIEN BARU
========================= */

#modalPasienBaru {
    z-index: 10001;
}

.modal-pasien-baru {
    width: 1025px;
    max-width: 100%;
    max-height: calc(100vh - 60px);

    display: flex;
    flex-direction: column;

    background: #ffffff;

    border-radius: 14px;

    overflow: hidden;

    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}


/* BODY MODAL */

.pasien-baru-body {
    padding: 26px 28px 30px;

    overflow-y: auto;

    flex: 1;
}


/* JUDUL SECTION */

.form-section-title {
    margin: 0 0 16px;

    font-size: 15px;
    font-weight: 600;

    color: #0f766e;
}


/* JARAK ANTAR SECTION */

.form-section-title:not(:first-child) {
    margin-top: 26px;
}


/* GRID */

.pasien-baru-body .form-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 20px 18px;
}


/* INPUT FULL WIDTH */

.pasien-baru-body .full-width {
    grid-column: 1 / -1;
}


/* TEXTAREA */

.pasien-baru-body textarea {
    width: 100%;
    min-height: 80px;

    padding: 12px 14px;

    border: 1px solid #d9e1e1;
    border-radius: 8px;

    background: #ffffff;

    font-family: 'Poppins', sans-serif;
    font-size: 13px;

    color: #222;

    resize: vertical;

    outline: none;
}

.pasien-baru-body textarea:focus {
    border-color: #0f766e;

    box-shadow:
        0 0 0 3px rgba(15, 118, 110, 0.08);
}


/* INPUT & SELECT */

.pasien-baru-body input,
.pasien-baru-body select {
    height: 46px;
}


/* FOOTER */

#modalPasienBaru .modal-footer {
    flex-shrink: 0;

    background: #ffffff;
}


/* RESPONSIVE */

@media (max-width: 700px) {

    .modal-pasien-baru {
        max-height: calc(100vh - 30px);
    }

    .pasien-baru-body .form-grid {
        grid-template-columns: 1fr;
    }

    .pasien-baru-body .full-width {
        grid-column: auto;
    }

}

</style>

@endsection


@section('content')

<div class="dashboard">

    {{-- SIDEBAR PUNYA PROJECT --}}
    <x-sidebar />


    {{-- MAIN --}}
    <main class="main">

        {{-- TOPBAR PUNYA PROJECT --}}
        <x-topbar
    title="Pendaftaran"
    icon="clipboard-plus"
/>


        <section class="content">

            {{-- TITLE --}}
            <h1 class="page-title">
                Pendaftaran Pasien
            </h1>


            {{-- SEARCH + BUTTON --}}
            <div class="top-action">

                <div class="search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        class="search-input"
                        placeholder="Cari No. RM atau nama pasien..."
                    >

                </div>


                <button
    type="button"
    class="btn-new"
    onclick="bukaModalPasienBaru()"
>
    <i class="bi bi-person-plus-fill"></i>
    Daftarkan Pasien Baru
</button>

            </div>


            {{-- TABLE --}}
            <div class="table-wrapper">

                <table class="patient-table">

                    <thead>

                        <tr>

                            <th class="col-no">
                                No
                            </th>

                            <th class="col-rm">
                                No. RM
                            </th>

                            <th class="col-nama">
                                Nama
                            </th>

                            <th class="col-alamat">
                                Alamat
                            </th>

                            <th class="col-jk">
                                JK
                            </th>

                            <th class="col-tanggal">
                                Tgl Kunjungan
                            </th>

                            <th class="col-poli">
                                Poli Tujuan
                            </th>

                            <th class="col-status">
                                Status
                            </th>

                            <th class="col-action">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- DATA CONTOH --}}

                        <tr>

                            <td class="col-no">
                                1
                            </td>

                            <td>
                                <span class="rm-number">
                                    RM001010
                                </span>
                            </td>

                            <td>
                                <span class="patient-name">
                                    Hendra Gunawan
                                </span>
                            </td>

                            <td>
                                Jl. Dahlia No. 3
                            </td>

                            <td>
                                Laki-laki
                            </td>

                            <td>
                                18 Sep 2026
                            </td>

                            <td>
                                Poli Umum
                            </td>

                            <td>

                                <span class="status-badge status-diperiksa">
                                    Sedang Diperiksa
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <button
                                        type="button"
                                        class="btn-kunjungan"
                                        onclick="bukaModalKunjungan(this)"
                                    >
                                        Daftarkan<br>
                                        Kunjungan
                                    </button>

                                    <button
    type="button"
    class="btn-detail"
    onclick="bukaDetailPasien(this)"
    data-rm="RM001010"
    data-nama="Hendra Gunawan"
    data-nik="3204012208650005"
    data-jk="Laki-laki"
    data-lahir="Sumedang, 22 Agu 1965"
    data-golongan="O"
    data-umur="61 th"
    data-telepon="081234567805"
    data-alamat="Jl. Dahlia No. 3, RT/RW 03/01, Pasteur, Sukajadi, Bandung, Jawa Barat"
>
    Detail
</button>

                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td class="col-no">
                                2
                            </td>

                            <td>
                                <span class="rm-number">
                                    RM001010
                                </span>
                            </td>

                            <td>
                                <span class="patient-name">
                                    Hendra Gunawan
                                </span>
                            </td>

                            <td>
                                Jl. Dahlia No. 3
                            </td>

                            <td>
                                Laki-laki
                            </td>

                            <td>
                                18 Sep 2026
                            </td>

                            <td>
                                Poli Dalam
                            </td>

                            <td>

                                <span class="status-badge status-selesai">
                                    Selesai Periksa
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan"
                                    >
                                        Daftarkan<br>
                                        Kunjungan
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td class="col-no">
                                3
                            </td>

                            <td>
                                <span class="rm-number">
                                    RM001009
                                </span>
                            </td>

                            <td>
                                <span class="patient-name">
                                    Dewi Puspita
                                </span>
                            </td>

                            <td>
                                Jl. Melati No. 21
                            </td>

                            <td>
                                Perempuan
                            </td>

                            <td>
                                18 Sep 2026
                            </td>

                            <td>
                                Poli Umum
                            </td>

                            <td>

                                <span class="status-badge status-diperiksa">
                                    Sedang Diperiksa
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan"
                                    >
                                        Daftarkan<br>
                                        Kunjungan
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td class="col-no">
                                4
                            </td>

                            <td>
                                <span class="rm-number">
                                    RM001008
                                </span>
                            </td>

                            <td>
                                <span class="patient-name">
                                    Agus Hidayat
                                </span>
                            </td>

                            <td>
                                Jl. Anggrek No. 8
                            </td>

                            <td>
                                Laki-laki
                            </td>

                            <td>
                                18 Sep 2026
                            </td>

                            <td>
                                Poli Gigi
                            </td>

                            <td>

                                <span class="status-badge status-selesai">
                                    Selesai Periksa
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan"
                                    >
                                        Daftarkan<br>
                                        Kunjungan
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td class="col-no">
                                5
                            </td>

                            <td>
                                <span class="rm-number">
                                    RM001007
                                </span>
                            </td>

                            <td>
                                <span class="patient-name">
                                    Siti Aminah
                                </span>
                            </td>

                            <td>
                                Jl. Kenanga No. 5
                            </td>

                            <td>
                                Perempuan
                            </td>

                            <td>
                                18 Sep 2026
                            </td>

                            <td>
                                Poli Dalam
                            </td>

                            <td>

                                <span class="status-badge status-menunggu">
                                    Menunggu
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan"
                                    >
                                        Daftarkan<br>
                                        Kunjungan
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>


                        <tr>

                            <td class="col-no">
                                6
                            </td>

                            <td>
                                <span class="rm-number">
                                    RM001006
                                </span>
                            </td>

                            <td>
                                <span class="patient-name">
                                    Budi Santoso
                                </span>
                            </td>

                            <td>
                                Jl. Merdeka No. 12
                            </td>

                            <td>
                                Laki-laki
                            </td>

                            <td>
                                18 Sep 2026
                            </td>

                            <td>
                                Poli Umum
                            </td>

                            <td>

                                <span class="status-badge status-diperiksa">
                                    Sedang Diperiksa
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan"
                                    >
                                        Daftarkan<br>
                                        Kunjungan
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>


{{-- =========================
     MODAL DETAIL PASIEN
========================== --}}

<div id="modalDetailPasien" class="detail-overlay">

    <div class="detail-modal">

        {{-- HEADER --}}
        <div class="detail-header">

            <div>
                <h2 id="detailNama">Hendra Gunawan</h2>

                <p>
                    No. RM <span id="detailRm">RM001010</span>
                </p>
            </div>

            <button
                type="button"
                class="detail-close"
                onclick="tutupDetailPasien()"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        {{-- BODY --}}
        <div class="detail-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <span>NIK</span>
                    <strong id="detailNik">-</strong>
                </div>

                <div class="detail-item">
                    <span>JENIS KELAMIN</span>
                    <strong id="detailJk">-</strong>
                </div>

                <div class="detail-item">
                    <span>TEMPAT, TGL LAHIR</span>
                    <strong id="detailLahir">-</strong>
                </div>

                <div class="detail-item">
                    <span>GOLONGAN DARAH</span>
                    <strong id="detailGolongan">-</strong>
                </div>

                <div class="detail-item">
                    <span>TELEPON</span>
                    <strong id="detailTelepon">-</strong>
                </div>

                <div class="detail-item">
                    <span>UMUR</span>
                    <strong id="detailUmur">-</strong>
                </div>

                <div class="detail-item detail-alamat">
                    <span>ALAMAT</span>
                    <strong id="detailAlamat">-</strong>
                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="detail-footer">

            <button
                type="button"
                class="btn-detail-tutup"
                onclick="tutupDetailPasien()"
            >
                Tutup
            </button>

        </div>

    </div>

</div>

{{-- =========================
     MODAL PENDAFTARAN PASIEN BARU
========================== --}}

<div id="modalPasienBaru" class="modal-overlay">

    <div class="modal-pasien-baru">

        {{-- HEADER --}}
        <div class="modal-header">

            <div>
                <h2>Pendaftaran Pasien Baru</h2>
                <p>Lengkapi identitas & informasi kunjungan</p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="tutupModalPasienBaru()"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        {{-- BODY --}}
        <div class="pasien-baru-body">

            {{-- IDENTITAS PASIEN --}}
            <div class="form-section-title">
                IDENTITAS PASIEN
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Nama lengkap</label>
                    <input
                        type="text"
                        placeholder="Nama sesuai KTP"
                    >
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input
                        type="text"
                        placeholder="16 digit"
                        maxlength="16"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                    >
                </div>

                <div class="form-group">
                    <label>Nomor Kartu Keluarga</label>
                    <input
                        type="text"
                        placeholder="16 digit"
                        maxlength="16"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                    >
                </div>

                <div class="form-group">
                    <label>Golongan Darah</label>
                    <select>
                        <option value="" selected disabled>Pilih golongan darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input
                        type="text"
                    >
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input
                        type="date"
                    >
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select>
                        <option value="" selected disabled>Pilih jenis kelamin</option>
                        <option value="Tidak diketahui">Tidak diketahui</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                        <option value="Tidak dapat ditentukan">Tidak dapat ditentukan</option>
                        <option value="Tidak mengisi">Tidak mengisi</option>
                    </select>
                </div>

            </div>


            {{-- ALAMAT --}}
            <div class="form-section-title">
                ALAMAT
            </div>

            <div class="form-grid">

                <div class="form-group full-width">
                    <label>Alamat Lengkap</label>
                    <textarea></textarea>
                </div>

                <div class="form-group">
                    <label>RT/RW</label>
                    <input
                        type="text"
                        placeholder="001/002"
                    >
                </div>

                <div class="form-group">
                    <label>Desa/Kelurahan</label>
                    <input
                        type="text"
                    >
                </div>

                <div class="form-group">
                    <label>Kecamatan</label>
                    <input
                        type="text"
                    >
                </div>

                <div class="form-group">
                    <label>Kabupaten/Kota</label>
                    <input
                        type="text"
                    >
                </div>

                <div class="form-group">
                    <label>Provinsi</label>
                    <input
                        type="text"
                    >
                </div>

            </div>


            {{-- INFORMASI KONTAK --}}
            <div class="form-section-title">
                INFORMASI KONTAK
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input
                        type="text"
                    >
                </div>

                <div class="form-group">
                    <label>Email (opsional)</label>
                    <input
                        type="email"
                    >
                </div>

            </div>


            {{-- INFORMASI KUNJUNGAN --}}
            <div class="form-section-title">
                INFORMASI KUNJUNGAN
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label>Tanggal Kunjungan</label>

                    <div class="input-icon">
                        <input
                            type="date"
                            value="{{ date('Y-m-d') }}"
                        >

                        <i class="bi bi-calendar"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>Poli Tujuan</label>

                    <select>
                        <option>Poli Umum</option>
                        <option>Poli Dalam</option>
                        <option>Poli Gigi</option>
                        <option>Fisioterapi</option>
                        <option>Mikrobiologi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Kunjungan</label>

                    <select>
                        <option>Rawat Jalan</option>
                        <option>Rawat Inap</option>
                        <option>IGD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Cara Pembayaran</label>

                    <select>
                        <option>Umum</option>
                        <option>BPJS</option>
                    </select>
                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="modal-footer">

            <button
                type="button"
                class="btn-batal"
                onclick="tutupModalPasienBaru()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-daftarkan"
                onclick="simpanPasienBaru()"
            >
                Daftarkan Pasien
            </button>

        </div>

    </div>

</div>


{{-- =========================
     MODAL PENDAFTARAN KUNJUNGAN
========================== --}}

<div id="modalKunjungan" class="modal-overlay">

    <div class="modal-kunjungan">

        {{-- HEADER MODAL --}}
        <div class="modal-header">

            <div>
                <h2>Pendaftaran Kunjungan</h2>

                <p>Data identitas pasien terisi otomatis</p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="tutupModalKunjungan()"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        {{-- BODY MODAL --}}
        <div class="modal-body">

            {{-- DATA IDENTITAS --}}
            <div class="identity-card">

                <div class="identity-item">
                    <span>NO. RM</span>
                    <strong id="modalRm">-</strong>
                </div>

                <div class="identity-item">
                    <span>NAMA</span>
                    <strong id="modalNama">-</strong>
                </div>

                <div class="identity-item">
                    <span>NIK</span>
                    <strong id="modalNik">-</strong>
                </div>

                <div class="identity-item">
                    <span>ALAMAT</span>
                    <strong id="modalAlamat">-</strong>
                </div>

                <div class="identity-item">
                    <span>TEMPAT/TGL LAHIR</span>
                    <strong id="modalLahir">-</strong>
                </div>

                <div class="identity-item">
                    <span>JENIS KELAMIN</span>
                    <strong id="modalJk">-</strong>
                </div>

            </div>


            {{-- FORM --}}
            <div class="form-grid">

                {{-- TANGGAL --}}
                <div class="form-group">

                    <label for="tanggalKunjungan">
                        Tanggal Kunjungan
                    </label>

                    <div class="input-icon">

                        <input
                            type="date"
                            id="tanggalKunjungan"
                            value="{{ date('Y-m-d') }}"
                        >

                        <i class="bi bi-calendar"></i>

                    </div>

                </div>


                {{-- POLI --}}
                <div class="form-group">

                    <label for="poliTujuan">
                        Poli Tujuan
                    </label>

                    <select id="poliTujuan">

                        <option value="Poli Umum">
                            Poli Umum
                        </option>

                        <option value="Poli Dalam">
                            Poli Dalam
                        </option>

                        <option value="Poli Gigi">
                            Poli Gigi
                        </option>

                        <option value="Fisioterapi">
                            Fisioterapi
                        </option>

                        <option value="Mikrobiologi">
                            Mikrobiologi
                        </option>

                    </select>

                </div>


                {{-- JENIS KUNJUNGAN --}}
                <div class="form-group">

                    <label for="jenisKunjungan">
                        Jenis Kunjungan
                    </label>

                    <select id="jenisKunjungan">

                        <option value="Rawat Jalan">
                            Rawat Jalan
                        </option>

                        <option value="Rawat Inap">
                            Rawat Inap
                        </option>

                        <option value="IGD">
                            IGD
                        </option>

                    </select>

                </div>


                {{-- PEMBAYARAN --}}
                <div class="form-group">

                    <label for="caraPembayaran">
                        Cara Pembayaran
                    </label>

                    <select id="caraPembayaran">

                        <option value="Umum">
                            Umum
                        </option>

                        <option value="BPJS">
                            BPJS
                        </option>

                    </select>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="modal-footer">

            <button
                type="button"
                class="btn-batal"
                onclick="tutupModalKunjungan()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-daftarkan"
                onclick="daftarkanKunjungan()"
            >
                Daftarkan Pasien
            </button>

        </div>

    </div>

</div>
<script>

// =========================
// MODAL PENDAFTARAN KUNJUNGAN
// =========================

function bukaModalKunjungan(button) {

    document.getElementById('modalRm').textContent =
        button.dataset.rm || '-';

    document.getElementById('modalNama').textContent =
        button.dataset.nama || '-';

    document.getElementById('modalNik').textContent =
        button.dataset.nik || '-';

    document.getElementById('modalAlamat').textContent =
        button.dataset.alamat || '-';

    document.getElementById('modalLahir').textContent =
        button.dataset.lahir || '-';

    document.getElementById('modalJk').textContent =
        button.dataset.jk || '-';

    document.getElementById('modalKunjungan')
        .classList.add('show');

    document.body.style.overflow = 'hidden';
}


function tutupModalKunjungan() {

    document.getElementById('modalKunjungan')
        .classList.remove('show');

    document.body.style.overflow = '';
}


function daftarkanKunjungan() {

    alert('Pendaftaran kunjungan berhasil diproses.');

    tutupModalKunjungan();
}


// =========================
// MODAL PASIEN BARU
// =========================

function bukaModalPasienBaru() {

    document.getElementById('modalPasienBaru')
        .classList.add('show');

    document.body.style.overflow = 'hidden';
}


function tutupModalPasienBaru() {

    document.getElementById('modalPasienBaru')
        .classList.remove('show');

    document.body.style.overflow = '';
}


function simpanPasienBaru() {

    alert('Pendaftaran pasien berhasil diproses.');

    tutupModalPasienBaru();
}


// =========================
// KLIK DI LUAR MODAL
// =========================

document.getElementById('modalKunjungan')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            tutupModalKunjungan();
        }

    });


document.getElementById('modalPasienBaru')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            tutupModalPasienBaru();
        }

    });


// =========================
// TOMBOL ESC
// =========================

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        tutupModalKunjungan();
        tutupModalPasienBaru();

        if (typeof tutupDetailPasien === 'function') {
            tutupDetailPasien();
        }

    }

});

</script>
@endsection