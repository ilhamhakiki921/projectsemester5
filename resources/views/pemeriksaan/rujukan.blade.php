@extends('layouts.pemeriksaan', [
    'tabAktif'  => 'Rujukan',
    'backUrl'   => route('pemeriksaan.index', $poli),
    'backLabel' => 'Kembali ke Antrian',
])

@push('styles')
<style>
    .rj-card{border:1px solid #6b6b6b;border-radius:14px;padding:22px 20px 26px;background:#fff}
    .rj-title{margin:0 0 14px;font-size:11px;font-weight:700;color:#c00000;text-transform:uppercase}
    .rj-sub{margin:2px 0 14px;font-size:9px;font-weight:500;color:#222}

    .rj-table{width:100%;border-collapse:separate;border-spacing:0}
    .rj-table thead th{background:linear-gradient(90deg,#a11a1e,#5c0a0c);color:#fff;font-size:11px;font-weight:700;padding:12px 10px;text-align:center;text-transform:uppercase}
    .rj-table thead th:first-child{border-radius:8px 0 0 8px}
    .rj-table thead th:last-child{border-radius:0 8px 8px 0}
    .rj-table tbody td{background:#ececec;padding:10px;border-top:1px solid #dcdcdc;text-align:center;font-size:11px;vertical-align:middle}
    .rj-table tbody tr.empty td{padding:44px 10px;color:#222;border-top:0}
    .rj-table small{display:block;color:#666;font-size:9.5px}
    .rj-tag{display:inline-block;border-radius:10px;padding:2px 10px;font-size:10px;font-weight:600;background:#fff;border:1px solid #a11a1e;color:#a11a1e}
    .rj-tag.eks{background:#a11a1e;color:#fff}
    .rj-aksi button{border:1px solid #a11a1e;color:#a11a1e;background:#fff;border-radius:6px;padding:2px 10px;font:inherit;font-size:10px;cursor:pointer;margin:0 2px}
    .rj-aksi button:hover{background:#a11a1e;color:#fff}

    .rj-h2{margin:22px 0 8px;font-size:11px;font-weight:700;color:#111;text-transform:uppercase}

    /* Pilihan jenis rujukan */
    .rj-pilih{display:grid;grid-template-columns:1fr 1fr;gap:26px}
    .rj-opt{display:flex;align-items:center;gap:12px;padding:12px 16px;border:1px solid #333;border-radius:8px;background:#fff;color:#111;font-size:11px;cursor:pointer;transition:.15s}
    .rj-opt input{position:absolute;opacity:0;pointer-events:none}
    .rj-opt .dot{width:18px;height:18px;border-radius:50%;border:1px solid #333;background:#fff;flex-shrink:0}
    .rj-opt b{font-weight:700}
    .rj-opt.on{background:linear-gradient(90deg,#a11a1e,#5c0a0c);border-color:#5c0a0c;color:#fff}
    .rj-opt.on .dot{background:#e8e8e8;border-color:#e8e8e8}

    .rj-grid2{display:grid;grid-template-columns:1fr 1fr;gap:6px 26px;margin-top:12px}
    .rj-f label{display:block;font-size:11px;font-weight:700;color:#111;margin:0 0 5px}
    .rj-f input,.rj-f select,.rj-f textarea{display:block;width:100%;border:1px solid #333;border-radius:8px;background:#fff;font:inherit;font-size:11px;padding:0 14px;height:40px;color:#111}
    .rj-f textarea{height:92px;padding:10px 14px;resize:vertical}
    .rj-f input.sm{height:32px;border-radius:6px;font-size:11px}
    .rj-f input:focus,.rj-f select:focus,.rj-f textarea:focus{outline:2px solid #a11a1e;outline-offset:1px}
    .rj-note{margin:14px 0 4px;font-size:10px;color:#222}
    .rj-resume{margin:22px 0 4px;font-size:11px;font-weight:700;text-transform:uppercase;color:#111}
    .rj-f.gap{margin-top:14px}
    [hidden]{display:none !important}

    .rj-act{margin-top:16px;display:flex;justify-content:flex-end}
    .rj-btn{border:0;border-radius:8px;padding:10px 28px;font:inherit;font-size:11px;font-weight:600;color:#fff;background:#1717d6;cursor:pointer}
    .rj-btn:hover{filter:brightness(.9)}
    .rj-btn:disabled{opacity:.6;cursor:wait}
    .rj-act{gap:10px}
    .rj-btn.simpan{background:#16a34a}
    .rj-btn.simpan:hover{background:#128a3e;filter:none}
    .rj-btn.hapus{background:#fff;color:#b40000;border:1px solid #b40000}
    .rj-btn.hapus:hover{background:#b40000;color:#fff;filter:none}

    .rj-toast{position:fixed;top:18px;right:18px;z-index:9999;background:#1d1d1d;color:#fff;font-size:12px;padding:10px 16px;border-radius:8px;border-left:4px solid #12c400;box-shadow:0 4px 14px rgba(0,0,0,.3);opacity:0;transform:translateY(-8px);transition:.25s;pointer-events:none;max-width:340px}
    .rj-toast.err{border-color:#ff5a5f}
    .rj-toast.show{opacity:1;transform:none}

    @media (max-width:800px){.rj-pilih,.rj-grid2{grid-template-columns:1fr}}
</style>
@endpush

@section('konten')
<section class="rj-card">
    <h3 class="rj-title">Surat Rujukan</h3>

    <table class="rj-table">
        <thead>
            <tr>
                <th style="width:50px">No</th><th>Jenis</th><th>Tujuan</th><th>Dokter Perujuk</th>
                <th>Tgl Surat</th><th>Jadwal Datang</th><th style="width:140px">Action</th>
            </tr>
        </thead>
        <tbody id="rjRows"></tbody>
    </table>

    <h3 class="rj-h2">Buat Surat Rujukan Baru</h3>

    <form id="rjForm" autocomplete="off">
        <div class="rj-pilih">
            <label class="rj-opt on" id="optInternal">
                <input type="radio" name="jenis" value="Internal" checked>
                <span class="dot"></span>
                <span><b>Rujukan Internal</b></span>
            </label>
            <label class="rj-opt" id="optEksternal">
                <input type="radio" name="jenis" value="Eksternal">
                <span class="dot"></span>
                <span><b>Rujukan Eksternal</b></span>
            </label>
        </div>

        {{-- ============ FORM RUJUKAN INTERNAL ============ --}}
        <div id="blokInternal">
            <div class="rj-grid2">
                <div class="rj-f">
                    <label for="rjPoli">Poli Tujuan</label>
                    <select id="rjPoli" name="poli_tujuan">
                        <option value="">Poli Tujuan</option>
                        @foreach ($daftarPoli as $kode => $nama)
                            <option value="{{ $kode }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="rj-f">
                    <label for="rjJadwal">Jadwal Pasien Harus Datang/Diperiksa</label>
                    <input type="date" id="rjJadwal" name="jadwal_datang" value="{{ now()->toDateString() }}" min="{{ now()->toDateString() }}">
                </div>
            </div>
        </div>

        {{-- ============ FORM RUJUKAN EKSTERNAL ============ --}}
        <div id="blokEksternal" hidden>
            <div class="rj-grid2">
                <div class="rj-f">
                    <label for="rjKepada">Ditujukan Kepada (TS.Dokter Jaga/Poli)</label>
                    <input type="text" id="rjKepada" name="ditujukan_kepada" placeholder="Contoh: TS.Dokter Jaga IGD" disabled>
                </div>
                <div class="rj-f">
                    <label for="rjFaskes">Nama Rumah Sakit/ Fasyankes Tujuan</label>
                    <input type="text" id="rjFaskes" name="nama_faskes" placeholder="Contoh: RSUD dr. Soeroto Ngawi" disabled>
                </div>
            </div>
        </div>

        {{-- ============ RESUME MEDIS (sama untuk keduanya) ============ --}}
        <h3 class="rj-resume">Resume Medis ( terisi otomatis dari data pemeriksaan )</h3>

        <div class="rj-f gap">
            <label for="rjAnamnesa">Anamnesa</label>
            <textarea id="rjAnamnesa" name="anamnesa">{{ $resume['anamnesa'] }}</textarea>
        </div>
        <div class="rj-f gap">
            <label for="rjDiagnosa">Diagnosa</label>
            <input type="text" id="rjDiagnosa" name="diagnosa" value="{{ $resume['diagnosa'] }}">
        </div>
        <div class="rj-f gap">
            <label for="rjTerapi">Terapi Diberikan</label>
            <textarea id="rjTerapi" name="terapi">{{ $resume['terapi'] }}</textarea>
        </div>
        <div class="rj-f gap">
            <label for="rjDokter">Dokter/Petugas Perujuk (tercantum di surat rujukan)</label>
            <input type="text" id="rjDokter" class="sm" name="dokter_perujuk" value="{{ $dokterPerujuk }}">
        </div>

        <div class="rj-act">
            <button type="button" class="rj-btn hapus" id="rjHapus">Hapus</button>
            <button type="button" class="rj-btn simpan" id="rjSimpan">Simpan</button>
            <button type="submit" class="rj-btn" id="rjBtn">Cetak Rujukan</button>
        </div>
    </form>
</section>

<div class="rj-toast" id="rjToast"></div>
@endsection

@push('scripts')
<script>
(() => {
    const storeUrl = @json($storeUrl);
    const csrf     = document.querySelector('meta[name="csrf-token"]').content;
    let data       = @json($riwayat);

    const form = document.getElementById('rjForm'), rows = document.getElementById('rjRows'),
          btn = document.getElementById('rjBtn'), toastEl = document.getElementById('rjToast'),
          btnSimpan = document.getElementById('rjSimpan'), btnHapus = document.getElementById('rjHapus');
    const blok = { Internal: document.getElementById('blokInternal'), Eksternal: document.getElementById('blokEksternal') };
    const opt  = { Internal: document.getElementById('optInternal'),  Eksternal: document.getElementById('optEksternal') };
    const esc  = s => String(s ?? '').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));

    function toast(msg, err) {
        toastEl.textContent = msg;
        toastEl.className = 'rj-toast show' + (err ? ' err' : '');
        clearTimeout(toast.t); toast.t = setTimeout(() => toastEl.classList.remove('show'), 3000);
    }

    // Tampilan form otomatis berganti sesuai jenis rujukan yang dipilih
    function pilihJenis(jenis) {
        Object.keys(blok).forEach(j => {
            const aktif = j === jenis;
            blok[j].hidden = !aktif;
            opt[j].classList.toggle('on', aktif);
            blok[j].querySelectorAll('input,select').forEach(el => el.disabled = !aktif); // yg disembunyikan tidak ikut terkirim
        });
    }
    form.querySelectorAll('input[name="jenis"]').forEach(r => r.addEventListener('change', () => pilihJenis(r.value)));
    pilihJenis('Internal');

    function render() {
        if (!data.length) {
            rows.innerHTML = '<tr class="empty"><td colspan="7">Belum ada surat rujukan.</td></tr>';
            return;
        }
        rows.innerHTML = data.map((r, i) => `
            <tr>
                <td>${i + 1}</td>
                <td><span class="rj-tag ${r.jenis === 'Eksternal' ? 'eks' : ''}">${esc(r.jenis)}</span></td>
                <td>${esc(r.tujuan)}</td>
                <td>${esc(r.dokter)}</td>
                <td>${esc(r.tgl_surat)}</td>
                <td>${esc(r.jadwal)}</td>
                <td class="rj-aksi">
                    <button type="button" data-aksi="cetak" data-i="${i}">Cetak</button>
                    <button type="button" data-aksi="hapus" data-i="${i}">Hapus</button>
                </td>
            </tr>`).join('');
    }

    rows.addEventListener('click', async e => {
        const b = e.target.closest('button'); if (!b) return;
        const r = data[+b.dataset.i];
        if (b.dataset.aksi === 'cetak') return window.open(r.cetak_url, '_blank');
        if (!confirm('Hapus surat rujukan ini?' + (r.jenis === 'Internal' ? '\nAntrian di poli tujuan juga dibatalkan (jika belum dilayani).' : ''))) return;
        try {
            const res = await fetch(r.hapus_url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
            if (!res.ok) throw new Error();
            data.splice(+b.dataset.i, 1); render(); toast('Surat rujukan dihapus');
        } catch (_) { toast('Gagal menghapus', true); }
    });

    function kunciTombol(kunci) { btn.disabled = btnSimpan.disabled = btnHapus.disabled = kunci; }

    // Kosongkan form: semua isian kembali ke kondisi awal (resume medis terisi lagi dari data pemeriksaan)
    function kosongkanForm() {
        form.reset();
        pilihJenis(form.querySelector('input[name="jenis"]:checked').value);
    }

    // cetak = true  -> simpan lalu buka surat rujukan untuk dicetak (tombol "Cetak Rujukan")
    // cetak = false -> hanya simpan, datanya masuk ke tabel di atas (tombol "Simpan")
    async function kirim(cetak) {
        kunciTombol(true);
        const jendela = cetak ? window.open('', '_blank') : null;   // dibuka sekarang supaya tidak diblokir popup-blocker
        try {
            const res = await fetch(storeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: new FormData(form) });
            if (res.status === 422) {
                const j = await res.json();
                const pesan = Object.values(j.errors || {})[0]?.[0] || 'Data belum lengkap';
                if (jendela) jendela.close();
                return toast(pesan, true);
            }
            if (!res.ok) throw new Error();
            const saved = await res.json();
            data.push(saved); render();
            if (cetak) {
                if (jendela) jendela.location = saved.cetak_url;
                else toast('Rujukan tersimpan. Klik "Cetak" di tabel untuk mencetak.');
                if (saved.jenis === 'Internal') toast('Rujukan internal dibuat, pasien masuk antrian poli tujuan');
            } else {
                kosongkanForm();
                toast(saved.jenis === 'Internal'
                    ? 'Rujukan internal tersimpan, pasien masuk antrian poli tujuan'
                    : 'Surat rujukan tersimpan');
            }
        } catch (_) {
            if (jendela) jendela.close();
            toast('Gagal menyimpan, coba lagi', true);
        } finally { kunciTombol(false); }
    }

    form.addEventListener('submit', e => { e.preventDefault(); kirim(true); });
    btnSimpan.addEventListener('click', () => kirim(false));
    btnHapus.addEventListener('click', () => { kosongkanForm(); toast('Isian form dikosongkan'); });

    render();
})();
</script>
@endpush
