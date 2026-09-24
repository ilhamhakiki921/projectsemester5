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

                        {{-- DATA CONTOH --}}

                        <tr>

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
    data-alamat="Jl. Dahlia No. 3, RT/RW 03/01, Pasteur, Sukajadi, Bandung, Jawa Barat"
><i class="bi bi-eye-fill"></i></button>

                                </div>

                            </td>

                        </tr>


                        


                        <tr>

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

                                </div>

                            </td>

                        </tr>


                        <tr>

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

                                </div>

                            </td>

                        </tr>


                        <tr>

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

                                </div>

                            </td>

                        </tr>


                        <tr>

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
                    <input type="text" id="cariRm" autocomplete="off" onkeydown="cariEnter(event)">
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
                    <input type="text" id="cariNik" placeholder="NIK lengkap" autocomplete="off" onkeydown="cariEnter(event)">
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

        // Detail Pasien sengaja TIDAK ditutup lewat ESC,
        // hanya bisa ditutup lewat tombol X sesuai permintaan.

    }

});

</script>
@endsection