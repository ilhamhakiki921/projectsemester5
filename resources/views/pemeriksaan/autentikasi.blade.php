@extends('layouts.pemeriksaan', [
    'tabAktif'  => 'Autentikasi',
    'backUrl'   => route('pemeriksaan.index', $poli),
    'backLabel' => 'Kembali ke Antrian',
])

@push('styles')
<style>
    .au-card{border:1px solid #6b6b6b;border-radius:14px;padding:22px 20px 22px;background:#fff}
    .au-title{margin:0;font-size:13px;font-weight:700;color:#c00000}
    .au-sub{margin:3px 0 14px;font-size:10px;font-weight:500;color:#222}

    .au-info{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:6px;background:#c5e8ee;color:#111;font-size:10.5px;margin-bottom:18px}
    .au-info i{font-size:14px}
    .au-warn{background:#fff1c9}
    .au-ok{background:#d9f2dd}

    .au-grid{display:grid;grid-template-columns:1fr 1fr;gap:6px 22px}
    .au-f label{display:block;font-size:10.5px;font-weight:700;color:#111;margin:0 0 6px}
    .au-f input{display:block;width:100%;height:36px;border:1px solid #9a9a9a;border-radius:8px;background:#fff;font:inherit;font-size:11px;padding:0 14px;color:#111}
    .au-f input::placeholder{color:#9a9a9a}
    .au-f input:focus{outline:2px solid #a11a1e;outline-offset:1px}

    .au-ttd-label{margin:16px 0 6px;font-size:10.5px;font-weight:700;color:#111}
    .au-pad{border:1px solid #9a9a9a;border-radius:8px;overflow:hidden;background:#e4e4e4}
    .au-pad .area{position:relative;height:190px}
    .au-pad canvas{position:absolute;inset:0;width:100%;height:100%;touch-action:none;cursor:crosshair;display:block}
    .au-pad .bar{display:flex;justify-content:space-between;align-items:center;padding:8px 14px;border-top:1px solid #bdbdbd;background:#ececec}
    .au-pad .hint{font-size:8.5px;color:#888}
    .au-pad .clear{border:0;background:none;color:#c00000;font:inherit;font-size:10.5px;font-weight:700;cursor:pointer}
    .au-pad .clear:hover{text-decoration:underline}

    .au-act{margin-top:14px;display:flex;justify-content:flex-end}
    .au-btn{display:inline-flex;align-items:center;gap:8px;border:0;border-radius:6px;padding:9px 20px;font:inherit;font-size:11px;font-weight:600;color:#fff;background:linear-gradient(90deg,#a11a1e,#5c0a0c);cursor:pointer}
    .au-btn:hover{filter:brightness(.9)}
    .au-btn:disabled{opacity:.6;cursor:wait}

    .au-toast{position:fixed;top:18px;right:18px;z-index:9999;background:#1d1d1d;color:#fff;font-size:12px;padding:10px 16px;border-radius:8px;border-left:4px solid #12c400;box-shadow:0 4px 14px rgba(0,0,0,.3);opacity:0;transform:translateY(-8px);transition:.25s;pointer-events:none;max-width:360px}
    .au-toast.err{border-color:#ff5a5f}
    .au-toast.show{opacity:1;transform:none}

    @media (max-width:800px){.au-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('konten')
<section class="au-card">
    <h3 class="au-title">Autentikasi Pemeriksaan</h3>
    <p class="au-sub">Pengesahan seluruh data pemeriksaan oleh dokter/petugas</p>

    @if ($autentikasi)
        <div class="au-info au-ok" id="auStatus">
            <i class="bi bi-patch-check"></i>
            <span>Pemeriksaan sudah diautentikasi oleh <b>{{ $autentikasi->nama_dokter }}</b> pada {{ $autentikasi->waktu_autentikasi->format('d-m-Y H:i') }}. Anda dapat mengautentikasi ulang bila ada perubahan.</span>
        </div>
    @elseif (count($bagianKosong))
        <div class="au-info au-warn">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Data <b>{{ implode(', ', $bagianKosong) }}</b> belum diisi. Lengkapi dulu sebelum melakukan autentikasi.</span>
        </div>
    @else
        <div class="au-info">
            <i class="bi bi-info-circle"></i>
            <span>Pastikan Assessment, SOAP, dan Diagnosis telah diisi sebelum melakukan autentikasi.</span>
        </div>
    @endif

    <form id="auForm" autocomplete="off">
        <div class="au-grid">
            <div class="au-f">
                <label for="auNama">Nama Dokter/Petugas</label>
                <input type="text" id="auNama" name="nama_dokter" value="{{ $namaDefault }}" placeholder="Contoh: dr. Agung Setiawan">
            </div>
            <div class="au-f">
                <label for="auJabatan">Jabatan</label>
                <input type="text" id="auJabatan" name="jabatan" value="{{ $autentikasi->jabatan ?? '' }}" placeholder="Contoh: Dokter Umum">
            </div>
        </div>

        <div class="au-ttd-label">Tanda Tangan Digital</div>
        <div class="au-pad">
            <div class="area"><canvas id="auCanvas"></canvas></div>
            <div class="bar">
                <span class="hint">Gunakan mouse/jari untuk menandatangani</span>
                <button type="button" class="clear" id="auClear">Bersihkan</button>
            </div>
        </div>

        <div class="au-act">
            <button type="submit" class="au-btn" id="auBtn"><i class="bi bi-shield-check"></i> Autentikasi Pemeriksaan</button>
        </div>
    </form>
</section>

<div class="au-toast" id="auToast"></div>
@endsection

@push('scripts')
<script>
(() => {
    const storeUrl = @json($storeUrl);
    const csrf     = document.querySelector('meta[name="csrf-token"]').content;
    const form = document.getElementById('auForm'), btn = document.getElementById('auBtn'),
          toastEl = document.getElementById('auToast'), canvas = document.getElementById('auCanvas'),
          ctx = canvas.getContext('2d');

    function toast(msg, err) {
        toastEl.textContent = msg;
        toastEl.className = 'au-toast show' + (err ? ' err' : '');
        clearTimeout(toast.t); toast.t = setTimeout(() => toastEl.classList.remove('show'), 3500);
    }

    // ---------- Kanvas tanda tangan ----------
    let menggambar = false, adaGoresan = false;

    function siapkanKanvas() {
        const r = canvas.getBoundingClientRect(), dpr = window.devicePixelRatio || 1;
        canvas.width = Math.round(r.width * dpr);
        canvas.height = Math.round(r.height * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        ctx.lineWidth = 2.2; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#111';
        adaGoresan = false;
    }
    siapkanKanvas();

    const titik = e => { const r = canvas.getBoundingClientRect(); return [e.clientX - r.left, e.clientY - r.top]; };

    canvas.addEventListener('pointerdown', e => {
        e.preventDefault();
        canvas.setPointerCapture(e.pointerId);
        menggambar = true;
        const [x, y] = titik(e);
        ctx.beginPath(); ctx.moveTo(x, y); ctx.lineTo(x + 0.01, y + 0.01); ctx.stroke();
        adaGoresan = true;
    });
    canvas.addEventListener('pointermove', e => {
        if (!menggambar) return;
        const [x, y] = titik(e);
        ctx.lineTo(x, y); ctx.stroke(); ctx.beginPath(); ctx.moveTo(x, y);
    });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach(ev =>
        canvas.addEventListener(ev, () => { menggambar = false; ctx.beginPath(); }));

    document.getElementById('auClear').addEventListener('click', siapkanKanvas);

    // ---------- Kirim ----------
    form.addEventListener('submit', async e => {
        e.preventDefault();

        if (!document.getElementById('auNama').value.trim()) return toast('Nama dokter/petugas wajib diisi.', true);
        if (!document.getElementById('auJabatan').value.trim()) return toast('Jabatan wajib diisi.', true);
        if (!adaGoresan) return toast('Tanda tangan digital wajib diisi.', true);

        btn.disabled = true;
        try {
            const body = new FormData(form);
            body.append('tanda_tangan', canvas.toDataURL('image/png'));

            const res = await fetch(storeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body });
            const j = await res.json().catch(() => ({}));

            if (res.status === 422) {
                return toast(Object.values(j.errors || {})[0]?.[0] || j.message || 'Data belum lengkap', true);
            }
            if (!res.ok) throw new Error();

            toast(j.message || 'Pemeriksaan berhasil diautentikasi');
            setTimeout(() => location.reload(), 1200);
        } catch (_) {
            toast('Gagal menyimpan, coba lagi', true);
        } finally { btn.disabled = false; }
    });
})();
</script>
@endpush
