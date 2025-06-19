<?php

return [
    'merchant_id' => env('MOBIKWIK_MERCHANT_ID'),
    'secret_key' => env('MOBIKWIK_SECRET_KEY'),
    'base_url' => env('MOBIKWIK_ENVIRONMENT', 'sandbox') === 'production' 
        ? env('MOBIKWIK_BASE_URL') 
        : env('MOBIKWIK_SANDBOX_URL'),
    'environment' => env('MOBIKWIK_ENVIRONMENT', 'sandbox'),
    'redirect_url' => env('APP_URL') . '/mobikwik/callback',
    'cancel_url' => env('APP_URL') . '/mobikwik/cancel',
];