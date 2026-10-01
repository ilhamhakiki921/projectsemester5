-- Jalankan di phpMyAdmin (database project_semester_5b -> tab SQL). Aman dijalankan berulang.
CREATE TABLE IF NOT EXISTS `autentikasi_kunjungan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kunjungan_id` bigint unsigned NOT NULL,
  `nama_dokter` varchar(150) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `tanda_tangan` longtext NOT NULL,
  `waktu_autentikasi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `autentikasi_kunjungan_kunjungan_id_unique` (`kunjungan_id`),
  CONSTRAINT `autentikasi_kunjungan_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
