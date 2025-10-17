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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY', 'A1PlifrWYCAjPm6jxhhHI6Ht5agRx5YJwLYMrkKe0MGQnLmhe31oJQQJ99BDACfhMk5XJ3w3AAAAACOGe69e'),
        'endpoint' => env('OPENAI_ENDPOINT', 'https://rania-m920fpt8-swedencentral.cognitiveservices.azure.com/openai/deployments/gpt-4/chat/completions'),
        'api_version' => env('OPENAI_API_VERSION', '2025-01-01-preview'),
        'dalle_endpoint' => env('DALLE_ENDPOINT', 'https://rania-m920fpt8-swedencentral.cognitiveservices.azure.com/openai/deployments/dall-e-3/images/generations'),
        'dalle_api_version' => env('DALLE_API_VERSION', '2024-02-01'),
    ],

];
