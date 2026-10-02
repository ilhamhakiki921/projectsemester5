@extends('layouts.pemeriksaan', ['tabAktif' => 'Informed Consent'])

@php
    // Identitas terisi otomatis dari data pendaftaran
    $dokter = $kunjungan->dokter ?? '';
    $jk     = $pasien->jenis_kelamin ?? '';
    $jkL    = $jk === 'Laki-laki';
    $jkP    = $jk === 'Perempuan';

    $daftarInformasi = [
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

    $daftarTindakan = [
        'pencabutan'   => 'Pencabutan gigi',
        'penambalan'   => 'Penambalan/restorasi',
        'scaling'      => 'Scaling',
        'bedah_minor'  => 'Tindakan bedah minor',
        'saluran_akar' => 'Perawatan saluran akar',
        'lainnya'      => 'Lainnya',
    ];

    $hubungan = ['Diri Sendiri', 'Suami', 'Istri', 'Anak', 'Orang Tua', 'Wali'];
@endphp

@push('styles')
<style>
    .ic-wrap{background:#fff;border:2px solid #b40000;border-radius:10px;padding:24px 26px 28px;margin-bottom:18px}
    .ic-title{margin:0 0 14px;font-size:20px;font-weight:700;color:#d40000}
    .ic-banner{padding:12px 18px;margin:0 0 18px;border-radius:8px;color:#fff;font-size:11px;font-weight:600;line-height:1.55;background:linear-gradient(90deg,#b01c1c 0%,#6d0d0d 60%,#3f0808 100%)}
    .ic-banner.ic-banner-sm{margin:18px 0}

    .ic-row3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px}
    .ic-lbl{display:block;margin-bottom:6px;font-size:10px;font-weight:600;color:#333}
    .ic-in{width:100%;height:32px;padding:0 10px;border:1px solid #aaa;border-radius:6px;background:#fff;font-family:'Poppins',sans-serif;font-size:10.5px;color:#222;outline:none}
    .ic-in:read-only{background:#f4f4f4;color:#444}
    .ic-in:disabled{background:#f4f4f4;cursor:not-allowed}
    .ic-in::placeholder{color:#999}
    /* Kolom aktif -> garis merah tebal (sama seperti kolom di Clinical Pathway) */
    .ic-wrap input:focus,.ic-wrap select:focus,.ic-wrap textarea:focus{outline:2px solid #c00000;outline-offset:1px;box-shadow:none}
    .ic-in:focus{border-color:#c00000}
    .ic-ttd canvas:focus,.ic-ttd canvas:active{outline:2px solid #c00000;outline-offset:1px}

    .ic-sec{margin:0 0 10px;font-size:14px;font-weight:700;color:#d40000}

    /* Tabel pemberian informasi */
    .ic-tbl{width:100%;border-collapse:collapse;border:1px solid #bbb;margin-bottom:16px}
    .ic-tbl th{padding:9px 14px;border-bottom:1px solid #bbb;font-size:11px;font-weight:600;text-align:left;color:#222}
    .ic-tbl th.c,.ic-tbl td.c{text-align:center}
    .ic-tbl td{padding:10px 14px;border-bottom:1px solid #ccc;font-size:11px;font-weight:500;color:#222;line-height:1.45;vertical-align:middle}
    .ic-tbl tr:last-child td{border-bottom:0}
    .ic-tbl td.no{width:60px;text-align:center;font-weight:600}
    .ic-tbl td.cek{width:150px;text-align:center}

    /* Kotak centang / radio bergaya kotak */
    .ic-box{appearance:none;-webkit-appearance:none;width:24px;height:18px;margin:0;border:1px solid #777;border-radius:3px;background:#fff;cursor:pointer;vertical-align:middle;position:relative;flex:none}
    .ic-box.sm{width:20px;height:16px}
    .ic-box:checked{background:#b40000;border-color:#b40000}
    .ic-box:checked::after{content:'';position:absolute;left:50%;top:44%;width:5px;height:10px;border:solid #fff;border-width:0 2px 2px 0;transform:translate(-50%,-50%) rotate(45deg)}
    .ic-box:focus,.ic-box:focus-visible{outline:2px solid #c00000;outline-offset:2px}
    .ic-box:disabled{opacity:.9;cursor:default}

    .ic-opt{display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;color:#222;cursor:pointer}
    .ic-tindakan{display:grid;grid-template-columns:230px 230px;gap:14px 40px;padding:6px 14px 0;margin-bottom:22px}
    .ic-tindakan .ic-opt{font-size:11.5px}

    .ic-row2{display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:6px}

    /* Tanda tangan */
    .ic-ttd-grid{display:grid;grid-template-columns:1fr 1fr;gap:70px;margin:0 0 24px}
    .ic-ttd{border:1px solid #999;border-radius:8px;padding:12px 12px 10px;background:#fff}
    .ic-ttd h4{margin:0 0 8px;font-size:11px;font-weight:600;text-align:center;color:#222;line-height:1.4}
    .ic-ttd canvas{display:block;width:100%;height:110px;border-radius:6px;background:#fafafa;touch-action:none;cursor:crosshair}
    .ic-ttd-clear{display:block;margin:6px 0 0 auto;padding:0;border:0;background:none;font-family:'Poppins',sans-serif;font-size:9.5px;font-weight:600;color:#b40000;cursor:pointer}

    /* Identitas pasien (otomatis) */
    .ic-ident{display:grid;grid-template-columns:150px 330px;gap:14px 0;align-items:center;margin-bottom:28px}
    .ic-ident .ic-lbl{margin:0;font-size:11px}
    .ic-jk{display:flex;align-items:center;gap:18px;font-size:10px;font-weight:700}
    .ic-jk label{display:inline-flex;align-items:center;gap:6px}

    /* Data penandatangan */
    .ic-ttp-h{margin:0 0 10px;font-size:14px;font-weight:600;color:#222}
    .ic-ttp{border:1px solid #bbb;border-radius:8px;padding:12px 14px 14px;margin-bottom:6px}
    .ic-ttp-top{display:grid;grid-template-columns:1.1fr 1.1fr 1fr 1.1fr;gap:16px;margin-bottom:12px}
    .ic-ttp .ic-lbl{font-size:10.5px;font-weight:700;color:#222}
    .ic-ttp-jk{display:flex;align-items:center;gap:16px;height:32px;font-size:10px;font-weight:700}
    .ic-ttp-jk label{display:inline-flex;align-items:center;gap:6px;cursor:pointer}
    .ic-hub{display:flex;flex-wrap:wrap;justify-content:space-between;gap:10px 16px;padding:8px 14px;margin-bottom:12px;border:1px solid #bbb;border-radius:6px}
    .ic-hub .ic-opt{font-size:11px}
    .ic-ttp-bot{display:grid;grid-template-columns:1.1fr 1fr 1fr 1.1fr;gap:16px;align-items:end}
    .ic-keputusan{display:flex;flex-direction:column;gap:6px;padding:8px 12px;border:1px solid #bbb;border-radius:6px}
    .ic-keputusan .ic-opt{font-size:11px}

    .ic-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:6px}
    .ic-cetak{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 26px;border:1.5px solid #b40000;border-radius:6px;background:#fff;color:#b40000;font-family:'Poppins',sans-serif;font-size:11.5px;font-weight:600;cursor:pointer}
    .ic-cetak:hover{background:#fdecec}
    .ic-simpan{height:36px;padding:0 34px;border:0;border-radius:6px;background:#17c200;color:#fff;font-family:'Poppins',sans-serif;font-size:11.5px;font-weight:600;cursor:pointer}
    .ic-simpan:hover{background:#12a300}

    .ic-err{margin:0 0 14px;padding:10px 14px;border-radius:6px;background:#fdecea;color:#b3261e;font-size:11px;font-weight:500}
    .ic-ok{margin:0 0 14px;padding:10px 14px;border-radius:6px;background:#e8f7ea;color:#14602a;font-size:11px;font-weight:500}
    [hidden]{display:none!important}

    /* ===== CETAK ===== */
    @media print{
        @page{size:A4;margin:12mm}
        html,body{background:#fff!important}
        .sidebar,.topbar,.identity-bar,.tabs,.flash,.toast-alert,.ic-banner-info,.ic-foot,.ic-ttd-clear,#icMsg{display:none!important}
        .main{margin:0!important;width:100%!important;min-height:0!important;background:#fff!important}
        .content{padding:0!important}
        .ic-wrap{border:0!important;border-radius:0!important;padding:0!important;margin:0!important}
        .ic-title{color:#000;font-size:18px;text-align:center;margin-bottom:10px}
        .ic-sec{color:#000}
        .ic-banner{background:#fff!important;color:#000!important;border:1px solid #000;border-radius:4px;font-size:10px;padding:8px 12px}
        .ic-in{border:0!important;border-bottom:1px solid #000!important;border-radius:0!important;background:#fff!important;color:#000!important;outline:none!important;padding-left:2px;font-size:10.5px}
        .ic-in::placeholder{color:transparent}
        input[type=time]::-webkit-calendar-picker-indicator{display:none}
        input[type=number]::-webkit-inner-spin-button{display:none}
        .ic-tbl,.ic-ttp,.ic-hub,.ic-keputusan,.ic-ttd{border-color:#000!important}
        .ic-tbl tr,.ic-ttd-grid,.ic-ident,.ic-ttp,.ic-banner{break-inside:avoid;page-break-inside:avoid}
        .ic-tbl th,.ic-tbl td{border-color:#555!important;padding-top:6px;padding-bottom:6px}
        .ic-ttd canvas{background:#fff!important}
        .ic-ttd-grid{gap:40px}
        .ic-box{border:1px solid #000!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
        .ic-box:checked{background:#000!important}
        *{-webkit-print-color-adjust:exact;print-color-adjust:exact}
    }

    @media (max-width:900px){
        .ic-row3,.ic-row2,.ic-ttd-grid,.ic-ttp-top,.ic-ttp-bot{grid-template-columns:1fr;gap:14px}
        .ic-ident{grid-template-columns:1fr}
        .ic-tindakan{grid-template-columns:1fr 1fr}
    }
</style>
@endpush

@section('konten')
<form class="ic-wrap" id="formIc" novalidate>
    @csrf
    <h2 class="ic-title">Informed Consent</h2>

    <div class="ic-banner ic-banner-info">
        Formulir persetujuan/penolakan tindakan kedokteran gigi. Identitas pasien terisi otomatis dari data pendaftaran; data pihak yang bertanda tangan wajib diisi manual.
    </div>

    <div id="icMsg" hidden></div>

    {{-- Pelaksana, pemberi & penerima informasi --}}
    <div class="ic-row3">
        <div>
            <label class="ic-lbl" for="icDokter">Dokter Pelaksana</label>
            <input type="text" class="ic-in" id="icDokter" name="dokter_pelaksana" value="{{ $dokter }}" readonly>
        </div>
        <div>
            <label class="ic-lbl" for="icPemberi">Pemberi Informasi</label>
            <input type="text" class="ic-in" id="icPemberi" name="pemberi_informasi" value="{{ $dokter }}" readonly>
        </div>
        <div>
            <label class="ic-lbl" for="icPenerima">Penerima Informasi/Pemberi Persetujuan</label>
            <input type="text" class="ic-in" id="icPenerima" name="penerima_informasi" placeholder="Nama penerima informasi" required>
        </div>
    </div>

    {{-- A. Pemberian Informasi --}}
    <h3 class="ic-sec">A. Pemberian Informasi</h3>
    <table class="ic-tbl">
        <thead>
            <tr>
                <th class="c" style="width:60px">No</th>
                <th>Jenis Informasi</th>
                <th class="c" style="width:150px">Sudah Disampaikan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daftarInformasi as $i => $teks)
                <tr>
                    <td class="no">{{ $i + 1 }}</td>
                    <td>{{ $teks }}</td>
                    <td class="cek"><input type="checkbox" class="ic-box ic-info" name="informasi[]" value="{{ $i + 1 }}" aria-label="Informasi nomor {{ $i + 1 }} sudah disampaikan"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Jenis tindakan --}}
    <div class="ic-tindakan">
        @foreach ($daftarTindakan as $kode => $nama)
            <label class="ic-opt">
                <input type="checkbox" class="ic-box" name="tindakan[]" value="{{ $kode }}" @if ($kode === 'lainnya') id="icLainnya" @endif>
                {{ $nama }}
            </label>
        @endforeach
    </div>

    <div class="ic-row2">
        <div>
            <label class="ic-lbl" for="icTindakanLain">Tindakan Lainnya (bila memilih "Lainnya")</label>
            <input type="text" class="ic-in" id="icTindakanLain" name="tindakan_lainnya" disabled>
        </div>
        <div>
            <label class="ic-lbl" for="icArea">Nomor Gigi / Area Tindakan</label>
            <input type="text" class="ic-in" id="icArea" name="area_tindakan" placeholder="Contoh : 36, 34 / regio kanan bawah">
        </div>
    </div>

    <div class="ic-banner ic-banner-sm">
        Dokter gigi/pemberi informasi menyatakan telah memberikan informasi secara benar, jelas dan lengkap, serta memberi kesempatan bertanya. Pasien/penerima informasi menyatakan telah menerima dan memahami informasi tersebut.
    </div>

    {{-- Tanda tangan --}}
    <div class="ic-ttd-grid">
        <div class="ic-ttd">
            <h4>Tanda Tangan Dokter Gigi/Pemberi Informasi</h4>
            <canvas data-ttd="ttd_dokter" tabindex="0"></canvas>
            <button type="button" class="ic-ttd-clear">Hapus</button>
            <input type="hidden" name="ttd_dokter">
        </div>
        <div class="ic-ttd">
            <h4>Tanda Tangan Pasien/Penerima Informasi</h4>
            <canvas data-ttd="ttd_pasien" tabindex="0"></canvas>
            <button type="button" class="ic-ttd-clear">Hapus</button>
            <input type="hidden" name="ttd_pasien">
        </div>
    </div>

    {{-- Identitas pasien (otomatis dari pendaftaran) --}}
    <div class="ic-ident">
        <label class="ic-lbl" for="icRm">No. Rekam Medis</label>
        <input type="text" class="ic-in" id="icRm" value="{{ $pasien->no_rm ?? $rm }}" readonly>

        <span class="ic-lbl">Jenis Kelamin</span>
        <div class="ic-jk">
            <label><input type="checkbox" class="ic-box sm" disabled @checked($jkL)> L</label>
            <label><input type="checkbox" class="ic-box sm" disabled @checked($jkP)> P</label>
        </div>

        <label class="ic-lbl" for="icNama">Nama Pasien</label>
        <input type="text" class="ic-in" id="icNama" value="{{ $pasien->nama ?? '' }}" readonly>

        <label class="ic-lbl" for="icJkPasien">Jenis Kelamin Pasien</label>
        <input type="text" class="ic-in" id="icJkPasien" value="{{ $jk }}" readonly>
    </div>

    {{-- Data yang bertanda tangan (manual) --}}
    <h3 class="ic-ttp-h">Data yang Bertanda Tangan (Pasien/Wali) — wajib diisi manual</h3>
    <div class="ic-ttp">
        <div class="ic-ttp-top">
            <div>
                <label class="ic-lbl" for="ttpNama">Nama</label>
                <input type="text" class="ic-in" id="ttpNama" name="ttp_nama" required>
            </div>
            <div>
                <label class="ic-lbl" for="ttpUmur">Umur (Tahun)</label>
                <input type="number" min="0" max="150" class="ic-in" id="ttpUmur" name="ttp_umur" required>
            </div>
            <div>
                <span class="ic-lbl">Jenis Kelamin</span>
                <div class="ic-ttp-jk">
                    <label><input type="radio" class="ic-box sm" name="ttp_jk" value="L"> L</label>
                    <label><input type="radio" class="ic-box sm" name="ttp_jk" value="P"> P</label>
                </div>
            </div>
            <div>
                <label class="ic-lbl" for="ttpAlamat">Alamat</label>
                <input type="text" class="ic-in" id="ttpAlamat" name="ttp_alamat" required>
            </div>
        </div>

        <span class="ic-lbl">Hubungan dengan Pasien</span>
        <div class="ic-hub">
            @foreach ($hubungan as $h)
                <label class="ic-opt"><input type="radio" class="ic-box sm" name="ttp_hubungan" value="{{ $h }}"> {{ $h }}</label>
            @endforeach
        </div>

        <div class="ic-ttp-bot">
            <div>
                <span class="ic-lbl">Keputusan</span>
                <div class="ic-keputusan">
                    <label class="ic-opt"><input type="radio" class="ic-box sm" name="keputusan" value="setuju"> Menyetujui Tindakan</label>
                    <label class="ic-opt"><input type="radio" class="ic-box sm" name="keputusan" value="tolak"> Menolak Tindakan</label>
                </div>
            </div>
            <div>
                <label class="ic-lbl" for="ttpTempat">Tempat, Tanggal</label>
                <input type="text" class="ic-in" id="ttpTempat" name="tempat_tanggal" required>
            </div>
            <div>
                <label class="ic-lbl" for="ttpPukul">Pukul</label>
                <input type="time" class="ic-in" id="ttpPukul" name="pukul" required>
            </div>
            <div>
                <label class="ic-lbl" for="ttpSaksi">Nama Saksi</label>
                <input type="text" class="ic-in" id="ttpSaksi" name="nama_saksi" required>
            </div>
        </div>
    </div>

    <div class="ic-banner ic-banner-sm" id="icPernyataan">
        Pasien/wali menyetujui dilakukannya tindakan kedokteran gigi sesuai penjelasan yang telah diberikan.
    </div>

    <div class="ic-foot">
        <button type="button" class="ic-cetak" id="icCetak"><i class="bi bi-printer"></i> Cetak</button>
        <button type="submit" class="ic-simpan">Simpan</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    var form   = document.getElementById('formIc');
    var msg    = document.getElementById('icMsg');
    var lain   = document.getElementById('icLainnya');
    var lainIn = document.getElementById('icTindakanLain');
    var pernyataan = document.getElementById('icPernyataan');

    // "Lainnya" dicentang -> kolom Tindakan Lainnya aktif
    function syncLainnya() {
        lainIn.disabled = ! lain.checked;
        if (! lain.checked) lainIn.value = '';
    }
    lain.addEventListener('change', syncLainnya);
    syncLainnya();

    // Teks pernyataan di bawah mengikuti keputusan (menyetujui / menolak)
    document.querySelectorAll('input[name="keputusan"]').forEach(function (r) {
        r.addEventListener('change', function () {
            pernyataan.textContent = this.value === 'tolak'
                ? 'Pasien/wali menolak dilakukannya tindakan kedokteran gigi sesuai penjelasan yang telah diberikan.'
                : 'Pasien/wali menyetujui dilakukannya tindakan kedokteran gigi sesuai penjelasan yang telah diberikan.';
        });
    });

    // ---------- Kotak tanda tangan (canvas) ----------
    function siapkanTtd(canvas) {
        var ctx = canvas.getContext('2d');
        var hidden = canvas.parentNode.querySelector('input[type="hidden"]');
        var clearBtn = canvas.parentNode.querySelector('.ic-ttd-clear');
        var menggambar = false, adaGoresan = false;

        function ukuran() {
            var r = canvas.getBoundingClientRect(), dpr = window.devicePixelRatio || 1;
            canvas.width = r.width * dpr;
            canvas.height = r.height * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#111';
            adaGoresan = false; hidden.value = '';
        }
        function pos(e) {
            var r = canvas.getBoundingClientRect();
            return { x: e.clientX - r.left, y: e.clientY - r.top };
        }
        canvas.addEventListener('pointerdown', function (e) {
            menggambar = true; canvas.setPointerCapture(e.pointerId);
            var p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y);
        });
        canvas.addEventListener('pointermove', function (e) {
            if (! menggambar) return;
            var p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); adaGoresan = true;
        });
        function selesai() {
            if (! menggambar) return;
            menggambar = false;
            if (adaGoresan) hidden.value = canvas.toDataURL('image/png');
        }
        canvas.addEventListener('pointerup', selesai);
        canvas.addEventListener('pointerleave', selesai);
        clearBtn.addEventListener('click', ukuran);

        ukuran();
        canvas._ukuran = ukuran;
    }
    document.querySelectorAll('canvas[data-ttd]').forEach(siapkanTtd);

    // ---------- Cetak ----------
    document.getElementById('icCetak').addEventListener('click', function () {
        // nilai kolom & tanda tangan di layar ikut tercetak apa adanya
        window.print();
    });

    // ---------- Simpan (validasi tampilan) ----------
    function tampil(jenis, teks) {
        msg.hidden = false;
        msg.className = jenis === 'ok' ? 'ic-ok' : 'ic-err';
        msg.textContent = teks;
        msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var kurang = [];
        if (! form.penerima_informasi.value.trim()) kurang.push('Penerima Informasi');
        if (! form.querySelectorAll('.ic-info:checked').length) kurang.push('minimal satu Jenis Informasi yang sudah disampaikan');
        if (! form.querySelectorAll('input[name="tindakan[]"]:checked').length) kurang.push('Jenis tindakan');
        if (lain.checked && ! lainIn.value.trim()) kurang.push('Tindakan Lainnya');
        if (! form.ttd_dokter.value) kurang.push('Tanda tangan dokter');
        if (! form.ttd_pasien.value) kurang.push('Tanda tangan pasien');
        if (! form.ttp_nama.value.trim()) kurang.push('Nama penandatangan');
        if (! form.ttp_umur.value) kurang.push('Umur');
        if (! form.querySelector('input[name="ttp_jk"]:checked')) kurang.push('Jenis kelamin penandatangan');
        if (! form.ttp_alamat.value.trim()) kurang.push('Alamat');
        if (! form.querySelector('input[name="ttp_hubungan"]:checked')) kurang.push('Hubungan dengan pasien');
        if (! form.querySelector('input[name="keputusan"]:checked')) kurang.push('Keputusan');
        if (! form.tempat_tanggal.value.trim()) kurang.push('Tempat, Tanggal');
        if (! form.pukul.value) kurang.push('Pukul');
        if (! form.nama_saksi.value.trim()) kurang.push('Nama Saksi');

        if (kurang.length) {
            tampil('err', 'Lengkapi dulu: ' + kurang.join(', ') + '.');
            return;
        }
        // TODO: kirim ke server setelah route & tabel penyimpanan Informed Consent dibuat
        tampil('ok', 'Formulir Informed Consent lengkap.');
    });
})();
</script>
@endpush
