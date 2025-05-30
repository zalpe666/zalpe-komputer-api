<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'rajaongkir/*'], // Menambahkan endpoint rajaongkir ke daftar path

    'allowed_methods' => ['*'],  // Atau spesifikkan metode seperti ['GET', 'POST', 'PUT', 'DELETE']

    'allowed_origins' => [
        'http://localhost:5173', // Ganti dengan domain frontend Anda
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Bisa lebih spesifik jika diperlukan

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,  // Jika menggunakan cookies atau token, ubah ke true

];
