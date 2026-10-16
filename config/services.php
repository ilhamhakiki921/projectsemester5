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

    /*
    | WHO ICD-API (ICD-10). Daftar & ambil Client ID/Secret di https://icd.who.int/icdapi
    */
    'who_icd' => [
        'client_id'     => env('ICD_CLIENT_ID'),
        'client_secret' => env('ICD_CLIENT_SECRET'),
        'token_url'     => env('ICD_TOKEN_URL', 'https://icdaccessmanagement.who.int/connect/token'),
        'base_url'      => env('ICD_BASE_URL', 'https://id.who.int'),
        'release'       => env('ICD_RELEASE', '2019'),
        'language'      => env('ICD_LANGUAGE', 'en'),
    ],

    'satusehat' => [
    'env'             => env('SATUSEHAT_ENV', 'sandbox'),
    'auth_url'        => env('SATUSEHAT_AUTH_URL'),
    'base_url'        => env('SATUSEHAT_BASE_URL'),
    'client_id'       => env('SATUSEHAT_CLIENT_ID'),
    'client_secret'   => env('SATUSEHAT_CLIENT_SECRET'),
    'organization_id' => env('SATUSEHAT_ORGANIZATION_ID'),
],

];
