<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class AiChatController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:4000',
            'context_title' => 'required|string',
            'context_description' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        $contextTitle = $request->input('context_title');
        $contextDescription = $request->input('context_description');
        $apiKey = env('OPENAI_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => 'OpenAI API key is not configured.'], 500);
        }

        $systemPrompt = "You are a helpful AI assistant for an e-learning platform. Your name is MBM Uni Assistant. You are an expert on the platform's content. Your goal is to answer student questions about the specific topic or course they are viewing. You must only answer questions related to the provided context. If a user asks a question that is off-topic, politely refuse and guide them back to the relevant material. Do not answer any general knowledge questions. Here is the context: \n\nTitle: {$contextTitle}\nDescription: {$contextDescription}";

        try {
            $response = Http::withToken($apiKey)
                ->timeout(120)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                ]);

            if ($response->failed()) {
                return response()->json(['reply' => 'Failed to get a response from the AI assistant.'], 500);
            }

            $reply = $response->json('choices.0.message.content');

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'An unexpected error occurred.'], 500);
        }
    }
}
