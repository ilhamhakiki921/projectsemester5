-- Alternatif tanpa artisan: jalankan di phpMyAdmin (database yang dipakai project)
-- jika tidak memakai "php artisan migrate".
CREATE TABLE IF NOT EXISTS `informed_consent` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kunjungan_id` BIGINT UNSIGNED NOT NULL,
    `poli` VARCHAR(50) NULL,
    `data` LONGTEXT NOT NULL,
    `lengkap` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `informed_consent_kunjungan_id_unique` (`kunjungan_id`),
    CONSTRAINT `informed_consent_kunjungan_id_foreign`
        FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
