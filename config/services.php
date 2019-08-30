<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
     */

    'mailgun'   => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses'       => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe'    => [
        'model'   => App\User::class,
        'key'     => env('STRIPE_KEY'),
        'secret'  => env('STRIPE_SECRET'),
        'webhook' => [
            'secret'    => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    'fcm'       => [
        'key' => env('FCM_SERVER_KEY', ''),
    ],
    'facebook' => [
        'client_id' => '754506008316559', //Facebook API
        'client_secret' => '937c341fc625c6ee5a5fededda735a97', //Facebook Secret
        'redirect' => env('APP_URL').'/login/facebook/callback',
    ],
    'instagram' => [
        'client_id' => 'b81e845e4e9b412abf032556738cfd8e',
        'client_secret' => '6258ab1f30534c8ba2aa1559bd212591',
        'redirect' => env('APP_URL').'/login/instagram/callback',
    ],
    'snapchat' => [
        'client_id' => 'e9e5ac05-03eb-4265-b944-b5d1b25e2eed',
        'client_secret' => '9MC0kr9NP-Rz9iTXbKiXWbeNuj2Dl0SGl25NE1_2UNE',
        'redirect' => env('APP_URL').'/login/snapchat/callback',
    ],
    'twitter' => [
        'client_id' => 'YH8oY75JhFGso0iW2D4C07dXS',
        'client_secret' => 'PIOdmNVbZMsUwnWhSDbmJtDDOdWxW0DWf72jpV00LQDZfxtoDJ',
        'redirect' => 'http://127.0.0.1:8000/api/login/twitter/callback',
    ],
    

];
