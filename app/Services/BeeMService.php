<?php

namespace App\Services;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class BeeMService
{
    protected $senderId;
    protected $apiUrl;
    protected $apiKey;
    protected $secretKey;

    public function __construct()
    {
        $this->apiUrl = env('BEEM_API_URL'); // Add to .env
        $this->apiKey = env('BEEM_API_KEY'); // Add to .env
        $this->senderId = env('BEEM_SENDER_ID'); // Add to .env
        $this->secretKey = env('BEEM_SECRET'); // Add to .env
    }

    /**
     * Send a message through the BeeM API.
     *
     * @param string $senderId
     * @param string $message
     * @param array $recipients
     * @param string|null $scheduleTime
     * @return mixed
     */
    public function sendBulkMessage(string $message, array $recipients, string $scheduleTime = null)
    {
        $postData = [
            'source_addr' => $this->senderId,
            'encoding' => "0",
            'message' => $message,
            'recipients' => $recipients,
        ];
        info($postData);
        try {
            $response = Http::withOptions([
                'verify' => false, // Disable SSL certificate verification
            ])->withHeaders([
                'Authorization' => 'Basic ' . base64_encode("{$this->apiKey}:{$this->secretKey}"),
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, $postData);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'status' => 'error',
                'message' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
