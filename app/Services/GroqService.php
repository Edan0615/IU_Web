<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service to interface directly with Groq LLM API.
 * Encapsulates client connection, payload building, and structured JSON object output.
 */
class GroqService
{
    protected ?string $apiKey;
    protected string $baseUrl = 'https://api.groq.com/openai/v1';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key', env('GROQ_API_KEY'));
    }

    /**
     * Send messages array and system prompt to Groq API requiring JSON response format.
     *
     * @param array $messages Conversation history messages
     * @param string $systemPrompt Dynamic system prompt
     * @param float $temperature Sampling temperature
     * @return string Raw completion string or JSON string from Groq API
     */
    public function generateCompletion(array $messages, string $systemPrompt, float $temperature = 0.7): string
    {
        if (empty($this->apiKey)) {
            Log::warning('GROQ_API_KEY is not configured in environment.');
            return $this->getFallbackResponse($messages);
        }

        try {
            $formattedMessages = array_merge(
                [['role' => 'system', 'content' => $systemPrompt]],
                $messages
            );

            $model = config('services.groq.model', env('GROQ_MODEL', 'openai/gpt-oss-120b'));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => $formattedMessages,
                'response_format' => ['type' => 'json_object'],
                'temperature' => $temperature,
                'max_tokens' => 1024,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? $this->getFallbackResponse($messages);
            }

            Log::error('Groq API HTTP Failure: ' . $response->body());
            return $this->getFallbackResponse($messages);
        } catch (\Throwable $e) {
            Log::error('Groq Service Exception: ' . $e->getMessage());
            return $this->getFallbackResponse($messages);
        }
    }

    /**
     * Fallback JSON string response when API service is unconfigured or offline.
     */
    protected function getFallbackResponse(array $messages): string
    {
        return json_encode([
            'message' => 'I am listening closely to what you are sharing. Let us take a quiet breath together and organize your thoughts step by step.',
            'dominant_function' => 'Fi',
            'rotation_target' => 'Te',
            'rotation_vector' => 'Fi -> Fe -> Te',
            'in_loop' => false,
            'emotional_clarity_score' => 0.85,
            'scores' => [
                'Ti' => 12, 'Te' => 10, 'Fi' => 24, 'Fe' => 14,
                'Ni' => 16, 'Ne' => 18, 'Si' => 11, 'Se' => 9,
            ],
        ], JSON_UNESCAPED_UNICODE);
    }
}
