-- Akun PERAWAT (untuk database yang sudah berjalan, tanpa menjalankan seeder).
--   Username : perawat
--   Password : perawat123   (disimpan sebagai hash bcrypt, sama seperti hasil Laravel)
-- Aman dijalankan berkali-kali: kalau username sudah ada, datanya diperbarui.
-- Alternatif lewat artisan:  php artisan db:seed --class=UserSeeder

INSERT INTO users (nama_lengkap, username, password, role, status, created_at, updated_at)
VALUES ('Perawat', 'perawat', '$2y$10$7eGA63PXFIoHLVun9a2oPu19/ZU8.Rb/1jrK9zX28dZfW2ppF3tZi', 'perawat', 'aktif', NOW(), NOW())
ON DUPLICATE KEY UPDATE
    nama_lengkap = VALUES(nama_lengkap),
    password     = VALUES(password),
    role         = 'perawat',
    status       = 'aktif',
    updated_at   = NOW();
