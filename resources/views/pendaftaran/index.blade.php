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
    height: 36px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    padding: 0 14px;

    border: none;
    border-radius: 8px;

    background: #dc2626;
    color: #ffffff;

    text-decoration: none;

    font-family: 'Poppins', sans-serif;
    font-size: 12px;
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
        font-size: 13px;
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
            color: #111827;
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
    width: 10%;
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

/* =========================
   TELUSURI DATA PASIEN
   (tombol di baris "tidak ditemukan" + modal pencarian)
========================= */

.no-result-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 18px 0 10px;
}

.no-result-box > i {
    font-size: 26px;
    color: #b0bec5;
}

.no-result-box p {
    margin: 0;
    font-size: 14px;
    color: #78909c;
}

.btn-telusuri {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 42px;
    margin-top: 6px;
    padding: 0 20px;
    border: 1px solid #0f766e;
    border-radius: 8px;
    background: #ffffff;
    color: #0f766e;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease;
}

.btn-telusuri:hover {
    background: #0f766e;
    color: #ffffff;
}

#modalCariPasien {
    z-index: 10000;
}

.modal-cari {
    width: 860px;
    max-width: 100%;
    max-height: calc(100vh - 40px);
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    animation: modalMasuk 0.2s ease;
}

.cari-body {
    padding: 22px 28px 26px;
    overflow-y: auto;
    flex: 1;
}

.cari-body .form-grid {
    gap: 16px 18px;
}

.cari-body .form-group input {
    height: 42px;
}

.cari-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin: 18px 0 20px;
}

.btn-cari,
.btn-cari-baru,
.btn-pilih {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 40px;
    padding: 0 18px;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-cari {
    border: none;
    background: #0f766e;
    color: #ffffff;
}

.btn-cari:hover {
    background: #0b5f59;
}

.btn-cari-baru {
    border: 1px solid #0f766e;
    background: #ffffff;
    color: #0f766e;
}

.btn-cari-baru:hover {
    background: #f0f7f6;
}

.cari-hasil {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.cari-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 14px;
    background: #f8faf9;
    border-bottom: 1px solid #e5e7eb;
}

.btn-pilih {
    height: 36px;
    border: none;
    background: #0f766e;
    color: #ffffff;
}

.btn-pilih:hover {
    background: #0b5f59;
}

.cari-info {
    font-size: 12px;
    color: #78909c;
}

.cari-info.warn {
    color: #c62828;
}

.cari-scroll {
    max-height: 260px;
    overflow: auto;
}

.cari-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.cari-table th {
    position: sticky;
    top: 0;
    padding: 10px 14px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #455a64;
    white-space: nowrap;
}

.cari-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #f0f3f3;
    color: #222;
}

.cari-table tbody tr {
    cursor: pointer;
}

.cari-table tbody tr:hover {
    background: #f5f9f9;
}

.cari-table tbody tr.terpilih {
    background: #d9efec;
}

.cari-table tbody tr.cari-kosong {
    cursor: default;
}

.cari-table tbody tr.cari-kosong:hover {
    background: transparent;
}

.cari-kosong td {
    padding: 26px 14px;
    text-align: center;
    color: #78909c;
}

@media (max-width: 700px) {
    .cari-body .form-grid {
        grid-template-columns: 1fr;
    }
}

/* =========================
   MODAL PASIEN BARU - UKURAN COMPACT
========================= */

#modalPasienBaru .modal-pasien-baru {
    width: 820px;
}

#modalPasienBaru .modal-header {
    min-height: 68px;
    padding: 14px 24px;
}

#modalPasienBaru .modal-header h2 {
    margin: 0 0 2px;
    font-size: 17px;
}

#modalPasienBaru .modal-header p {
    font-size: 12px;
}

#modalPasienBaru .modal-close {
    width: 32px;
    height: 32px;
    font-size: 16px;
}

#modalPasienBaru .pasien-baru-body {
    padding: 18px 24px 22px;
}

#modalPasienBaru .form-section-title {
    margin: 0 0 12px;
    font-size: 13px;
}

#modalPasienBaru .form-section-title:not(:first-child) {
    margin-top: 20px;
}

#modalPasienBaru .pasien-baru-body .form-grid {
    gap: 14px 16px;
}

#modalPasienBaru .form-group {
    gap: 5px;
}

#modalPasienBaru .form-group label {
    font-size: 12px;
}

#modalPasienBaru .pasien-baru-body input,
#modalPasienBaru .pasien-baru-body select {
    height: 36px;
    padding: 0 12px;
    font-size: 12px;
    border-radius: 7px;
}

#modalPasienBaru .pasien-baru-body textarea {
    min-height: 60px;
    padding: 9px 12px;
    font-size: 12px;
    border-radius: 7px;
}

#modalPasienBaru .input-icon input {
    padding-right: 36px;
}

#modalPasienBaru .modal-footer {
    padding: 12px 24px;
}

#modalPasienBaru .btn-batal,
#modalPasienBaru .btn-daftarkan {
    height: 36px;
    padding: 0 16px;
    font-size: 12px;
}

/* =========================
   ACTION - ICON SAJA
========================= */

.action {
    gap: 8px;
}

.btn-kunjungan,
.btn-detail {
    width: 36px;
    height: 36px;
    min-height: 0;
    padding: 0;
    line-height: 1;
    font-size: 16px;
    border-radius: 8px;
}

.btn-kunjungan i,
.btn-detail i {
    font-size: 16px;
    line-height: 1;
}

.btn-kunjungan {
    color: #0f766e;
    border-color: #b7dcd8;
}

.btn-kunjungan:hover {
    background: #0f766e;
    border-color: #0f766e;
    color: #ffffff;
}

.btn-detail {
    color: #455a64;
}

.btn-detail:hover {
    background: #455a64;
    border-color: #455a64;
    color: #ffffff;
}

/* =========================
   ACTION - EDIT PASIEN
========================= */

/* 3 tombol aksi perlu lebih lebar dari sebelumnya */
.col-action {
    width: 136px;
}

.btn-edit {
    width: 36px;
    height: 36px;
    padding: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #ecd9b0;
    border-radius: 8px;

    background: #ffffff;
    color: #b7791f;

    font-size: 16px;
    line-height: 1;

    cursor: pointer;

    transition: background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease;
}

.btn-edit i {
    font-size: 16px;
    line-height: 1;
}

.btn-edit:hover {
    background: #b7791f;
    border-color: #b7791f;
    color: #ffffff;
}

/* baris yang baru diedit menyala sebentar */
@keyframes barisDisorot {
    from { background: #d9f0ec; }
    to   { background: #ffffff; }
}

.patient-table tbody tr.baris-diedit td {
    animation: barisDisorot 2.4s ease;
}


/* =========================
   MODAL EDIT PASIEN
========================= */

#modalEditPasien {
    z-index: 10001;
}

#modalEditPasien .modal-pasien-baru {
    width: 820px;
}

#modalEditPasien .modal-header {
    min-height: 68px;
    padding: 14px 24px;
}

#modalEditPasien .modal-header h2 {
    margin: 0 0 2px;
    font-size: 17px;
}

#modalEditPasien .modal-header p {
    font-size: 12px;
}

#modalEditPasien .modal-header p strong {
    color: #0f766e;
}

#modalEditPasien .modal-close {
    width: 32px;
    height: 32px;
    font-size: 16px;
}

#modalEditPasien .pasien-baru-body {
    padding: 18px 24px 22px;
}

#modalEditPasien .form-section-title {
    margin: 0 0 12px;
    font-size: 13px;
}

#modalEditPasien .form-section-title:not(:first-child) {
    margin-top: 20px;
}

#modalEditPasien .pasien-baru-body .form-grid {
    gap: 14px 16px;
}

#modalEditPasien .form-group {
    gap: 5px;
}

#modalEditPasien .form-group label {
    font-size: 12px;
}

#modalEditPasien .pasien-baru-body input,
#modalEditPasien .pasien-baru-body select {
    height: 36px;
    padding: 0 12px;
    font-size: 12px;
    border-radius: 7px;
}

#modalEditPasien .pasien-baru-body textarea {
    min-height: 60px;
    padding: 9px 12px;
    font-size: 12px;
    border-radius: 7px;
}

#modalEditPasien .modal-footer {
    flex-shrink: 0;
    padding: 12px 24px;
    background: #ffffff;
}

#modalEditPasien .btn-batal,
#modalEditPasien .btn-daftarkan {
    height: 36px;
    padding: 0 16px;
    font-size: 12px;
}

/* tanda wajib diisi + pesan error */
.wajib {
    margin-left: 2px;
    color: #dc2626;
}

.form-group.has-error input,
.form-group.has-error select,
.form-group.has-error textarea {
    border-color: #dc2626;
}

.form-group.has-error input:focus,
.form-group.has-error select:focus,
.form-group.has-error textarea:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
}

.field-error {
    font-size: 11px;
    line-height: 1.4;
    color: #dc2626;
}


/* =========================
   POPUP BERHASIL DISIMPAN
========================= */

.popup-overlay {
    position: fixed;
    inset: 0;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(0, 0, 0, 0.48);

    z-index: 10050;
}

.popup-overlay.show {
    display: flex;
}

.popup-box {
    width: 380px;
    max-width: 100%;

    padding: 28px 26px 22px;

    background: #ffffff;
    border-radius: 14px;

    text-align: center;

    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);

    animation: modalMasuk 0.2s ease;
}

.popup-icon {
    width: 56px;
    height: 56px;

    margin: 0 auto 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #e4f5eb;
    color: #28885a;

    font-size: 28px;
}

.popup-icon.info {
    background: #e5f1fa;
    color: #2875ad;
}

.popup-icon.peringatan {
    background: #fdf0d9;
    color: #c77700;
}

.popup-ok.peringatan {
    background: #d97706;
}

.popup-ok.peringatan:hover {
    background: #b45f04;
}

.popup-box h3 {
    margin: 0 0 6px;

    font-size: 17px;
    font-weight: 600;

    color: #111827;
}

.popup-box p {
    margin: 0 0 20px;

    font-size: 13px;
    line-height: 1.5;

    color: #607d8b;
}

.popup-box p strong {
    color: #111827;
}

.popup-ok {
    height: 40px;
    min-width: 110px;

    padding: 0 22px;

    border: none;
    border-radius: 8px;

    background: #0f766e;
    color: #ffffff;

    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;

    cursor: pointer;
}

.popup-ok:hover {
    background: #0b5f59;
}

@media (max-width: 700px) {

    #modalEditPasien .pasien-baru-body .form-grid {
        grid-template-columns: 1fr;
    }

    #modalEditPasien .pasien-baru-body .full-width {
        grid-column: auto;
    }

}

/* =========================
   HINT PADA PILIHAN & TANGGAL (PASIEN BARU)
   Abu-abu selama belum diisi, normal setelah diisi.
========================= */

#modalPasienBaru select:required:invalid,
#modalPasienBaru input[type="date"]:required:invalid {
    color: #757575;
}

#modalPasienBaru select option {
    color: #222222;
}

/* =========================
   HINT PADA PILIHAN & TANGGAL (KUNJUNGAN)
   Abu-abu selama belum diisi, normal setelah diisi.
========================= */

#modalKunjungan select:required:invalid,
#modalKunjungan input[type="date"]:required:invalid {
    color: #757575;
}

#modalKunjungan select option {
    color: #222222;
}

/* =========================
   ISI ALAMAT OTOMATIS (PASIEN BARU)
========================= */

.form-hint {
    font-size: 11px;
    line-height: 1.5;
    color: #78909c;
}

/* kolom yang terisi otomatis dari alamat lengkap */
#modalPasienBaru .pasien-baru-body input.terisi-otomatis {
    background: #f0faf8;
    border-color: #9fd3cc;
}

/* =========================
   KOLOM NOMOR BPJS (PASIEN BARU)
========================= */

.form-group[hidden] {
    display: none;
}

/* =========================
   MODAL GENERAL CONSENT (PASIEN BARU)
   Ukurannya dulu - konten/desain persetujuannya menyusul.
========================= */

#modalGeneralConsent {
    z-index: 10002;
}

.modal-general-consent {
    width: 650px;
    max-width: 100%;
    max-height: calc(100vh - 60px);

    display: flex;
    flex-direction: column;

    background: #ffffff;

    border-radius: 14px;

    overflow: hidden;

    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);

    animation: modalMasuk 0.2s ease;
}

#modalGeneralConsent .modal-body {
    overflow-y: auto;
}

/* Kotak kosong pengganti sementara, sebelum kontennya diisi */
.consent-placeholder {
    min-height: 220px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 24px;

    border: 1px dashed #d5dddd;
    border-radius: 10px;

    background: #f8faf9;

    color: #90a4ae;

    font-size: 13px;
    text-align: center;
}

.consent-agree {
    display: flex;
    align-items: flex-start;
    gap: 10px;

    margin-top: 18px;

    font-size: 12px;
    color: #455a64;

    cursor: pointer;
}

.consent-agree input[type="checkbox"] {
    margin-top: 2px;
}

.btn-daftarkan:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
                        id="searchInput"
                        class="search-input"
                        placeholder="Cari No. RM atau nama pasien..."
                        maxlength="16"
                        oninput="filterPasien()"
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

                <table class="patient-table" id="tabelPasien">

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


                    <tbody id="tabelPasienBody">

                        {{-- DATA CONTOH (atribut data-kecamatan dipakai dashboard untuk statistik pasien per kecamatan) --}}

                        <tr data-kecamatan="Paron">

                            <td class="col-no">
                                1
                            </td>

                            <td>
                                <span class="rm-number">
                                    123456
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

                            <span class="status-badge status-terdaftar">
                                Sudah Terdaftar
                            </span>

                            </td>

                            <td>

                                <div class="action">

                                    <button
                                        type="button"
                                        class="btn-kunjungan" title="Daftarkan Kunjungan" aria-label="Daftarkan Kunjungan"
                                        onclick="bukaModalKunjungan(this)"
                                    >
                                        <i class="bi bi-person-plus-fill"></i>
                                    </button>

                                    <button
    type="button"
    class="btn-detail" title="Detail Pasien" aria-label="Detail Pasien"
    onclick="bukaDetailPasien(this)"
    data-rm="123456"
    data-nama="Hendra Gunawan"
    data-nik="3204012208650005"
    data-jk="Laki-laki"
    data-lahir="Sumedang, 22 Agu 1965"
    data-golongan="O"
    data-umur="61 th"
    data-telepon="081234567805"
    data-alamat="Jl. Dahlia No. 3, RT/RW 03/01, Dawu, Paron, Ngawi, Jawa Timur"
><i class="bi bi-eye-fill"></i></button>

                                    <button
                                        type="button"
                                        class="btn-edit" title="Edit Data Pasien" aria-label="Edit Data Pasien"
                                        onclick="bukaModalEdit(this)"
                                    ><i class="bi bi-pencil-square"></i></button>

                                </div>

                            </td>

                        </tr>


                        


                        <tr data-kecamatan="Padas">

                            <td class="col-no">
                                2
                            </td>

                            <td>
                                <span class="rm-number">
                                    123457
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

                                <span class="status-badge status-terdaftar">
                                    Sudah Terdaftar
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan" title="Daftarkan Kunjungan" aria-label="Daftarkan Kunjungan"
                                    >
                                        <i class="bi bi-person-plus-fill"></i>
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail" title="Detail Pasien" aria-label="Detail Pasien"
                                    ><i class="bi bi-eye-fill"></i></a>

                                    <button
                                        type="button"
                                        class="btn-edit" title="Edit Data Pasien" aria-label="Edit Data Pasien"
                                        onclick="bukaModalEdit(this)"
                                    ><i class="bi bi-pencil-square"></i></button>

                                </div>

                            </td>

                        </tr>


                        <tr data-kecamatan="Geneng">

                            <td class="col-no">
                                3
                            </td>

                            <td>
                                <span class="rm-number">
                                    123458
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

                                <span class="status-badge status-terdaftar">
                                    Sudah Terdaftar
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan" title="Daftarkan Kunjungan" aria-label="Daftarkan Kunjungan"
                                    >
                                        <i class="bi bi-person-plus-fill"></i>
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail" title="Detail Pasien" aria-label="Detail Pasien"
                                    ><i class="bi bi-eye-fill"></i></a>

                                    <button
                                        type="button"
                                        class="btn-edit" title="Edit Data Pasien" aria-label="Edit Data Pasien"
                                        onclick="bukaModalEdit(this)"
                                    ><i class="bi bi-pencil-square"></i></button>

                                </div>

                            </td>

                        </tr>


                        <tr data-kecamatan="Paron">

                            <td class="col-no">
                                4
                            </td>

                            <td>
                                <span class="rm-number">
                                    123459
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

                                <span class="status-badge status-terdaftar">
                                    Sudah Terdaftar
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan" title="Daftarkan Kunjungan" aria-label="Daftarkan Kunjungan"
                                    >
                                        <i class="bi bi-person-plus-fill"></i>
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail" title="Detail Pasien" aria-label="Detail Pasien"
                                    ><i class="bi bi-eye-fill"></i></a>

                                    <button
                                        type="button"
                                        class="btn-edit" title="Edit Data Pasien" aria-label="Edit Data Pasien"
                                        onclick="bukaModalEdit(this)"
                                    ><i class="bi bi-pencil-square"></i></button>

                                </div>

                            </td>

                        </tr>


                        <tr data-kecamatan="Kedunggalar">

                            <td class="col-no">
                                5
                            </td>

                            <td>
                                <span class="rm-number">
                                    123460
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

                                <span class="status-badge status-terdaftar">
                                    Sudah Terdaftar
                                </span>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="#"
                                        class="btn-kunjungan" title="Daftarkan Kunjungan" aria-label="Daftarkan Kunjungan"
                                    >
                                        <i class="bi bi-person-plus-fill"></i>
                                    </a>

                                    <a
                                        href="#"
                                        class="btn-detail" title="Detail Pasien" aria-label="Detail Pasien"
                                    ><i class="bi bi-eye-fill"></i></a>

                                    <button
                                        type="button"
                                        class="btn-edit" title="Edit Data Pasien" aria-label="Edit Data Pasien"
                                        onclick="bukaModalEdit(this)"
                                    ><i class="bi bi-pencil-square"></i></button>

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
     MODAL PENCARIAN DATA PASIEN
========================== --}}

<div id="modalCariPasien" class="modal-overlay">

    <div class="modal-cari">

        {{-- HEADER --}}
        <div class="modal-header">

            <div>
                <h2>Pencarian Data Pasien</h2>
                <p>Telusuri pasien yang sudah terdaftar di sistem</p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="tutupModalCariPasien()"
                aria-label="Tutup"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        {{-- BODY --}}
        <div class="cari-body">

            <div class="form-grid">

                <div class="form-group">
                    <label for="cariRm">No. RM</label>
                    <input
                        type="text"
                        id="cariRm"
                        autocomplete="off"
                        inputmode="numeric"
                        maxlength="6"
                        onkeydown="cariEnter(event)"
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)"
                    >
                </div>

                <div class="form-group">
                    <label for="cariNama">Nama Pasien</label>
                    <input type="text" id="cariNama" autocomplete="off" onkeydown="cariEnter(event)">
                </div>

                <div class="form-group">
                    <label for="cariAlamat">Alamat</label>
                    <input type="text" id="cariAlamat" autocomplete="off" onkeydown="cariEnter(event)">
                </div>

                <div class="form-group">
                    <label for="cariLahir">Tanggal Lahir</label>
                    <input type="date" id="cariLahir" onkeydown="cariEnter(event)">
                </div>

                <div class="form-group">
                    <label for="cariNik">NIK</label>
                    <input
                        type="text"
                        id="cariNik"
                        placeholder="NIK lengkap"
                        autocomplete="off"
                        inputmode="numeric"
                        maxlength="16"
                        onkeydown="cariEnter(event)"
                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 16)"
                    >
                </div>

            </div>

            <div class="cari-actions">

                <button type="button" class="btn-cari" onclick="jalankanCariPasien()">
                    <i class="bi bi-search"></i> Cari
                </button>

                <button type="button" class="btn-cari-baru" onclick="cariPasienBaru()">
                    <i class="bi bi-plus-circle-fill"></i> Pasien Baru
                </button>

            </div>

            <div class="cari-hasil">

                <div class="cari-toolbar">

                    <button type="button" class="btn-pilih" onclick="pilihPasienDariCari()">
                        <i class="bi bi-check-circle-fill"></i> Pilih
                    </button>

                    <span class="cari-info" id="cariInfo">
                        Isi kriteria lalu klik Cari.
                    </span>

                </div>

                <div class="cari-scroll">

                    <table class="cari-table">

                        <thead>
                            <tr>
                                <th>No. RM</th>
                                <th>NIK</th>
                                <th>Nama Pasien</th>
                                <th>Alamat</th>
                                <th>Usia</th>
                                <th>Wali Pasien</th>
                            </tr>
                        </thead>

                        <tbody id="cariHasilBody">
                            <tr class="cari-kosong">
                                <td colspan="6">Belum ada pencarian.</td>
                            </tr>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

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
                    No. RM <span id="detailRm">123456</span>
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
                    <label for="baruNama">Nama lengkap<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruNama"
                        placeholder="Ketik nama sesuai KTP"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="baruNik">NIK<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruNik"
                        placeholder="16 digit angka sesuai KTP"
                        maxlength="16"
                        inputmode="numeric"
                        autocomplete="off"
                        required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                    >
                </div>

                <div class="form-group">
                    <label for="baruGolongan">Golongan Darah<span class="wajib">*</span></label>
                    <select id="baruGolongan" required>
                        <option value="" selected disabled>Pilih golongan darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="baruJk">Jenis Kelamin<span class="wajib">*</span></label>
                    <select id="baruJk" required>
                        <option value="" selected disabled>Pilih jenis kelamin</option>
                        <option value="Tidak diketahui">Tidak diketahui</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                        <option value="Tidak dapat ditentukan">Tidak dapat ditentukan</option>
                        <option value="Tidak mengisi">Tidak mengisi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="baruTempat">Tempat Lahir<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruTempat"
                        placeholder="Kota/kabupaten tempat lahir"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="baruTglLahir">Tanggal Lahir<span class="wajib">*</span></label>
                    <input
                        type="date"
                        id="baruTglLahir"
                        required
                    >
                </div>

            </div>


            {{-- ALAMAT --}}
            <div class="form-section-title">
                ALAMAT
            </div>

            <div class="form-grid">

                <div class="form-group full-width">
                    <label for="baruAlamat">Alamat Lengkap<span class="wajib">*</span></label>
                    <textarea
                        id="baruAlamat"
                        placeholder="Contoh: Jl. Dahlia No. 3, RT 03/RW 01, Dawu, Paron, Ngawi, Jawa Timur"
                        required
                    ></textarea>
                    <small class="form-hint">
                        Tulis berurutan dipisah koma: Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi.
                        Kolom RT/RW sampai Provinsi di bawah akan terisi otomatis.
                    </small>
                </div>

                <div class="form-group">
                    <label for="baruRtRw">RT/RW<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruRtRw"
                        placeholder="Contoh: 001/002"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="baruDesa">Desa/Kelurahan<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruDesa"
                        placeholder="Nama desa/kelurahan"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="baruKecamatan">Kecamatan<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruKecamatan"
                        placeholder="Nama kecamatan"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="baruKabupaten">Kabupaten/Kota<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruKabupaten"
                        placeholder="Nama kabupaten/kota"
                        autocomplete="off"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="baruProvinsi">Provinsi<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruProvinsi"
                        placeholder="Nama provinsi"
                        autocomplete="off"
                        required
                    >
                </div>

            </div>


            {{-- INFORMASI KONTAK --}}
            <div class="form-section-title">
                INFORMASI KONTAK
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="baruTelepon">Nomor Telepon<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruTelepon"
                        placeholder="Contoh: 081234567890"
                        maxlength="15"
                        inputmode="numeric"
                        autocomplete="off"
                        required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                    >
                </div>

                <div class="form-group">
                    <label for="baruEmail">Email (opsional)</label>
                    <input
                        type="email"
                        id="baruEmail"
                        placeholder="Contoh: nama@email.com"
                        autocomplete="off"
                    >
                </div>

            </div>


            {{-- INFORMASI KUNJUNGAN --}}
            <div class="form-section-title">
                INFORMASI KUNJUNGAN
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="baruTglKunjungan">Tanggal Kunjungan<span class="wajib">*</span></label>

                    <div class="input-icon">
                        <input
                            type="date"
                            id="baruTglKunjungan"
                            value="{{ date('Y-m-d') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="baruPoli">Poli Tujuan<span class="wajib">*</span></label>

                    <select id="baruPoli" required>
                        <option value="" selected disabled>Pilih poli tujuan</option>
                        <option>Poli Umum</option>
                        <option>Poli Dalam</option>
                        <option>Poli Gigi</option>
                        <option>Fisioterapi</option>
                        <option>Mikrobiologi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="baruJenisKunjungan">Jenis Kunjungan<span class="wajib">*</span></label>

                    <select id="baruJenisKunjungan" required>
                        <option value="" selected disabled>Pilih jenis kunjungan</option>
                        <option>Rawat Jalan</option>
                        <option>Rawat Inap</option>
                        <option>IGD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="baruPembayaran">Cara Pembayaran<span class="wajib">*</span></label>

                    <select id="baruPembayaran" required>
                        <option value="" selected disabled>Pilih cara pembayaran</option>
                        <option>Umum</option>
                        <option>BPJS</option>
                    </select>
                </div>

                {{-- Muncul otomatis kalau Cara Pembayaran = BPJS --}}
                <div class="form-group" id="grupBpjs" hidden>
                    <label for="baruNoBpjs">Nomor BPJS<span class="wajib">*</span></label>
                    <input
                        type="text"
                        id="baruNoBpjs"
                        placeholder="13 digit nomor kartu BPJS"
                        maxlength="13"
                        inputmode="numeric"
                        autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);"
                    >
                </div>

            </div>

        </div>


        {{-- FOOTER --}}
<div class="modal-footer">

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
     MODAL GENERAL CONSENT (PASIEN BARU)
     Kerangka & tombolnya dulu - konten/desain persetujuannya menyusul,
     tinggal isi bagian "consent-placeholder" di bawah kalau desainnya sudah fix.
========================== --}}

<div id="modalGeneralConsent" class="modal-overlay">

    <div class="modal-general-consent">

        {{-- HEADER --}}
        <div class="modal-header">

            <div>
                <h2>General Consent</h2>
                <p>Persetujuan umum tindakan medis pasien</p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="tutupModalGeneralConsent()"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        {{-- BODY --}}
        <div class="modal-body">

            {{-- TODO: ganti kotak placeholder ini dengan konten/desain General Consent yang sebenarnya --}}
            <div class="consent-placeholder">
                Konten General Consent menyusul (masih proses desain)
            </div>

            <label class="consent-agree">

                <input
                    type="checkbox"
                    id="cbGeneralConsent"
                    onchange="document.getElementById('btnSetujuGeneralConsent').disabled = !this.checked;"
                >

                Pasien/keluarga menyetujui isi General Consent di atas

            </label>

        </div>


        {{-- FOOTER --}}
        <div class="modal-footer">

            <button
                type="button"
                class="btn-batal"
                onclick="tutupModalGeneralConsent()"
            >
                Batal
            </button>

            <button
                type="button"
                id="btnSetujuGeneralConsent"
                class="btn-daftarkan"
                onclick="setujuiGeneralConsent()"
                disabled
            >
                Setuju & Lanjutkan
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
                        Tanggal Kunjungan<span class="wajib">*</span>
                    </label>

                    <div class="input-icon">

                        <input
                            type="date"
                            id="tanggalKunjungan"
                            value="{{ date('Y-m-d') }}"
                            required
                        >

                        <i class="bi bi-calendar"></i>

                    </div>

                </div>


                {{-- POLI --}}
                <div class="form-group">

                    <label for="poliTujuan">
                        Poli Tujuan<span class="wajib">*</span>
                    </label>

                    <select id="poliTujuan" required>

                        <option value="" selected disabled>
                            Pilih poli tujuan
                        </option>

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
                        Jenis Kunjungan<span class="wajib">*</span>
                    </label>

                    <select id="jenisKunjungan" required>

                        <option value="" selected disabled>
                            Pilih jenis kunjungan
                        </option>

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
                        Cara Pembayaran<span class="wajib">*</span>
                    </label>

                    <select id="caraPembayaran" required>

                        <option value="" selected disabled>
                            Pilih cara pembayaran
                        </option>

                        <option value="Umum">
                            Umum
                        </option>

                        <option value="BPJS">
                            BPJS
                        </option>

                    </select>

                </div>


                {{-- NOMOR BPJS: muncul otomatis kalau Cara Pembayaran = BPJS --}}
                <div class="form-group" id="grupBpjsKunjungan" hidden>

                    <label for="noBpjsKunjungan">
                        Nomor BPJS<span class="wajib">*</span>
                    </label>

                    <input
                        type="text"
                        id="noBpjsKunjungan"
                        placeholder="13 digit nomor kartu BPJS"
                        maxlength="13"
                        inputmode="numeric"
                        autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);"
                    >

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
<div class="modal-footer">

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


{{-- =========================
     MODAL EDIT DATA PASIEN
========================== --}}

<div id="modalEditPasien" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="editJudul">

    <div class="modal-pasien-baru">

        {{-- HEADER --}}
        <div class="modal-header">

            <div>
                <h2 id="editJudul">Edit Data Pasien</h2>
                <p>No. RM <strong id="editRm">-</strong> &middot; ubah data yang salah, lalu simpan</p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="tutupModalEdit()"
                aria-label="Tutup"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>


        {{-- BODY --}}
        <div class="pasien-baru-body">

            <div class="form-section-title">
                IDENTITAS PASIEN
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="editNama">Nama lengkap<span class="wajib">*</span></label>
                    <input type="text" id="editNama" placeholder="Nama sesuai KTP" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="editNik">NIK</label>
                    <input
                        type="text"
                        id="editNik"
                        placeholder="16 digit"
                        maxlength="16"
                        inputmode="numeric"
                        autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                    >
                </div>

                <div class="form-group">
                    <label for="editGolongan">Golongan Darah</label>
                    <select id="editGolongan">
                        <option value="" selected>Pilih golongan darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="editJk">Jenis Kelamin<span class="wajib">*</span></label>
                    <select id="editJk">
                        <option value="" selected>Pilih jenis kelamin</option>
                        <option value="Tidak diketahui">Tidak diketahui</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                        <option value="Tidak dapat ditentukan">Tidak dapat ditentukan</option>
                        <option value="Tidak mengisi">Tidak mengisi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="editTempat">Tempat Lahir</label>
                    <input type="text" id="editTempat" placeholder="Kota/kabupaten kelahiran" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="editTglLahir">Tanggal Lahir</label>
                    <input type="date" id="editTglLahir">
                </div>

            </div>


            <div class="form-section-title">
                ALAMAT &amp; KONTAK
            </div>

            <div class="form-grid">

                <div class="form-group full-width">
                    <label for="editAlamat">Alamat Lengkap<span class="wajib">*</span></label>
                    <textarea
                        id="editAlamat"
                        placeholder="Jalan, nomor, RT/RW, kelurahan, kecamatan, kota, provinsi"
                    ></textarea>
                </div>

                <div class="form-group">
                    <label for="editTelepon">Nomor Telepon</label>
                    <input
                        type="text"
                        id="editTelepon"
                        placeholder="Contoh: 081234567890"
                        maxlength="15"
                        inputmode="numeric"
                        autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                    >
                </div>

            </div>

        </div>


{{-- FOOTER --}}
<div class="modal-footer">

    <button
        type="button"
        class="btn-daftarkan"
        onclick="simpanEdit()"
    >
        Simpan Perubahan
    </button>

</div>

    </div>

</div>


{{-- =========================
     POPUP DATA BERHASIL DISIMPAN
========================== --}}

<div id="popupSimpan" class="popup-overlay" role="alertdialog" aria-modal="true" aria-labelledby="popupSimpanJudul">

    <div class="popup-box">

        <div class="popup-icon" id="popupSimpanIkon">
            <i class="bi bi-check-lg"></i>
        </div>

        <h3 id="popupSimpanJudul">Data berhasil disimpan</h3>

        <p id="popupSimpanPesan"></p>

        <button type="button" class="popup-ok" id="popupSimpanOk" onclick="tutupPopupSimpan()">
            OK
        </button>

    </div>

</div>

{{-- =========================
     POPUP PERINGATAN NOMOR BPJS (KUNJUNGAN)
========================== --}}

<div id="popupPeringatan" class="popup-overlay" role="alertdialog" aria-modal="true" aria-labelledby="popupPeringatanJudul">

    <div class="popup-box">

        <div class="popup-icon peringatan">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>

        <h3 id="popupPeringatanJudul">Nomor BPJS tidak valid</h3>

        <p id="popupPeringatanPesan"></p>

        <button type="button" class="popup-ok peringatan" id="popupPeringatanOk" onclick="tutupPopupPeringatan()">
            Perbaiki
        </button>

    </div>

</div>

<script>

// =========================
// PENCARIAN / FILTER TABEL PASIEN
// =========================

function filterPasien() {

    const keyword = document.getElementById('searchInput')
        .value.trim().toLowerCase();

    const tbody = document.getElementById('tabelPasienBody');
    const baris = tbody.querySelectorAll('tr');

    let adaHasil = false;

    baris.forEach(function(tr) {

        // Lewati baris pesan "tidak ditemukan" saat filter ulang
        if (tr.classList.contains('no-result-row')) {
            return;
        }

        const rm = tr.querySelector('.rm-number');
        const nama = tr.querySelector('.patient-name');

        const teksRm = rm ? rm.textContent.toLowerCase() : '';
        const teksNama = nama ? nama.textContent.toLowerCase() : '';

        const cocok = teksRm.includes(keyword) || teksNama.includes(keyword);

        tr.style.display = cocok ? '' : 'none';

        if (cocok) {
            adaHasil = true;
        }

    });

    let baristKosong = tbody.querySelector('.no-result-row');

    if (!adaHasil) {

        if (!baristKosong) {

            baristKosong = document.createElement('tr');
            baristKosong.className = 'no-result-row';

            const kolom = document.querySelectorAll('#tabelPasien thead th').length || 9;

            baristKosong.innerHTML =
                '<td colspan="' + kolom + '">' +
                    '<div class="no-result-box">' +
                        '<i class="bi bi-search"></i>' +
                        '<p>Data pasien tidak ditemukan.</p>' +
                        '<button type="button" class="btn-telusuri" onclick="bukaModalCariPasien()">' +
                            '<i class="bi bi-person-lines-fill"></i> Telusuri Data Pasien' +
                        '</button>' +
                    '</div>' +
                '</td>';

            tbody.appendChild(baristKosong);

        } else {
            baristKosong.style.display = '';
        }

    } else if (baristKosong) {
        baristKosong.style.display = 'none';
    }

}


// =========================
// MODAL TELUSURI DATA PASIEN
// =========================

let cariTerpilih = null;

function bukaModalCariPasien() {

    // Isi otomatis dari kata kunci di kolom pencarian utama
    const keyword = document.getElementById('searchInput').value.trim();

    ['cariRm', 'cariNama', 'cariAlamat', 'cariLahir', 'cariNik'].forEach(function(id) {
        document.getElementById(id).value = '';
    });

    if (keyword) {
        // Ada angka -> dianggap No. RM, selain itu dianggap nama
        const target = /\d/.test(keyword) ? 'cariRm' : 'cariNama';
        document.getElementById(target).value = keyword;
    }

    resetHasilCari('Belum ada pencarian.');

    document.getElementById('modalCariPasien').classList.add('show');
    document.body.style.overflow = 'hidden';

    if (keyword) {
        jalankanCariPasien();
    }

    document.getElementById('cariRm').focus();
}


function tutupModalCariPasien() {

    document.getElementById('modalCariPasien').classList.remove('show');
    document.body.style.overflow = '';
}


function cariEnter(event) {

    if (event.key === 'Enter') {
        event.preventDefault();
        jalankanCariPasien();
    }
}


function setInfoCari(teks, warn) {

    const info = document.getElementById('cariInfo');

    info.textContent = teks;
    info.classList.toggle('warn', !!warn);
}


function resetHasilCari(pesan) {

    cariTerpilih = null;

    const body = document.getElementById('cariHasilBody');
    body.innerHTML = '';

    const tr = document.createElement('tr');
    tr.className = 'cari-kosong';

    const td = document.createElement('td');
    td.colSpan = 6;
    td.textContent = pesan;

    tr.appendChild(td);
    body.appendChild(tr);

    setInfoCari('Isi kriteria lalu klik Cari.');
}


function jalankanCariPasien() {

    const params = new URLSearchParams();

    const kolom = {
        no_rm: 'cariRm',
        nama: 'cariNama',
        alamat: 'cariAlamat',
        tanggal_lahir: 'cariLahir',
        nik: 'cariNik'
    };

    Object.keys(kolom).forEach(function(key) {
        const nilai = document.getElementById(kolom[key]).value.trim();
        if (nilai) {
            params.append(key, nilai);
        }
    });

    resetHasilCari('Mencari data pasien...');

    fetch("{{ route('pasien.cari') }}?" + params.toString(), {
        headers: { 'Accept': 'application/json' }
    })
        .then(function(res) {
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            return res.json();
        })
        .then(function(data) {
            tampilkanHasilCari(data);
        })
        .catch(function() {
            resetHasilCari('Gagal mengambil data. Silakan coba lagi.');
        });
}


function tampilkanHasilCari(data) {

    const body = document.getElementById('cariHasilBody');
    body.innerHTML = '';
    cariTerpilih = null;

    if (!data.length) {
        resetHasilCari('Data pasien tidak ditemukan.');
        return;
    }

    data.forEach(function(p) {

        const tr = document.createElement('tr');

        [p.no_rm, p.nik, p.nama, p.alamat, p.usia, p.wali].forEach(function(teks) {
            const td = document.createElement('td');
            td.textContent = (teks === null || teks === undefined || teks === '') ? '-' : teks;
            tr.appendChild(td);
        });

        tr.addEventListener('click', function() {
            body.querySelectorAll('tr.terpilih').forEach(function(x) {
                x.classList.remove('terpilih');
            });
            tr.classList.add('terpilih');
            cariTerpilih = p;
            setInfoCari('Terpilih: ' + p.nama);
        });

        tr.addEventListener('dblclick', pilihPasienDariCari);

        body.appendChild(tr);
    });

    setInfoCari(data.length + ' pasien ditemukan. Klik baris untuk memilih.');
}


function pilihPasienDariCari() {

    if (!cariTerpilih) {
        setInfoCari('Pilih salah satu pasien terlebih dahulu.', true);
        return;
    }

    const p = cariTerpilih;

    tutupModalCariPasien();

    // bukaModalKunjungan membaca button.dataset, jadi cukup kirim objek dataset
    bukaModalKunjungan({
        dataset: {
            rm: p.no_rm,
            nama: p.nama,
            nik: p.nik,
            alamat: p.alamat,
            lahir: p.lahir,
            jk: p.jk
        }
    });
}


function cariPasienBaru() {

    tutupModalCariPasien();
    bukaModalPasienBaru();
}


// =========================
// MODAL DETAIL PASIEN (PENCARIAN DATA PASIEN)
// =========================

function bukaDetailPasien(button) {

    document.getElementById('detailNama').textContent =
        button.dataset.nama || '-';

    document.getElementById('detailRm').textContent =
        button.dataset.rm || '-';

    document.getElementById('detailNik').textContent =
        button.dataset.nik || '-';

    document.getElementById('detailJk').textContent =
        button.dataset.jk || '-';

    document.getElementById('detailLahir').textContent =
        button.dataset.lahir || '-';

    document.getElementById('detailGolongan').textContent =
        button.dataset.golongan || '-';

    document.getElementById('detailTelepon').textContent =
        button.dataset.telepon || '-';

    document.getElementById('detailUmur').textContent =
        button.dataset.umur || '-';

    document.getElementById('detailAlamat').textContent =
        button.dataset.alamat || '-';

    document.getElementById('modalDetailPasien')
        .classList.add('show');

    document.body.style.overflow = 'hidden';
}


function tutupDetailPasien() {

    document.getElementById('modalDetailPasien')
        .classList.remove('show');

    document.body.style.overflow = '';
}


// =========================
// MODAL PENDAFTARAN KUNJUNGAN
// =========================

function bukaModalKunjungan(button) {

    let d;

    if (button && typeof button.closest === 'function') {

        // Diklik dari tombol action di baris tabel -> ambil data lengkap milik barisnya
        const tr = button.closest('tr');
        d = tr ? ambilDataPasien(tr) : {};

    } else {

        // Dipanggil dari hasil "Pilih" pada modal Cari Pasien (bukan tombol asli di tabel)
        d = button.dataset || {};
    }

    const teksLahir = d.tglLahir ? isoKeTeks(d.tglLahir) : '';
    const lahir = (d.tempat && teksLahir)
        ? d.tempat + ', ' + teksLahir
        : (teksLahir || d.tempat || d.lahir || '');

    document.getElementById('modalRm').textContent = d.rm || '-';
    document.getElementById('modalNama').textContent = d.nama || '-';
    document.getElementById('modalNik').textContent = d.nik || '-';
    document.getElementById('modalAlamat').textContent = d.alamat || '-';
    document.getElementById('modalLahir').textContent = lahir || '-';
    document.getElementById('modalJk').textContent = d.jk || '-';

    // Kolom kunjungan selalu mulai bersih (hint tampil) tiap buka modal
    document.getElementById('tanggalKunjungan').value = hariIniIso();
    document.getElementById('poliTujuan').selectedIndex = 0;
    document.getElementById('jenisKunjungan').selectedIndex = 0;
    document.getElementById('caraPembayaran').selectedIndex = 0;
    document.getElementById('noBpjsKunjungan').value = '';
    document.getElementById('grupBpjsKunjungan').hidden = true;

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

    const wajib = ['tanggalKunjungan', 'poliTujuan', 'jenisKunjungan', 'caraPembayaran'];

    for (let i = 0; i < wajib.length; i++) {

        const el = document.getElementById(wajib[i]);

        if (!el.value) {
            el.focus();
            return;
        }
    }

    if (document.getElementById('caraPembayaran').value === 'BPJS') {

        const noBpjs = document.getElementById('noBpjsKunjungan');

        if (!/^\d{13}$/.test(noBpjs.value.trim())) {

            const jumlah = noBpjs.value.trim().length;

            const pesan = jumlah === 0
                ? 'Nomor BPJS wajib diisi dan harus terdiri dari <strong>13 digit</strong> angka.'
                : 'Nomor BPJS harus terdiri dari <strong>13 digit</strong> angka. '
                    + 'Yang Anda masukkan baru ' + jumlah + ' digit.';

            tampilkanPopupPeringatan(pesan);
            return;
        }
    }

    tutupModalKunjungan();

    const nama = document.getElementById('modalNama').textContent || '-';

    tampilkanPopupSimpan(
        'Kunjungan berhasil didaftarkan',
        'Pendaftaran kunjungan untuk pasien <strong></strong> berhasil diproses.',
        nama,
        false
    );
}


// Kolom Nomor BPJS (kunjungan) muncul otomatis kalau Cara Pembayaran = BPJS
document.getElementById('caraPembayaran')
    .addEventListener('change', function() {

        const bpjs = this.value === 'BPJS';
        const grup = document.getElementById('grupBpjsKunjungan');
        const input = document.getElementById('noBpjsKunjungan');

        grup.hidden = !bpjs;

        if (bpjs) {
            input.focus();
        } else {
            input.value = '';
        }
    });


// =========================
// MODAL PASIEN BARU
// =========================

const FIELD_BARU = {
    baruNama:           'Nama lengkap',
    baruNik:            'NIK',
    baruGolongan:       'Golongan darah',
    baruJk:             'Jenis kelamin',
    baruTempat:         'Tempat lahir',
    baruTglLahir:       'Tanggal lahir',
    baruAlamat:         'Alamat lengkap',
    baruRtRw:           'RT/RW',
    baruDesa:           'Desa/Kelurahan',
    baruKecamatan:      'Kecamatan',
    baruKabupaten:      'Kabupaten/Kota',
    baruProvinsi:       'Provinsi',
    baruTelepon:        'Nomor telepon',
    baruEmail:          'Email',
    baruTglKunjungan:   'Tanggal kunjungan',
    baruPoli:           'Poli tujuan',
    baruJenisKunjungan: 'Jenis kunjungan',
    baruPembayaran:     'Cara pembayaran',
    baruNoBpjs:         'Nomor BPJS'
};

function hariIniIso() {

    const d = new Date();

    return d.getFullYear() + '-' +
        String(d.getMonth() + 1).padStart(2, '0') + '-' +
        String(d.getDate()).padStart(2, '0');
}

let generalConsentDisetujui = false;

function bukaModalPasienBaru() {

    generalConsentDisetujui = false;

    Object.keys(FIELD_BARU).forEach(function(id) {
        tampilkanError(id, '');
    });

    // Tanggal lahir tidak boleh melewati hari ini
    document.getElementById('baruTglLahir').max = hariIniIso();

    document.getElementById('modalPasienBaru')
        .classList.add('show');

    document.body.style.overflow = 'hidden';

    document.getElementById('baruNama').focus();
}


function tutupModalPasienBaru() {

    document.getElementById('modalPasienBaru')
        .classList.remove('show');

    document.body.style.overflow = '';
}


// =========================
// GENERAL CONSENT (PASIEN BARU)
// Wajib disetujui dulu sebelum data pasien baru benar-benar disimpan.
// =========================

function bukaModalGeneralConsent() {

    document.getElementById('cbGeneralConsent').checked = false;
    document.getElementById('btnSetujuGeneralConsent').disabled = true;

    document.getElementById('modalGeneralConsent')
        .classList.add('show');
}


function tutupModalGeneralConsent() {

    document.getElementById('modalGeneralConsent')
        .classList.remove('show');
}


function setujuiGeneralConsent() {

    generalConsentDisetujui = true;

    tutupModalGeneralConsent();

    // Lanjutkan proses pendaftaran yang tadi berhenti nunggu persetujuan
    simpanPasienBaru();
}


function simpanPasienBaru() {

    const nilai = function(id) {
        return document.getElementById(id).value.trim();
    };

    // ---------- VALIDASI KOLOM WAJIB ----------
    const error = {};

    Object.keys(FIELD_BARU).forEach(function(id) {

        // Email boleh kosong
        if (id === 'baruEmail') {
            return;
        }

        // Nomor BPJS hanya wajib kalau Cara Pembayaran = BPJS
        if (id === 'baruNoBpjs' && nilai('baruPembayaran') !== 'BPJS') {
            return;
        }

        if (!nilai(id)) {

            const el = document.getElementById(id);

            error[id] = FIELD_BARU[id] +
                (el.tagName === 'SELECT' ? ' wajib dipilih.' : ' wajib diisi.');
        }
    });

    if (!error.baruNik && !/^\d{16}$/.test(nilai('baruNik'))) {
        error.baruNik = 'NIK harus 16 digit angka.';
    }

    if (!error.baruTelepon && !/^\d{8,15}$/.test(nilai('baruTelepon'))) {
        error.baruTelepon = 'Nomor telepon harus 8-15 digit angka.';
    }

    if (!error.baruNoBpjs && nilai('baruPembayaran') === 'BPJS' && !/^\d{13}$/.test(nilai('baruNoBpjs'))) {
        error.baruNoBpjs = 'Nomor BPJS harus 13 digit angka.';
    }

    if (!error.baruTglLahir && nilai('baruTglLahir') > hariIniIso()) {
        error.baruTglLahir = 'Tanggal lahir tidak boleh melebihi hari ini.';
    }

    if (nilai('baruEmail') && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(nilai('baruEmail'))) {
        error.baruEmail = 'Format email belum benar.';
    }

    let pertama = null;

    Object.keys(FIELD_BARU).forEach(function(id) {

        tampilkanError(id, error[id] || '');

        if (error[id] && !pertama) {
            pertama = id;
        }
    });

    if (pertama) {
        document.getElementById(pertama).focus();
        return;
    }

    // Semua kolom sudah benar -> minta persetujuan General Consent dulu
    // sebelum data pasien beneran disimpan
    if (!generalConsentDisetujui) {
        bukaModalGeneralConsent();
        return;
    }

    // ---------- BERHASIL ----------
    // ---------- BERHASIL ----------

    const dataBaru = {
        noRm:     generateNoRm(),
        nama:     nilai('baruNama'),
        nik:      nilai('baruNik'),
        golongan: nilai('baruGolongan'),
        jk:       nilai('baruJk'),
        tempat:   nilai('baruTempat'),
        tglLahir: nilai('baruTglLahir'),
        alamat:   nilai('baruAlamat'),
        kecamatan: nilai('baruKecamatan'),
        telepon:  nilai('baruTelepon'),
        tglKunjungan: nilai('baruTglKunjungan'),
        poli:     nilai('baruPoli')
    };

    tambahBarisPasienBaru(dataBaru);

    // Kosongkan form supaya siap dipakai untuk pasien berikutnya
    Object.keys(FIELD_BARU).forEach(function(id) {

        const el = document.getElementById(id);

        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
        } else {
            el.value = el.defaultValue;   // tanggal kunjungan kembali ke hari ini
        }

        el.classList.remove('terisi-otomatis');
        delete el.dataset.manual;
    });

    aturKolomBpjs();

    generalConsentDisetujui = false;

    tutupModalPasienBaru();

    tampilkanPopupSimpan(
        'Pasien berhasil didaftarkan',
        'Pendaftaran pasien <strong></strong> berhasil diproses.',
        dataBaru.nama,
        false
    );
}


// Nomor RM otomatis: ambil nomor terbesar yang sudah ada di tabel, lalu +1
function generateNoRm() {

    let terbesar = 0;

    document.querySelectorAll('#tabelPasien .rm-number').forEach(function(el) {
        const angka = parseInt(el.textContent.trim(), 10);
        if (!isNaN(angka) && angka > terbesar) {
            terbesar = angka;
        }
    });

    return String(terbesar + 1);
}


// Tambahkan satu baris pasien baru ke tabel (tanpa server/database)
function tambahBarisPasienBaru(d) {

    const tbody = document.getElementById('tabelPasienBody');

    // Baris "tidak ditemukan" (kalau lagi kefilter) tidak ikut dihitung
    const barisSekarang = tbody.querySelectorAll('tr:not(.no-result-row)').length;
    const noUrut = barisSekarang + 1;

    const teksLahir = d.tglLahir ? isoKeTeks(d.tglLahir) : '';
    const lahir = (d.tempat && teksLahir) ? d.tempat + ', ' + teksLahir : (teksLahir || d.tempat);
    const alamatJalan = d.alamat.split(',')[0].trim();
    const tglKunjunganTeks = d.tglKunjungan ? isoKeTeks(d.tglKunjungan) : '';

    const tr = document.createElement('tr');
    tr.dataset.kecamatan = d.kecamatan || '';

    tr.innerHTML =
        '<td class="col-no">' + noUrut + '</td>' +
        '<td><span class="rm-number">' + d.noRm + '</span></td>' +
        '<td><span class="patient-name"></span></td>' +
        '<td class="col-alamat-cell"></td>' +
        '<td>' + d.jk + '</td>' +
        '<td>' + tglKunjunganTeks + '</td>' +
        '<td>' + d.poli + '</td>' +
        '<td><span class="status-badge status-terdaftar">Sudah Terdaftar</span></td>' +
        '<td><div class="action">' +
            '<button type="button" class="btn-kunjungan" title="Daftarkan Kunjungan" aria-label="Daftarkan Kunjungan" onclick="bukaModalKunjungan(this)"><i class="bi bi-person-plus-fill"></i></button>' +
            '<button type="button" class="btn-detail" title="Detail Pasien" aria-label="Detail Pasien" onclick="bukaDetailPasien(this)"></button>' +
            '<button type="button" class="btn-edit" title="Edit Data Pasien" aria-label="Edit Data Pasien" onclick="bukaModalEdit(this)"><i class="bi bi-pencil-square"></i></button>' +
        '</div></td>';

    // Teks nama & alamat dimasukkan lewat textContent (bukan innerHTML) supaya aman dari karakter spesial
    tr.querySelector('.patient-name').textContent = d.nama;
    tr.querySelector('.col-alamat-cell').textContent = alamatJalan;

    const tombolDetail = tr.querySelector('.btn-detail');
    tombolDetail.dataset.rm = d.noRm;
    tombolDetail.dataset.nama = d.nama;
    tombolDetail.dataset.nik = d.nik;
    tombolDetail.dataset.jk = d.jk;
    tombolDetail.dataset.lahir = lahir;
    tombolDetail.dataset.golongan = d.golongan;
    tombolDetail.dataset.umur = hitungUmur(d.tglLahir);
    tombolDetail.dataset.telepon = d.telepon;
    tombolDetail.dataset.alamat = d.alamat;
    tombolDetail.innerHTML = '<i class="bi bi-eye-fill"></i>';

    tbody.appendChild(tr);
}


// Pesan error hilang begitu kolomnya diperbaiki
Object.keys(FIELD_BARU).forEach(function(id) {

    const el = document.getElementById(id);

    ['input', 'change'].forEach(function(evt) {
        el.addEventListener(evt, function() {
            tampilkanError(id, '');
        });
    });
});


// =========================
// KOLOM NOMOR BPJS (PASIEN BARU)
// Muncul kalau Cara Pembayaran = BPJS, hilang lagi kalau bukan.
// =========================

function aturKolomBpjs(fokus) {

    const bpjs = document.getElementById('baruPembayaran').value === 'BPJS';
    const grup = document.getElementById('grupBpjs');
    const input = document.getElementById('baruNoBpjs');

    grup.hidden = !bpjs;

    if (bpjs) {

        if (fokus) {
            input.focus();
        }

    } else {

        // Bukan BPJS -> kosongkan supaya nomor lama tidak ikut tersimpan
        input.value = '';
        tampilkanError('baruNoBpjs', '');
    }
}

document.getElementById('baruPembayaran')
    .addEventListener('change', function() {
        aturKolomBpjs(true);
    });


// =========================
// ISI OTOMATIS DARI ALAMAT LENGKAP (PASIEN BARU)
// Format: Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi
// =========================

const DAFTAR_PROVINSI = [
    'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan',
    'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung', 'Kepulauan Riau', 'DKI Jakarta',
    'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali',
    'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah',
    'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara',
    'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat',
    'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Selatan', 'Papua Tengah',
    'Papua Pegunungan', 'Papua Barat Daya'
];

// Nama singkatan / penulisan lain -> nama resmi
const ALIAS_PROVINSI = {
    'yogyakarta': 'DI Yogyakarta',
    'diy': 'DI Yogyakarta',
    'daerah istimewa yogyakarta': 'DI Yogyakarta',
    'jakarta': 'DKI Jakarta',
    'bangka belitung': 'Kepulauan Bangka Belitung',
    'ntb': 'Nusa Tenggara Barat',
    'ntt': 'Nusa Tenggara Timur'
};

function normKey(teks) {
    return teks.toLowerCase().replace(/\./g, '').replace(/\s+/g, ' ').trim();
}

const PETA_PROVINSI = {};

DAFTAR_PROVINSI.forEach(function(nama) {
    PETA_PROVINSI[normKey(nama)] = nama;
});

Object.keys(ALIAS_PROVINSI).forEach(function(k) {
    PETA_PROVINSI[k] = ALIAS_PROVINSI[k];
});

// Nama terpanjang dicek dulu ("Papua Barat Daya" sebelum "Papua Barat")
const URUT_PROVINSI = DAFTAR_PROVINSI.slice().sort(function(a, b) {
    return b.length - a.length;
});

function kanonProvinsi(teks) {
    return PETA_PROVINSI[normKey(teks)] || teks;
}

function bersihkanTeks(teks) {
    return teks.replace(/^[\s.,;:\-]+|[\s.,;:\-]+$/g, '').replace(/\s+/g, ' ');
}


// Memecah alamat lengkap. Mengembalikan null kalau bagian wilayahnya
// belum cukup jelas (kurang dari 3 bagian yang berhasil dikenali).
function uraikanAlamat(teks) {

    let t = teks.replace(/[\r\n]+/g, ', ').replace(/\s+/g, ' ').trim();

    const hasil = { rtrw: '', desa: '', kecamatan: '', kabupaten: '', provinsi: '' };

    // Ditulis seperti kalimat (hampir tanpa koma)?
    const modaKalimat = (t.match(/,/g) || []).length < 2;

    // ---------- RT / RW ----------
    const pad = function(angka) {
        return angka.length < 3 ? angka.padStart(3, '0') : angka;
    };

    const POLA_RTRW = [
        /\bRT\s*\/\s*RW\.?\s*[:.]?\s*(\d{1,3})\s*[\/\-]\s*(\d{1,3})\b/i,       // RT/RW 03/01
        /\bRT\.?\s*[:.]?\s*(\d{1,3})\s*[,\/\-]?\s*RW\.?\s*[:.]?\s*(\d{1,3})\b/i, // RT 03/RW 01, RT 03 RW 01
        /\bRT\.?\s*[:.]?\s*(\d{1,3})\s*[\/\-]\s*(\d{1,3})\b/i                   // RT 03/01
    ];

    POLA_RTRW.forEach(function(pola) {

        const m = pola.exec(t);

        if (!hasil.rtrw && m) {
            hasil.rtrw = pad(m[1]) + '/' + pad(m[2]);
            t = t.replace(m[0], ', ');
        }
    });

    if (!hasil.rtrw) {

        const rt = /\bRT\.?\s*[:.]?\s*(\d{1,3})\b/i.exec(t);
        const rw = /\bRW\.?\s*[:.]?\s*(\d{1,3})\b/i.exec(t);

        if (rt && rw) {
            hasil.rtrw = pad(rt[1]) + '/' + pad(rw[1]);
            t = t.replace(rt[0], ', ').replace(rw[0], ', ');
        }
    }

    // ---------- Alamat bergaya kalimat: sisipkan koma sebelum kata kunci wilayah ----------
    if (modaKalimat) {

        t = t.replace(
            /\s+(?=(?:desa|ds|kelurahan|kel|kecamatan|kec|kabupaten|kab|provinsi|prov)\.?\s+\S)/gi,
            ', '
        );

        // "Kota" hanya dianggap wilayah kalau sudah ada desa/kelurahan/kecamatan sebelumnya
        t = t.replace(/\s+(?=(?:kota|kodya)\s+\S)/gi, function(spasi, posisi, semua) {
            return /(?:^|[\s,])(?:desa|ds|kelurahan|kel|kecamatan|kec)\.?\s/i.test(semua.slice(0, posisi))
                ? ', '
                : spasi;
        });
    }

    const bagian = t.split(',').map(bersihkanTeks).filter(Boolean);

    if (!bagian.length) {
        return null;
    }

    // ---------- Provinsi yang menempel di ujung tanpa koma ("... Bandung Jawa Barat") ----------
    const terakhir = bagian[bagian.length - 1];

    for (let i = 0; i < URUT_PROVINSI.length; i++) {

        const nama = URUT_PROVINSI[i];
        const pola = new RegExp('\\s' + nama.replace(/\s+/g, '\\s+') + '$', 'i');

        if (pola.test(terakhir)) {

            const depan = bersihkanTeks(terakhir.replace(pola, ''));

            // "Provinsi Jawa Barat" / "Kota Jambi" jangan dipecah
            if (depan && !/^(?:kota|kodya|kabupaten|kab|provinsi|prov)\.?$/i.test(depan)) {
                bagian[bagian.length - 1] = depan;
                bagian.push(nama);
            }

            break;
        }
    }

    // ---------- Bagian pertama = jalan (kecuali langsung diawali kata kunci wilayah) ----------
    const KATA_WILAYAH = /^(?:desa|ds|kelurahan|kel|kecamatan|kec|kabupaten|kab|kota|kodya|provinsi|prov)\.?\s+\S/i;

    if (!KATA_WILAYAH.test(bagian[0])) {
        bagian.shift();
    }

    // ---------- Bagian yang punya label ----------
    const sisa = [];

    bagian.forEach(function(b) {

        let x;

        if ((x = /^(?:kelurahan|kel|desa|ds)\.?\s+(.+)$/i.exec(b))) {
            hasil.desa = hasil.desa || bersihkanTeks(x[1]);
        } else if ((x = /^(?:kecamatan|kec)\.?\s+(.+)$/i.exec(b))) {
            hasil.kecamatan = hasil.kecamatan || bersihkanTeks(x[1]);
        } else if ((x = /^(?:kabupaten|kab)\.?\s+(.+)$/i.exec(b))) {
            hasil.kabupaten = hasil.kabupaten || 'Kabupaten ' + bersihkanTeks(x[1]);
        } else if ((x = /^(?:kota|kodya)\.?\s+(.+)$/i.exec(b))) {
            hasil.kabupaten = hasil.kabupaten || 'Kota ' + bersihkanTeks(x[1]);
        } else if ((x = /^(?:provinsi|prov)\.?\s+(.+)$/i.exec(b))) {
            hasil.provinsi = hasil.provinsi || kanonProvinsi(bersihkanTeks(x[1]));
        } else {
            sisa.push(b);
        }
    });

    // ---------- Provinsi tanpa label: dikenali dari daftar provinsi ----------
    if (!hasil.provinsi && sisa.length) {

        const prov = PETA_PROVINSI[normKey(sisa[sisa.length - 1])];

        if (prov) {
            hasil.provinsi = prov;
            sisa.pop();
        }
    }

    // ---------- Sisanya tanpa label: diisi dari belakang (kabupaten/kota, kecamatan, desa) ----------
    const slot = ['desa', 'kecamatan', 'kabupaten'].filter(function(k) {
        return !hasil[k];
    });

    // Lebih banyak bagian daripada slot -> bagian paling belakang pasti provinsi
    if (!hasil.provinsi && sisa.length > slot.length) {
        slot.push('provinsi');
    }

    for (let i = 1; i <= Math.min(slot.length, sisa.length); i++) {
        hasil[slot[slot.length - i]] = sisa[sisa.length - i];
    }

    // Huruf awal tiap kata dikapitalkan ("pasteur" -> "Pasteur")
    ['desa', 'kecamatan', 'kabupaten'].forEach(function(k) {

        if (hasil[k]) {
            hasil[k] = hasil[k].replace(/(^|\s)(\S)/g, function(m, awal, huruf) {
                return awal + huruf.toUpperCase();
            });
        }
    });

    const jumlah = Object.keys(hasil).filter(function(k) {
        return hasil[k];
    }).length;

    return jumlah >= 3 ? hasil : null;
}


const KOLOM_ALAMAT_OTOMATIS = {
    rtrw:      'baruRtRw',
    desa:      'baruDesa',
    kecamatan: 'baruKecamatan',
    kabupaten: 'baruKabupaten',
    provinsi:  'baruProvinsi'
};

let timerAlamat = null;

// selesai = true  -> dipanggil saat pindah dari kolom alamat (selalu diproses)
// selesai = false -> dipanggil saat jeda mengetik (hanya kalau alamat sudah lengkap sampai provinsi)
function isiAlamatOtomatis(selesai) {

    const teks = document.getElementById('baruAlamat').value.trim();

    const semuaKolom = Object.keys(KOLOM_ALAMAT_OTOMATIS).map(function(k) {
        return document.getElementById(KOLOM_ALAMAT_OTOMATIS[k]);
    });

    // Alamat dikosongkan -> hapus isian otomatis (isian yang diketik sendiri tidak disentuh)
    if (!teks) {

        semuaKolom.forEach(function(el) {

            if (el.classList.contains('terisi-otomatis')) {
                el.value = '';
                el.classList.remove('terisi-otomatis');
            }
        });

        return;
    }

    const hasil = uraikanAlamat(teks);

    if (!hasil || (!selesai && !hasil.provinsi)) {
        return;
    }

    Object.keys(KOLOM_ALAMAT_OTOMATIS).forEach(function(k) {

        const id = KOLOM_ALAMAT_OTOMATIS[k];
        const el = document.getElementById(id);

        // Kolom yang sudah diketik/diubah sendiri oleh petugas tidak ditimpa
        if (el.dataset.manual === '1') {
            return;
        }

        if (hasil[k]) {

            el.value = hasil[k];
            el.classList.add('terisi-otomatis');
            tampilkanError(id, '');

        } else if (el.classList.contains('terisi-otomatis')) {

            el.value = '';
            el.classList.remove('terisi-otomatis');
        }
    });
}


(function() {

    const alamat = document.getElementById('baruAlamat');

    alamat.addEventListener('input', function() {
        clearTimeout(timerAlamat);
        timerAlamat = setTimeout(function() {
            isiAlamatOtomatis(false);
        }, 700);
    });

    alamat.addEventListener('blur', function() {
        clearTimeout(timerAlamat);
        isiAlamatOtomatis(true);
    });

    // Kalau petugas mengetik sendiri di kolom wilayah, jangan ditimpa lagi
    Object.keys(KOLOM_ALAMAT_OTOMATIS).forEach(function(k) {

        const el = document.getElementById(KOLOM_ALAMAT_OTOMATIS[k]);

        el.addEventListener('input', function() {

            el.classList.remove('terisi-otomatis');

            if (el.value.trim() === '') {
                delete el.dataset.manual;   // dikosongkan -> boleh diisi otomatis lagi
            } else {
                el.dataset.manual = '1';
            }
        });
    });

})();


// =========================
// ENTER = PINDAH OTOMATIS KE KOLOM BERIKUTNYA (MODAL PASIEN BARU)
// Supaya petugas tidak perlu mengarahkan kursor manual ke tiap kolom.
// =========================

(function () {

    const modalBaru = document.getElementById('modalPasienBaru');

    function isVisible(el) {
        return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
    }

    modalBaru.addEventListener('keydown', function (event) {

        if (event.key !== 'Enter') {
            return;
        }

        const target = event.target;

        // Kolom Alamat boleh multi-baris: Shift+Enter tetap bikin baris baru seperti biasa
        if (target.tagName === 'TEXTAREA' && event.shiftKey) {
            return;
        }

        // Jangan ganggu tombol Batal / Daftarkan Pasien
        if (target.tagName === 'BUTTON') {
            return;
        }

        // Hanya proses kolom-kolom form pasien baru (sesuai daftar FIELD_BARU)
        if (!FIELD_BARU.hasOwnProperty(target.id)) {
            return;
        }

        event.preventDefault();

        // Urutan kolom yang sedang tampil (kolom Nomor BPJS ikut cuma kalau lagi kelihatan)
        const urutan = Object.keys(FIELD_BARU).filter(function (id) {
            const el = document.getElementById(id);
            return el && !el.disabled && isVisible(el);
        });

        const index = urutan.indexOf(target.id);

        if (index === -1) {
            return;
        }

        const idBerikutnya = urutan[index + 1];

        if (idBerikutnya) {

            const next = document.getElementById(idBerikutnya);
            next.focus();

            // Kalau kolom berikutnya kolom teks, langsung blok isinya biar gampang ditimpa
            if (target.tagName !== 'SELECT' && typeof next.select === 'function' && next.tagName === 'INPUT') {
                try {
                    next.select();
                } catch (e) {}
            }

        } else {

            // Sudah di kolom terakhir -> arahkan fokus ke tombol Daftarkan Pasien (tidak langsung submit)
            const tombolDaftar = modalBaru.querySelector('.btn-daftarkan');

            if (tombolDaftar) {
                tombolDaftar.focus();
            }
        }
    });

})();


// =========================
// ENTER = PINDAH OTOMATIS KE KOLOM BERIKUTNYA (MODAL KUNJUNGAN)
// Kalau sudah di kolom terakhir, Enter langsung mendaftarkan kunjungannya.
// =========================

(function () {

    const modalKj = document.getElementById('modalKunjungan');

    function urutanKunjungan() {

        const dasar = ['tanggalKunjungan', 'poliTujuan', 'jenisKunjungan', 'caraPembayaran'];

        if (!document.getElementById('grupBpjsKunjungan').hidden) {
            dasar.push('noBpjsKunjungan');
        }

        return dasar;
    }

    modalKj.addEventListener('keydown', function (event) {

        if (event.key !== 'Enter') {
            return;
        }

        const target = event.target;

        // Jangan ganggu tombol Batal / Daftarkan Pasien
        if (target.tagName === 'BUTTON') {
            return;
        }

        const urutan = urutanKunjungan();
        const index = urutan.indexOf(target.id);

        if (index === -1) {
            return;
        }

        event.preventDefault();

        const idBerikutnya = urutan[index + 1];

        if (idBerikutnya) {

            const next = document.getElementById(idBerikutnya);
            next.focus();

            if (next.tagName === 'INPUT' && typeof next.select === 'function') {
                try {
                    next.select();
                } catch (e) {}
            }

        } else {

            // Sudah di kolom terakhir dan semua terisi -> langsung daftarkan
            daftarkanKunjungan();
        }
    });

})();


// =========================
// EDIT DATA PASIEN
// =========================

const NAMA_BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

const FIELD_EDIT = [
    'editNama', 'editNik', 'editGolongan', 'editJk',
    'editTempat', 'editTglLahir', 'editAlamat', 'editTelepon'
];

let barisEdit = null;     // baris tabel yang sedang diedit
let dataAwalEdit = null;  // data sebelum diedit (untuk cek ada perubahan atau tidak)


// Ambil sel tabel berdasarkan class header kolomnya (col-alamat, col-jk, ...)
function selKolom(tr, kelas) {

    const kepala = Array.from(document.querySelectorAll('#tabelPasien thead th'));
    const i = kepala.findIndex(function(th) {
        return th.classList.contains(kelas);
    });

    return i >= 0 ? tr.children[i] : null;
}


// 1965-08-22 -> "22 Agu 1965"
function isoKeTeks(iso) {

    const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');

    if (!m) {
        return '';
    }

    return parseInt(m[3], 10) + ' ' + NAMA_BULAN[parseInt(m[2], 10) - 1] + ' ' + m[1];
}


// "22 Agu 1965" -> 1965-08-22
function teksKeIso(teks) {

    const m = /^(\d{1,2})\s+([A-Za-z]+)\.?\s+(\d{4})$/.exec((teks || '').trim());

    if (!m) {
        return '';
    }

    const bulan = NAMA_BULAN.findIndex(function(b) {
        return b.toLowerCase() === m[2].substring(0, 3).toLowerCase();
    });

    if (bulan < 0) {
        return '';
    }

    return m[3] + '-' + String(bulan + 1).padStart(2, '0') + '-' + m[1].padStart(2, '0');
}


function hitungUmur(iso) {

    const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');

    if (!m) {
        return '';
    }

    const sekarang = new Date();

    let umur = sekarang.getFullYear() - parseInt(m[1], 10);

    const bulanIni = sekarang.getMonth() + 1;

    if (bulanIni < parseInt(m[2], 10) ||
        (bulanIni === parseInt(m[2], 10) && sekarang.getDate() < parseInt(m[3], 10))) {
        umur--;
    }

    return umur >= 0 ? umur + ' th' : '';
}


// Kumpulkan data pasien dari baris tabel + atribut data-* tombolnya
function ambilDataPasien(tr) {

    const tombolEdit = tr.querySelector('.btn-edit');
    const tombolDetail = tr.querySelector('.btn-detail[data-rm]');

    const s = Object.assign(
        {},
        tombolDetail ? tombolDetail.dataset : {},
        tombolEdit ? tombolEdit.dataset : {}
    );

    let tempat = s.tempat || '';
    let tglLahir = s.tglLahir || '';

    // Data awal: "Sumedang, 22 Agu 1965" -> tempat + tanggal terpisah
    if (!tglLahir && s.lahir) {

        const koma = s.lahir.lastIndexOf(',');

        if (koma >= 0) {
            tempat = s.lahir.slice(0, koma).trim();
            tglLahir = teksKeIso(s.lahir.slice(koma + 1));
        } else {
            tglLahir = teksKeIso(s.lahir);

            if (!tglLahir) {
                tempat = s.lahir;
            }
        }
    }

    const selAlamat = selKolom(tr, 'col-alamat');
    const selJk = selKolom(tr, 'col-jk');

    return {
        rm: tr.querySelector('.rm-number').textContent.trim(),
        nama: tr.querySelector('.patient-name').textContent.trim(),
        nik: s.nik || '',
        golongan: s.golongan || '',
        jk: s.jk || (selJk ? selJk.textContent.trim() : ''),
        tempat: tempat,
        tglLahir: tglLahir,
        alamat: s.alamat || (selAlamat ? selAlamat.textContent.trim() : ''),
        telepon: s.telepon || ''
    };
}


function isiSelect(id, nilai) {

    const el = document.getElementById(id);

    // Kalau nilai lama tidak ada di daftar pilihan, tambahkan supaya tidak hilang
    if (nilai && !Array.from(el.options).some(function(o) { return o.value === nilai; })) {

        const opsi = document.createElement('option');
        opsi.value = nilai;
        opsi.textContent = nilai;
        el.appendChild(opsi);
    }

    el.value = nilai || '';
}


function tampilkanError(id, pesan) {

    const grup = document.getElementById(id).closest('.form-group');
    let info = grup.querySelector('.field-error');

    if (pesan) {

        grup.classList.add('has-error');

        if (!info) {
            info = document.createElement('small');
            info.className = 'field-error';
            grup.appendChild(info);
        }

        info.textContent = pesan;

    } else {

        grup.classList.remove('has-error');

        if (info) {
            info.remove();
        }
    }
}


function bukaModalEdit(tombol) {

    barisEdit = tombol.closest('tr');

    const d = ambilDataPasien(barisEdit);

    dataAwalEdit = d;

    document.getElementById('editRm').textContent = d.rm || '-';

    document.getElementById('editNama').value = d.nama;
    document.getElementById('editNik').value = d.nik;
    document.getElementById('editTempat').value = d.tempat;
    document.getElementById('editTglLahir').value = d.tglLahir;
    document.getElementById('editAlamat').value = d.alamat;
    document.getElementById('editTelepon').value = d.telepon;

    isiSelect('editGolongan', d.golongan);
    isiSelect('editJk', d.jk);

    // Tanggal lahir tidak boleh melewati hari ini
    const hariIni = new Date();
    document.getElementById('editTglLahir').max =
        hariIni.getFullYear() + '-' +
        String(hariIni.getMonth() + 1).padStart(2, '0') + '-' +
        String(hariIni.getDate()).padStart(2, '0');

    FIELD_EDIT.forEach(function(id) {
        tampilkanError(id, '');
    });

    document.getElementById('modalEditPasien').classList.add('show');
    document.body.style.overflow = 'hidden';

    document.getElementById('editNama').focus();
}


function tutupModalEdit() {

    const modal = document.getElementById('modalEditPasien');

    if (!modal.classList.contains('show')) {
        return;
    }

    modal.classList.remove('show');
    document.body.style.overflow = '';
}


function simpanEdit() {

    if (!barisEdit) {
        return;
    }

    const d = {
        rm: dataAwalEdit.rm,
        nama: document.getElementById('editNama').value.trim().replace(/\s+/g, ' '),
        nik: document.getElementById('editNik').value.trim(),
        golongan: document.getElementById('editGolongan').value,
        jk: document.getElementById('editJk').value,
        tempat: document.getElementById('editTempat').value.trim(),
        tglLahir: document.getElementById('editTglLahir').value,
        alamat: document.getElementById('editAlamat').value.trim(),
        telepon: document.getElementById('editTelepon').value.trim()
    };

    // ---------- VALIDASI ----------
    const error = {};

    if (!d.nama) {
        error.editNama = 'Nama lengkap wajib diisi.';
    }

    if (d.nik && !/^\d{16}$/.test(d.nik)) {
        error.editNik = 'NIK harus 16 digit angka.';
    }

    if (!d.jk) {
        error.editJk = 'Jenis kelamin wajib dipilih.';
    }

    if (d.tglLahir && d.tglLahir > document.getElementById('editTglLahir').max) {
        error.editTglLahir = 'Tanggal lahir tidak boleh melebihi hari ini.';
    }

    if (!d.alamat) {
        error.editAlamat = 'Alamat lengkap wajib diisi.';
    }

    if (d.telepon && !/^\d{8,15}$/.test(d.telepon)) {
        error.editTelepon = 'Nomor telepon harus 8-15 digit angka.';
    }

    let pertama = null;

    FIELD_EDIT.forEach(function(id) {

        tampilkanError(id, error[id] || '');

        if (error[id] && !pertama) {
            pertama = id;
        }
    });

    if (pertama) {
        document.getElementById(pertama).focus();
        return;
    }

    // ---------- CEK ADA PERUBAHAN ----------
    const berubah = Object.keys(d).some(function(k) {
        return d[k] !== dataAwalEdit[k];
    });

    tutupModalEdit();

    if (!berubah) {
        tampilkanPopupSimpan(
            'Tidak ada perubahan',
            'Data pasien <strong></strong> tidak diubah.',
            d.nama,
            true
        );
        return;
    }

    // ---------- TERAPKAN KE TABEL ----------
    const tr = barisEdit;

    tr.querySelector('.patient-name').textContent = d.nama;

    const selAlamat = selKolom(tr, 'col-alamat');
    if (selAlamat) {
        // Tabel hanya menampilkan bagian pertama alamat (nama jalan)
        selAlamat.textContent = d.alamat.split(',')[0].trim();
    }

    const selJk = selKolom(tr, 'col-jk');
    if (selJk) {
        selJk.textContent = d.jk;
    }

    const teksLahir = d.tglLahir ? isoKeTeks(d.tglLahir) : '';
    const lahir = (d.tempat && teksLahir) ? d.tempat + ', ' + teksLahir : (teksLahir || d.tempat);

    // Tombol edit menyimpan data lengkapnya
    const tombolEdit = tr.querySelector('.btn-edit');

    tombolEdit.dataset.nik = d.nik;
    tombolEdit.dataset.golongan = d.golongan;
    tombolEdit.dataset.jk = d.jk;
    tombolEdit.dataset.tempat = d.tempat;
    tombolEdit.dataset.tglLahir = d.tglLahir;
    tombolEdit.dataset.alamat = d.alamat;
    tombolEdit.dataset.telepon = d.telepon;

    // Tombol detail (kalau sudah aktif) ikut diperbarui
    const tombolDetail = tr.querySelector('.btn-detail[data-rm]');

    if (tombolDetail) {
        tombolDetail.dataset.nama = d.nama;
        tombolDetail.dataset.nik = d.nik;
        tombolDetail.dataset.jk = d.jk;
        tombolDetail.dataset.lahir = lahir;
        tombolDetail.dataset.golongan = d.golongan;
        tombolDetail.dataset.umur = hitungUmur(d.tglLahir);
        tombolDetail.dataset.telepon = d.telepon;
        tombolDetail.dataset.alamat = d.alamat;
    }

    // Sorot baris yang baru diubah
    tr.classList.remove('baris-diedit');
    void tr.offsetWidth;
    tr.classList.add('baris-diedit');

    setTimeout(function() {
        tr.classList.remove('baris-diedit');
    }, 2500);

    tampilkanPopupSimpan(
        'Data berhasil disimpan',
        'Data pasien <strong></strong> (No. RM ' + d.rm + ') berhasil diperbarui.',
        d.nama,
        false
    );
}


// =========================
// POPUP BERHASIL DISIMPAN
// =========================

function tampilkanPopupSimpan(judul, pesanHtml, nama, netral) {

    document.getElementById('popupSimpanJudul').textContent = judul;

    const pesan = document.getElementById('popupSimpanPesan');
    pesan.innerHTML = pesanHtml;
    pesan.querySelector('strong').textContent = nama;   // nama dimasukkan sebagai teks (aman)

    const ikon = document.getElementById('popupSimpanIkon');
    ikon.classList.toggle('info', !!netral);
    ikon.innerHTML = netral
        ? '<i class="bi bi-info-lg"></i>'
        : '<i class="bi bi-check-lg"></i>';

    document.getElementById('popupSimpan').classList.add('show');
    document.getElementById('popupSimpanOk').focus();
}


function tutupPopupSimpan() {

    document.getElementById('popupSimpan').classList.remove('show');
}


// =========================
// POPUP PERINGATAN NOMOR BPJS (KUNJUNGAN)
// =========================

function tampilkanPopupPeringatan(pesanHtml) {

    document.getElementById('popupPeringatanPesan').innerHTML = pesanHtml;
    document.getElementById('popupPeringatan').classList.add('show');
    document.getElementById('popupPeringatanOk').focus();
}


function tutupPopupPeringatan() {

    const popup = document.getElementById('popupPeringatan');

    if (!popup.classList.contains('show')) {
        return;
    }

    popup.classList.remove('show');

    // Kembalikan kursor ke kolom Nomor BPJS supaya langsung bisa diperbaiki
    const noBpjs = document.getElementById('noBpjsKunjungan');

    if (!document.getElementById('grupBpjsKunjungan').hidden) {
        noBpjs.focus();
    }
}


// Pesan error hilang begitu kolomnya diperbaiki
FIELD_EDIT.forEach(function(id) {

    const el = document.getElementById(id);

    ['input', 'change'].forEach(function(evt) {
        el.addEventListener(evt, function() {
            tampilkanError(id, '');
        });
    });
});

// Enter di kolom isian = simpan (kecuali di kolom alamat yang boleh multi-baris)
document.getElementById('modalEditPasien')
    .addEventListener('keydown', function(event) {

        if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
            event.preventDefault();
            simpanEdit();
        }

    });

document.getElementById('popupSimpan')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            tutupPopupSimpan();
        }

    });

document.getElementById('popupPeringatan')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            tutupPopupPeringatan();
        }

    });


// =========================
// KLIK DI LUAR MODAL
// =========================

document.getElementById('modalGeneralConsent')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            tutupModalGeneralConsent();
        }

    });


// =========================
// TOMBOL ESC
// =========================

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        if (document.getElementById('popupPeringatan').classList.contains('show')) {
            tutupPopupPeringatan();
            return;
        }

        tutupModalKunjungan();
        tutupModalPasienBaru();
        tutupModalGeneralConsent();
        tutupModalEdit();
        tutupPopupSimpan();

        // Detail Pasien sengaja TIDAK ditutup lewat ESC,
        // hanya bisa ditutup lewat tombol X sesuai permintaan.

    }

});

</script>
@endsection