@extends('layouts.pemeriksaan', [
    'tabAktif'  => 'Penunjang',
    'backUrl'   => route('pemeriksaan.index', $poli),
    'backLabel' => 'Kembali ke Antrian',
])

@php
    // Struktur form: kolom => kotak (dropdown) => [sub-judul (atau null), daftar pemeriksaan]
    $kolom = [
        [
            ['Kimia Klinik', [
                ['Kadar Gula Darah', ['Glukosa darah puasa', 'Glukosa darah 2 jam pp', 'Glukosa darah sewaktu', 'HbA1c']],
                ['Profil Lemak', ['Cholesterol total', 'HDL - Cholesterol', 'LDL - Cholesterol', 'Trigliserida']],
                ['Faal Hati', ['Bilirubin (Direk/Total)', 'Alkali Phosphatase', 'Gamma GT', 'SGOT', 'SGPT', 'Total Protein', 'Albumin', 'Globulin']],
                ['Faal Ginjal', ['Serum Creatinin', 'BUN', 'Uric Acid']],
                ['Penanda Tumor', ['CEA', 'Ca 125', 'Ca 15-3', 'AFP', 'Beta HCG', 'Ca 19-9']],
            ]],
            ['Elektrolit', [[null, ['Natrium', 'Kalium', 'Chlorida', 'Calcium']]]],
            ['Hematologi', [[null, ['Darah Lengkap', 'LED / BBS', 'Hb', 'Eritrosit', 'Leukosit', 'Hitung Jenis', 'Hapusan darah tepi', 'Trombosit', 'Hematokrit / PCV', 'Malaria', 'Golongan darah + Rhesus', 'Waktu pendarahan', 'Waktu pembekuan']]]],
        ],
        [
            ['Faeces', [[null, ['Faeces lengkap']]]],
            ['Tiroid', [[null, ['T3', 'T4', 'TSHs', 'FT4']]]],
            ['TORCH', [[null, ['Toxoplasma', 'Rubella', 'Cyto Megalo Virus (CMV)', 'Herpes Simplex']]]],
        ],
        [
            ['Urine', [[null, ['Urine lengkap', 'Albumin', 'Reduksi', 'Urobilin', 'Bilirubin', 'Keton', 'Sedimen', 'pH', 'Berat Jenis', 'Nitrit', 'Test Kehamilan', 'Plano Test', 'Narkotika', 'Amphetamine']]]],
            ['Immunologi', [[null, ['Widal', 'VDRL / TPHA', 'HIV', 'HBsAg', 'HBsAb', 'HBeAg', 'HBeAb', 'Anti HCV', 'Anti Hbs', 'ASTO', 'Helicobacter Pylori']]]],
            ['Lain - lain', [[null, ['Analisa Sperma', 'Pemeriksaan ECG']]]],
        ],
    ];
@endphp

@push('styles')
<style>
    .pj-card{border:1px solid #6b6b6b;border-radius:14px;padding:26px 22px 28px;background:#fff}
    .pj-h{margin:0;font-size:14px;font-weight:600;color:#111}
    .pj-sub{margin:2px 0 14px;font-size:9px;font-weight:500;color:#333}

    .pj-table{width:100%;border-collapse:separate;border-spacing:0}
    .pj-table thead th{background:linear-gradient(90deg,#a11a1e,#5c0a0c);color:#fff;font-size:12px;font-weight:600;padding:11px 14px;text-align:center}
    .pj-table thead th:first-child{border-radius:6px 0 0 6px}
    .pj-table thead th:last-child{border-radius:0 6px 6px 0}
    .pj-table tbody td{padding:12px 14px;border-bottom:1px solid #eee;text-align:center;font-size:12px;vertical-align:top}
    .pj-table tbody td.l{text-align:left}
    .pj-table tbody tr.empty td{border:0;padding:34px 14px 80px;font-size:10px;font-weight:500;color:#222}
    .pj-badge{display:inline-block;background:#fff3cd;color:#8a6100;border-radius:10px;padding:2px 10px;font-size:11px;font-weight:500}
    .pj-del{border:1px solid #a11a1e;color:#a11a1e;background:#fff;border-radius:6px;padding:2px 12px;font:inherit;font-size:11px;cursor:pointer}
    .pj-del:hover{background:#a11a1e;color:#fff}

    .pj-ftitle{margin:34px 0 14px 4px;font-size:12px;font-weight:600;color:#111}
    .pj-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;align-items:start}
    .pj-col{display:flex;flex-direction:column;gap:16px}
    .pj-box{border:1px solid #cfcfcf;border-radius:8px;background:#fff;box-shadow:0 2px 5px rgba(0,0,0,.18);overflow:hidden}
    .pj-box > h4{margin:0;font-size:12px;font-weight:600;border-radius:8px;background:linear-gradient(90deg,#a11a1e,#5c0a0c)}
    .pj-box.open > h4{border-radius:8px 8px 0 0}

    /* Judul kategori = tombol dropdown */
    .pj-toggle{position:relative;display:flex;align-items:center;justify-content:center;width:100%;padding:11px 44px;border:0;background:transparent;color:#fff;font:inherit;font-size:12px;font-weight:600;cursor:pointer;text-align:center}
    .pj-toggle:focus-visible{outline:2px solid #fff;outline-offset:-4px;border-radius:8px}
    .pj-meta{position:absolute;right:14px;top:50%;transform:translateY(-50%);display:flex;align-items:center;gap:8px}
    .pj-count{display:inline-block;background:#fff;color:#a11a1e;border-radius:10px;padding:1px 8px;font-size:9.5px;font-weight:700;line-height:1.5}
    .pj-count[hidden]{display:none}
    .pj-chev{display:inline-block;width:7px;height:7px;margin-top:-3px;border-right:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(45deg);transition:transform .25s,margin .25s}
    .pj-box.open .pj-chev{transform:rotate(225deg);margin-top:3px}

    /* Isi dropdown: membuka/menutup dengan halus, kotak di bawahnya otomatis bergeser */
    .pj-body{display:grid;grid-template-rows:0fr;visibility:hidden;transition:grid-template-rows .25s ease,visibility 0s .25s}
    .pj-box.open .pj-body{grid-template-rows:1fr;visibility:visible;transition:grid-template-rows .25s ease,visibility 0s}
    .pj-clip{overflow:hidden;min-height:0}
    .pj-in{padding:10px 14px 14px}
    .pj-grp{font-size:9.5px;font-weight:700;color:#111;margin:8px 0 4px}
    .pj-grp:first-child{margin-top:0}
    .pj-ck{display:flex;align-items:center;gap:10px;padding:4px 0 4px 6px;font-size:9.5px;font-weight:500;color:#222;cursor:pointer}
    .pj-ck input{width:13px;height:13px;flex-shrink:0;accent-color:#a11a1e;cursor:pointer}

    .pj-ket{margin-top:26px}
    .pj-ket label{display:block;font-size:10px;font-weight:600;color:#111;margin:0 0 6px 2px}
    .pj-ket textarea{display:block;width:100%;height:68px;border:1px solid #bdbdbd;border-radius:8px;padding:10px 14px;font:inherit;font-size:10px;resize:vertical;text-align:center}
    .pj-ket textarea::placeholder{color:#777;font-size:9px;text-align:center}
    .pj-ket textarea:focus{outline:2px solid #a11a1e;outline-offset:1px}

    .pj-act{margin-top:24px;display:flex;justify-content:flex-end}
    .pj-btn{border:0;border-radius:6px;padding:9px 16px;font:inherit;font-size:10px;font-weight:600;color:#fff;background:#12c400;cursor:pointer}
    .pj-btn:hover{filter:brightness(.92)}
    .pj-btn:disabled{opacity:.6;cursor:wait}

    .pj-toast{position:fixed;top:18px;right:18px;z-index:9999;background:#1d1d1d;color:#fff;font-size:12px;padding:10px 16px;border-radius:8px;border-left:4px solid #12c400;box-shadow:0 4px 14px rgba(0,0,0,.3);opacity:0;transform:translateY(-8px);transition:.25s;pointer-events:none}
    .pj-toast.err{border-color:#ff5a5f}
    .pj-toast.show{opacity:1;transform:none}

    @media (max-width:1100px){.pj-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media (max-width:700px){.pj-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('konten')
<section class="pj-card">
    <h3 class="pj-h">Pemeriksaan Penunjang — Permintaan Laboratorium</h3>
    <p class="pj-sub">Formulir mengikuti form Instalasi Laboratorium Merah Putih Group</p>

    <table class="pj-table">
        <thead>
            <tr>
                <th style="width:70px">No</th>
                <th>Pemeriksaan Diminta</th>
                <th style="width:150px">Tanggal</th>
                <th style="width:130px">Status</th>
                <th style="width:120px">Action</th>
            </tr>
        </thead>
        <tbody id="pjRows"></tbody>
    </table>

    <h3 class="pj-ftitle">Formulir Permintaan Pemeriksaan Laboratorium</h3>

    <form id="pjForm" autocomplete="off">
        <div class="pj-grid">
            @foreach ($kolom as $kotakKotak)
                <div class="pj-col">
                    @foreach ($kotakKotak as [$judul, $grupGrup])
                        <div class="pj-box">
                            <h4>
                                <button type="button" class="pj-toggle" aria-expanded="false">
                                    {{ $judul }}
                                    <span class="pj-meta">
                                        <span class="pj-count" hidden></span>
                                        <span class="pj-chev"></span>
                                    </span>
                                </button>
                            </h4>
                            <div class="pj-body">
                                <div class="pj-clip">
                                    <div class="pj-in">
                                        @foreach ($grupGrup as [$sub, $items])
                                            @if ($sub)
                                                <div class="pj-grp">{{ $sub }}</div>
                                            @endif
                                            @foreach ($items as $item)
                                                <label class="pj-ck">
                                                    <input type="checkbox" name="pemeriksaan[]" value="{{ $item }}">
                                                    {{ $item }}
                                                </label>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="pj-ket">
            <label for="pjKet">Keterangan Klinik</label>
            <textarea id="pjKet" name="keterangan" placeholder="Catatan klinis untuk laboratorium (opsional)"></textarea>
        </div>

        <div class="pj-act">
            <button type="submit" class="pj-btn" id="pjBtn">+ Buat Permintaan Laboratorium</button>
        </div>
    </form>
</section>

<div class="pj-toast" id="pjToast"></div>
@endsection

@push('scripts')
<script>
(() => {
    const storeUrl = @json($storeUrl);
    const csrf     = document.querySelector('meta[name="csrf-token"]').content;
    let data       = @json($riwayat);

    const rows  = document.getElementById('pjRows');
    const form  = document.getElementById('pjForm');
    const btn   = document.getElementById('pjBtn');
    const toastEl = document.getElementById('pjToast');
    const boxes = form.querySelectorAll('.pj-box');
    const esc = s => String(s).replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));

    function toast(msg, err) {
        toastEl.textContent = msg;
        toastEl.className = 'pj-toast show' + (err ? ' err' : '');
        clearTimeout(toast.t);
        toast.t = setTimeout(() => toastEl.classList.remove('show'), 2400);
    }

    // Dropdown kategori: buka/tutup, dan tampilkan jumlah pemeriksaan yang dicentang
    function hitung(box) {
        const n = box.querySelectorAll('input[type=checkbox]:checked').length;
        const c = box.querySelector('.pj-count');
        c.textContent = n + ' dipilih';
        c.hidden = !n;
    }
    function aturBuka(box, buka) {
        box.classList.toggle('open', buka);
        box.querySelector('.pj-toggle').setAttribute('aria-expanded', buka ? 'true' : 'false');
    }
    boxes.forEach(box => {
        box.querySelector('.pj-toggle').addEventListener('click', () => aturBuka(box, !box.classList.contains('open')));
        box.addEventListener('change', () => hitung(box));
    });

    function render() {
        if (!data.length) {
            rows.innerHTML = '<tr class="empty"><td colspan="5">Belum ada permintaan penunjang.</td></tr>';
            return;
        }
        rows.innerHTML = data.map((r, i) => `
            <tr>
                <td>${i + 1}</td>
                <td class="l">${esc(r.items.join(', '))}</td>
                <td>${esc(r.tanggal)}</td>
                <td><span class="pj-badge">${esc(r.status || 'Menunggu')}</span></td>
                <td><button type="button" class="pj-del" data-i="${i}">Hapus</button></td>
            </tr>`).join('');
    }

    rows.addEventListener('click', async e => {
        const b = e.target.closest('.pj-del');
        if (!b) return;
        if (!confirm('Hapus permintaan ini?')) return;
        const r = data[+b.dataset.i];
        try {
            const res = await fetch(r.hapus_url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error();
            data.splice(+b.dataset.i, 1);
            render();
            toast('Permintaan dihapus');
        } catch (_) {
            toast('Gagal menghapus (mungkin sudah diproses)', true);
        }
    });

    form.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(form);
        if (!fd.getAll('pemeriksaan[]').length) {
            return toast('Pilih minimal satu pemeriksaan', true);
        }
        btn.disabled = true;
        try {
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: fd
            });
            if (!res.ok) throw new Error();
            data.push(await res.json());
            render();
            form.reset();
            boxes.forEach(box => { hitung(box); aturBuka(box, false); });
            toast('Permintaan laboratorium berhasil dibuat');
        } catch (_) {
            toast('Gagal menyimpan, coba lagi', true);
        } finally {
            btn.disabled = false;
        }
    });

    render();
})();
</script>
@endpush
