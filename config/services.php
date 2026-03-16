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

    'nvidia_ai' => [
        'key' => env('NVIDIA_AI_API_KEY'),
        'url' => env('NVIDIA_AI_URL', 'https://integrate.api.nvidia.com/v1'),
        'model' => env('NVIDIA_AI_MODEL', 'qwen/qwen3.5-397b-a17b'),
        'timeout' => (int) env('NVIDIA_AI_TIMEOUT', 90),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
        'allowed_chat_ids' => array_values(array_filter(array_map('trim', explode(',', (string) env('TELEGRAM_ALLOWED_CHAT_IDS', ''))))),
        'news_author_email' => env('TELEGRAM_NEWS_AUTHOR_EMAIL'),
        'news_default_category_slug' => env('TELEGRAM_NEWS_DEFAULT_CATEGORY_SLUG'),
        'news_auto_publish' => filter_var(env('TELEGRAM_NEWS_AUTO_PUBLISH', true), FILTER_VALIDATE_BOOLEAN),
    ],

];
