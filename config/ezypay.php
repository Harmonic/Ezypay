<?php

// There are 3 URLs used by ezypay (api, vault and token)
// This code calculates each
$url = env('EZY_PAY_API_URL');
$tokenURL = 'https://identity.ezypay.com/token';
$vaultURL = 'https://vault.ezypay.com';

if (env('APP_ENV') !== 'production') {
    $url = str_replace('api-global.', 'api-sandbox.', $url);
    $tokenURL = str_replace('identity.', 'identity-sandbox.', $tokenURL);
    $vaultURL = str_replace('vault.', 'vault-sandbox.', $vaultURL);
}

// Append /v2/ on vault and API URL
$url = $url.'/v2/';
$vaultURL = $vaultURL.'/v2/';

return [
    /*
     * Handles the Ezypay API related configuration
     */

    'user' => env('EZY_PAY_USER'),
    'password' => env('EZY_PAY_PASSWORD'),
    'client_id' => env('EZY_PAY_API_CLIENT_ID'),
    'client_secret' => env('EZY_PAY_CLIENT_SECRET'),
    'merchant_id' => env('EZY_PAY_MERCHANT_ID'),
    'url' => $url,
    'token_url' => $tokenURL,
    'vault_url' => $vaultURL,
    'failed_payment_fee' => 9.79,
    'trial_days' => 14, // Number of days that are free after signing up
];
