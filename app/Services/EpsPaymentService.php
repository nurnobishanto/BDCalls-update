<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EpsPaymentService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected string $merchantId;
    protected string $storeId;
    protected string $hashKey;

    public function __construct()
    {
        $this->baseUrl    = config('eps.base_url');
        $this->username   = config('eps.username');
        $this->password   = config('eps.password');
        $this->merchantId = config('eps.merchant_id');
        $this->storeId    = config('eps.store_id');
        $this->hashKey    = config('eps.hash_key');
    }

    /**
     * Generate x-hash using HMACSHA512 and Base64
     */
    protected function generateHash(string $value): string
    {
        $utf8Value = utf8_encode($value);
        $hmac = hash_hmac('sha512', $utf8Value, $this->hashKey, true);
        return base64_encode($hmac);
    }

    /**
     * Get EPS Token
     */
    public function getToken(): array
    {
        $hash = $this->generateHash($this->username);

        $response = Http::withHeaders([
            'x-hash' => $hash,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/v1/Auth/GetToken', [
            'userName' => $this->username,
            'password' => $this->password,
        ]);

        return $response->json();
    }

    /**
     * Initialize Payment
     */
    public function initializePayment(array $data, string $token): array
    {
        $hash = $this->generateHash($data['merchantTransactionId']);

        $response = Http::withHeaders([
            'x-hash' => $hash,
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/v1/EPSEngine/InitializeEPS', $data);

        return $response->json();
    }

    /**
     * Verify Transaction
     */
    public function verifyTransaction(string $merchantTransactionId, string $token): array
    {
        $hash = $this->generateHash($merchantTransactionId);

        $response = Http::withHeaders([
            'x-hash' => $hash,
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/v1/EPSEngine/CheckMerchantTransactionStatus', [
            'merchantTransactionId' => $merchantTransactionId
        ]);

        return $response->json();
    }
}
