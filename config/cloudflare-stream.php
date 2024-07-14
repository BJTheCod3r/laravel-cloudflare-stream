<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    | This is the cloudflare API token for authentication of API requests
    |
    */
    'api_token' => env('CLOUDFLARE_API_TOKEN', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Account ID
    |--------------------------------------------------------------------------
    | This is the cloudflare Account ID
    |
    */
    'account_id' => env('CLOUDFLARE_ACCOUNT_ID', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    | This is the webhook secret
    |
    */
    'webhook_secret' => env('CLOUDFLARE_WEBHOOK_SECRET', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Key ID
    |--------------------------------------------------------------------------
    | This is the jwk Key ID
    |
    */
    'key_id' => env('CLOUDFLARE_KEY_ID', 'null'),


    /*
    |--------------------------------------------------------------------------
    | jwk Key
    |--------------------------------------------------------------------------
    | This is the jwk Key
    |
    */
    'jwk_key' => env('CLOUDFLARE_JWK_KEY', 'null'),

    /*
    |--------------------------------------------------------------------------
    | pem
    |--------------------------------------------------------------------------
    | This is the pem string generated
    |
    */
    'pem' => env('CLOUDFLARE_PEM', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    | This is the cloudflare API Base URL
    |
    */
    'base_url' => env('CLOUDFLARE_API_BASE_URL', 'https://api.cloudflare.com/client/v4/accounts'),

    /*
    |--------------------------------------------------------------------------
    | Customer Domain
    |--------------------------------------------------------------------------
    | The domain that handles all request
    |
    */
    'customer_domain' => env('CLOUDFLARE_CUSTOMER_DOMAIN', 'https://customer-<customer_id>.cloudflarestream.com'),

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    | This is the default options that will be applied to all upload
    | The `thumbnailTimestampPct` is a floating point value between 0.0 and 1.0.
    | All the commented defaults here don't seem to work for uploading via link
    |
    */
    'default_options' => [
        'requireSignedURLs' => true,
        //'allowedOrigins' => [],
        //'thumbnailTimestampPct' => 0.0
    ]
];
