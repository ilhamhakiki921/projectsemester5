@extends('layouts.app')

@section('title', 'Resep Obat — ' . $pasien->nama)

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
        background: #f5f5f5;
    }

    .content {
        width: 100%;
        padding: 25px 22px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        margin-bottom: 14px;

        color: #444;
        text-decoration: none;

        font-size: 11px;
        font-weight: 500;
    }

    .back-link:hover {
        color: #ed1111;
    }

    .identity-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 22px;

        padding: 16px 22px;
        margin-bottom: 16px;

        border-radius: 10px;

        background: linear-gradient(180deg, #980000 0%, #570000 100%);
        color: #ffffff;
    }

    .identity-name-block {
        min-width: 170px;
    }

    .identity-label-top {
        font-size: 9px;
        opacity: 0.8;
        margin-bottom: 3px;
    }

    .identity-name {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .identity-field {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .identity-field label {
        font-size: 9px;
        opacity: 0.85;
    }

    .identity-field .value-box {
        min-width: 120px;
        height: 28px;

        display: flex;
        align-items: center;

        padding: 0 10px;

        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 5px;

        font-size: 10px;
        font-weight: 500;
    }

    .tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;

        margin-bottom: 16px;
    }

    .tab-item {
        height: 32px;

        display: inline-flex;
        align-items: center;

        padding: 0 16px;

        border-radius: 20px;

        font-size: 10px;
        font-weight: 600;

        text-decoration: none;

        color: #1a1a1a;
        background: transparent;
    }

    .tab-item.active {
        background: #7a0000;
        color: #ffffff;
    }

    .tab-item.disabled {
        cursor: not-allowed;
    }

    /* =========================
       CARD RESEP
    ========================== */

    .resep-card {
        background: #ffffff;

        border: 2px solid #3b82f6;
        border-radius: 10px;

        padding: 20px 22px;
    }

    .resep-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 16px;
    }

    .resep-title {
        font-size: 15px;
        font-weight: 700;
        color: #ed1111;
    }

    .btn-tambah {
        height: 34px;

        padding: 0 18px;

        border: none;
        border-radius: 6px;

        background: #ed1111;
        color: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .btn-tambah:hover {
        background: #c40d0d;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .resep-table {
        width: 100%;
        border-collapse: collapse;
    }

    .resep-table th {
        padding: 10px 8px;

        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #888;
        text-align: left;

        border-bottom: 1px solid #e2e2e2;
        white-space: nowrap;
    }

    .resep-table td {
        padding: 12px 8px;

        font-size: 10px;
        color: #333;

        border-bottom: 1px solid #f0f0f0;
        white-space: nowrap;
    }

    .empty-row td {
        padding: 30px 8px;

        text-align: center;
        color: #888;
        font-size: 10px;
        white-space: normal;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;

        margin-top: 22px;
    }

    .btn-lanjut {
        height: 34px;

        padding: 0 22px;

        border: none;
        border-radius: 6px;

        background: #16a34a;
        color: #ffffff;

        text-decoration: none;

        display: inline-flex;
        align-items: center;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .btn-lanjut:hover {
        background: #128a3e;
    }

    /* =========================
       MODAL TAMBAH RESEP
    ========================== */

    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9998;

        display: none;
        align-items: center;
        justify-content: center;

        background: rgba(0, 0, 0, 0.5);

        font-family: 'Poppins', sans-serif;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        width: 480px;
        max-width: 92vw;
        max-height: 90vh;
        overflow-y: auto;

        background: #ffffff;
        border-radius: 12px;

        padding: 26px 28px;

        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
    }

    .modal-title {
        margin-bottom: 18px;

        font-size: 16px;
        font-weight: 700;
        color: #ed1111;
    }

    .modal-field-group {
        margin-bottom: 14px;
    }

    .modal-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;

        margin-bottom: 14px;
    }

    .modal-field label {
        display: block;
        margin-bottom: 6px;

        font-size: 10px;
        font-weight: 600;
        color: #333;
    }

    .modal-field label .required-mark {
        color: #ed1111;
        margin-left: 2px;
    }

    .modal-field input {
        width: 100%;

        padding: 8px 10px;

        border: 1px solid #ccc;
        border-radius: 6px;

        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        color: #222;

        outline: none;
    }

    .modal-field input:focus {
        border-color: #3b82f6;
    }

    .modal-field input[readonly] {
        background: #f3f4f6;
        color: #777;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;

        margin-top: 20px;
    }

    .btn-batal {
        height: 34px;

        padding: 0 18px;

        border: 1px solid #ccc;
        border-radius: 6px;

        background: #ffffff;
        color: #333;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .btn-simpan-resep {
        height: 34px;

        padding: 0 18px;

        border: none;
        border-radius: 6px;

        background: #16a34a;
        color: #ffffff;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 600;

        cursor: pointer;
    }

    .btn-simpan-resep:hover {
        background: #128a3e;
    }

    .modal-error {
        margin-bottom: 14px;
        padding: 10px 12px;

        border: 1px solid #fecaca;
        border-radius: 6px;

        background: #fef2f2;
        color: #b91c1c;

        font-size: 10px;
        line-height: 1.5;
    }

    .modal-error ul {
        margin: 0;
        padding-left: 16px;
    }

</style>

@endsection


@section('content')

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            <x-popup-alert />

            <a href="{{ route('pemeriksaan.diagnosa', [$poli, $pasien->no_rm]) }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Diagnosa
            </a>

            <div class="identity-bar">

                <div class="identity-name-block">
                    <div class="identity-label-top">Identitas Pasien</div>
                    <div class="identity-name">{{ strtoupper($pasien->nama) }}</div>
                </div>

                <div class="identity-field">
                    <label>No.RM</label>
                    <div class="value-box">{{ $pasien->no_rm }}</div>
                </div>

                <div class="identity-field">
                    <label>NIK</label>
                    <div class="value-box">{{ $pasien->nik }}</div>
                </div>

                <div class="identity-field">
                    <label>Jenis Kelamin</label>
                    <div class="value-box">{{ $pasien->jenis_kelamin }}</div>
                </div>

                <div class="identity-field">
                    <label>Tanggal Lahir</label>
                    <div class="value-box">{{ $pasien->tanggal_lahir->format('d-m-Y') }}</div>
                </div>

                <div class="identity-field">
                    <label>Usia</label>
                    <div class="value-box">{{ $pasien->tanggal_lahir->age }} Tahun</div>
                </div>

            </div>

            <div class="tabs">
                <a href="{{ route('pemeriksaan.asesmen', [$poli, $pasien->no_rm]) }}" class="tab-item">Asesment</a>
                <a href="{{ route('pemeriksaan.soap', [$poli, $pasien->no_rm]) }}" class="tab-item">SOAP</a>
                <a href="{{ route('pemeriksaan.diagnosa', [$poli, $pasien->no_rm]) }}" class="tab-item">Diagnosa</a>
                <span class="tab-item active">Resep</span>
                <a href="{{ route('pemeriksaan.clinical-pathway', [$poli, $pasien->no_rm]) }}" class="tab-item">Clinical Pathway</a>
                <span class="tab-item disabled">Penunjang</span>
                <span class="tab-item disabled">Rujukan</span>
                <span class="tab-item disabled">Autentikasi</span>
            </div>

            {{-- RESEP CARD --}}
            <div class="resep-card">

                <div class="resep-header">
                    <h2 class="resep-title">RESEP OBAT</h2>

                    <button type="button" class="btn-tambah" onclick="document.getElementById('modalTambahResep').classList.add('show')">
                        + Tambah Obat
                    </button>
                </div>

                <div class="table-wrapper">
                    <table class="resep-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Obat</th>
                                <th>Nama Obat</th>
                                <th>ID Resep</th>
                                <th>Sediaan</th>
                                <th>Jumlah Obat</th>
                                <th>Dosis Obat</th>
                                <th>Frekuensi</th>
                                <th>Tgl. Penulisan Resep</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kunjungan->resep as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ str_pad($item->obat_id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item->obat->nama_obat }}</td>
                                    <td>{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item->obat->sediaan }}</td>
                                    <td>{{ $item->jumlah_obat }}</td>
                                    <td>{{ $item->dosis_obat }}</td>
                                    <td>{{ $item->frekuensi }}</td>
                                    <td>{{ $item->tanggal_penulisan->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr class="empty-row">
                                    <td colspan="9">Belum ada resep obat untuk pasien ini. Klik "+ Tambah Obat" untuk menambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="form-footer">
                    <a href="{{ route('pemeriksaan.clinical-pathway', [$poli, $pasien->no_rm]) }}" class="btn-lanjut">
                        Lanjut ke Clinical Pathway
                    </a>
                </div>

            </div>

        </section>

    </main>

</div>

{{-- MODAL TAMBAH RESEP OBAT --}}
<div class="modal-overlay" id="modalTambahResep">
    <div class="modal-box">

        <h3 class="modal-title">Tambah Resep Obat</h3>

        <form method="POST" action="{{ route('pemeriksaan.resep.store', [$poli, $pasien->no_rm]) }}" id="form-resep">
            @csrf

            @if ($errors->any())
                <div class="modal-error">
                    <ul>
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $daftarObatJson = $daftarObat->map(fn ($o) => [
                    'id'      => $o->id,
                    'nama'    => $o->nama_obat,
                    'dosis'   => $o->dosis,
                    'sediaan' => $o->sediaan,
                ]);
            @endphp

            <div class="modal-field-group">
                <div class="modal-field">
                    <label>Nama Obat<span class="required-mark">*</span></label>
                    <input
                        type="text"
                        id="inputNamaObat"
                        name="nama_obat"
                        value="{{ old('nama_obat') }}"
                        placeholder="Nama saja, tanpa dosis (mis. Paracetamol)"
                        autocomplete="off"
                        required
                    >
                </div>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>ID Obat</label>
                    <input type="text" id="displayIdObat" placeholder="Otomatis setelah disimpan" readonly>
                </div>
                <div class="modal-field">
                    <label>ID Resep</label>
                    <input type="text" value="Otomatis setelah disimpan" readonly>
                </div>
            </div>

            <div class="modal-field-group">
                <div class="modal-field">
                    <label>Sediaan<span class="required-mark">*</span></label>
                    <input type="text" name="sediaan" id="displaySediaan" value="{{ old('sediaan') }}" placeholder="misal: Tablet, Kapsul, Sirup" required>
                </div>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Jumlah Obat<span class="required-mark">*</span></label>
                    <input type="number" name="jumlah_obat" min="1" value="{{ old('jumlah_obat') }}" placeholder="misal: 10" required>
                </div>
                <div class="modal-field">
                    <label>Dosis Obat<span class="required-mark">*</span></label>
                    <input type="text" name="dosis_obat" id="inputDosis" value="{{ old('dosis_obat') }}" placeholder="misal: 500 mg" required>
                </div>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Frekuensi<span class="required-mark">*</span></label>
                    <input type="text" name="frekuensi" value="{{ old('frekuensi') }}" placeholder="misal: 3x sehari" required>
                </div>
                <div class="modal-field">
                    <label>Tanggal Penulisan Resep<span class="required-mark">*</span></label>
                    <input type="date" name="tanggal_penulisan" value="{{ old('tanggal_penulisan', now()->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-batal" onclick="document.getElementById('modalTambahResep').classList.remove('show')">
                    Batal
                </button>
                <button type="submit" class="btn-simpan-resep">
                    Simpan Resep
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    var daftarObat = @json($daftarObatJson);

    var inputNama      = document.getElementById('inputNamaObat');
    var displayId      = document.getElementById('displayIdObat');
    var inputSediaan   = document.getElementById('displaySediaan');

    var inputDosis = document.getElementById('inputDosis');

    // Nama obat ditulis TANPA dosis; dosis ada di kolom sendiri. Kalau nama (+ dosis)
    // yang diketik sudah ada di Data Obat, ID Obat ditampilkan dan Sediaan / Dosis
    // diisi otomatis bila masih kosong.
    function cocokkanObat() {
        var nama  = inputNama.value.trim().toLowerCase();
        var dosis = inputDosis.value.trim().toLowerCase().replace(/\s+/g, '');

        var kandidat = daftarObat.filter(function (o) { return o.nama.toLowerCase() === nama; });
        var cocok = kandidat.find(function (o) {
            return (o.dosis || '').toLowerCase().replace(/\s+/g, '') === dosis;
        });

        // Hanya satu obat dengan nama ini -> aman diisi otomatis
        if (!cocok && kandidat.length === 1 && dosis === '') {
            cocok = kandidat[0];
            inputDosis.value = cocok.dosis || '';
        }

        if (cocok) {
            displayId.value = String(cocok.id).padStart(3, '0');
            if (inputSediaan.value.trim() === '') {
                inputSediaan.value = cocok.sediaan;
            }
        } else {
            displayId.value = '';
        }
    }

    inputNama.addEventListener('input', cocokkanObat);
    inputDosis.addEventListener('input', cocokkanObat);

    document.querySelectorAll('#form-resep [required]').forEach(function (field) {
        field.addEventListener('invalid', function () {
            field.setCustomValidity('Kolom ini wajib diisi.');
        });
        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });
    });

    @if ($errors->any())
        document.getElementById('modalTambahResep').classList.add('show');
    @endif
</script>

@endsection
