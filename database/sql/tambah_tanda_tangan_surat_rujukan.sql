-- Alternatif tanpa artisan: jalankan di phpMyAdmin (database yang dipakai project) jika tidak memakai "php artisan migrate".
ALTER TABLE `surat_rujukan`
    ADD COLUMN `tanda_tangan` LONGTEXT NULL AFTER `dokter_perujuk`;
