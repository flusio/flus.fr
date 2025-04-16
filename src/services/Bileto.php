<?php

namespace Website\services;

use Website\models;

class Bileto
{
    private ?string $url;
    private ?string $api_token;

    public function __construct()
    {
        $this->url = \Minz\Configuration::$application['bileto_url'];
        $this->api_token = \Minz\Configuration::$application['bileto_api_token'];
    }

    public function isEnabled(): bool
    {
        return $this->url && $this->api_token;
    }

    public function sendMessage(models\Message $message): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $endpoint = "{$this->url}/api/tickets";
        $http = new \SpiderBits\Http();
        try {
            $response = $http->post($endpoint, [
                'from' => $message->email,
                'title' => $message->subject,
                'content' => $message->content,
            ], [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->api_token}",
                ],
            ]);

            return $response->success;
        } catch (\SpiderBits\HttpError $e) {
            \Minz\Log::error($e->getMessage());

            return false;
        }
    }
}
