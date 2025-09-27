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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'sendpulse' => [
        'client_id' => env('SENDPULSE_CLIENT_ID'),
        'client_secret' => env('SENDPULSE_CLIENT_SECRET'),
        'token_path' => env('SENDPULSE_TOKEN_PATH', storage_path('sendpulse')),
    ],

    'recommendation' => [
        'endpoint' => env('RECOMMENDATION_ENDPOINT'),
        'api_key' => env('RECOMMENDATION_API_KEY'),
        'cache_ttl_minutes' => 30, // кэш рекомендаций
    ],

    'api_token' => env('API_TOKEN'), // простой API-токен для мобильного приложения

];
