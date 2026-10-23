@extends('layouts.app')

@section('title', 'Berkas Rekam Medis — ' . $pasien->nama)

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
        padding: 26px 24px;
    }

    /* Tombol kembali ke daftar rekam medis */
    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        height: 34px;
        padding: 0 18px;
        margin-bottom: 16px;

        border: 1px solid #a30000;
        border-radius: 8px;
        background: #ffffff;
        color: #a30000;

        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;

        transition: background 0.15s ease, color 0.15s ease;
    }

    .btn-kembali:hover {
        background: #a30000;
        color: #ffffff;
    }

    /* =================================
       BAR IDENTITAS RINGKAS
    ================================== */

    .berkas-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px 48px;

        padding: 20px 34px;
        margin-bottom: 26px;

        border-radius: 10px;
        background: linear-gradient(100deg, #a81d21 0%, #7d0c10 50%, #520609 100%);
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(80, 0, 0, 0.3);
    }

    .berkas-bar .item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .berkas-bar .item .lbl {
        font-size: 11px;
        font-weight: 400;
        opacity: 0.92;
    }

    .berkas-bar .item .val {
        font-size: 12px;
        font-weight: 700;
    }

    /* =================================
       KARTU + TAB
    ================================== */

    .berkas-card {
        border: 1px solid #9b9b9b;
        border-radius: 8px;
        background: #ffffff;
        overflow: hidden;
    }

    .berkas-tabs {
        display: flex;
        gap: 34px;

        padding: 0 18px;
        overflow-x: auto;

        border-bottom: 1px solid #9b9b9b;
        scrollbar-width: thin;
        scrollbar-color: #9b9b9b #f1f1f1;
    }

    .berkas-tabs::-webkit-scrollbar {
        height: 8px;
    }

    .berkas-tabs::-webkit-scrollbar-thumb {
        border-radius: 4px;
        background: #9b9b9b;
    }

    .berkas-tabs::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .berkas-tab {
        position: relative;
        flex-shrink: 0;

        height: 34px;
        padding: 0 3px;

        border: none;
        background: transparent;
        cursor: pointer;

        font-family: 'Poppins', sans-serif;
        font-size: 11.5px;
        font-weight: 400;
        color: #222;
        white-space: nowrap;
    }

    .berkas-tab:hover {
        color: #a30000;
    }

    .berkas-tab.active::after {
        content: '';

        position: absolute;
        left: 0;
        right: 0;
        bottom: 5px;

        height: 1.5px;
        background: #d00000;
    }

    .berkas-panel {
        display: none;
        padding: 20px 18px 26px;
    }

    .berkas-panel.active {
        display: block;
    }

    /* =================================
       IDENTITAS (label : nilai)
    ================================== */

    .kv-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .kv-row {
        display: flex;
        font-size: 12px;
        color: #111;
    }

    .kv-row .k {
        width: 161px;
        flex-shrink: 0;
    }

    .kv-row .v {
        flex: 1;
        word-break: break-word;
    }

    /* Identitas pasien: dikelompokkan per bagian, isi tersusun rapi dalam kolom */
    .idn-section {
        margin-bottom: 22px;
    }

    .idn-section:last-child {
        margin-bottom: 0;
    }

    .idn-judul {
        margin: 0 0 12px;
        padding: 7px 12px;
        border-left: 4px solid #a81d21;
        border-radius: 0 6px 6px 0;
        background: #fbeeee;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.3px;
        color: #7a0a0a;
        text-transform: uppercase;
    }

    .idn-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px 28px;
        padding: 0 4px;
    }

    .idn-item {
        min-width: 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }

    .idn-item.lebar {
        grid-column: 1 / -1;
    }

    .idn-item .k {
        display: block;
        margin-bottom: 3px;
        font-size: 10.5px;
        color: #777;
    }

    .idn-item .v {
        display: block;
        font-size: 12.5px;
        font-weight: 500;
        color: #111;
        word-break: break-word;
    }

    @media (max-width: 1100px) {
        .idn-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 640px) {
        .idn-grid { grid-template-columns: 1fr; }
    }

    /* =================================
       TABEL RIWAYAT
    ================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .rw-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rw-table th {
        padding: 9px 10px;

        font-size: 10px;
        font-weight: 600;
        text-align: left;
        white-space: nowrap;
        color: #ffffff;

        background: linear-gradient(90deg, #b01c20 0%, #7a0a0a 55%, #4a0000 100%);
    }

    .rw-table th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
    .rw-table th:last-child  { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

    .rw-table td {
        padding: 11px 10px;

        font-size: 11px;
        color: #222;
        vertical-align: top;

        border-bottom: 1px solid #f0f0f0;
    }

    .rw-table tbody tr:hover td {
        background: #fafafa;
    }

    .empty-note {
        padding: 18px 0;

        font-size: 11.5px;
        color: #888;
        font-style: italic;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 9.5px;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-menunggu { background: #fff4e5; color: #b06a00; }
    .badge-sedang-diperiksa { background: #e8f0fe; color: #1967d2; }
    .badge-selesai { background: #e6f4ea; color: #1a7f37; }
    .badge-sudah { background: #c6e8c0; color: #1c5a14; border-radius: 5px; }
    .badge-belum { background: #e6e8b4; color: #6d6a00; border-radius: 5px; }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        height: 22px;
        padding: 0 10px;

        border: 1px solid #555;
        border-radius: 4px;
        background: #ffffff;

        color: #222;
        text-decoration: none;
        font-size: 9px;
        font-weight: 500;
    }

    .btn-detail:hover {
        background: #a30000;
        border-color: #a30000;
        color: #ffffff;
    }

    /* =================================
       RIWAYAT PEMERIKSAAN & SOAP
    ================================== */

    .periksa-item {
        margin-bottom: 14px;

        border: 1px solid #e2e2e2;
        border-radius: 8px;
        overflow: hidden;
    }

    .periksa-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;

        padding: 10px 14px;

        background: #f7f1f1;
        cursor: pointer;

        font-size: 11.5px;
        font-weight: 600;
        color: #5a0000;
    }

    .periksa-head .sub {
        font-weight: 400;
        color: #666;
        font-size: 10.5px;
    }

    .periksa-head i {
        transition: transform 0.2s ease;
    }

    .periksa-item.open .periksa-head i {
        transform: rotate(180deg);
    }

    .periksa-body {
        display: none;
        padding: 14px;
    }

    .periksa-item.open .periksa-body {
        display: block;
    }

    .sub-title {
        margin: 14px 0 8px;

        font-size: 11px;
        font-weight: 700;
        color: #a30000;
    }

    .sub-title:first-child {
        margin-top: 0;
    }

    .kv-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px 20px;
    }

    .kv-item {
        font-size: 11px;
        color: #222;
    }

    .kv-item.full {
        grid-column: 1 / -1;
    }

    .kv-item strong {
        display: block;
        margin-bottom: 2px;

        font-size: 9.5px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .chip {
        display: inline-block;
        margin: 0 4px 4px 0;
        padding: 2px 9px;

        border-radius: 12px;
        background: #f3e3e3;
        color: #6e0000;

        font-size: 10px;
    }

    /* =================================
       RIWAYAT KUNJUNGAN
    ================================== */

    .berkas-panel#panel-kunjungan {
        padding-top: 24px;
    }

    .kj-table {
        width: 100%;
        border-collapse: collapse;
    }

    .kj-table th {
        padding: 7px 8px;

        background: #9c1a1d;
        color: #ffffff;

        font-size: 9.5px;
        font-weight: 500;
        text-align: center;
    }

    .kj-table th:first-child { border-top-left-radius: 3px; border-bottom-left-radius: 3px; }
    .kj-table th:last-child  { border-top-right-radius: 3px; border-bottom-right-radius: 3px; }

    .kj-table td {
        padding: 9px 8px;

        font-size: 10px;
        color: #111;
        text-align: center;

        border-bottom: 1px solid #f1f1f1;
    }

    .kj-table .kiri {
        padding-left: 8px;
        text-align: left;
    }

    .kj-row {
        cursor: pointer;
    }

    .kj-row:hover td {
        background: #fbf3f3;
    }

    /* =================================
       RIWAYAT PEMERIKSAAN & SOAP
    ================================== */

    .rp-card {
        margin-bottom: 18px;

        border: 1px solid #9b9b9b;
        border-radius: 8px;
        background: #ffffff;
    }

    .rp-head {
        padding: 12px 14px;

        border-bottom: 1px solid #9b9b9b;

        font-size: 12px;
        font-weight: 700;
        color: #111;
    }

    .rp-body {
        padding: 14px 18px 20px;
    }

    .vital-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .vital-table th {
        height: 76px;
        padding: 6px;

        border: 1px solid #888;
        background: #e9e9e9;

        font-size: 12px;
        font-weight: 400;
        color: #111;
        text-align: center;
    }

    .vital-table td {
        height: 76px;
        padding: 6px;

        border: 1px solid #888;

        font-size: 12px;
        color: #111;
        text-align: center;
    }

    .soap-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 40px;

        margin: 24px 0 26px;
    }

    .soap-item .lbl {
        display: block;
        margin-bottom: 4px;

        font-size: 12px;
        color: #8a8a8a;
    }

    .soap-item .isi {
        display: block;

        font-size: 12px;
        color: #111;
        line-height: 1.55;
        white-space: pre-line;
    }

    .dx-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dx-row {
        display: flex;
        align-items: center;

        font-size: 12px;
    }

    .dx-row .k {
        width: 172px;
        flex-shrink: 0;

        color: #8a8a8a;
    }

    .dx-row .v {
        flex: 1;
        color: #111;
    }

    .koding-aksi {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .pill-koding {
        display: inline-block;
        min-width: 106px;
        padding: 4px 14px;

        border: 1px solid #9b9b9b;
        border-radius: 14px;

        font-size: 10px;
        font-weight: 500;
        text-align: center;
    }

    .pill-koding.ok    { background: #84ee84; border-color: #5fd05f; color: #0f4a0f; }
    .pill-koding.sudah { background: #e6e8b4; border-color: #cfd27d; color: #6d6a00; }
    .pill-koding.belum { background: #ececec; border-color: #cfcfcf; color: #666; }

    .btn-koding {
        min-width: 82px;
        height: 25px;
        padding: 0 14px;

        border: 1px solid #777;
        border-radius: 4px;
        background: #ffffff;
        cursor: pointer;

        font-family: 'Poppins', sans-serif;
        font-size: 9.5px;
        color: #111;
    }

    .btn-koding:hover {
        background: #a30000;
        border-color: #a30000;
        color: #ffffff;
    }

    @media (max-width: 800px) {
        .soap-grid { grid-template-columns: 1fr; }
    }

    /* ----- Aksi Edit pemeriksaan & SOAP ----- */
    .rp-aksi {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 14px;
    }

    .btn-edit-soap {
        min-width: 82px;
        height: 25px;
        padding: 0 14px;

        border: 1px solid #777;
        border-radius: 4px;
        background: #ffffff;
        cursor: pointer;

        font-family: 'Poppins', sans-serif;
        font-size: 9.5px;
        color: #111;
    }

    .btn-edit-soap:hover {
        background: #a30000;
        border-color: #a30000;
        color: #ffffff;
    }

    .info-terkunci {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;

        border: 1px solid #cfcfcf;
        border-radius: 14px;
        background: #ececec;

        font-size: 10px;
        color: #666;
        cursor: not-allowed;
    }

    /* ----- Tab Rujukan ----- */
    .ick-pill.rj-int { background: #dff3e2; border-color: #a9d9b0; color: #1a5c26; }
    .ick-pill.rj-ext { background: #e4ecfb; border-color: #b4c8ee; color: #1f3f86; }

    .img-ttd {
        display: block;
        max-width: 240px;
        max-height: 110px;
        padding: 6px;

        border: 1px solid #d9d9d9;
        border-radius: 6px;
        background: #ffffff;
    }

    a.btn-edit-soap.rj-cetak {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .f-group textarea {
        width: 100%;
        min-height: 112px;
        padding: 10px 14px;

        border: 1px solid #9b9b9b;
        border-radius: 8px;
        background: #ffffff;
        color: #222;
        resize: vertical;
        outline: none;

        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        line-height: 1.5;

        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .f-group textarea:focus {
        border-color: #b40000;
        box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
    }

    .f-group.salah textarea {
        border-color: #dc2626;
    }

    .f-group input[readonly] {
        background: #f3f3f3;
        color: #555;
    }

    .f-grid.dua {
        grid-template-columns: 1fr 1fr;
    }

    @media (max-width: 800px) {
        .f-grid.dua { grid-template-columns: 1fr; }
    }

    /* =================================
       HASIL LABORATORIUM (kosong)
    ================================== */

    .kosong-penunjang {
        padding: 56px 20px 64px;
        text-align: center;
    }

    .kosong-penunjang .judul {
        margin-bottom: 8px;

        font-size: 14px;
        font-weight: 600;
        color: #37474f;
    }

    .kosong-penunjang .sub {
        font-size: 14px;
        color: #78909c;
    }

    /* =================================
       INFORMED CONSENT (semua poli)
    ================================== */
    .ick-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .ick-pill {
        display: inline-block; padding: 3px 12px; border-radius: 999px;
        font-size: 12px; font-weight: 600; background: #ececec; color: #444;
    }
    .ick-pill.ok    { background: #84ee84; color: #0f4a0f; }
    .ick-pill.draft { background: #e6e8b4; color: #6d6a00; }
    .ick-pill.setuju { background: #84ee84; color: #0f4a0f; }
    .ick-pill.tolak  { background: #f6c4c4; color: #8a0f0f; }

    .ick-acc > summary { list-style: none; cursor: pointer; }
    .ick-acc > summary::-webkit-details-marker { display: none; }
    .ick-summary {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 8px 14px; user-select: none;
    }
    .ick-summary:hover { background: #fdf4f4; }
    .ick-summary .ick-judul { flex: 1 1 auto; }
    .ick-summary .ick-meta { margin: 0; }
    .ick-chevron {
        width: 10px; height: 10px; flex: 0 0 auto; margin: 0 4px 3px 4px;
        border-right: 2px solid #8a0f0f; border-bottom: 2px solid #8a0f0f;
        transform: rotate(45deg); transition: transform .2s;
    }
    .ick-acc[open] > summary .ick-chevron { transform: rotate(-135deg); margin-top: 6px; margin-bottom: 0; }
    .ick-acc > summary.rp-head { border-bottom: 0; }
    .ick-acc[open] > summary.rp-head { border-bottom: 1px solid #9b9b9b; }

    .ick-sub {
        margin: 18px 0 8px; font-size: 13px; font-weight: 600; color: #8a0f0f;
    }
    .ick-sub:first-child { margin-top: 0; }

    .ick-tbl { width: 100%; border-collapse: collapse; font-size: 13px; }
    .ick-tbl th, .ick-tbl td {
        padding: 8px 10px; border-bottom: 1px solid #eee; text-align: left; vertical-align: top;
    }
    .ick-tbl th { background: #fbeeee; color: #6d0d0d; font-weight: 600; }
    .ick-tbl td.c, .ick-tbl th.c { text-align: center; width: 150px; }
    .ick-tbl td.no { width: 50px; }

    .ick-ttd-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-top: 8px; }
    .ick-ttd { border: 1px solid #e3e3e3; border-radius: 8px; padding: 10px; text-align: center; }
    .ick-ttd .lbl { display: block; font-size: 12px; color: #78909c; margin-bottom: 6px; }
    .ick-ttd img { max-width: 100%; max-height: 110px; background: #fff; }

    /* =================================
       GENERAL CONSENT
    ================================== */

    .gc-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
    }

    .gc-top .kv-list {
        gap: 17px;
    }

    .gc-top .kv-row .k {
        width: 200px;
    }

    .gc-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .btn-outline,
    .btn-biru {
        height: 24px;
        padding: 0 18px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 9.5px;
        font-weight: 500;
    }

    .btn-outline {
        border: 1px solid #555;
        background: #ffffff;
        color: #222;
    }

    .btn-outline:hover {
        background: #f3f3f3;
    }

    .btn-biru {
        border: none;
        background: #2000c8;
        color: #ffffff;
    }

    .btn-biru:hover {
        background: #17009a;
        color: #ffffff;
    }

    .gc-tabel-wrap {
        margin-top: 30px;
    }

    .gc-table {
        width: 100%;
        border-collapse: collapse;
    }

    .gc-table th {
        padding: 8px 12px;

        background: #9c1a1d;
        color: #ffffff;

        font-size: 9.5px;
        font-weight: 500;
        text-align: left;
    }

    .gc-table th:first-child { border-top-left-radius: 3px; }
    .gc-table th:last-child  { border-top-right-radius: 3px; text-align: center; }

    .gc-table td {
        padding: 12px;

        font-size: 10.5px;
        color: #111;
        vertical-align: top;

        border-bottom: 1px solid #f0f0f0;
    }

    .gc-table .c-no { width: 50px; }
    .gc-table .c-sifat { width: 110px; }
    .gc-table .c-putusan { width: 120px; text-align: center; }

    .gc-table .judul {
        display: block;
        font-weight: 600;
    }

    .gc-table .isi {
        display: block;
        margin-top: 2px;
        color: #555;
        font-size: 10px;
        line-height: 1.5;
    }

    .keputusan {
        display: inline-block;
        min-width: 54px;
        padding: 3px 12px;

        border-radius: 12px;

        font-size: 9.5px;
        font-weight: 500;
        text-align: center;
    }

    .keputusan.setuju { background: #1fe81f; color: #063a06; }
    .keputusan.belum  { background: #e6e8b4; color: #6d6a00; }

    .flash-ok {
        margin-bottom: 14px;
        padding: 9px 14px;

        border-radius: 6px;
        background: #e6f4ea;
        color: #1a7f37;

        font-size: 11px;
        font-weight: 500;
    }

    /* =================================
       MODAL PERBARUI GENERAL CONSENT
       (gaya kolom sama dengan form Pendaftaran)
    ================================== */

    .modal-bg {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;

        align-items: center;
        justify-content: center;

        padding: 20px;

        background: rgba(17, 24, 39, .55);
        backdrop-filter: blur(2px);
    }

    .modal-bg.show {
        display: flex;
    }

    .modal-box {
        width: 900px;
        max-width: 100%;
        max-height: calc(100vh - 40px);

        display: flex;
        flex-direction: column;

        border-radius: 14px;
        background: #ffffff;
        overflow: hidden;

        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    .modal-head {
        padding: 22px 34px;
        border-bottom: 1px solid #c9c9c9;
    }

    .modal-head h3 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }

    .modal-main {
        flex: 1;
        overflow-y: auto;
        padding: 22px 34px 8px;
    }

    .alert-merah {
        margin-bottom: 24px;
        padding: 16px 28px;

        border-radius: 10px;
        background: #ff8f8f;
        color: #222;

        font-size: 12.5px;
        line-height: 1.6;
    }

    .f-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px 52px;

        margin-bottom: 26px;
    }

    .f-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .f-group.lebar {
        grid-column: span 2;
        padding-right: 52px;
    }

    .f-group label {
        font-size: 13px;
        font-weight: 500;
        color: #222;
    }

    .f-group .wajib {
        color: #dc2626;
    }

    .f-group input,
    .f-group select {
        width: 100%;
        height: 40px;
        padding: 0 14px;

        border: 1px solid #9b9b9b;
        border-radius: 8px;
        background: #ffffff;
        color: #222;

        font-family: 'Poppins', sans-serif;
        font-size: 13px;

        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .f-group input::placeholder {
        color: #9aa5a5;
    }

    /* Hint pada pilihan yang belum dipilih */
    .f-group select:invalid {
        color: #9aa5a5;
    }

    .f-group select option {
        color: #222;
    }

    /* Kolom aktif (diklik) = merah, sama seperti Pendaftaran & Pemeriksaan */
    .f-group input:focus,
    .f-group select:focus {
        border-color: #b40000;
        box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
    }

    .f-group.salah input,
    .f-group.salah select {
        border-color: #dc2626;
    }

    .f-pesan {
        font-size: 11px;
        color: #dc2626;
    }

    .pernyataan-judul {
        padding-bottom: 6px;
        margin-bottom: 12px;

        border-bottom: 1px solid #9b9b9b;

        font-size: 13px;
        font-weight: 500;
        color: #222;
    }

    .f-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;

        margin-bottom: 10px;

        font-size: 13px;
        line-height: 1.5;
        color: #222;
        cursor: pointer;
    }

    .f-check input {
        width: 15px;
        height: 15px;
        margin-top: 4px;
        flex-shrink: 0;

        accent-color: #b40000;
        cursor: pointer;
    }

    .tag-sifat {
        font-style: normal;
        font-size: 11px;
        color: #8a8a8a;
    }

    .tag-sifat.wajib {
        color: #b40000;
    }

    .f-error {
        margin-bottom: 16px;
        padding: 10px 14px;

        border-radius: 8px;
        background: #fdecec;
        color: #b00000;

        font-size: 12px;
    }

    .modal-foot {
        display: flex;
        justify-content: flex-end;
        gap: 16px;

        padding: 18px 34px;

        border-top: 1px solid #c9c9c9;
    }

    .btn-batal,
    .btn-simpan {
        height: 38px;
        padding: 0 26px;

        border-radius: 10px;
        cursor: pointer;

        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 500;
    }

    .btn-batal {
        border: 1px solid #555;
        background: #ffffff;
        color: #222;
    }

    .btn-batal:hover {
        background: #f3f3f3;
    }

    .btn-simpan {
        border: 1px solid #00d800;
        background: #00e600;
        color: #ffffff;
    }

    .btn-simpan:hover:not(:disabled) {
        background: #00c400;
    }

    .btn-simpan:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    @media (max-width: 800px) {
        .f-grid { grid-template-columns: 1fr; }
        .f-group.lebar { grid-column: auto; padding-right: 0; }
    }

</style>

@endsection


@section('content')

@php
    $umur = $pasien->tanggal_lahir ? $pasien->tanggal_lahir->age . ' tahun' : '-';
    $tempatLahir = $pasien->tempat_lahir ?? null;
    $tglLahir = $pasien->tanggal_lahir ? $pasien->tanggal_lahir->format('d-m-Y') : null;
    $ttl = trim(($tempatLahir ? $tempatLahir . ', ' : '') . ($tglLahir ?? ''));
    $alamat = trim(($pasien->alamat ?? '') . ($pasien->kecamatan ? ', ' . $pasien->kecamatan : ''));
    $consent = $pasien->generalConsent;
    $namaPoliDari = fn ($slug) => $namaPoli[$slug] ?? 'Poli ' . ucfirst($slug);
    $slugStatus = fn ($s) => str_replace(' ', '-', strtolower($s));
    $bulanPendek = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $tglIndo = fn ($tgl) => $tgl->format('j') . ' ' . $bulanPendek[(int) $tgl->format('n')] . ' ' . $tgl->format('Y');
    // Kode antrian: awalan dari nama poli (Poli Umum -> PU) + nomor 3 digit
    $kodeAntrian = function ($k) use ($namaPoliDari) {
        $kata = preg_split('/\s+/', trim($namaPoliDari($k->poli)));
        $awalan = count($kata) >= 2
            ? mb_substr($kata[0], 0, 1) . mb_substr($kata[1], 0, 1)
            : mb_substr($kata[0], 0, 2);
        return strtoupper($awalan) . '-' . str_pad((int) $k->no_antrian, 3, '0', STR_PAD_LEFT);
    };
    $namaPembayaran = fn ($v) => strtoupper($v) === 'BPJS' ? 'BPJS Kesehatan' : $v;
    $idKunjunganPertama = optional($kunjungan->sortBy([['tanggal_kunjungan', 'asc'], ['id', 'asc']])->first())->id;
@endphp

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Rekam Medis" icon="house-fill" />

        <section class="content">

            {{-- KEMBALI KE HALAMAN PERTAMA REKAM MEDIS --}}
            <a href="{{ route('rekam-medis.index') }}" class="btn-kembali">
                <span aria-hidden="true">&larr;</span> Kembali ke Daftar Rekam Medis
            </a>

            {{-- BAR IDENTITAS RINGKAS --}}
            <div class="berkas-bar">
                <div class="item"><span class="lbl">No.RM</span><span class="val">{{ $pasien->no_rm }}</span></div>
                <div class="item"><span class="lbl">Nama</span><span class="val">{{ $pasien->nama }}</span></div>
                <div class="item"><span class="lbl">NIK</span><span class="val">{{ $pasien->nik ?: '-' }}</span></div>
                <div class="item"><span class="lbl">Jenis Kelamin</span><span class="val">{{ $pasien->jenis_kelamin ?: 'Tidak diketahui' }}</span></div>
                <div class="item"><span class="lbl">Umur</span><span class="val">{{ $umur }}</span></div>
                <div class="item"><span class="lbl">Total Kunjungan</span><span class="val">{{ $kunjungan->count() }}x</span></div>
            </div>


            <div class="berkas-card">

                <div class="berkas-tabs">
                    <button type="button" class="berkas-tab active" data-tab="identitas">Identitas</button>
                    <button type="button" class="berkas-tab" data-tab="consent">General Consent</button>
                    <button type="button" class="berkas-tab" data-tab="kunjungan">Riwayat Kunjungan</button>
                    <button type="button" class="berkas-tab" data-tab="soap">Riwayat Pemeriksaan &amp; SOAP</button>
                    <button type="button" class="berkas-tab" data-tab="lab">Hasil Laboratorium</button>
                    <button type="button" class="berkas-tab" data-tab="resep">Riwayat Resep &amp; Obat</button>
                    <button type="button" class="berkas-tab" data-tab="rujukan">Rujukan</button>
                    <button type="button" class="berkas-tab" data-tab="informed-consent">Informed Consent</button>
                    <button type="button" class="berkas-tab" data-tab="resume">Resume Medis</button>
                    <button type="button" class="berkas-tab" data-tab="surat-medis">Surat Medis</button>
                    <button type="button" class="berkas-tab" data-tab="autentikasi">Autentikasi</button>
                </div>


                {{-- ========== IDENTITAS ========== --}}
                <div class="berkas-panel active" id="panel-identitas">
                    @php
                        // Semua data di bawah ini diambil dari form Pendaftaran
                        $isi = fn ($v) => ($v !== null && trim((string) $v) !== '') ? $v : '-';
                        $kelompokIdentitas = [
                            'Identitas Pasien' => [
                                ['No. Rekam Medis', $pasien->no_rm],
                                ['NIK', $pasien->nik],
                                ['Title', $pasien->title],
                                ['Nama Lengkap', $pasien->nama],
                                ['Tempat Lahir', $pasien->tempat_lahir],
                                ['Tanggal Lahir', $tglLahir],
                                ['Usia', $umur],
                                ['Jenis Kelamin', $pasien->jenis_kelamin],
                                ['Agama', $pasien->agama],
                                ['Status Pernikahan', $pasien->status_pernikahan],
                                ['No. Telp Pasien', $pasien->no_telepon],
                            ],
                            'Alamat' => [
                                ['Alamat', $pasien->alamat, 'lebar' => true],
                                ['Provinsi', $pasien->provinsi],
                                ['Kabupaten', $pasien->kabupaten],
                                ['Kecamatan', $pasien->kecamatan],
                                ['Kelurahan', $pasien->kelurahan],
                                ['Negara', $pasien->negara],
                            ],
                            'Pendidikan & Pekerjaan' => [
                                ['Pendidikan', $pasien->pendidikan],
                                ['Pekerjaan', $pasien->pekerjaan],
                            ],
                            'Data Lain Pasien' => [
                                ['No. KK', $pasien->no_kk],
                                ['Domisili', $pasien->domisili],
                                ['NIP', $pasien->nip],
                                ['Jabatan', $pasien->jabatan],
                                ['Divisi', $pasien->divisi],
                                ['Departemen', $pasien->departemen],
                                ['Sub Departemen', $pasien->sub_departemen],
                                ['Perusahaan', $pasien->perusahaan],
                                ['Suku', $pasien->suku],
                                ['Bahasa', $pasien->bahasa],
                                ['Kewarganegaraan', $pasien->kewarganegaraan],
                            ],
                            'Keluarga/Kerabat yang Bisa Dihubungi' => [
                                ['Nama Lengkap', $pasien->keluarga_nama],
                                ['Jenis Kelamin', $pasien->keluarga_jk],
                                ['Hubungan', $pasien->keluarga_hubungan],
                                ['No. Telp', $pasien->keluarga_telepon],
                            ],
                        ];
                    @endphp

                    @foreach ($kelompokIdentitas as $judulKelompok => $baris)
                        <div class="idn-section">
                            <h3 class="idn-judul">{{ $judulKelompok }}</h3>
                            <div class="idn-grid">
                                @foreach ($baris as $b)
                                    <div class="idn-item {{ ! empty($b['lebar']) ? 'lebar' : '' }}">
                                        <span class="k">{{ $b[0] }}</span>
                                        <span class="v">{{ $isi($b[1]) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>


                {{-- ========== GENERAL CONSENT ========== --}}
                <div class="berkas-panel" id="panel-consent">

                    @if (session('sukses_consent'))
                        <div class="flash-ok">{{ session('sukses_consent') }}</div>
                    @endif

                    @php
                        $setujuIdx = $consent ? $consent->pernyataanDisetujui() : [];
                        $pemberiWali = $consent && $consent->pemberi_persetujuan === 'Wali';
                    @endphp

                    <div style="margin:0 0 12px;padding:9px 12px;border:1px solid #f1d28a;border-left:4px solid #e0a100;border-radius:8px;background:#fff8e1;font-size:12px;color:#5a4300;">
                        <strong>Catatan:</strong> General Consent hanya diberikan satu kali kepada pasien baru. Pasien lama tidak diberikan General Consent pada kunjungan berikutnya.
                    </div>

                    <div class="gc-top">
                        <div class="kv-list">
                            <div class="kv-row"><span class="k">Tanggal</span><span class="v">{{ $consent && $consent->disetujui_pada ? $consent->disetujui_pada->format('d-m-Y H:i') : '-' }}</span></div>
                            <div class="kv-row"><span class="k">Penandatangan</span><span class="v">{{ $consent->nama_penyetuju ?? '-' }}</span></div>
                            <div class="kv-row"><span class="k">Hubungan dengan pasien</span><span class="v">{{ $consent ? ($pemberiWali ? ($consent->hubungan ?: '-') : 'Pasien sendiri') : '-' }}</span></div>
                            <div class="kv-row"><span class="k">Keluarga/pendamping</span><span class="v">{{ $consent->pendamping ?? '-' }}</span></div>
                            <div class="kv-row"><span class="k">Saksi</span><span class="v">{{ $consent->saksi ?? '-' }}</span></div>
                            <div class="kv-row"><span class="k">Petugas yang mencatat</span><span class="v">{{ $consent->petugas_pencatat ?? '-' }}</span></div>
                        </div>

                        <div class="gc-actions">
                            @if (auth()->user()?->bisaKelolaRekamMedis())
                            <a href="{{ route('rekam-medis.consent.cetak', $pasien->id) }}" target="_blank" class="btn-biru">Cetak Formulir</a>
                            @endif
                        </div>
                    </div>

                    <div class="gc-tabel-wrap table-wrapper">
                        <table class="gc-table">
                            <thead>
                                <tr>
                                    <th class="c-no">No</th>
                                    <th>Pernyataan</th>
                                    <th class="c-sifat">Sifat</th>
                                    <th class="c-putusan">Keputusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\GeneralConsent::PERNYATAAN as $n => $row)
                                    <tr>
                                        <td>{{ $n + 1 }}</td>
                                        <td>{{ $row[0] }}</td>
                                        <td>{{ $row[1] }}</td>
                                        <td class="c-putusan">
                                            @if (in_array($n, $setujuIdx, true))
                                                <span class="keputusan setuju">Setuju</span>
                                            @else
                                                <span class="keputusan belum">{{ $row[1] === 'Opsional' && $consent ? 'Tidak' : 'Belum' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>


                {{-- ========== RIWAYAT KUNJUNGAN ========== --}}
                <div class="berkas-panel" id="panel-kunjungan">
                    @if ($kunjungan->isEmpty())
                        <p class="empty-note">Belum ada riwayat kunjungan.</p>
                    @else
                        <div class="table-wrapper">
                            <table class="kj-table">
                                <thead>
                                    <tr>
                                        <th class="kiri">Tanggal</th>
                                        <th>Poli</th>
                                        <th>No. Antrian</th>
                                        <th>Jenis</th>
                                        <th>Pembayaran</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kunjungan as $k)
                                        <tr class="kj-row" data-href="{{ route('rekam-medis.show', $k->id) }}" title="Klik untuk melihat detail kunjungan">
                                            <td class="kiri">{{ $tglIndo($k->tanggal_kunjungan) }}</td>
                                            <td>{{ $namaPoliDari($k->poli) }}</td>
                                            <td>{{ $kodeAntrian($k) }}</td>
                                            <td>{{ $k->id === $idKunjunganPertama ? 'Baru' : 'Lama' }}</td>
                                            <td>{{ $namaPembayaran($k->pembayaran) }}</td>
                                            <td><span class="badge badge-{{ $slugStatus($k->status) }}">{{ $k->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>


                {{-- ========== RIWAYAT PEMERIKSAAN & SOAP ========== --}}
                <div class="berkas-panel" id="panel-soap">

                    @if (session('sukses_koding'))
                        <div class="flash-ok">{{ session('sukses_koding') }}</div>
                    @endif

                    @if (session('sukses_soap'))
                        <div class="flash-ok">{{ session('sukses_soap') }}</div>
                    @endif

                    @if (session('gagal_soap'))
                        <div class="f-error">{{ session('gagal_soap') }}</div>
                    @endif

                    @php
                        $adaPeriksa = $kunjungan->filter(fn ($k) => $k->rekamMedis);
                        // Nomor kunjungan menurut urutan waktu (kunjungan paling awal = 1)
                        $noKunjungan = $kunjungan->sortBy([['tanggal_kunjungan', 'asc'], ['id', 'asc']])
                            ->values()->pluck('id')->flip()->map(fn ($i) => $i + 1);
                    @endphp

                    @if ($adaPeriksa->isEmpty())
                        <p class="empty-note">Belum ada riwayat pemeriksaan.</p>
                    @else
                        @foreach ($adaPeriksa as $k)
                            @php
                                $rm = $k->rekamMedis;
                                $terverifikasi = ($rm->status_koding ?? null) === 'Terverifikasi';
                                $sudahKode = ! empty($rm->kode_icd10);
                            @endphp

                            <details class="rp-card ick-acc" @if (session('koding_kunjungan_id') == $k->id) open @endif>
                                <summary class="rp-head ick-summary">
                                    <span class="ick-judul">Kunjungan {{ $noKunjungan[$k->id] }} — {{ $namaPoliDari($k->poli) }}</span>
                                    <span class="ick-meta">
                                        <span class="ick-pill">{{ $k->tanggal_kunjungan ? $k->tanggal_kunjungan->format('d-m-Y') : '-' }}</span>
                                        @if ($rm->diagnosis_utama)
                                            <span class="ick-pill">{{ \Illuminate\Support\Str::limit($rm->diagnosis_utama, 40) }}{{ $sudahKode ? ' (' . $rm->kode_icd10 . ')' : '' }}</span>
                                        @endif
                                        @if ($terverifikasi)
                                            <span class="ick-pill ok">Terverifikasi</span>
                                        @elseif ($sudahKode)
                                            <span class="ick-pill draft">Sudah dikoding</span>
                                        @else
                                            <span class="ick-pill">Belum dikoding</span>
                                        @endif
                                    </span>
                                    <span class="ick-chevron" aria-hidden="true"></span>
                                </summary>

                                <div class="rp-body">

                                                                        <div class="rp-aksi">
                                        @if ($terverifikasi)
                                            <span class="info-terkunci" title="Sudah dikoding dan terverifikasi, data tidak dapat diedit lagi">&#128274; Terverifikasi — tidak dapat diedit</span>
                                        @elseif (auth()->user()?->isDokter())
                                            <button type="button" class="btn-edit-soap"
                                                    data-id="{{ $k->id }}"
                                                    data-action="{{ route('rekam-medis.soap.update', $k->id) }}"
                                                    data-rm="{{ json_encode([
                                                        'tb'               => $rm->tb,
                                                        'berat_badan'      => $rm->berat_badan,
                                                        'imt'              => $rm->imt,
                                                        'suhu_badan'       => $rm->suhu_badan,
                                                        'sistole'          => $rm->sistole,
                                                        'diastole'         => $rm->diastole,
                                                        'detak_jantung'    => $rm->detak_jantung,
                                                        'respiratory_rate' => $rm->respiratory_rate,
                                                        'saturasi'         => $rm->saturasi,
                                                        'gcs'              => $rm->gcs,
                                                        'subjective'       => $rm->subjective,
                                                        'objective'        => $rm->objective,
                                                        'assessment'       => $rm->assessment,
                                                        'plan'             => $rm->plan,
                                                    ]) }}">Edit</button>
                                        @else
                                            <span class="info-terkunci" title="Riwayat Pemeriksaan & SOAP hanya dapat dilihat">&#128274; Hanya dapat dilihat</span>
                                        @endif
                                    </div>

                                    <div class="table-wrapper">
                                        <table class="vital-table">
                                            <thead>
                                                <tr>
                                                    <th>TB</th>
                                                    <th>BB</th>
                                                    <th>IMT</th>
                                                    <th>Suhu</th>
                                                    <th>TD</th>
                                                    <th>Denyut<br>Jantung</th>
                                                    <th>RR</th>
                                                    <th>Saturasi</th>
                                                    <th>GCS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $rm->tb ?: '' }}</td>
                                                    <td>{{ $rm->berat_badan ?: '' }}</td>
                                                    <td>{{ $rm->imt ?: '' }}</td>
                                                    <td>{{ $rm->suhu_badan ?: '' }}</td>
                                                    <td>{{ $rm->td ?: '' }}</td>
                                                    <td>{{ $rm->detak_jantung ?: '' }}</td>
                                                    <td>{{ $rm->respiratory_rate ?: '' }}</td>
                                                    <td>{{ $rm->saturasi ?: '' }}</td>
                                                    <td>{{ $rm->gcs ?: '' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="soap-grid">
                                        <div class="soap-item"><span class="lbl">S — Subjective</span><span class="isi">{{ $rm->subjective ?: '-' }}</span></div>
                                        <div class="soap-item"><span class="lbl">O — Objective</span><span class="isi">{{ $rm->objective ?: '-' }}</span></div>
                                        <div class="soap-item"><span class="lbl">A — Assessment</span><span class="isi">{{ $rm->assessment ?: '-' }}</span></div>
                                        <div class="soap-item"><span class="lbl">P — Plan</span><span class="isi">{{ $rm->plan ?: '-' }}</span></div>
                                    </div>

                                    <div class="dx-list">
                                        <div class="dx-row"><span class="k">Diagnosa Utama</span><span class="v">{{ $rm->diagnosis_utama ?: '-' }}{{ $sudahKode ? ' (' . $rm->kode_icd10 . ')' : '' }}</span></div>
                                        <div class="dx-row"><span class="k">Diagnosa Sekunder</span><span class="v">{{ $rm->diagnosis_sekunder ? str_replace(' | ', '; ', $rm->diagnosis_sekunder) : '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Tindakan</span><span class="v">{{ $rm->tindakan ?: '-' }}</span></div>
                                        <div class="dx-row">
                                            <span class="k">Status Koding</span>
                                            <span class="v koding-aksi">
                                                @if ($terverifikasi)
                                                    <span class="pill-koding ok">Terverifikasi</span>
                                                @elseif ($sudahKode)
                                                    <span class="pill-koding sudah">Sudah dikoding</span>
                                                @else
                                                    <span class="pill-koding belum">Belum dikoding</span>
                                                @endif

                                                @if (auth()->user()?->bisaKelolaRekamMedis())
                                                <button type="button" class="btn-koding"
                                                        data-action="{{ route('rekam-medis.koding.update', $k->id) }}"
                                                        data-kode="{{ $rm->kode_icd10 }}"
                                                        data-diagnosa="{{ $rm->diagnosis_utama }}">Koding</button>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </details>
                        @endforeach
                    @endif
                </div>


                {{-- ========== HASIL LABORATORIUM ========== --}}
                <div class="berkas-panel" id="panel-lab">
                    {{-- Ubah jadi true kalau halaman Penunjang sudah jadi, supaya daftar permintaan/hasil ikut tampil --}}
                    @php $penunjangSiap = false; @endphp

                    @if (! $penunjangSiap || $penunjang->isEmpty())
                        <div class="kosong-penunjang">
                            <div class="judul">Belum ada pemeriksaan penunjang</div>
                            <div class="sub">Permintaan laboratorium untuk pasien ini belum pernah dibuat.</div>
                        </div>
                    @else
                        <div class="table-wrapper">
                            <table class="rw-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Pemeriksaan</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penunjang as $i => $p)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y') }}</td>
                                            <td>
                                                @foreach ($p->daftar as $nama)
                                                    <span class="chip">{{ $nama }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $p->keterangan ?: '-' }}</td>
                                            <td><span class="badge badge-{{ $slugStatus($p->status) }}">{{ $p->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>


                {{-- ========== RIWAYAT RESEP & OBAT ========== --}}
                <div class="berkas-panel" id="panel-resep">
                    @if ($resep->isEmpty())
                        <p class="empty-note">Belum ada riwayat resep obat.</p>
                    @else
                        <div class="table-wrapper">
                            <table class="rw-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Poli</th>
                                        <th>Nama Obat</th>
                                        <th>Sediaan</th>
                                        <th>Jumlah</th>
                                        <th>Dosis</th>
                                        <th>Frekuensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resep as $i => $r)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $r->tanggal_penulisan ? $r->tanggal_penulisan->format('d-m-Y') : $r->kunjungan->tanggal_kunjungan->format('d-m-Y') }}</td>
                                            <td>{{ $namaPoliDari($r->kunjungan->poli) }}</td>
                                            <td>{{ optional($r->obat)->nama_obat ?: '-' }}</td>
                                            <td>{{ optional($r->obat)->sediaan ?: '-' }}</td>
                                            <td>{{ $r->jumlah_obat }}</td>
                                            <td>{{ $r->dosis_obat }}</td>
                                            <td>{{ $r->frekuensi }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>


                {{-- ========== RUJUKAN ========== --}}
                <div class="berkas-panel" id="panel-rujukan">
                    @php
                        $kunjunganById = $kunjungan->keyBy('id');
                    @endphp

                    @if ($rujukan->isEmpty())
                        <p class="empty-note">Belum ada surat rujukan.</p>
                    @else
                        @foreach ($rujukan as $r)
                            @php
                                $internal = $r->jenis === 'Internal';
                                $kAsal    = $kunjunganById[$r->kunjungan_id] ?? null;
                                $tujuan   = $internal ? $namaPoliDari($r->poli_tujuan) : ($r->nama_faskes ?: '-');
                            @endphp

                            <details class="rp-card ick-acc">
                                <summary class="rp-head ick-summary">
                                    <span class="ick-judul">Rujukan {{ $internal ? 'Internal' : 'Eksternal' }} — {{ $tujuan }}</span>
                                    <span class="ick-meta">
                                        <span class="ick-pill">{{ $r->tanggal_surat ? $r->tanggal_surat->format('d-m-Y') : '-' }}</span>
                                        <span class="ick-pill {{ $internal ? 'rj-int' : 'rj-ext' }}">{{ $r->jenis }}</span>
                                    </span>
                                    <span class="ick-chevron" aria-hidden="true"></span>
                                </summary>

                                <div class="rp-body">

                                    <div class="rp-aksi">
                                        @if ($kAsal)
                                            @if (auth()->user()?->bisaKelolaRekamMedis())
                                            <a href="{{ route('rekam-medis.rujukan.cetak', [$kAsal->poli, $pasien->no_rm, $r->id]) }}"
                                               target="_blank" rel="noopener" class="btn-edit-soap rj-cetak">Cetak Surat</a>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="dx-list" style="margin-bottom: 22px;">
                                        <div class="dx-row"><span class="k">Tanggal Surat</span><span class="v">{{ $r->tanggal_surat ? $tglIndo($r->tanggal_surat) : '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Dari</span><span class="v">{{ $kAsal ? $namaPoliDari($kAsal->poli) : '-' }}</span></div>
                                        @if ($internal)
                                            <div class="dx-row"><span class="k">Poli Tujuan</span><span class="v">{{ $namaPoliDari($r->poli_tujuan) }}</span></div>
                                            <div class="dx-row"><span class="k">Jadwal Datang</span><span class="v">{{ $r->jadwal_datang ? $tglIndo($r->jadwal_datang) : '-' }}</span></div>
                                        @else
                                            <div class="dx-row"><span class="k">Ditujukan Kepada</span><span class="v">{{ $r->ditujukan_kepada ?: '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Rumah Sakit / Fasyankes</span><span class="v">{{ $r->nama_faskes ?: '-' }}</span></div>
                                        @endif
                                        <div class="dx-row"><span class="k">Dokter / Petugas Perujuk</span><span class="v">{{ $r->dokter_perujuk ?: '-' }}</span></div>
                                    </div>

                                    <div class="soap-grid">
                                        <div class="soap-item"><span class="lbl">Anamnesa</span><span class="isi">{{ $r->anamnesa ?: '-' }}</span></div>
                                        <div class="soap-item"><span class="lbl">Diagnosa</span><span class="isi">{{ $r->diagnosa ?: '-' }}</span></div>
                                        <div class="soap-item"><span class="lbl">Terapi</span><span class="isi">{{ $r->terapi ?: '-' }}</span></div>
                                    </div>

                                </div>
                            </details>
                        @endforeach
                    @endif
                </div>


                {{-- ========== INFORMED CONSENT (semua poli) ========== --}}
                <div class="berkas-panel" id="panel-informed-consent">

                    @php
                        // Seluruh Informed Consent pasien ini, dari poli apa pun, terbaru di atas
                        $daftarIc = $kunjungan->filter(fn ($k) => isset($informedConsent[$k->id]));
                        $noKunjunganIc = $kunjungan->sortBy([['tanggal_kunjungan', 'asc'], ['id', 'asc']])
                            ->values()->pluck('id')->flip()->map(fn ($i) => $i + 1);

                        // Isian tersimpan berbentuk daftar pasangan [nama_field, nilai] -> jadikan peta nama => [nilai, ...]
                        $icPeta = function ($ic) {
                            $peta = [];
                            foreach (($ic->data ?? []) as $baris) {
                                if (! is_array($baris) || ! isset($baris[0])) continue;
                                $peta[$baris[0]][] = $baris[1] ?? '';
                            }
                            return $peta;
                        };

                        $infoGigi = [
                            'Diagnosis/kelainan gigi dan mulut berdasarkan hasil pemeriksaan.',
                            'Hasil anamnesis, pemeriksaan gigi dan mulut serta pemeriksaan penunjang apabila diperlukan.',
                            'Tindakan kedokteran gigi yang akan dilakukan.',
                            'Nomor gigi/area yang akan dilakukan tindakan.',
                            'Alasan medis dilakukannya tindakan berdasarkan kondisi dan diagnosis pasien.',
                            'Tata cara tindakan sesuai prosedur kedokteran gigi, termasuk anestesi lokal apabila diperlukan.',
                            'Tujuan & manfaat: mengatasi keluhan, menghilangkan sumber infeksi/kerusakan, menjaga fungsi gigi.',
                            'Risiko & efek samping: nyeri, tidak nyaman, perdarahan, pembengkakan, reaksi obat/anestesi.',
                            'Komplikasi: infeksi, perdarahan berkepanjangan, reaksi alergi, gangguan penyembuhan.',
                            'Alternatif tindakan/perawatan lain yang sesuai kondisi pasien, termasuk observasi atau rujukan.',
                            'Risiko bila tindakan tidak dilakukan: keluhan menetap/memburuk, nyeri, infeksi, gangguan fungsi.',
                            'Prognosis dipengaruhi kondisi gigi/jaringan pendukung, kesehatan pasien, dan kepatuhan perawatan.',
                        ];
                        $tindakanGigi = [
                            'pencabutan'   => 'Pencabutan gigi',
                            'penambalan'   => 'Penambalan/restorasi',
                            'scaling'      => 'Scaling',
                            'bedah_minor'  => 'Tindakan bedah minor',
                            'saluran_akar' => 'Perawatan saluran akar',
                            'lainnya'      => 'Lainnya',
                        ];
                        $infoUmum = [
                            'Diagnosis', 'Dasar diagnosis', 'Tindakan kedokteran', 'Indikasi tindakan', 'Tata cara',
                            'Tujuan tindakan', 'Risiko bila tindakan dilakukan', 'Risiko bila tindakan tidak dilakukan',
                            'Komplikasi tindakan', 'Prognosis', 'Alternatif dan risiko',
                        ];
                    @endphp

                    @if ($daftarIc->isEmpty())
                        <p class="empty-note">Belum ada Informed Consent.</p>
                    @else
                        @foreach ($daftarIc as $k)
                            @php
                                $ic   = $informedConsent[$k->id];
                                $d    = $icPeta($ic);
                                $nilai = fn ($nama) => trim((string) ($d[$nama][0] ?? ''));
                                $adalahUmum = $k->poli === 'umum';
                                $keputusan = $nilai('keputusan');
                                $ttd = [
                                    'ttd_dokter' => 'Tanda tangan dokter',
                                    'ttd_pasien' => 'Tanda tangan pasien/wali',
                                    'ttd_saksi'  => 'Tanda tangan saksi',
                                ];
                                $dicentang = array_map('strval', $d[$adalahUmum ? 'dijelaskan[]' : 'informasi[]'] ?? []);
                                $tglIc = $adalahUmum
                                    ? ($nilai('tanggal') ? \Carbon\Carbon::parse($nilai('tanggal'))->format('d-m-Y') : '')
                                    : $nilai('tempat_tanggal');
                            @endphp

                            <details class="rp-card ick-acc">
                                <summary class="rp-head ick-summary">
                                    <span class="ick-judul">Kunjungan {{ $noKunjunganIc[$k->id] }} — {{ $namaPoliDari($k->poli) }}</span>
                                    <span class="ick-meta">
                                        <span class="ick-pill">{{ $k->tanggal_kunjungan ? $k->tanggal_kunjungan->format('d-m-Y') : '-' }}</span>
                                        @if ($k->dokter_tampil)
                                            <span class="ick-pill">{{ $k->dokter_tampil }}</span>
                                        @endif
                                        <span class="ick-pill {{ $ic->lengkap ? 'ok' : 'draft' }}">{{ $ic->lengkap ? 'Lengkap' : 'Draft (belum disimpan final)' }}</span>
                                        @if ($keputusan === 'setuju')
                                            <span class="ick-pill setuju">Menyetujui tindakan</span>
                                        @elseif ($keputusan === 'tolak')
                                            <span class="ick-pill tolak">Menolak tindakan</span>
                                        @endif
                                    </span>
                                    <span class="ick-chevron" aria-hidden="true"></span>
                                </summary>

                                <div class="rp-body">

                                    <div class="rp-aksi">
                                        @if (auth()->user()?->bisaKelolaRekamMedis())
                                        <a href="{{ route('rekam-medis.informed-consent.cetak', $k->id) }}"
                                           target="_blank" rel="noopener" class="btn-edit-soap rj-cetak">Cetak Informed Consent</a>
                                        @endif
                                    </div>

                                    {{-- Pemberian informasi --}}
                                    <div class="ick-sub">Pemberian Informasi</div>
                                    <div class="dx-list">
                                        <div class="dx-row"><span class="k">Penerima Informasi</span><span class="v">{{ $nilai('penerima_informasi') ?: '-' }}</span></div>
                                        @if ($adalahUmum)
                                            <div class="dx-row"><span class="k">Nama Tindakan</span><span class="v">{{ $nilai('nama_tindakan') ?: '-' }}</span></div>
                                        @else
                                            <div class="dx-row">
                                                <span class="k">Jenis Tindakan</span>
                                                <span class="v">
                                                    @php
                                                        $pilihTindakan = collect($d['tindakan[]'] ?? [])
                                                            ->map(fn ($kode) => $kode === 'lainnya' && $nilai('tindakan_lainnya')
                                                                ? 'Lainnya: ' . $nilai('tindakan_lainnya')
                                                                : ($tindakanGigi[$kode] ?? $kode))
                                                            ->implode(', ');
                                                    @endphp
                                                    {{ $pilihTindakan ?: '-' }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="dx-row"><span class="k">{{ $adalahUmum ? 'Area Tindakan' : 'Nomor Gigi / Area Tindakan' }}</span><span class="v">{{ $nilai('area_tindakan') ?: '-' }}</span></div>
                                    </div>

                                    <div class="table-wrapper" style="margin-top: 12px;">
                                        <table class="ick-tbl">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px">No</th>
                                                    <th>Jenis Informasi</th>
                                                    @if ($adalahUmum)<th>Isi Informasi</th>@endif
                                                    <th class="c">Sudah {{ $adalahUmum ? 'Dijelaskan' : 'Disampaikan' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (($adalahUmum ? $infoUmum : $infoGigi) as $i => $teks)
                                                    <tr>
                                                        <td class="no">{{ $i + 1 }}</td>
                                                        <td>{{ $teks }}</td>
                                                        @if ($adalahUmum)
                                                            <td>{{ $nilai('isi_informasi[' . ($i + 1) . ']') ?: '-' }}</td>
                                                        @endif
                                                        <td class="c">{{ in_array((string) ($i + 1), $dicentang, true) ? '✔' : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Data penandatangan --}}
                                    <div class="ick-sub">Penandatangan (Pasien/Wali)</div>
                                    <div class="dx-list">
                                        <div class="dx-row"><span class="k">Nama</span><span class="v">{{ $nilai('ttp_nama') ?: '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Umur</span><span class="v">{{ $nilai('ttp_umur') !== '' ? $nilai('ttp_umur') . ' tahun' : '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Jenis Kelamin</span><span class="v">{{ ['L' => 'Laki-laki', 'P' => 'Perempuan'][$nilai('ttp_jk')] ?? ($nilai('ttp_jk') ?: '-') }}</span></div>
                                        <div class="dx-row"><span class="k">Alamat</span><span class="v">{{ $nilai('ttp_alamat') ?: '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Hubungan dengan Pasien</span><span class="v">{{ $nilai('ttp_hubungan') ?: '-' }}</span></div>
                                    </div>

                                    {{-- Keputusan --}}
                                    <div class="ick-sub">Keputusan</div>
                                    <div class="dx-list">
                                        <div class="dx-row">
                                            <span class="k">Keputusan</span>
                                            <span class="v">{{ $keputusan === 'setuju' ? 'Menyetujui tindakan' : ($keputusan === 'tolak' ? 'Menolak tindakan' : '-') }}</span>
                                        </div>
                                        @if ($keputusan === 'tolak' && $adalahUmum)
                                            <div class="dx-row"><span class="k">Alasan Penolakan</span><span class="v">{{ $nilai('alasan_penolakan') ?: '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Memahami Risiko Penolakan</span><span class="v">{{ $nilai('paham_penolakan') === '1' ? 'Ya' : 'Belum' }}</span></div>
                                        @endif
                                        <div class="dx-row"><span class="k">{{ $adalahUmum ? 'Tanggal' : 'Tempat, Tanggal' }}</span><span class="v">{{ $tglIc ?: '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Pukul</span><span class="v">{{ $nilai('pukul') ?: '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Nama Saksi</span><span class="v">{{ $nilai('nama_saksi') ?: '-' }}</span></div>
                                    </div>

                                    {{-- Tanda tangan --}}
                                    @php
                                        $adaTtd = collect(array_keys($ttd))->filter(fn ($n) => str_starts_with($nilai($n), 'data:image/'));
                                    @endphp
                                    @if ($adaTtd->isNotEmpty())
                                        <div class="ick-sub">Tanda Tangan</div>
                                        <div class="ick-ttd-grid">
                                            @foreach ($adaTtd as $n)
                                                <div class="ick-ttd">
                                                    <span class="lbl">{{ $ttd[$n] }}</span>
                                                    <img src="{{ $nilai($n) }}" alt="{{ $ttd[$n] }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </details>
                        @endforeach
                    @endif
                </div>


                {{-- ========== RESUME MEDIS ========== --}}
                <div class="berkas-panel" id="panel-resume">
                    <link rel="stylesheet" href="{{ asset('css/resume-medis.css') }}">

                    @if ($kunjungan->isEmpty())
                        <p class="empty-note">Belum ada kunjungan, sehingga resume medis belum tersedia.</p>
                    @else
                        @foreach ($kunjungan as $k)
                            @php $rsm = $resume[$k->id] ?? null; @endphp
                            @continue(! $rsm)

                            <details class="rp-card ick-acc" @if ($loop->first) open @endif>
                                <summary class="rp-head ick-summary">
                                    <span class="ick-judul">Resume Medis — Kunjungan {{ $noKunjungan[$k->id] ?? $loop->iteration }} · {{ $namaPoliDari($k->poli) }}</span>
                                    <span class="ick-meta">
                                        <span class="ick-pill">{{ $tglIndo($k->tanggal_kunjungan) }}</span>
                                        <span class="ick-pill {{ $rsm['ada_rm'] ? 'ok' : 'draft' }}">{{ $rsm['ada_rm'] ? 'Terisi' : 'Belum diperiksa' }}</span>
                                    </span>
                                    <span class="ick-chevron" aria-hidden="true"></span>
                                </summary>

                                <div class="rp-body">
                                    <div class="rp-aksi">
                                        @if (auth()->user()?->bisaKelolaRekamMedis())
                                        <a href="{{ route('rekam-medis.resume.cetak', $k->id) }}"
                                           target="_blank" rel="noopener" class="btn-edit-soap rj-cetak">Cetak</a>
                                        @endif
                                    </div>

                                    @unless ($rsm['ada_rm'])
                                        <p class="rsm-catatan">Pemeriksaan untuk kunjungan ini belum diisi, jadi bagian pemeriksaan masih kosong.</p>
                                    @endunless

                                    <div style="overflow-x:auto; border:1px solid #d6d6d6; border-radius:8px; padding:22px 26px; background:#fff;">
                                        @include('rekam-medis._resume-medis', ['resume' => $rsm])
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    @endif
                </div>


                {{-- ========== SURAT MEDIS ========== --}}
                <div class="berkas-panel" id="panel-surat-medis">
                    @php
                        $kunjunganSm = $kunjungan->keyBy('id');
                        $angkaSm = fn ($v) => rtrim(rtrim(number_format((float) $v, 1, ',', ''), '0'), ',');
                    @endphp

                    @if (empty($suratMedis) || $suratMedis->isEmpty())
                        <p class="empty-note">Belum ada surat medis.</p>
                    @else
                        @foreach ($suratMedis as $sm)
                            @php
                                $kSm   = $kunjunganSm[$sm->kunjungan_id] ?? null;
                                $sakit = $sm->jenis === 'Sakit';
                            @endphp

                            <details class="rp-card ick-acc">
                                <summary class="rp-head ick-summary">
                                    <span class="ick-judul">Surat Keterangan {{ $sakit ? 'Sakit' : 'Sehat' }} — {{ $sm->nomor_surat }}</span>
                                    <span class="ick-meta">
                                        <span class="ick-pill">{{ $sm->tanggal_surat ? $sm->tanggal_surat->format('d-m-Y') : '-' }}</span>
                                        <span class="ick-pill {{ $sakit ? 'tolak' : 'setuju' }}">{{ $sakit ? 'Sakit' : 'Sehat' }}</span>
                                    </span>
                                    <span class="ick-chevron" aria-hidden="true"></span>
                                </summary>

                                <div class="rp-body">

                                    <div class="rp-aksi">
                                        @if ($kSm)
                                            @if (auth()->user()?->bisaKelolaRekamMedis())
                                            <a href="{{ route('rekam-medis.surat-medis.cetak', [$kSm->poli, $pasien->no_rm, $sm->id]) }}"
                                               target="_blank" rel="noopener" class="btn-edit-soap rj-cetak">Cetak</a>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="dx-list" style="margin-bottom: 22px;">
                                        <div class="dx-row"><span class="k">Nomor Surat</span><span class="v">{{ $sm->nomor_surat }}</span></div>
                                        <div class="dx-row"><span class="k">Poli</span><span class="v">{{ $kSm ? $namaPoliDari($kSm->poli) : '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Alamat Pasien</span><span class="v">{{ $sm->alamat_pasien ?: '-' }}</span></div>

                                        @if ($sakit)
                                            <div class="dx-row"><span class="k">Lama Istirahat</span><span class="v">{{ $sm->lama_istirahat }} hari</span></div>
                                            <div class="dx-row"><span class="k">Mulai Tanggal</span><span class="v">{{ $sm->mulai_tanggal ? $tglIndo($sm->mulai_tanggal) : '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Sampai Tanggal</span><span class="v">{{ $sm->sampai_tanggal ? $tglIndo($sm->sampai_tanggal) : '-' }}</span></div>
                                        @else
                                            <div class="dx-row"><span class="k">Tanggal Pemeriksaan</span><span class="v">{{ $sm->tanggal_pemeriksaan ? $tglIndo($sm->tanggal_pemeriksaan) : '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Dipergunakan Untuk</span><span class="v">{{ $sm->keperluan ?: '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Hasil Pemeriksaan</span><span class="v">{{ $sm->hasil_pemeriksaan ?: '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Berat Badan</span><span class="v">{{ $sm->berat_badan !== null ? $angkaSm($sm->berat_badan) . ' kg' : '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Tinggi Badan</span><span class="v">{{ $sm->tinggi_badan !== null ? $angkaSm($sm->tinggi_badan) . ' cm' : '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Tekanan Darah</span><span class="v">{{ $sm->tekanan_darah ? $sm->tekanan_darah . ' mmHg' : '-' }}</span></div>
                                        @endif

                                        <div class="dx-row"><span class="k">Tempat, Tanggal Surat</span><span class="v">{{ $sm->tempat }}, {{ $sm->tanggal_surat ? $tglIndo($sm->tanggal_surat) : '-' }}</span></div>
                                        <div class="dx-row"><span class="k">Dokter yang Memeriksa</span><span class="v">{{ $sm->dokter ?: '-' }}</span></div>
                                        <div class="dx-row" style="align-items: flex-start;">
                                            <span class="k">Tanda Tangan</span>
                                            <span class="v">
                                                @if ($sm->ttd_dokter)
                                                    <img src="{{ $sm->ttd_dokter }}" alt="Tanda tangan dokter" class="img-ttd">
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </details>
                        @endforeach
                    @endif
                </div>


                {{-- ========== AUTENTIKASI ========== --}}
                <div class="berkas-panel" id="panel-autentikasi">
                    @php
                        $adaAuth = $kunjungan->filter(fn ($k) => $k->rekamMedis);
                        $noKunjunganAuth = $kunjungan->sortBy([['tanggal_kunjungan', 'asc'], ['id', 'asc']])
                            ->values()->pluck('id')->flip()->map(fn ($i) => $i + 1);
                    @endphp

                    @if ($adaAuth->isEmpty())
                        <p class="empty-note">Belum ada pemeriksaan yang perlu diautentikasi.</p>
                    @else
                        @foreach ($adaAuth as $k)
                            @php
                                $rmA = $k->rekamMedis;
                                $auth = $autentikasi[$k->id] ?? null;
                                // Sama dengan halaman Pemeriksaan: terautentikasi kalau data lengkap, ditandatangani, dan status Selesai
                                $dataLengkap = filled($rmA->keluhan_utama)
                                    && (filled($rmA->subjective) || filled($rmA->assessment))
                                    && filled($rmA->diagnosis_utama);
                                $terautentikasi = $auth && $dataLengkap && $k->status === 'Selesai';
                            @endphp

                            <details class="rp-card ick-acc">
                                <summary class="rp-head ick-summary">
                                    <span class="ick-judul">Kunjungan {{ $noKunjunganAuth[$k->id] }} — {{ $namaPoliDari($k->poli) }}</span>
                                    <span class="ick-meta">
                                        <span class="ick-pill">{{ $k->tanggal_kunjungan ? $k->tanggal_kunjungan->format('d-m-Y') : '-' }}</span>
                                        @if ($terautentikasi)
                                            <span class="ick-pill ok">Terautentikasi</span>
                                        @else
                                            <span class="ick-pill">Belum diautentikasi</span>
                                        @endif
                                    </span>
                                    <span class="ick-chevron" aria-hidden="true"></span>
                                </summary>

                                <div class="rp-body">
                                    @if ($terautentikasi)
                                        <div class="rp-aksi">
                                            @if (auth()->user()?->bisaKelolaRekamMedis())
                                            <a href="{{ route('rekam-medis.autentikasi.cetak', $k->id) }}"
                                               target="_blank" rel="noopener" class="btn-edit-soap rj-cetak">Cetak Autentikasi</a>
                                            @endif
                                        </div>

                                        <div class="dx-list">
                                            <div class="dx-row"><span class="k">Nama Dokter / Petugas</span><span class="v">{{ $auth->nama_dokter }}</span></div>
                                            <div class="dx-row"><span class="k">Jabatan</span><span class="v">{{ $auth->jabatan }}</span></div>
                                            <div class="dx-row"><span class="k">Waktu Autentikasi</span><span class="v">{{ $auth->waktu_autentikasi ? $auth->waktu_autentikasi->format('d-m-Y H:i') . ' WIB' : '-' }}</span></div>
                                            <div class="dx-row"><span class="k">Diagnosa Utama</span><span class="v">{{ $rmA->diagnosis_utama ?: '-' }}{{ $rmA->kode_icd10 ? ' (' . $rmA->kode_icd10 . ')' : '' }}</span></div>
                                            <div class="dx-row" style="align-items: flex-start;">
                                                <span class="k">Tanda Tangan</span>
                                                <span class="v">
                                                    @if ($auth->tanda_tangan)
                                                        <img src="{{ $auth->tanda_tangan }}" alt="Tanda tangan {{ $auth->nama_dokter }}" class="img-ttd">
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="empty-note" style="margin: 4px 0;">Pemeriksaan pada kunjungan ini belum diautentikasi oleh dokter/petugas.</p>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    @endif
                </div>

            </div>

        </section>

    </main>

</div>

{{-- MODAL KODING (ICD-10) --}}
<div class="modal-bg" id="modalKoding">
    <form class="modal-box" style="width: 520px;" method="POST" action="#" id="formKoding" novalidate>
        @csrf
        @method('PUT')

        <div class="modal-head">
            <h3>Koding &amp; Verifikasi</h3>
        </div>

        <div class="modal-main">
            <div class="f-group" style="margin-bottom: 16px;">
                <label>Diagnosa Utama</label>
                <div id="kdDiagnosa" style="font-size: 13px; color: #111;">-</div>
            </div>

            <div class="f-group {{ $errors->has('kode_icd10') ? 'salah' : '' }}" style="margin-bottom: 16px;">
                <label for="kdKode">Kode ICD-10 <span class="wajib">*</span></label>
                <input type="text" id="kdKode" name="kode_icd10" placeholder="Contoh: J06.9" maxlength="20" autocomplete="off">
                @error('kode_icd10')
                    <span class="f-pesan">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="modal-foot">
            <button type="button" class="btn-batal" id="btnBatalKoding">Batal</button>
            <button type="submit" class="btn-simpan" id="btnSimpanKoding" disabled>Simpan &amp; Verifikasi</button>
        </div>
    </form>
</div>

{{-- MODAL EDIT PEMERIKSAAN & SOAP (hanya untuk kunjungan yang belum terverifikasi) --}}
@php $errEdit = $errors->getBag('editSoap'); @endphp
<div class="modal-bg" id="modalEditSoap">
    <form class="modal-box" style="width: 860px;" method="POST" action="#" id="formEditSoap" novalidate>
        @csrf
        @method('PUT')

        <div class="modal-head">
            <h3>Edit Pemeriksaan &amp; SOAP</h3>
        </div>

        <div class="modal-main">
            <div class="pernyataan-judul">Tanda Vital</div>
            <div class="f-grid">
                <div class="f-group {{ $errEdit->has('tb') ? 'salah' : '' }}">
                    <label for="es_tb">TB (cm) <span class="wajib">*</span></label>
                    <input type="number" id="es_tb" name="tb" min="1" max="300" step="1" placeholder="Tinggi badan dalam cm" required>
                    @if ($errEdit->has('tb'))<span class="f-pesan">{{ $errEdit->first('tb') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('berat_badan') ? 'salah' : '' }}">
                    <label for="es_berat_badan">BB (kg) <span class="wajib">*</span></label>
                    <input type="text" id="es_berat_badan" name="berat_badan" placeholder="Berat badan dalam kg" autocomplete="off">
                    @if ($errEdit->has('berat_badan'))<span class="f-pesan">{{ $errEdit->first('berat_badan') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('imt') ? 'salah' : '' }}">
                    <label for="es_imt">IMT</label>
                    <input type="text" id="es_imt" name="imt" placeholder="Terisi otomatis" autocomplete="off" readonly>
                    @if ($errEdit->has('imt'))<span class="f-pesan">{{ $errEdit->first('imt') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('suhu_badan') ? 'salah' : '' }}">
                    <label for="es_suhu_badan">Suhu (°C) <span class="wajib">*</span></label>
                    <input type="number" id="es_suhu_badan" name="suhu_badan" min="1" max="50" step="1" placeholder="Contoh: 37" autocomplete="off">
                    @if ($errEdit->has('suhu_badan'))<span class="f-pesan">{{ $errEdit->first('suhu_badan') }}</span>@endif
                </div>
                                <div class="f-group {{ $errEdit->has('sistole') ? 'salah' : '' }}">
                    <label for="es_sistole">Sistole (mmHg) <span class="wajib">*</span></label>
                    <input type="number" id="es_sistole" name="sistole" min="1" max="300" step="1" placeholder="Contoh: 120" autocomplete="off">
                    @if ($errEdit->has('sistole'))<span class="f-pesan">{{ $errEdit->first('sistole') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('diastole') ? 'salah' : '' }}">
                    <label for="es_diastole">Diastole (mmHg) <span class="wajib">*</span></label>
                    <input type="number" id="es_diastole" name="diastole" min="1" max="200" step="1" placeholder="Contoh: 80" autocomplete="off">
                    @if ($errEdit->has('diastole'))<span class="f-pesan">{{ $errEdit->first('diastole') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('detak_jantung') ? 'salah' : '' }}">
                    <label for="es_detak_jantung">Denyut Jantung <span class="wajib">*</span></label>
                    <input type="text" id="es_detak_jantung" name="detak_jantung" placeholder="x/menit" autocomplete="off">
                    @if ($errEdit->has('detak_jantung'))<span class="f-pesan">{{ $errEdit->first('detak_jantung') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('respiratory_rate') ? 'salah' : '' }}">
                    <label for="es_respiratory_rate">RR</label>
                    <input type="text" id="es_respiratory_rate" name="respiratory_rate" placeholder="x/menit" autocomplete="off">
                    @if ($errEdit->has('respiratory_rate'))<span class="f-pesan">{{ $errEdit->first('respiratory_rate') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('saturasi') ? 'salah' : '' }}">
                    <label for="es_saturasi">Saturasi</label>
                    <input type="text" id="es_saturasi" name="saturasi" placeholder="Contoh: 98" autocomplete="off">
                    @if ($errEdit->has('saturasi'))<span class="f-pesan">{{ $errEdit->first('saturasi') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('gcs') ? 'salah' : '' }}">
                    <label for="es_gcs">GCS</label>
                    <input type="number" id="es_gcs" name="gcs" min="3" max="15" step="1" placeholder="Total skor, contoh: 15" autocomplete="off">
                    @if ($errEdit->has('gcs'))<span class="f-pesan">{{ $errEdit->first('gcs') }}</span>@endif
                </div>
            </div>

            <div class="pernyataan-judul">SOAP</div>
            <div class="f-grid dua">
                <div class="f-group {{ $errEdit->has('subjective') ? 'salah' : '' }}">
                    <label for="es_subjective">S — Subjective <span class="wajib">*</span></label>
                    <textarea id="es_subjective" name="subjective"></textarea>
                    @if ($errEdit->has('subjective'))<span class="f-pesan">{{ $errEdit->first('subjective') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('objective') ? 'salah' : '' }}">
                    <label for="es_objective">O — Objective <span class="wajib">*</span></label>
                    <textarea id="es_objective" name="objective"></textarea>
                    @if ($errEdit->has('objective'))<span class="f-pesan">{{ $errEdit->first('objective') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('assessment') ? 'salah' : '' }}">
                    <label for="es_assessment">A — Assessment <span class="wajib">*</span></label>
                    <textarea id="es_assessment" name="assessment"></textarea>
                    @if ($errEdit->has('assessment'))<span class="f-pesan">{{ $errEdit->first('assessment') }}</span>@endif
                </div>
                <div class="f-group {{ $errEdit->has('plan') ? 'salah' : '' }}">
                    <label for="es_plan">P — Plan <span class="wajib">*</span></label>
                    <textarea id="es_plan" name="plan"></textarea>
                    @if ($errEdit->has('plan'))<span class="f-pesan">{{ $errEdit->first('plan') }}</span>@endif
                </div>
            </div>
        </div>

        <div class="modal-foot">
            <button type="button" class="btn-batal" id="btnBatalEditSoap">Batal</button>
            <button type="submit" class="btn-simpan">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    (function () {
        var tabs   = document.querySelectorAll('.berkas-tab');
        var panels = document.querySelectorAll('.berkas-panel');

        function bukaTab(nama) {
            tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.tab === nama); });
            panels.forEach(function (p) { p.classList.toggle('active', p.id === 'panel-' + nama); });
        }

        tabs.forEach(function (t) {
            t.addEventListener('click', function () { bukaTab(t.dataset.tab); });
        });

        // Buka tab sesuai hash URL (mis. setelah menyimpan General Consent)
        var hash = (location.hash || '').replace('#', '');
        if (hash && document.getElementById('panel-' + hash)) {
            bukaTab(hash);
        }

        // Modal Koding (ICD-10)
        var modalKoding = document.getElementById('modalKoding');
        var formKoding  = document.getElementById('formKoding');
        var kdKode      = document.getElementById('kdKode');
        var btnSimpanKd = document.getElementById('btnSimpanKoding');

        function aturTombolKoding() {
            btnSimpanKd.disabled = kdKode.value.trim() === '';
        }
        kdKode.addEventListener('input', aturTombolKoding);

        document.querySelectorAll('.btn-koding').forEach(function (b) {
            b.addEventListener('click', function () {
                formKoding.action = b.dataset.action;
                kdKode.value = b.dataset.kode || '';
                document.getElementById('kdDiagnosa').textContent = b.dataset.diagnosa || '-';
                aturTombolKoding();
                modalKoding.classList.add('show');
                kdKode.focus();
            });
        });
        document.getElementById('btnBatalKoding').addEventListener('click', function () {
            modalKoding.classList.remove('show');
        });
        modalKoding.addEventListener('click', function (e) {
            if (e.target === modalKoding) modalKoding.classList.remove('show');
        });

        // Modal Edit Pemeriksaan & SOAP
        var modalEdit = document.getElementById('modalEditSoap');
        var formEdit  = document.getElementById('formEditSoap');
                var kolomEdit = ['tb', 'berat_badan', 'imt', 'suhu_badan', 'sistole', 'diastole', 'detak_jantung',
                         'respiratory_rate', 'saturasi', 'gcs',
                         'subjective', 'objective', 'assessment', 'plan'];

        function esEl(n) { return document.getElementById('es_' + n); }

        // IMT otomatis: BB (kg) / (TB (m))^2, TB dalam cm
        function hitungImtEdit() {
            var tb = parseFloat(String(esEl('tb').value).replace(',', '.'));
            var bb = parseFloat(String(esEl('berat_badan').value).replace(',', '.'));
            esEl('imt').value = (tb > 0 && bb > 0) ? (bb / Math.pow(tb / 100, 2)).toFixed(2) : '';
        }
        esEl('tb').addEventListener('input', hitungImtEdit);
        esEl('berat_badan').addEventListener('input', hitungImtEdit);

        function bukaEdit(action, nilai) {
            formEdit.action = action;
            kolomEdit.forEach(function (n) {
                esEl(n).value = (nilai && nilai[n] != null) ? nilai[n] : '';
            });
            modalEdit.classList.add('show');
        }

        document.querySelectorAll('.btn-edit-soap:not(.rj-cetak)').forEach(function (b) {
            b.addEventListener('click', function () {
                bukaEdit(b.dataset.action, JSON.parse(b.dataset.rm || '{}'));
            });
        });
        document.getElementById('btnBatalEditSoap').addEventListener('click', function () {
            modalEdit.classList.remove('show');
        });
        // Modal Edit Pemeriksaan & SOAP sengaja TIDAK ditutup saat klik di luar pop up;
        // hanya bisa ditutup lewat tombol Batal.

        @if (session('edit_soap_kunjungan_id'))
            // Validasi gagal: buka lagi modalnya dengan isian terakhir + pesan error
            bukaTab('soap');
            bukaEdit(
                @json(route('rekam-medis.soap.update', session('edit_soap_kunjungan_id'))),
                @json(old())
            );
        @endif

        // Klik baris riwayat kunjungan -> detail kunjungan
        document.querySelectorAll('.kj-row').forEach(function (tr) {
            tr.addEventListener('click', function () { location.href = tr.dataset.href; });
        });

        // Buka/tutup rincian pemeriksaan per kunjungan
        document.querySelectorAll('.periksa-head').forEach(function (h) {
            h.addEventListener('click', function () {
                h.parentElement.classList.toggle('open');
            });
        });
    })();
</script>

@endsection
