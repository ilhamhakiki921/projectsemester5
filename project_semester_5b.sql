-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Sep 2026 pada 05.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_semester_5b`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `assesment`
--

CREATE TABLE `assesment` (
  `keluhan_utama` text NOT NULL,
  `riwayat_penyakit_sekarang` text NOT NULL,
  `riwayat_penyakit_dahulu` text NOT NULL,
  `riwayat_alergi` text NOT NULL,
  `riwayat_pengobatan` text NOT NULL,
  `tekanan_darah` int(10) NOT NULL,
  `nadi` int(10) NOT NULL,
  `suhu` int(10) NOT NULL,
  `respirasi` int(11) NOT NULL,
  `berat_badan` varchar(11) NOT NULL,
  `tinggi_badan` varchar(11) NOT NULL,
  `pemeriksaan_fisik` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `diagnosa`
--

CREATE TABLE `diagnosa` (
  `diagnosa_utama` text NOT NULL,
  `diagnosa_sekunder` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien_baru`
--

CREATE TABLE `pasien_baru` (
  `No_RM` int(6) NOT NULL,
  `Nama_Lengkap` text NOT NULL,
  `NIK` int(16) NOT NULL,
  `Alamat` text NOT NULL,
  `Jenis_Kelamin` varchar(6) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `Nomor_Telepon` int(14) NOT NULL,
  `Tanggal_Kunjungan` date NOT NULL,
  `Poli_Tujuan` varchar(25) NOT NULL,
  `Nama_Dokter` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penunjang`
--

CREATE TABLE `penunjang` (
  `NO_RM` int(6) NOT NULL,
  `Nama_Pasien` text NOT NULL,
  `POLI` varchar(5) NOT NULL,
  `Dokter_Pengirim` text NOT NULL,
  `Jenis_pemeriksaan` varchar(50) NOT NULL,
  `Waktu_pemeriksaan` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rujukan_pasien`
--

CREATE TABLE `rujukan_pasien` (
  `id_rujukan` int(11) NOT NULL,
  `no_surat` varchar(50) DEFAULT NULL,
  `tanggal_rujukan` date NOT NULL,
  `poli_tujuan` varchar(100) NOT NULL,
  `rumah_sakit_tujuan` varchar(150) NOT NULL,
  `nama_pasien` text NOT NULL,
  `umur` int(3) NOT NULL,
  `jenis_kelamin` varchar(5) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `anamnesa` text NOT NULL,
  `diagnosa` text NOT NULL,
  `terapi_diberikan` text NOT NULL,
  `dokter_pengirim` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `soap`
--

CREATE TABLE `soap` (
  `subjective` varchar(100) NOT NULL,
  `objective` varchar(100) NOT NULL,
  `assesment` varchar(100) NOT NULL,
  `plan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('petugas_pendaftaran','dokter','perawat','penunjang','farmasi','rm','admin','kepala_klinik') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Petugas Pendaftaran', 'pendaftaran', '$2y$12$9nzvUdlwVv.7ubxK6hXBK.OaGPfVebynGfj7tiHePyUMYDt63tmsW', 'petugas_pendaftaran', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(2, 'Dokter Klinik', 'dokter', '$2y$12$j8YJ1omnbva2BdHwDNwhVuq.apbwvrgvDoCoDwbhe5c4Fe7JyNbmq', 'dokter', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(3, 'Perawat Klinik', 'perawat', '$2y$12$/GmCbkBH/jPB6frDmLdvfucJ4UU814.BcVYHSCN5k7tRgEc5pUODK', 'perawat', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(4, 'Petugas Penunjang', 'penunjang', '$2y$12$Qv8eLqF.DO5CX8rkZOor..zeNdvBuj7scPlVqGKkgH2IUog39wz1W', 'penunjang', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(5, 'Petugas Farmasi', 'farmasi', '$2y$12$GvbdzhVubB4J1AljZM62AObJGWLc3o6MNvb4udLF6BsUTAYxZOSL6', 'farmasi', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(6, 'Petugas Rekam Medis', 'rm', '$2y$12$tApqHwGnMXYYuWe.JiddRunQeTYPFAKOnwXUbajaWks/9L5XY0NsC', 'rm', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(7, 'Administrator', 'admin', '$2y$12$2wkMQ..gJNpiXUwXAyhtK.9j2IhSLVn960US1Vf26vyILsfqdxhUW', 'admin', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10'),
(8, 'Kepala Klinik', 'kepala', '$2y$12$o5pDvvqzOPwKEu13cbzt0OXb9jM8dC/UtDYvsmTr8bgvwZ6MwwvjK', 'kepala_klinik', 'aktif', '2026-09-16 11:54:10', '2026-09-16 11:54:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pasien_baru`
--
ALTER TABLE `pasien_baru`
  ADD PRIMARY KEY (`No_RM`);

--
-- Indeks untuk tabel `penunjang`
--
ALTER TABLE `penunjang`
  ADD PRIMARY KEY (`NO_RM`);

--
-- Indeks untuk tabel `rujukan_pasien`
--
ALTER TABLE `rujukan_pasien`
  ADD PRIMARY KEY (`id_rujukan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pasien_baru`
--
ALTER TABLE `pasien_baru`
  MODIFY `No_RM` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penunjang`
--
ALTER TABLE `penunjang`
  MODIFY `NO_RM` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rujukan_pasien`
--
ALTER TABLE `rujukan_pasien`
  MODIFY `id_rujukan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
