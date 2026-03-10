<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private string $baseUrl;
    private string $shortcode;
    private string $passkey;
    private string $consumerKey;
    private string $consumerSecret;
    private string $callbackUrl;

    public function __construct()
    {
        $this->baseUrl        = config('mpesa.base_url');
        $this->shortcode      = config('mpesa.shortcode');
        $this->passkey        = config('mpesa.passkey');
        $this->consumerKey    = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->callbackUrl    = config('mpesa.callback_url');
    }

    /**
     * Get OAuth access token, cached for 55 minutes.
     */
    public function getAccessToken(): string
    {
        return Cache::remember('mpesa_access_token', 55 * 60, function () {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate", ['grant_type' => 'client_credentials']);

            if ($response->failed()) {
                Log::error('M-Pesa token request failed', ['body' => $response->body()]);
                throw new \RuntimeException('Could not obtain M-Pesa access token.');
            }

            return $response->json('access_token');
        });
    }

    /**
     * Initiate an STK Push (Lipa Na M-Pesa Online).
     *
     * @return array Daraja response (contains CheckoutRequestID on success)
     * @throws \RuntimeException on HTTP failure
     */
    public function stkPush(string $phone, int $amount, string $accountRef, string $description): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = $this->generatePassword($timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $phone,
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => $accountRef,
            'TransactionDesc'   => $description,
        ];

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", $payload);

        $data = $response->json();

        Log::info('M-Pesa STK push', ['phone' => $phone, 'amount' => $amount, 'response' => $data]);

        if ($response->failed() || isset($data['errorCode'])) {
            $msg = $data['errorMessage'] ?? $response->body();
            throw new \RuntimeException('M-Pesa STK push failed: ' . $msg);
        }

        return $data;
    }

    /**
     * Query STK push status from Daraja using CheckoutRequestID.
     */
    public function stkQuery(string $checkoutRequestId): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = $this->generatePassword($timestamp);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", $payload);

        $data = $response->json();

        Log::info('M-Pesa STK query', [
            'checkout_request_id' => $checkoutRequestId,
            'response' => $data,
        ]);

        if ($response->failed() || isset($data['errorCode'])) {
            $msg = $data['errorMessage'] ?? $response->body();
            throw new \RuntimeException('M-Pesa STK query failed: ' . $msg);
        }

        return $data;
    }

    /**
     * Format Kenyan phone number to 254XXXXXXXXX format.
     */
    public function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // strip non-digits

        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        } elseif (str_starts_with($phone, '+254')) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Generate STK push password: base64(shortcode + passkey + timestamp).
     */
    public function generatePassword(string $timestamp): string
    {
        return base64_encode($this->shortcode . $this->passkey . $timestamp);
    }
}
