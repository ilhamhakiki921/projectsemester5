{{--
    Hak akses PERAWAT (aturannya ada di config/akses.php).

    Dipasang sekali di components/sidebar.blade.php, jadi otomatis aktif di semua
    halaman yang memakai sidebar. Untuk role selain perawat komponen ini tidak
    mengeluarkan apa-apa (kecuali pesan "akses dibatasi" kalau memang ada).

    Prinsipnya: TAMPILAN TIDAK DIUBAH. Halaman yang "hanya lihat" tetap tampil
    persis sama; bedanya kolom isian tidak bisa diketik dan tombol
    simpan/tambah/edit/hapus tidak bisa dipakai (muncul pemberitahuan kecil).
    Pengamanan sebenarnya ada di server (App\Http\Middleware\BatasiAkses),
    jadi walaupun tampilan dilewati, data tetap tidak bisa tersimpan.
--}}
@php
    $penggunaAkses = auth()->user();
    $aturanAkses   = null;

    if ($penggunaAkses && $penggunaAkses->role === 'perawat') {
        $namaRouteAkses = (string) optional(request()->route())->getName();

        foreach (config('akses.perawat.ui', []) as $polaAkses => $aturan) {
            if (\Illuminate\Support\Str::is($polaAkses, $namaRouteAkses)) {
                $aturanAkses = $aturan;
                break;
            }
        }
    }

    // Petugas Rekam Medis: halaman Pemeriksaan hanya boleh dilihat (lihat config/akses.php, blok 'rm')
    if ($penggunaAkses && $penggunaAkses->isRm()) {
        $namaRouteRm = (string) optional(request()->route())->getName();

        if ($namaRouteRm !== '' && in_array($namaRouteRm, config('akses.rm.route', []), true)) {
            $aturanAkses = config('akses.rm.ui.*');
        }
    }

    $pesanAkses = session('akses_ditolak');
@endphp

@if ($aturanAkses || $pesanAkses)
<style>
    .akses-toast {
        position: fixed;
        top: 18px;
        right: 18px;
        z-index: 99999;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        max-width: 340px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #ffffff;
        border-left: 4px solid #f59e0b;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        font-family: 'Poppins', sans-serif;
        color: #222;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity .25s ease, transform .25s ease;
        pointer-events: none;
    }
    .akses-toast.tampil { opacity: 1; transform: translateY(0); }
    .akses-toast i { color: #f59e0b; font-size: 18px; margin-top: 1px; }
    .akses-toast strong { display: block; font-size: 12px; font-weight: 600; }
    .akses-toast span   { display: block; font-size: 11px; line-height: 1.45; color: #555; }

    [data-akses-kunci], [data-akses-blok] { cursor: not-allowed !important; }
    @if (! empty($aturanAkses['pointer']))
    {!! $aturanAkses['pointer'] !!},
    {!! $aturanAkses['pointer'] !!} * { pointer-events: none !important; cursor: not-allowed; }
    @endif
</style>

<script>
(function () {
    var ATURAN = @json($aturanAkses);
    var FLASH  = @json($pesanAkses);
    var PESAN_LIHAT = (ATURAN && typeof ATURAN.pesan === 'string') ? ATURAN.pesan : 'Akses perawat pada bagian ini hanya untuk melihat data.';
    var elToast = null, timerToast = null;

    function toast(teks) {
        if (!teks) { return; }
        if (!elToast) {
            elToast = document.createElement('div');
            elToast.className = 'akses-toast';
            elToast.innerHTML = '<i class="bi bi-lock-fill"></i><div><strong>Akses dibatasi</strong><span></span></div>';
            document.body.appendChild(elToast);
        }
        elToast.querySelector('span').textContent = teks;
        elToast.classList.add('tampil');
        clearTimeout(timerToast);
        timerToast = setTimeout(function () { elToast.classList.remove('tampil'); }, 2600);
    }

    function semua(sel) {
        if (!sel) { return []; }
        try { return document.querySelectorAll(sel); } catch (e) { return []; }
    }

    function dekat(el, sel) {
        if (!sel || !el || !el.closest) { return null; }
        try { return el.closest(sel); } catch (e) { return null; }
    }

    function kunciSemua() {
        // Kolom ketik -> readonly (tampilan tidak berubah). Pilihan/centang -> dikunci lewat event.
        semua(ATURAN.bidang).forEach(function (el) {
            var tipe = (el.type || '').toLowerCase();
            if (el.tagName === 'SELECT' || ['checkbox', 'radio', 'file', 'range', 'color'].indexOf(tipe) > -1) {
                el.setAttribute('tabindex', '-1');
            } else {
                el.readOnly = true;
            }
            el.setAttribute('aria-readonly', 'true');
            el.setAttribute('data-akses-kunci', '1');
        });

        semua(ATURAN.blok).forEach(function (el) {
            el.setAttribute('data-akses-blok', '1');
            el.setAttribute('aria-disabled', 'true');
        });
    }

    function hentikan(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        toast(PESAN_LIHAT);
    }

    if (ATURAN) {
        // Klik tombol yang diblokir / klik pada pilihan & centang yang dikunci
        document.addEventListener('click', function (e) {
            if (dekat(e.target, ATURAN.blok)) { return hentikan(e); }

            var kunci = dekat(e.target, '[data-akses-kunci]');
            if (kunci && kunci.matches('select, input[type="checkbox"], input[type="radio"], input[type="file"]')) {
                return hentikan(e);
            }
        }, true);

        // Dropdown <select> tidak boleh terbuka / berubah lewat mouse atau keyboard
        document.addEventListener('mousedown', function (e) {
            var s = dekat(e.target, 'select[data-akses-kunci]');
            if (s) { e.preventDefault(); toast(PESAN_LIHAT); }
        }, true);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Tab') { return; }
            var s = e.target && e.target.matches && e.target.matches('select[data-akses-kunci]');
            if (s) { e.preventDefault(); }
        }, true);

        // Form tidak boleh terkirim (termasuk lewat tombol Enter)
        document.addEventListener('submit', function (e) {
            if (ATURAN.form && e.target.matches && e.target.matches(ATURAN.form)) { hentikan(e); }
        }, true);

        // Konten yang dibuat JavaScript (mis. baris tabel Penunjang) ikut dikunci
        var terjadwal = false;
        function jadwalkan() {
            if (terjadwal) { return; }
            terjadwal = true;
            requestAnimationFrame(function () { terjadwal = false; kunciSemua(); });
        }

        function mulai() {
            kunciSemua();
            new MutationObserver(jadwalkan).observe(document.body, { childList: true, subtree: true });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', mulai);
        } else {
            mulai();
        }
    }

    if (FLASH) {
        var tampilFlash = function () { toast(FLASH); };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', tampilFlash);
        } else {
            tampilFlash();
        }
    }
})();
</script>
@endif
