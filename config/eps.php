<?php
return [
    'base_url'    => env('EPS_MODE') === 'production'
        ? env('EPS_BASE_URL_PRODUCTION')
        : env('EPS_BASE_URL_SANDBOX'),
    'username'    => env('EPS_USERNAME'),
    'password'    => env('EPS_PASSWORD'),
    'merchant_id' => env('EPS_MERCHANT_ID'),
    'store_id'    => env('EPS_STORE_ID'),
    'hash_key'    => env('EPS_HASH_KEY'),
];
