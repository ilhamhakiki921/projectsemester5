```blade
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

    .phone-input {
    display: flex;
    align-items: center;
    width: 100%;
    height: 34px;
    border: 1px solid #999;
    border-radius: 6px;
    background: #ffffff;
    overflow: hidden;
    box-sizing: border-box;
}

.phone-input:focus-within {
    border-color: #2f6f6a;
    box-shadow: 0 0 0 3px rgba(47, 111, 106, 0.10);
}

.phone-prefix {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 90px;
    min-width: 90px;
    height: 100%;
    padding: 0 12px;
    background: #f8fafc;
    border-right: 1px solid #e1e5ea;
    color: #111827;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;
    box-sizing: border-box;
}

.phone-input input,
.form-group .phone-input input {
    flex: 1;
    width: auto;
    min-width: 0;
    height: 100%;
    margin: 0;
    padding: 0 10px;
    border: none !important;
    border-radius: 0 !important;
    outline: none !important;
    box-shadow: none !important;
    background: transparent;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: #374151;
    box-sizing: border-box;
}

.phone-input input::placeholder {
    color: #9ca3af;
    font-size: 12px;
}

.phone-input input:focus {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
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
    row-gap: 15px;
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

.form-group textarea {
    width: 100%;
    min-height: 70px;
    padding: 10px 12px;
    border: 1px solid #999;
    border-radius: 6px;
    outline: none;
    background: #ffffff;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: #555;
    resize: vertical;
}

.form-group textarea::placeholder {
    color: #aaa;
    font-size: 12px;
}

.form-group textarea:focus {
    border-color: #555;
}

    .form-group input:focus,
    .form-group select:focus {
        border-color: #555;
    }

    .radio-wrapper {
        min-height: 40px;
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .radio-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
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
        <x-topbar title="Pendaftaran Pasien Baru" icon="person-plus" />

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

                            <input type="text" 
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
                                        value="0"
                                        {{ old('jenis_kelamin') == '0' ? 'checked' : '' }}
                                        required
                                    >

                                    Tidak diketahui

                                </label>


                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="1"
                                        {{ old('jenis_kelamin') == '1' ? 'checked' : '' }}
                                    >

                                    Laki-laki

                                </label>


                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="2"
                                        {{ old('jenis_kelamin') == '2' ? 'checked' : '' }}
                                    >

                                    Perempuan

                                </label>


                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="3"
                                        {{ old('jenis_kelamin') == '3' ? 'checked' : '' }}
                                    >

                                    Tidak dapat ditentukan

                                </label>


                                <label class="radio-item">

                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="4"
                                        {{ old('jenis_kelamin') == '4' ? 'checked' : '' }}
                                    >

                                    Tidak mengisi

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
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input
                                type="date"
                                id="tanggal_lahir"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}"
                                lang="id-ID"
                                required
                            >
                        </div>


                        {{-- NIK --}}

                        <div class="form-group">

                            <label>
                                Nomor Induk Kependudukan (NIK)
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="nik"
                                name="nik"
                                value="{{ old('nik') }}"
                                placeholder="16 digit NIK"
                                inputmode="numeric"
                                maxlength="16"
                                required
                            >

                        </div>


                        {{-- Nomor Telepon Selular Pasien --}}

                        <div class="form-group">
    <label for="no_telepon">Nomor Telepon Selular Pasien</label>

    <div class="phone-input">
        <span class="phone-prefix">+62</span>

        <input
            type="tel"
            id="no_telepon"
            name="no_telepon"
            value="{{ old('no_telepon') }}"
            placeholder="81234567890"
            inputmode="numeric"
        >
    </div>
</div>


                        {{-- Alamat Lengkap --}}

                        <div class="form-group">

                            <label>
                                Alamat Lengkap
                                <span class="required">*</span>
                            </label>

                            <textarea
                                name="alamat"
                                placeholder="Nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan"
                                required
                            >{{ old('alamat') }}</textarea>

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
                                value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}"
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

                                <option value="dokter1"
                                    {{ old('dokter') == 'dokter1' ? 'selected' : '' }}>
                                    Dr. Ahmad
                                </option>

                                <option value="dokter2"
                                    {{ old('dokter') == 'dokter2' ? 'selected' : '' }}>
                                    Dr. Budi
                                </option>

                                <option value="dokter3"
                                    {{ old('dokter') == 'dokter3' ? 'selected' : '' }}>
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

                                <option value="umum"
                                    {{ old('poli') == 'umum' ? 'selected' : '' }}>
                                    Poli Umum
                                </option>

                                <option value="gigi"
                                    {{ old('poli') == 'gigi' ? 'selected' : '' }}>
                                    Poli Gigi
                                </option>

                                <option value="anak"
                                    {{ old('poli') == 'anak' ? 'selected' : '' }}>
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

                                <option value="bpjs"
                                    {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>
                                    BPJS
                                </option>

                                <option value="umum"
                                    {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>
                                    Umum
                                </option>

                                <option value="asuransi"
                                    {{ old('pembayaran') == 'asuransi' ? 'selected' : '' }}>
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

{{-- Enter di keyboard = pindah otomatis ke kolom berikutnya, jadi tidak perlu klik/arahkan kursor manual --}}
<script>
(function () {

    var form = document.querySelector('.content form');

    if (!form) {
        return;
    }

    function isVisible(el) {
        return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
    }

    // Semua kolom isian di form ini, sesuai urutan tampil di layar
    function getFields() {

        var selector = 'input:not([type="hidden"]):not([type="button"]):not([type="submit"]):not([type="reset"]), select, textarea';

        return Array.prototype.filter.call(form.querySelectorAll(selector), function (el) {
            return !el.disabled && isVisible(el);
        });
    }

    form.addEventListener('keydown', function (event) {

        if (event.key !== 'Enter') {
            return;
        }

        var target = event.target;

        // Kolom Alamat boleh multi-baris: Shift+Enter tetap bikin baris baru seperti biasa
        if (target.tagName === 'TEXTAREA' && event.shiftKey) {
            return;
        }

        // Biarkan tombol Batal & Daftar bekerja seperti biasa
        if (target.tagName === 'BUTTON' || target.type === 'submit' || target.type === 'button') {
            return;
        }

        event.preventDefault();

        var fields = getFields();
        var index = fields.indexOf(target);

        if (index === -1) {
            return;
        }

        var next = fields[index + 1];

        if (next) {

            next.focus();

            // Kalau kolom berikutnya kolom teks, langsung blok isinya biar gampang ditimpa
            if (typeof next.select === 'function' && next.tagName === 'INPUT') {
                try {
                    next.select();
                } catch (e) {}
            }

        } else {

            // Sudah di kolom terakhir -> arahkan fokus ke tombol Daftar (tidak langsung submit)
            var tombolDaftar = form.querySelector('button[type="submit"]');

            if (tombolDaftar) {
                tombolDaftar.focus();
            }
        }

            // Validasi NIK: hanya angka, wajib 16 digit
    var nikInput = document.getElementById('nik');

    if (nikInput) {

        nikInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });

        form.addEventListener('submit', function (event) {
            if (nikInput.value.length > 0 && nikInput.value.length !== 16) {
                event.preventDefault();
                alert('Periksa ulang Nomor Induk Kependudukan');
                nikInput.focus();
            }
        });
    }
    });

})();
</script>

@endsection
```
