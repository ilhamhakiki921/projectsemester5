// "+ Tambah" menambah satu kotak isian; "×" menghapus kotak tambahan. Tanpa library.
(function () {
    var form = document.getElementById('form-diagnosa');
    if (!form) return;

    form.addEventListener('click', function (e) {
        var section = e.target.closest('[data-dx-section]');
        if (!section) return;

        if (e.target.closest('[data-add]')) {
            var tpl = section.querySelector('[data-template]');
            section.querySelector('[data-list]').appendChild(tpl.content.cloneNode(true));
            var selects = section.querySelectorAll('[data-list] select');
            selects[selects.length - 1].focus();
        } else if (e.target.closest('[data-remove]')) {
            e.target.closest('[data-row]').remove();
        }
    });
})();
