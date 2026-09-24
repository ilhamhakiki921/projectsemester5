<?php

// Kunci yang tidak ada di sini otomatis memakai bahasa Inggris (fallback_locale).
return [
    'required'  => ':attribute wajib diisi.',
    'email'     => ':attribute harus berupa alamat email yang valid.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'min'       => [
        'string' => ':attribute minimal :min karakter.',
    ],
    'string'    => ':attribute harus berupa teks.',

    'attributes' => [
        'username'              => 'Username',
        'password'              => 'Password',
        'password_confirmation' => 'Konfirmasi password',
        'email'                 => 'Email',
    ],
];
