<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production', false);
    }

    /**
     * Get the base URL for the Snap API.
     */
    protected function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Request a Snap Token from Midtrans.
     *
     * @param array $payload
     * @return string
     * @throws \Exception
     */
    public function getSnapToken(array $payload): string
    {
        $url = $this->getSnapUrl();
        $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $authHeader,
            ])->post($url, $payload);

            if ($response->failed()) {
                Log::error('Midtrans API Request Failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Midtrans API returned error: ' . ($response->json('error_messages')[0] ?? $response->body()));
            }

            $token = $response->json('token');

            if (!$token) {
                throw new \Exception('Snap token not found in Midtrans response.');
            }

            return $token;
        } catch (\Exception $e) {
            Log::error('Exception in Midtrans getSnapToken', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);
            throw $e;
        }
    }

    /**
     * Verify the signature key of a webhook notification.
     *
     * @param array $data Webhook POST body data
     * @return bool True if valid, false otherwise
     */
    public function verifySignature(array $data): bool
    {
        $orderId = $data['order_id'] ?? null;
        $statusCode = $data['status_code'] ?? null;
        $grossAmount = $data['gross_amount'] ?? null;
        $signatureKey = $data['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            Log::warning('Midtrans verification skipped due to missing parameters', $data);
            return false;
        }

        // Format gross amount to match the decimal places or representation if needed, 
        // but Midtrans sends it back exactly as it was or formatted as decimal. Let's make sure it matches.
        // Usually Midtrans returns it exactly as a string, e.g. "5000000.00". Let's use the raw value first.
        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $isValid = ($computed === $signatureKey);

        if (!$isValid) {
            Log::warning('Midtrans webhook signature verification failed!', [
                'received' => $signatureKey,
                'computed' => $computed,
                'payload' => $data,
            ]);
        }

        return $isValid;
    }

    /**
     * Get the transaction status from Midtrans API.
     *
     * @param string $orderId
     * @return array
     */
    public function getTransactionStatus(string $orderId): array
    {
        $baseUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
            
        $url = $baseUrl . '/' . $orderId . '/status';
        $authHeader = 'Basic ' . base64_encode($this->serverKey . ':');

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => $authHeader,
            ])->get($url);

            if ($response->failed()) {
                Log::error('Midtrans Status Request Failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return [];
            }

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Exception in Midtrans getTransactionStatus', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            return [];
        }
    }
}
