<?php

namespace App\Services;

use GuzzleHttp\Client;
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
        $this->apiUrl = env('BEEM_API_URL', "https://apisms.beem.africa/v1/send"); // Add to .env
        $this->apiKey = env('BEEM_API_KEY', '5472fb3edef1e55b'); // Add to .env
        $this->senderId = env('BEEM_SENDER_ID', 'MISANA'); // Add to .env
        $this->secretKey = env('BEEM_SECRET', 'YzUyZjY0NjQ5YWZmNTNlNmY4ODhlZGY3NjA2NGUwYzZjMjA5OTAwZmIzZmQ5ZjUyOWYwMDliMmJjZDcyYTc5ZA=='); // Add to .env
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
    public function rawSendBulkMessage(string $message, array $recipients, string $scheduleTime = null)
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
    public function sendBulkMessage(string $message, array $recipients, string $scheduleTime = null)
    {
        $client = new Client();

        $postData = [
            'source_addr' => $this->senderId,
            'encoding' => "0",
            'message' => $message,
            'recipients' => $recipients,
        ];

        try {
            $response = $client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode("{$this->apiKey}:{$this->secretKey}"),
                    'Content-Type' => 'application/json',
                ],
                'json' => $postData, // Automatically encodes as JSON
                'verify' => false, // Disable SSL certificate verification
            ]);

            return [
                'status' => 'success',
                'data' => json_decode($response->getBody(), true),
            ];
        } catch (RequestException $e) {
            $errorResponse = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            return [
                'status' => 'error',
                'message' => $errorResponse,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
