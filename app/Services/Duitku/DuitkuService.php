<?php

namespace App\Services\Duitku;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected $merchantCode;
    protected $apiKey;
    protected $isSandbox;
    protected $expiryPeriod;

    public function __construct()
    {
        // Ambil konfigurasi dari Database (Setting Model) dengan fallback ke config/services.php
        $this->merchantCode = Setting::get('duitku_merchant_code');
        $this->apiKey       = Setting::get('duitku_api_key');

        $sandboxSetting     = Setting::get('duitku_is_sandbox');
        $this->isSandbox    = filter_var(Setting::get('duitku_is_sandbox', true), FILTER_VALIDATE_BOOLEAN);
        $this->expiryPeriod = (int) Setting::get('duitku_expiry_period', 60);
    }

    public function getPaymentMethods($amount = 10000)
    {
        $datetime = date('Y-m-d H:i:s');
        $signature = hash('sha256', $this->merchantCode . (int)$amount . $datetime . $this->apiKey);
        $endpoint  = $this->isSandbox
            ? 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod'
            : 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod';

        $payload = [
            'merchantcode' => $this->merchantCode,
            'amount' => (int) $amount,
            'datetime' => $datetime,
            'signature' => $signature
        ];

        try {
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($endpoint, $payload);
            if ($response->successful() && isset($response->json()['paymentFee'])) {
                return [
                    'status' => true,
                    'methods' => $response->json()['paymentFee']
                ];
            }

            Log::error('Duitku Get Payment Method Failed', ['response' => $response->json()]);
            return ['status' => false, 'methods' => []];
        } catch (\Throwable $th) {
            Log::error('Duitku Get Payment Method Exception: ' . $e->getMessage());
            return ['status' => false, 'methods' => []];
        }
    }

    private function getInquiryEndpoint()
    {
        return $this->isSandbox
            ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'
            : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';
    }

    private function getCheckStatusEndpoint()
    {
        return $this->isSandbox
            ? 'https://sandbox.duitku.com/webapi/api/merchant/transactionStatus'
            : 'https://passport.duitku.com/webapi/api/merchant/transactionStatus';
    }

    /**
     * Membuat Invoice Pembayaran Baru ke Duitku
     */
    public function createInvoice($merchantOrderId, $amount, $paymentMethod, $email, $customerDetailName = 'User')
    {
        // Signature Inquiry: MD5(merchantCode + merchantOrderId + amount + apiKey)
        $signature = md5($this->merchantCode . $merchantOrderId . (int)$amount . $this->apiKey);

        $payload = [
            'merchantCode'    => $this->merchantCode,
            'paymentAmount'   => (int) $amount,
            'paymentMethod'   => $paymentMethod, // e.g., 'NQ' (QRIS), 'BC' (BCA VA)
            'merchantOrderId' => $merchantOrderId,
            'productDetails'  => 'Topup Kisa Bean',
            'email'           => $email,
            'customerVaName'  => $customerDetailName,
            'callbackUrl'     => route('duitku.callback'),
            'returnUrl'       => route('topup.return'),
            'signature'       => $signature,
            'expiryPeriod'    => $this->expiryPeriod, // Menggunakan setting dinamis dari Admin
        ];

        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->getInquiryEndpoint(), $payload);

            if ($response->failed()) {
                Log::error('Duitku HTTP Request Failed', ['body' => $response->body()]);
                return [
                    'statusCode'    => '99',
                    'statusMessage' => 'Gagal terhubung ke gateway Duitku (HTTP ' . $response->status() . ')'
                ];
            }

            Log::info('Duitku API Response:', [
                'payload_sent'  => $payload,
                'response_body' => $response->json()
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Duitku Exception: ' . $e->getMessage());
            return [
                'statusCode'    => '99',
                'statusMessage' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mengecek Status Transaksi Langsung ke Server Duitku
     */
    public function checkTransactionStatus($merchantOrderId)
    {
        // Signature Check Status: MD5(merchantCode + merchantOrderId + apiKey)
        $signature = md5($this->merchantCode . $merchantOrderId . $this->apiKey);

        try {
            $response = Http::withoutVerifying()->asForm()->post($this->getCheckStatusEndpoint(), [
                'merchantCode'    => $this->merchantCode,
                'merchantOrderId' => $merchantOrderId,
                'signature'       => $signature,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Duitku Check Status Exception: ' . $e->getMessage());
            return [
                'statusCode'    => '99',
                'statusMessage' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validasi Signature dari Request Callback Duitku
     */
    public function validateCallbackSignature($amount, $merchantOrderId, $signature)
    {
        // Rumus Signature Callback: MD5(merchantCode + amount + merchantOrderId + apiKey)
        $calcSignature = md5($this->merchantCode . $amount . $merchantOrderId . $this->apiKey);

        return strtolower($calcSignature) === strtolower($signature);
    }
}
