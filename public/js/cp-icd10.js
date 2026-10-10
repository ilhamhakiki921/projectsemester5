// Autocomplete ICD-10 untuk form Clinical Pathway (memakai endpoint yang sama dengan tab Diagnosa).
// Input diagnosis  : data-cp-icd data-cp-kode="<kolom kode>"
// Input kode ICD   : data-cp-icd data-cp-nama="<kolom diagnosis>"
// <kolom> berupa "#id" (di luar panel) atau nama field (di dalam panel kunjungan yang sama).
(function () {
    var form = document.getElementById('form-cp');
    if (!form || !form.dataset.icdUrl) return;

    var url = form.dataset.icdUrl;
    var box = document.createElement('div');
    box.className = 'icd-dropdown';
    box.hidden = true;
    document.body.appendChild(box);

    var active = null, items = [], index = -1, timer = null, controller = null;

    function esc(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function resolve(input, ref) {
        if (!ref) return null;
        if (ref.charAt(0) === '#') return document.querySelector(ref);
        var panel = input.closest('[data-panel]');
        return panel ? panel.querySelector('[name$="[' + ref + ']"]') : null;
    }

    function pasangan(input) {
        if (input.hasAttribute('data-cp-kode')) {
            return { nama: input, kode: resolve(input, input.getAttribute('data-cp-kode')) };
        }
        return { nama: resolve(input, input.getAttribute('data-cp-nama')), kode: input };
    }

    function fire(el) { if (el) el.dispatchEvent(new Event('input', { bubbles: true })); }

    function hide() { box.hidden = true; items = []; index = -1; }

    function position() {
        if (!active) return;
        var r = active.getBoundingClientRect();
        box.style.left = (r.left + window.scrollX) + 'px';
        box.style.top = (r.bottom + window.scrollY + 2) + 'px';
        box.style.width = Math.max(r.width, 260) + 'px';
    }

    function render(list, pesan) {
        items = list;
        index = -1;
        if (!list.length) {
            box.innerHTML = '<div class="icd-empty">' +
                esc(pesan || 'Tidak ada hasil. Anda tetap bisa mengetik secara manual.') + '</div>';
        } else {
            box.innerHTML = list.map(function (it, i) {
                return '<div class="icd-item" data-i="' + i + '">' +
                    '<span class="icd-code">' + esc(it.code) + '</span>' +
                    '<span class="icd-title">' + esc(it.title) + '</span></div>';
            }).join('');
        }
        position();
        box.hidden = false;
    }

    function highlight() {
        var nodes = box.querySelectorAll('.icd-item');
        nodes.forEach(function (n, i) { n.classList.toggle('is-active', i === index); });
        if (nodes[index]) nodes[index].scrollIntoView({ block: 'nearest' });
    }

    function choose(it) {
        if (!active || !it) return;
        var p = pasangan(active);
        if (p.nama) { p.nama.value = it.title; p.nama.dataset.icdPicked = it.title; }
        if (p.kode) p.kode.value = active.hasAttribute('data-cp-gabung') ? (it.code + ' ' + it.title) : it.code;
        hide();
        fire(p.nama); fire(p.kode);
    }

    function search(q) {
        if (controller) controller.abort();
        controller = new AbortController();
  fetch((active.dataset.cpUrl || url) + '?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json' },
            signal: controller.signal
        })
            .then(function (r) { return r.ok ? r.json() : Promise.reject(); })
            .then(function (res) {
                if (active && active.value.trim() === q) render(res.data || [], res.pesan);
            })
            .catch(function (err) {
                if (!err || err.name !== 'AbortError') hide();
            });
    }

    form.addEventListener('input', function (e) {
        var input = e.target.closest('[data-cp-icd]');
        if (!input || !e.isTrusted) return;   // abaikan perubahan yang dibuat lewat script
        active = input;

        // Teks diagnosis diubah setelah memilih -> kode dikosongkan agar tidak salah pasangan
        if (input.hasAttribute('data-cp-kode') && input.dataset.icdPicked &&
            input.value !== input.dataset.icdPicked) {
            var p = pasangan(input);
            if (p.kode) { p.kode.value = ''; fire(p.kode); }
            delete input.dataset.icdPicked;
        }

        clearTimeout(timer);
        var q = input.value.trim();
        if (q.length < 2) { hide(); return; }
        timer = setTimeout(function () { search(q); }, 300);
    });

    form.addEventListener('focusin', function (e) {
        var input = e.target.closest('[data-cp-icd]');
        if (input) active = input;
    });

    document.addEventListener('keydown', function (e) {
        if (box.hidden) return;
        if (e.key === 'ArrowDown' && items.length) { e.preventDefault(); index = (index + 1) % items.length; highlight(); }
        else if (e.key === 'ArrowUp' && items.length) { e.preventDefault(); index = (index - 1 + items.length) % items.length; highlight(); }
        else if (e.key === 'Enter' && index >= 0) { e.preventDefault(); choose(items[index]); }
        else if (e.key === 'Escape') { hide(); }
    });

    box.addEventListener('mousedown', function (e) {
        var row = e.target.closest('.icd-item');
        if (!row) return;
        e.preventDefault();
        choose(items[Number(row.dataset.i)]);
    });

    document.addEventListener('mousedown', function (e) {
        if (!box.contains(e.target) && !e.target.closest('[data-cp-icd]')) hide();
    });

    window.addEventListener('resize', position);

    // Tombol "Ambil dari Diagnosa": isi kolom dari data tab Diagnosa
    form.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-cp-tarik]');
        if (!btn) return;
        var data = {};
        try { data = JSON.parse(btn.getAttribute('data-cp-tarik')); } catch (err) {}
        var ada = Object.keys(data).some(function (k) { return data[k]; });
        if (!ada) { alert('Belum ada data di tab Diagnosa. Isi dan simpan Diagnosa terlebih dahulu.'); return; }

        var scope = btn.closest('[data-panel]') || form;
        Object.keys(data).forEach(function (k) {
            if (!data[k]) return;
            var el = scope.querySelector('[name="' + k + '"]') || scope.querySelector('[name$="[' + k + ']"]');
            if (el) { el.value = data[k]; delete el.dataset.icdPicked; fire(el); }
        });
    });
})();
