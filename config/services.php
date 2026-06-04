<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER', ''),
    ],

    // Google Places — used to pull the real business rating into trust badges.
    // The API key can also be set in Admin → Settings (general.google_maps_api_key),
    // which takes precedence over this env value.
    'google_places' => [
        'key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    // Android TWA (.apk) packaging — Digital Asset Links for the admin PWA.
    // Fill ANDROID_APP_FINGERPRINT with the SHA256 signing fingerprint(s) that
    // PWABuilder/Play Store gives you (comma-separated for multiple).
    'pwa_android' => [
        'package'     => env('ANDROID_APP_PACKAGE', 'com.sujailaketoba.admin'),
        'fingerprint' => env('ANDROID_APP_FINGERPRINT', ''),
    ],

];
