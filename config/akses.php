<?php

/*
|--------------------------------------------------------------------------
| Hak akses per role
|--------------------------------------------------------------------------
| Saat ini hanya role PERAWAT yang dibatasi. Role lain (admin, dokter, dst.)
| tidak berubah sama sekali.
|
| Untuk mengubah hak akses perawat cukup edit file ini:
|   - 'tulis_boleh' : nama route SIMPAN/UBAH/HAPUS (POST/PUT/PATCH/DELETE)
|                     yang boleh dipakai perawat. Route tulis lain otomatis
|                     ditolak. Halaman (GET) tetap boleh dibuka semuanya.
|   - 'tutup'       : halaman yang ditutup total (menu Data Master & Audit Trail).
|   - 'ui'          : aturan tampilan "hanya lihat" per halaman. Tampilannya
|                     tidak diubah; kolom isian dikunci (tidak bisa diketik)
|                     dan tombol simpan/tambah/edit/hapus tidak bisa dipakai.
|                     'bidang' = isian yang dikunci, 'blok' = tombol/elemen yang
|                     diblokir kliknya, 'form' = form yang submit-nya diblokir,
|                     'pointer' = elemen yang tidak menerima mouse/jari.
*/

return [

    'perawat' => [

        'tulis_boleh' => [
            // Pemeriksaan: semua boleh diinput kecuali diagnosa, resep, penunjang, surat medis
            'pemeriksaan.asesmen.store',
            'pemeriksaan.soap.store',
            'pemeriksaan.informed-consent.simpan',
            'pemeriksaan.informed-consent.status',
            'clinical-pathway.store',
            'rujukan.store',
            'rujukan.destroy',
            'autentikasi.store',

            // Rekam Medis: hanya input pemeriksaan (tanda vital) & SOAP
            'rekam-medis.soap.update',
        ],

        'tutup' => [
            'user.*',
            'poli.*',
            'diagnosa.*',        // Data Diagnosa/Penyakit (bukan tab Diagnosa di Pemeriksaan)
            'tindakan.*',
            'obat.*',
            'data-pasien.*',
            'audit-log.*',
        ],

        'ui' => [

            // ---- Pendaftaran: hanya lihat (kolom cari & tombol detail tetap jalan) ----
            'pendaftaran' => [
                'blok' => '.btn-new, .btn-kunjungan, .btn-edit, .btn-telusuri',
            ],
            'pasien.*' => [
                'bidang' => 'form[method="POST" i] input:not([type="hidden"]), form[method="POST" i] textarea, form[method="POST" i] select',
                'form'   => 'form[method="POST" i]',
                'blok'   => 'form[method="POST" i] button[type="submit"]',
            ],

            // ---- Pemeriksaan: tab yang hanya boleh dilihat ----
            'pemeriksaan.diagnosa' => [
                'bidang' => '#form-diagnosa input:not([type="hidden"]), #form-diagnosa textarea, #form-diagnosa select',
                'form'   => '#form-diagnosa',
                'blok'   => '#form-diagnosa button, #form-diagnosa [data-add], #form-diagnosa [data-remove]',
            ],
            'pemeriksaan.resep' => [
                'bidang' => '#form-resep input:not([type="hidden"]), #form-resep textarea, #form-resep select',
                'form'   => '#form-resep',
                'blok'   => '.btn-tambah, #form-resep button',
            ],
            'penunjang.index' => [
                'bidang' => '#pjForm input:not([type="hidden"]), #pjForm textarea, #pjForm select',
                'form'   => '#pjForm',
                'blok'   => '#pjAdd, #pjBtn, #pjBatal, .pj-ab.edit, .pj-ab.hapus',
            ],
            'surat-medis.index' => [
                // pilihan Sakit/Sehat tetap bisa diganti untuk melihat surat yang sudah ada
                'bidang'  => '#smForm input:not([type="hidden"]):not([name="jenis"]), #smForm textarea, #smForm select',
                'form'    => '#smForm',
                // "Cetak" ikut diblokir karena di sistem ini Cetak menyimpan surat dulu
                'blok'    => '#smSimpan, #smCetak, #smHapus',
                'pointer' => '#smTtdBox',
            ],

            // ---- Rekam Medis: koding & verifikasi hanya lihat (edit SOAP tetap boleh) ----
            'rekam-medis.berkas' => [
                'blok' => '.btn-koding',
            ],
        ],
    ],

];
