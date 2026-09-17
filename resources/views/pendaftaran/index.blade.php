@extends('layouts.app')

@section('title', 'Data Pasien')

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

    /* =========================
       MAIN
    ========================== */

    .main {
        width: calc(100% - 230px);
        min-height: 100vh;
        margin-left: 230px;
        background: #ffffff;
    }


    /* =========================
       CONTENT
    ========================== */

    .content {
        width: 100%;
        padding: 25px 22px;
        background: #ffffff;
    }


    /* =========================
       PAGE TITLE
    ========================== */

    .page-title {
        margin-bottom: 4px;

        font-size: 21px;
        font-weight: 700;

        text-transform: uppercase;

        color: #222;
    }

    .page-description {
        margin-bottom: 15px;

        font-size: 10px;
        color: #555;
    }


    /* =========================
       SEARCH & BUTTON
    ========================== */

    .top-action {
        display: flex;
        align-items: center;

        gap: 10px;

        margin-bottom: 18px;
    }

    .search-box {
        position: relative;
        width: 260px;
    }

    .search-box i {
        position: absolute;

        left: 10px;
        top: 50%;

        transform: translateY(-50%);

        font-size: 11px;
        color: #555;
    }

    .search-input {
        width: 100%;
        height: 30px;

        padding: 0 10px 0 28px;

        border: 1px solid #999;
        border-radius: 5px;

        outline: none;

        font-family: 'Poppins', sans-serif;
        font-size: 9px;

        color: #333;
    }

    .search-input::placeholder {
        color: #999;
    }

    .search-input:focus {
        border-color: #777;
    }


    /* =========================
       BUTTON PASIEN BARU
    ========================== */

    .btn-new {
        height: 30px;

        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 0 12px;

        border: none;
        border-radius: 5px;

        background: #1111b8;
        color: #ffffff;

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 9px;
        font-weight: 500;

        cursor: pointer;

        transition: 0.2s;
    }

    .btn-new:hover {
        background: #08088f;
    }

    .btn-new i {
        font-size: 10px;
    }


    /* =========================
       TABLE
    ========================== */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;

        border: 1px solid #777;
    }

    .patient-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: fixed;
    }

    .patient-table th,
    .patient-table td {
        border: 1px solid #999;

        padding: 8px 7px;

        font-size: 9px;

        text-align: left;

        vertical-align: middle;
    }

    .patient-table th {
        height: 34px;

        background: #e5e5e5;

        font-size: 9px;
        font-weight: 600;

        color: #222;

        text-align: center;
    }

    .patient-table td {
        height: 38px;

        color: #444;

        background: #ffffff;
    }

    .patient-table tbody tr:hover td {
        background: #f8f8f8;
    }


    /* =========================
       LEBAR KOLOM
    ========================== */

    .col-rm {
        width: 8%;
    }

    .col-nama {
        width: 15%;
    }

    .col-nik {
        width: 17%;
    }

    .col-bpjs {
        width: 14%;
    }

    .col-alamat {
        width: 15%;
    }

    .col-jk {
        width: 10%;
    }

    .col-lahir {
        width: 13%;
    }

    .col-action {
        width: 8%;
    }


    /* =========================
       ACTION
    ========================== */

    .action {
        display: flex;

        align-items: center;
        justify-content: center;

        gap: 10px;
    }

    .btn-edit,
    .btn-delete {
        width: 24px;
        height: 24px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: none;
        background: transparent;

        cursor: pointer;

        text-decoration: none;
    }

    .btn-edit {
        color: #5555cc;
    }

    .btn-delete {
        color: #d00000;
    }

    .btn-edit:hover {
        color: #222299;
    }

    .btn-delete:hover {
        color: #990000;
    }

    .btn-edit i,
    .btn-delete i {
        font-size: 13px;
    }


    /* =========================
       PAGINATION
    ========================== */

    .table-footer {
        display: flex;

        align-items: center;
        justify-content: space-between;

        margin-top: 12px;

        font-size: 9px;
        color: #888;
    }

    .pagination {
        display: flex;
        align-items: center;

        gap: 4px;
    }

    .pagination button {
        min-width: 24px;
        height: 24px;

        padding: 0 6px;

        border: 1px solid #ddd;
        border-radius: 4px;

        background: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 9px;

        color: #555;

        cursor: pointer;
    }

    .pagination button.active {
        background: #1111b8;
        border-color: #1111b8;
        color: #ffffff;
    }

    .pagination button:hover {
        background: #eeeeee;
    }

    .pagination button.active:hover {
        background: #1111b8;
    }

</style>

@endsection


@section('content')

<div class="dashboard">

    {{-- SIDEBAR --}}
    <x-sidebar />


    {{-- MAIN --}}
    <main class="main">
        <x-topbar title="Pendaftaran" icon="clipboard-plus" />

        <section class="content">

            {{-- TITLE --}}
            <h1 class="page-title">
                Data Pasien
            </h1>

            <p class="page-description">
                Cari data pasien
            </p>


            {{-- SEARCH + BUTTON --}}
            <div class="top-action">

                <div class="search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        class="search-input"
                        placeholder="Cari berdasarkan nama atau nomor RM"
                    >

                </div>


                <a
                    href="{{ route('pasien.baru') }}"
                    class="btn-new"
                >
                    <i class="bi bi-person-plus-fill"></i>
                    Daftarkan Pasien Baru
                </a>

            </div>


            {{-- TABLE --}}
            <div class="table-wrapper">

                <table class="patient-table">

                    <thead>

                        <tr>

                            <th class="col-rm">
                                No. RM
                            </th>

                            <th class="col-nama">
                                Nama Pasien
                            </th>

                            <th class="col-nik">
                                NIK
                            </th>

                            <th class="col-bpjs">
                                Nomor BPJS
                            </th>

                            <th class="col-alamat">
                                Alamat
                            </th>

                            <th class="col-jk">
                                Jenis Kelamin
                            </th>

                            <th class="col-lahir">
                                Tanggal Lahir
                            </th>

                            <th class="col-action">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- DATA DUMMY --}}

                        <tr>

                            <td>
                                123456
                            </td>

                            <td>
                                MR.X
                            </td>

                            <td>
                                1234567891234567
                            </td>

                            <td>
                                123321
                            </td>

                            <td>
                                Bulakrejo
                            </td>

                            <td style="text-align: center;">
                                L
                            </td>

                            <td>
                                20 April 2006
                            </td>

                            <td>

                                <div class="action">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('pasien.lama') }}"
                                        class="btn-edit"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </a>


                                    {{-- HAPUS --}}
                                    <button
                                        type="button"
                                        class="btn-delete"
                                        title="Hapus"
                                    >
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- FOOTER TABLE --}}
            <div class="table-footer">

                <span>
                    Showing 1 to 1 of 1 entries
                </span>


                <div class="pagination">

                    <button>
                        Previous
                    </button>

                    <button class="active">
                        1
                    </button>

                    <button>
                        Next
                    </button>

                </div>

            </div>

        </section>

    </main>

</div>

@endsection