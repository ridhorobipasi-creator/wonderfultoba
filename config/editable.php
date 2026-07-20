<?php

/*
|--------------------------------------------------------------------------
| Web-editable application config
|--------------------------------------------------------------------------
|
| Values an admin may change from the panel without FTP or SSH. They are
| stored in the `settings` table under the `app_config` key and applied over
| config() at boot — the .env file is never written to, so the web process
| does not need write access to it.
|
| ONLY the keys listed in 'fields' below can be read or written. Anything not
| listed is invisible to the editor, including any key a crafted form post
| tries to introduce. Adding a field here is a deliberate act; review the
| DENIED list before you do.
|
*/

return [

    /*
    | Never editable, never displayed, under any circumstance.
    |
    | These are credentials, not settings. Exposing them on a web page turns
    | a single compromised admin session into full database, mail and storage
    | access — which means every customer's name, email, phone and booking
    | history. They belong in .env, changed on the server, and rotated there.
    |
    | This list is enforced in AppConfigService, not merely documented here.
    */
    'denied' => [
        'APP_KEY',
        'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
        'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'AWS_BUCKET', 'AWS_DEFAULT_REGION',
        'REDIS_HOST', 'REDIS_PASSWORD', 'REDIS_PORT', 'REDIS_CLIENT',
        'MAIL_PASSWORD', 'MAIL_USERNAME',
        'SESSION_ENCRYPT', 'SESSION_DOMAIN',
        'BCRYPT_ROUNDS',
    ],

    /*
    | Editable fields.
    |
    | key    — identifier used in the form and in storage
    | config — dotted config() path this value overrides at boot
    | label  — shown in the panel
    | help   — one line explaining the consequence of changing it
    | type   — text | url | email | number | boolean | select
    | rules  — Laravel validation rules
    */
    'fields' => [

        'app_name' => [
            'config' => 'app.name',
            'label' => 'Nama Aplikasi',
            'help' => 'Muncul di judul tab, email, dan invoice.',
            'type' => 'text',
            'rules' => 'required|string|max:60',
            'group' => 'Umum',
        ],

        'app_url' => [
            'config' => 'app.url',
            'label' => 'URL Situs',
            'help' => 'Dipakai untuk membentuk tautan di email dan invoice. Salah isi = tautan rusak.',
            'type' => 'url',
            'rules' => 'required|url|max:255',
            'group' => 'Umum',
        ],

        'app_debug' => [
            'config' => 'app.debug',
            'label' => 'Mode Debug',
            'help' => 'WAJIB mati di situs publik. Bila hidup, pesan error menampilkan isi konfigurasi ke pengunjung.',
            'type' => 'boolean',
            'rules' => 'boolean',
            'group' => 'Umum',
        ],

        'log_level' => [
            'config' => 'logging.channels.stack.level',
            'label' => 'Level Log',
            'help' => 'debug mencatat paling banyak. Di produksi pakai error agar log tidak membengkak.',
            'type' => 'select',
            'options' => ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'],
            'rules' => 'required|in:debug,info,notice,warning,error,critical,alert,emergency',
            'group' => 'Umum',
        ],

        'mail_from_address' => [
            'config' => 'mail.from.address',
            'label' => 'Email Pengirim',
            'help' => 'Alamat yang tampil sebagai pengirim invoice dan notifikasi.',
            'type' => 'email',
            'rules' => 'required|email|max:255',
            'group' => 'Email',
        ],

        'mail_from_name' => [
            'config' => 'mail.from.name',
            'label' => 'Nama Pengirim',
            'help' => 'Nama yang tampil di kotak masuk pelanggan.',
            'type' => 'text',
            'rules' => 'required|string|max:60',
            'group' => 'Email',
        ],

        'session_lifetime' => [
            'config' => 'session.lifetime',
            'label' => 'Durasi Sesi (menit)',
            'help' => 'Berapa lama admin tetap login tanpa aktivitas.',
            'type' => 'number',
            'rules' => 'required|integer|min:5|max:10080',
            'group' => 'Keamanan',
        ],
    ],
];
