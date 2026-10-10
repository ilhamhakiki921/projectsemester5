@extends('layouts.pemeriksaan', [
    'tabAktif'  => 'Surat Medis',
    'backUrl'   => route('pemeriksaan.index', $poli),
    'backLabel' => 'Kembali ke Antrian',
])

@push('styles')
<style>
    .sm-card{border:1px solid #6b6b6b;border-radius:14px;background:#fff;overflow:hidden}
    .sm-body{padding:22px 24px 26px}

    /* Pilihan jenis surat */
    .sm-pilih{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:22px}
    .sm-opt{display:block;padding:14px 18px;border:1px solid #777;border-radius:12px;background:#fff;cursor:pointer;transition:.15s}
    .sm-opt input{position:absolute;opacity:0;pointer-events:none}
    .sm-opt b{display:block;font-size:11px;font-weight:700;color:#111}
    .sm-opt span{display:block;margin-top:3px;font-size:10px;font-weight:500;color:#9a9a9a}
    .sm-opt:hover{border-color:#a11a1e}
    .sm-opt.on{border-color:#a11a1e;background:#fdf1f1;box-shadow:0 0 0 1px #a11a1e inset}
    .sm-opt.on b{color:#a11a1e}

    /* Judul bagian dengan garis bawah */
    .sm-h{margin:0 0 14px;padding-bottom:6px;border-bottom:1px solid #8a8a8a;font-size:11px;font-weight:700;color:#111}
    .sm-h.gap{margin-top:24px}

    .sm-grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px 20px}
    .sm-grid3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px 24px}
    .sm-grid2+.sm-grid2,.sm-f+.sm-grid2,.sm-grid2+.sm-f{margin-top:14px}
    .sm-blok>.sm-f{margin-top:14px}
    .sm-f label{display:block;margin:0 0 6px;font-size:10.5px;font-weight:700;color:#111}
    .sm-f input{display:block;width:100%;height:34px;padding:0 12px;border:1px solid #777;border-radius:5px;background:#fff;font:inherit;font-size:10.5px;color:#111}
    .sm-f input::placeholder{color:#b0b0b0}
    .sm-f input[readonly]{background:#fff;color:#555;cursor:default}
    .sm-f input:focus{outline:none;border-color:#a11a1e;box-shadow:0 0 0 3px rgba(161,26,30,.12)}

    /* Tanda tangan */
    .sm-ttd{position:relative;border:1px solid #777;border-radius:5px;background:#fff;overflow:hidden}
    .sm-ttd canvas{display:block;width:100%;height:100px;touch-action:none;cursor:crosshair}
    .sm-ttd .ph{position:absolute;left:0;right:0;top:50%;transform:translateY(-50%);text-align:center;font-size:10px;color:#aaa;pointer-events:none}
    .sm-ttd.terisi .ph{display:none}
    .sm-btn-kecil{margin-top:10px;height:28px;padding:0 20px;border:1px solid #777;border-radius:5px;background:#fff;font:inherit;font-size:10px;font-weight:600;color:#111;cursor:pointer}
    .sm-btn-kecil:hover{border-color:#a11a1e;color:#a11a1e}

    /* Bilah tombol bawah */
    .sm-foot{display:flex;justify-content:flex-end;gap:14px;padding:16px 24px;border-top:1px solid #8a8a8a}
    .sm-btn{min-width:84px;height:30px;padding:0 18px;border:1px solid #777;border-radius:5px;background:#fff;font:inherit;font-size:10.5px;font-weight:600;color:#111;cursor:pointer}
    .sm-btn:hover{border-color:#a11a1e;color:#a11a1e}
    .sm-btn:disabled{opacity:.55;cursor:wait}

    [hidden]{display:none !important}

    .sm-toast{position:fixed;top:18px;right:18px;z-index:9999;background:#1d1d1d;color:#fff;font-size:12px;padding:10px 16px;border-radius:8px;border-left:4px solid #12c400;box-shadow:0 4px 14px rgba(0,0,0,.3);opacity:0;transform:translateY(-8px);transition:.25s;pointer-events:none;max-width:340px}
    .sm-toast.err{border-color:#ff5a5f}
    .sm-toast.show{opacity:1;transform:none}

    @media (max-width:800px){.sm-pilih,.sm-grid2,.sm-grid3{grid-template-columns:1fr}}
</style>
@endpush

@section('konten')
<form id="smForm" class="sm-card" autocomplete="off">
    <div class="sm-body">

        <div class="sm-pilih">
            <label class="sm-opt on" id="optSakit">
                <input type="radio" name="jenis" value="Sakit" checked>
                <b>Surat Keterangan Sakit</b>
                <span>Izin istirahat karena sakit</span>
            </label>
            <label class="sm-opt" id="optSehat">
                <input type="radio" name="jenis" value="Sehat">
                <b>Surat Keterangan Sehat</b>
                <span>Hasil pemeriksaan kesehatan jasmani</span>
            </label>
        </div>

        <h3 class="sm-h" id="smJudul">Isi Surat Keterangan Sakit</h3>

        <div class="sm-f">
            <label for="smNomor">Nomor Surat (otomatis)</label>
            <input type="text" id="smNomor" name="nomor_surat" readonly tabindex="-1">
        </div>

        <div class="sm-grid2">
            <div class="sm-f">
                <label for="smAlamat">Alamat Pasien</label>
                <input type="text" id="smAlamat" name="alamat_pasien">
            </div>

            {{-- Khusus Surat Keterangan Sakit --}}
            <div class="sm-f" data-jenis="Sakit">
                <label for="smLama">Lama Istirahat (hari)</label>
                <input type="number" id="smLama" name="lama_istirahat" min="1" max="365">
            </div>

            {{-- Khusus Surat Keterangan Sehat --}}
            <div class="sm-f" data-jenis="Sehat" hidden>
                <label for="smTglPeriksa">Tanggal Pemeriksaan</label>
                <input type="date" id="smTglPeriksa" name="tanggal_pemeriksaan">
            </div>
        </div>

        {{-- Khusus Surat Keterangan Sehat --}}
        <div class="sm-blok" data-jenis="Sehat" hidden>
            <div class="sm-f">
                <label for="smKeperluan">Dipergunakan Untuk</label>
                <input type="text" id="smKeperluan" name="keperluan">
            </div>
            <div class="sm-f">
                <label for="smHasil">Hasil Pemeriksaan</label>
                <input type="text" id="smHasil" name="hasil_pemeriksaan">
            </div>

            <h3 class="sm-h gap">Keterangan Fisik</h3>
            <div class="sm-grid3">
                <div class="sm-f">
                    <label for="smBB">Berat Badan (kg)</label>
                    <input type="number" id="smBB" name="berat_badan" min="1" max="500" step="0.1">
                </div>
                <div class="sm-f">
                    <label for="smTB">Tinggi Badan (cm)</label>
                    <input type="number" id="smTB" name="tinggi_badan" min="30" max="250" step="0.1">
                </div>
                <div class="sm-f">
                    <label for="smTD">Tekanan Darah (mmHg)</label>
                    <input type="text" id="smTD" name="tekanan_darah" placeholder="120/80" maxlength="7">
                </div>
            </div>
        </div>

        <div class="sm-grid2" data-jenis="Sakit">
            <div class="sm-f">
                <label for="smMulai">Mulai Tanggal</label>
                <input type="date" id="smMulai" name="mulai_tanggal">
            </div>
            <div class="sm-f">
                <label for="smSampai">Sampai Tanggal (otomatis)</label>
                <input type="text" id="smSampai" readonly tabindex="-1">
            </div>
        </div>

        <h3 class="sm-h gap">Penerbitan Surat</h3>

        <div class="sm-grid3">
            <div class="sm-f">
                <label for="smTempat">Tempat</label>
                <input type="text" id="smTempat" name="tempat">
            </div>
            <div class="sm-f">
                <label for="smTanggal">Tanggal Surat</label>
                <input type="date" id="smTanggal" name="tanggal_surat">
            </div>
            <div class="sm-f">
                <label for="smDokter">Dokter yang Memeriksa</label>
                <input type="text" id="smDokter" name="dokter">
            </div>
        </div>

        <h3 class="sm-h gap">Tanda Tangan Dokter</h3>

        <div class="sm-ttd" id="smTtdBox">
            <canvas id="smTtd"></canvas>
            <div class="ph">Gunakan mouse/jari untuk mentandatangani</div>
        </div>
        <input type="hidden" name="ttd_dokter" id="smTtdData">
        <button type="button" class="sm-btn-kecil" id="smHapus">Hapus</button>
    </div>

    <div class="sm-foot">
        <button type="button" class="sm-btn" id="smSimpan">Simpan</button>
        <button type="button" class="sm-btn" id="smCetak">Cetak</button>
        <button type="button" class="sm-btn" id="smUnduh">Unduh</button>
    </div>
</form>

<div class="sm-toast" id="smToast"></div>
@endsection

@push('scripts')
<script>
(() => {
    const storeUrl = @json($storeUrl);
    const csrf     = document.querySelector('meta[name="csrf-token"]').content;
    const data     = @json($data);   // isian per jenis: { Sakit: {...}, Sehat: {...} }

    const form = document.getElementById('smForm');
    const el = id => document.getElementById(id);
    const toastEl = el('smToast');
    const tombol = [el('smSimpan'), el('smCetak'), el('smUnduh')];
    const opt = { Sakit: el('optSakit'), Sehat: el('optSehat') };
    const kolom = ['nomor_surat', 'alamat_pasien', 'lama_istirahat', 'mulai_tanggal', 'keperluan',
                   'tanggal_pemeriksaan', 'hasil_pemeriksaan', 'berat_badan', 'tinggi_badan', 'tekanan_darah',
                   'tempat', 'tanggal_surat', 'dokter'];

    let jenis = 'Sakit';

    function toast(msg, err) {
        toastEl.textContent = msg;
        toastEl.className = 'sm-toast show' + (err ? ' err' : '');
        clearTimeout(toast.t); toast.t = setTimeout(() => toastEl.classList.remove('show'), 3000);
    }

    // ---------- Tanda tangan (canvas) ----------
    // Goresan dicatat sebagai titik, lalu saat selesai digambar ulang ke kanvas tersembunyi:
    // dipotong pas di sekitar tanda tangan, latar transparan, resolusi tinggi (tinggi 300px)
    // supaya tetap tajam saat dicetak/diunduh.
    const canvas = el('smTtd'), box = el('smTtdBox'), ttdData = el('smTtdData'), ctx = canvas.getContext('2d');
    let menggambar = false, strokes = [], dariSimpan = false;

    function siapkanCanvas(gambar) {
        const r = canvas.getBoundingClientRect(), dpr = window.devicePixelRatio || 1;
        canvas.width = r.width * dpr; canvas.height = r.height * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#111';
        strokes = []; ttdData.value = ''; box.classList.remove('terisi'); dariSimpan = false;
        if (gambar) {   // tampilkan tanda tangan yang sudah tersimpan (pas di tengah kotak, perbandingan asli)
            const img = new Image();
            img.onload = () => {
                const k = Math.min((r.width - 20) / img.width, (r.height - 16) / img.height, 1);
                const w = img.width * k, h = img.height * k;
                ctx.drawImage(img, (r.width - w) / 2, (r.height - h) / 2, w, h);
                ttdData.value = gambar; box.classList.add('terisi'); dariSimpan = true;
            };
            img.src = gambar;
        }
    }

    function eksporTtd() {
        const semua = strokes.flat();
        if (!semua.length) return '';
        const pad = 8;
        const minX = Math.min(...semua.map(p => p.x)) - pad, maxX = Math.max(...semua.map(p => p.x)) + pad;
        const minY = Math.min(...semua.map(p => p.y)) - pad, maxY = Math.max(...semua.map(p => p.y)) + pad;
        const bw = maxX - minX, bh = maxY - minY;
        let s = 300 / bh;                     // tinggi hasil 300px
        if (bw * s > 1800) s = 1800 / bw;     // batasi lebar untuk tanda tangan yang sangat lebar
        const out = document.createElement('canvas');
        out.width = Math.ceil(bw * s); out.height = Math.ceil(bh * s);
        const c = out.getContext('2d');
        c.lineCap = 'round'; c.lineJoin = 'round'; c.strokeStyle = '#111';
        c.lineWidth = 2 * (out.height / 70);   // ketebalan ±2px saat ditampilkan setinggi 70px
        const T = p => ({ x: (p.x - minX) * s, y: (p.y - minY) * s });
        strokes.forEach(st => {
            const pts = st.map(T);
            c.beginPath();
            if (pts.length === 1) {            // titik tunggal
                c.arc(pts[0].x, pts[0].y, c.lineWidth / 2, 0, Math.PI * 2); c.fillStyle = '#111'; c.fill(); return;
            }
            c.moveTo(pts[0].x, pts[0].y);
            for (let i = 1; i < pts.length - 1; i++) {   // garis dihaluskan lewat titik tengah
                const m = { x: (pts[i].x + pts[i + 1].x) / 2, y: (pts[i].y + pts[i + 1].y) / 2 };
                c.quadraticCurveTo(pts[i].x, pts[i].y, m.x, m.y);
            }
            c.lineTo(pts[pts.length - 1].x, pts[pts.length - 1].y);
            c.stroke();
        });
        return out.toDataURL('image/png');
    }

    const pos = e => { const r = canvas.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; };
    canvas.addEventListener('pointerdown', e => {
        if (dariSimpan) siapkanCanvas(null);   // mulai menggambar = mengganti tanda tangan yang tersimpan
        menggambar = true; canvas.setPointerCapture(e.pointerId);
        const p = pos(e); strokes.push([p]); ctx.beginPath(); ctx.moveTo(p.x, p.y);
    });
    canvas.addEventListener('pointermove', e => {
        if (!menggambar) return;
        const p = pos(e); strokes[strokes.length - 1].push(p);
        ctx.lineTo(p.x, p.y); ctx.stroke(); box.classList.add('terisi');
    });
    const selesai = () => {
        if (!menggambar) return;
        menggambar = false;
        ttdData.value = eksporTtd();
    };
    canvas.addEventListener('pointerup', selesai);
    canvas.addEventListener('pointerleave', selesai);
    el('smHapus').addEventListener('click', () => siapkanCanvas(null));

    // ---------- Isi / ambil isian form ----------
    function ambil() {
        const d = { id: data[jenis].id, cetak_url: data[jenis].cetak_url, unduh_url: data[jenis].unduh_url };
        kolom.forEach(k => { d[k] = form.elements[k].value; });
        d.sampai_tanggal = el('smSampai').dataset.iso || '';
        d.ttd_dokter = ttdData.value;
        return d;
    }
    function isi(d) {
        kolom.forEach(k => { form.elements[k].value = d[k] ?? ''; });
        hitungSampai();
        siapkanCanvas(d.ttd_dokter || null);
    }

    // ---------- Sampai tanggal otomatis = mulai + lama - 1 hari ----------
    function hitungSampai() {
        const lama = parseInt(form.elements.lama_istirahat.value, 10), mulai = form.elements.mulai_tanggal.value;
        const out = el('smSampai');
        if (!lama || lama < 1 || !mulai) { out.value = ''; out.dataset.iso = ''; return; }
        const t = new Date(mulai + 'T00:00:00');
        t.setDate(t.getDate() + lama - 1);
        const iso = [t.getFullYear(), String(t.getMonth() + 1).padStart(2, '0'), String(t.getDate()).padStart(2, '0')].join('-');
        out.dataset.iso = iso;
        out.value = iso.split('-').reverse().join('-');   // dd-mm-yyyy
    }
    form.elements.lama_istirahat.addEventListener('input', hitungSampai);
    form.elements.mulai_tanggal.addEventListener('input', hitungSampai);

    // ---------- Ganti jenis surat ----------
    function pilihJenis(baru) {
        data[jenis] = ambil();   // simpan dulu isian jenis sebelumnya supaya tidak hilang
        jenis = baru;
        Object.keys(opt).forEach(j => opt[j].classList.toggle('on', j === jenis));
        form.querySelectorAll('[data-jenis]').forEach(b => {
            const aktif = b.dataset.jenis === jenis;
            b.hidden = !aktif;
            b.querySelectorAll('input').forEach(i => i.disabled = !aktif);
        });
        el('smJudul').textContent = 'Isi Surat Keterangan ' + jenis;
        isi(data[jenis]);
    }
    form.querySelectorAll('input[name="jenis"]').forEach(r => r.addEventListener('change', () => pilihJenis(r.value)));

    // ---------- Simpan ----------
    function kunci(k) { tombol.forEach(b => b.disabled = k); }

    async function simpan() {
        const fd = new FormData();
        fd.append('jenis', jenis);
        kolom.forEach(k => {
            if (k === 'nomor_surat') return;
            const input = form.elements[k];
            if (!input.disabled) fd.append(k, input.value);
        });
        fd.append('ttd_dokter', ttdData.value);

        const res = await fetch(storeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
        if (res.status === 422) {
            const j = await res.json();
            throw new Error(Object.values(j.errors || {})[0]?.[0] || 'Data belum lengkap');
        }
        if (!res.ok) throw new Error('Gagal menyimpan, coba lagi');

        const saved = await res.json();
        data[jenis] = saved;
        form.elements.nomor_surat.value = saved.nomor_surat;
        return saved;
    }

    // aksi: 'simpan' | 'cetak' | 'unduh'
    async function jalankan(aksi) {
        kunci(true);
        const jendela = aksi === 'cetak' ? window.open('', '_blank') : null;   // dibuka dulu supaya tidak diblokir popup-blocker
        try {
            const saved = await simpan();
            if (aksi === 'cetak') {
                if (jendela) jendela.location = saved.cetak_url;
                else toast('Surat tersimpan. Izinkan pop-up lalu klik Cetak lagi.');
            } else if (aksi === 'unduh') {
                window.location = saved.unduh_url;   // respon berupa file PDF, halaman tidak berpindah
                toast('Surat tersimpan, PDF sedang diunduh');
            } else {
                toast('Surat keterangan ' + jenis.toLowerCase() + ' tersimpan');
            }
        } catch (e) {
            if (jendela) jendela.close();
            toast(e.message || 'Gagal menyimpan, coba lagi', true);
        } finally { kunci(false); }
    }

    el('smSimpan').addEventListener('click', () => jalankan('simpan'));
    el('smCetak').addEventListener('click', () => jalankan('cetak'));
    el('smUnduh').addEventListener('click', () => jalankan('unduh'));
    form.addEventListener('submit', e => e.preventDefault());

    // ---------- Awal ----------
    form.querySelectorAll('[data-jenis="Sehat"] input').forEach(i => i.disabled = true);
    isi(data.Sakit);
})();
</script>
@endpush
