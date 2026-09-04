<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\CognitiveState;
use Illuminate\Support\Str;

/**
 * Main Counseling Orchestrator Service.
 * Coordinates conversation session management, AI JSON cognitive evaluation, and database persistence.
 */
class CounselingService
{
    protected GroqService $groqService;
    protected CognitiveRotationService $rotationService;

    public function __construct(
        GroqService $groqService,
        CognitiveRotationService $rotationService
    ) {
        $this->groqService = $groqService;
        $this->rotationService = $rotationService;
    }

    /**
     * Retrieve existing Chat model by session token or create a new session instance.
     */
    public function getOrCreateChat(?string $sessionToken): Chat
    {
        $userId = auth()->id();

        if ($sessionToken) {
            $chat = Chat::where('session_token', $sessionToken)->first();
            if ($chat) {
                if ($userId && !$chat->user_id) {
                    $chat->update(['user_id' => $userId]);
                }
                return $chat;
            }
        }

        return Chat::create([
            'user_id' => $userId,
            'session_token' => Str::random(32),
            'title' => 'Counseling Session',
            'dominant_function' => 'Fi',
            'in_loop' => false,
        ]);
    }

    /**
     * Process student message, directly call Groq AI for completion + JSON 8-function evaluation, and store analytics.
     */
    public function processUserMessage(Chat $chat, string $userContent): array
    {
        // 1. Save user message
        $userMsg = ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => $userContent,
        ]);

        // 2. Determine initial state from chat history or default
        $lastDominant = $chat->dominant_function ?: 'Fi';
        $initialAnalysis = [
            'primary_function' => $lastDominant,
            'secondary_function' => 'Te',
            'in_loop' => false,
        ];

        // 3. Build system prompt for Groq LLM
        $systemPrompt = $this->rotationService->getSystemPrompt($initialAnalysis);
        $messagesPayload = $chat->messages()->select('role', 'content')->get()->toArray();

        // 4. Single Direct LLM Call to Groq
        $rawResponse = $this->groqService->generateCompletion($messagesPayload, $systemPrompt);

        // 5. Parse JSON output from AI response
        $replyText = $rawResponse;
        $analysis = [
            'primary_function' => $lastDominant,
            'secondary_function' => 'Te',
            'scores' => [
                'Ti' => 12, 'Te' => 12, 'Fi' => 20, 'Fe' => 14,
                'Ni' => 15, 'Ne' => 16, 'Si' => 11, 'Se' => 10,
            ],
            'in_loop' => false,
            'emotional_clarity_score' => 0.8,
        ];

        $cleanJson = preg_replace('/^```json\s*|\s*```$/i', '', trim($rawResponse));
        $parsed = json_decode($cleanJson, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
            $replyText = $parsed['message'] ?? $rawResponse;
            if (isset($parsed['scores']) && is_array($parsed['scores'])) {
                $analysis['scores'] = array_merge($analysis['scores'], $parsed['scores']);
            }
            if (isset($parsed['dominant_function'])) {
                $analysis['primary_function'] = $parsed['dominant_function'];
            }
            if (isset($parsed['rotation_target'])) {
                $analysis['rotation_target'] = $parsed['rotation_target'];
            }
            if (isset($parsed['rotation_vector'])) {
                $analysis['rotation_vector'] = $parsed['rotation_vector'];
            }
            if (isset($parsed['emotional_clarity_score'])) {
                $analysis['emotional_clarity_score'] = (float)$parsed['emotional_clarity_score'];
            }
        }

        // 6. Persist Cognitive State record
        CognitiveState::create([
            'chat_id' => $chat->id,
            'primary_function' => $analysis['primary_function'],
            'secondary_function' => $analysis['secondary_function'],
            'loop_detected' => null,
            'rotation_applied' => 'Empathetic Alignment',
            'emotional_clarity_score' => $analysis['emotional_clarity_score'],
        ]);

        // 7. Update Chat model dominant function
        $chat->update([
            'dominant_function' => $analysis['primary_function'],
            'in_loop' => false,
        ]);

        // 8. Save assistant message with cognitive metadata
        $assistantMsg = ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => $replyText,
            'cognitive_tags' => [$analysis['primary_function']],
            'analysis_metadata' => $analysis,
        ]);

        return [
            'chat' => $chat->fresh(['messages', 'cognitiveStates']),
            'latest_analysis' => $analysis,
            'user_message' => $userMsg,
            'assistant_message' => $assistantMsg,
        ];
    }
}
