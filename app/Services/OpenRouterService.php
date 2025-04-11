<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected $apiKey;
    protected $baseUrl = 'https://openrouter.ai/api/v1';
    protected $siteUrl;
    protected $siteName;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key');
        $this->siteUrl = config('services.openrouter.site_url');
        $this->siteName = config('services.openrouter.site_name');
    }

    public function sendMessage($message, $conversationHistory = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => $this->siteUrl,
                'X-Title' => $this->siteName,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => 'deepseek/deepseek-r1:free',  // Using DeepSeek model via OpenRouter
                'messages' => array_merge($conversationHistory, [
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ]),
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            } else {
                Log::error('OpenRouter API Error: ' . $response->body());
                return 'Sorry, I encountered an error processing your request.';
            }
        } catch (\Exception $e) {
            Log::error('OpenRouter API Exception: ' . $e->getMessage());
            return 'Sorry, I encountered an error processing your request.';
        }
    }
}