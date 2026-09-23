<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekam Medis - {{ $antrian->pasien->nama }}</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #b30000;
        }

        .header h1 {
            font-size: 15px;
            color: #b30000;
        }

        .header p {
            font-size: 10px;
            color: #555;
        }

        table.identitas {
            width: 100%;
            margin-bottom: 14px;
        }

        table.identitas td {
            padding: 3px 0;
            vertical-align: top;
        }

        table.identitas .label {
            width: 120px;
            color: #555;
        }

        .section-title {
            margin-top: 14px;
            margin-bottom: 6px;

            font-size: 12px;
            font-weight: bold;

            color: #b30000;

            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }

        .field {
            margin-bottom: 8px;
        }

        .field .field-label {
            font-size: 9px;
            color: #777;
            text-transform: uppercase;
        }

        .field .field-value {
            font-size: 11px;
        }

        table.vital {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.vital td {
            width: 33.33%;
            padding: 6px 8px;
            border: 1px solid #eee;
        }

        table.vital .field-label {
            font-size: 9px;
            color: #777;
        }

    </style>
</head>
<body>

    <div class="header">
        <h1>Klinik Merah Putih</h1>
        <p>Rekam Medis Pemeriksaan Pasien</p>
    </div>

    <table class="identitas">
        <tr>
            <td class="label">Nama Pasien</td>
            <td><strong>{{ $antrian->pasien->nama }}</strong></td>
            <td class="label">No. RM</td>
            <td>{{ $antrian->pasien->no_rm }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td>{{ $antrian->pasien->nik }}</td>
            <td class="label">Jenis Kelamin</td>
            <td>{{ $antrian->pasien->jenis_kelamin }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir</td>
            <td>{{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->translatedFormat('d M Y') }}</td>
            <td class="label">Usia</td>
            <td>{{ \Carbon\Carbon::parse($antrian->pasien->tanggal_lahir)->age }} tahun</td>
        </tr>
        <tr>
            <td class="label">Poli</td>
            <td>{{ ucfirst($antrian->poli) }}</td>
            <td class="label">Tgl Kunjungan</td>
            <td>{{ \Carbon\Carbon::parse($antrian->tanggal_kunjungan)->translatedFormat('d M Y') }}</td>
        </tr>
    </table>

    <div class="section-title">Asesmen Pemeriksaan</div>

    <div class="field">
        <div class="field-label">Keluhan Utama</div>
        <div class="field-value">{{ $antrian->rekamMedis->keluhan_utama ?: '-' }}</div>
    </div>

    <div class="field">
        <div class="field-label">Riwayat Penyakit Sekarang</div>
        <div class="field-value">{{ $antrian->rekamMedis->riwayat_penyakit_sekarang ?: '-' }}</div>
    </div>

    <div class="field">
        <div class="field-label">Riwayat Penyakit Terdahulu</div>
        <div class="field-value">{{ $antrian->rekamMedis->riwayat_penyakit_terdahulu ?: '-' }}</div>
    </div>

    <div class="field">
        <div class="field-label">Riwayat Alergi</div>
        <div class="field-value">{{ $antrian->rekamMedis->riwayat_alergi ?: '-' }}</div>
    </div>

    <div class="field">
        <div class="field-label">Riwayat Pengobatan</div>
        <div class="field-value">{{ $antrian->rekamMedis->riwayat_pengobatan ?: '-' }}</div>
    </div>

    <div class="section-title">Tanda Vital</div>

    <table class="vital">
        <tr>
            <td><div class="field-label">TB (Tinggi Badan)</div>{{ $antrian->rekamMedis->tb ?: '-' }}</td>
            <td><div class="field-label">TD (Tekanan Darah)</div>{{ $antrian->rekamMedis->td ?: '-' }}</td>
            <td><div class="field-label">LP (Lingkar Perut)</div>{{ $antrian->rekamMedis->lp ?: '-' }}</td>
        </tr>
        <tr>
            <td><div class="field-label">Respiratory Rate</div>{{ $antrian->rekamMedis->respiratory_rate ?: '-' }}</td>
            <td><div class="field-label">Berat Badan</div>{{ $antrian->rekamMedis->berat_badan ?: '-' }}</td>
            <td><div class="field-label">Detak Jantung</div>{{ $antrian->rekamMedis->detak_jantung ?: '-' }}</td>
        </tr>
        <tr>
            <td><div class="field-label">Lingkar Dada</div>{{ $antrian->rekamMedis->lingkar_dada ?: '-' }}</td>
            <td><div class="field-label">Saturasi</div>{{ $antrian->rekamMedis->saturasi ?: '-' }}</td>
            <td><div class="field-label">IMT</div>{{ $antrian->rekamMedis->imt ?: '-' }}</td>
        </tr>
        <tr>
            <td><div class="field-label">Suhu Badan</div>{{ $antrian->rekamMedis->suhu_badan ?: '-' }}</td>
            <td><div class="field-label">Lingkar Kepala</div>{{ $antrian->rekamMedis->lingkar_kepala ?: '-' }}</td>
            <td><div class="field-label">GCS</div>{{ $antrian->rekamMedis->gcs ?: '-' }}</td>
        </tr>
    </table>

</body>
</html>
