<?php

/**
 * Daftar dokter per poli (sumber: jadwal praktik Merah Putih Klinik).
 * Kunci = slug poli (sama dengan yang dipakai di URL pemeriksaan:
 * "Poli Umum" -> umum, "Mikrobiologi" -> mikrobiologi, dst).
 * Dokter pertama di tiap poli dipakai sebagai dokter bawaan poli tersebut.
 *
 * Untuk menambah/mengubah dokter, cukup edit daftar ini lalu jalankan:
 *     php artisan config:clear
 */
return [
    'daftar' => [
        'umum'         => ['dr. Denisa Khoirunnisa'],
        'gigi'         => ['drg. Aisya Nurrachma'],
        'dalam'        => ['dr. Hendrik Wahyudi, Sp.PD'],
        'jantung'      => ['dr. Bagus Fitriadi, Sp.JP'],
        'saraf'        => ['dr. Ratna Wijayanti, Sp.S'],
        'kandungan'    => ['dr. Aditya Iqbal Maulana, Sp.OG'],
        'radiologi'    => ['dr. Ichwanul Aminin, Sp.Rad'],
        'mikrobiologi' => ['dr. Muhammad Amin, Sp.MK, M.Ked.Klin'],
        'jiwa'         => ['dr. Devi Anggraini P., Sp.KJ'],
        'fisioterapi'  => ['Veti Novitasari, Amd.Ft'],
    ],

    // Jabatan dokter/petugas per poli (mengisi kolom Jabatan otomatis di tab Autentikasi).
    'jabatan' => [
        'umum'         => 'Dokter Umum',
        'gigi'         => 'Dokter Gigi',
        'dalam'        => 'Dokter Spesialis Penyakit Dalam',
        'jantung'      => 'Dokter Spesialis Jantung dan Pembuluh Darah',
        'saraf'        => 'Dokter Spesialis Saraf',
        'kandungan'    => 'Dokter Spesialis Kebidanan dan Kandungan',
        'radiologi'    => 'Dokter Spesialis Radiologi',
        'mikrobiologi' => 'Dokter Spesialis Mikrobiologi Klinik',
        'jiwa'         => 'Dokter Spesialis Kedokteran Jiwa',
        'fisioterapi'  => 'Fisioterapis',
    ],
];
