<?php

return [
    'merchant_id' => env('MOBIKWIK_MERCHANT_ID'),
    'secret_key' => env('MOBIKWIK_SECRET_KEY'),
    'base_url' => env('MOBIKWIK_BASE_URL', 'https://alpha3.mobikwik.com'),
    'redirect_url' => env('MOBIKWIK_REDIRECT_URL', env('APP_URL') . '/api/wallet/callback'),
    'cancel_url' => env('MOBIKWIK_CANCEL_URL', env('APP_URL') . '/api/wallet/cancel'),
    'webhook_url' => env('MOBIKWIK_WEBHOOK_URL', env('APP_URL') . '/webhook/mobikwik'),
    
    // Environment settings
    'environment' => env('MOBIKWIK_ENVIRONMENT', 'test'), // test or production
    
    // API endpoints
    'endpoints' => [
        'create_wallet' => '/wallet/create',
        'add_money' => '/wallet/addmoney',
        'transfer' => '/wallet/transfer',
        'balance' => '/wallet/balance',
        'transaction_status' => '/wallet/status',
    ],
    
    // Default limits
    'limits' => [
        'min_add_money' => 10,
        'max_add_money' => 50000,
        'min_transfer' => 1,
        'max_transfer' => 25000,
        'daily_limit' => 100000,
        'monthly_limit' => 500000,
    ],
    
    // Timeout settings
    'timeout' => 30,
    'connect_timeout' => 10,
];
