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
];
