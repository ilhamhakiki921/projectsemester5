// Ikon mata: tampilkan / sembunyikan isi kolom (titik-titik <-> teks)
document.addEventListener('DOMContentLoaded', function () {
    var tombol = document.querySelectorAll('.eye-btn');

    Array.prototype.forEach.call(tombol, function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;

            var tampil = input.type === 'password';
            input.type = tampil ? 'text' : 'password';
            btn.classList.toggle('aktif', tampil);
            btn.setAttribute('aria-label', tampil ? 'Sembunyikan' : 'Tampilkan');
            input.focus();
        });
    });
});
