<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function generateText(string $prompt, int $maxLines = 3): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => "You are a helpful assistant. The user will provide a prompt, and you will generate a response that is exactly {$maxLines} lines long."],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return trim($response->json('choices.0.message.content'));
            }

            return 'Error: Could not generate text from AI. API returned an error.';
        } catch (ConnectionException $e) {
            return 'Error: Could not connect to the AI service. Please check your network connection.';
        }
    }
}
