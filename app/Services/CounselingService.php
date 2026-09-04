<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\CognitiveState;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Main Counseling Orchestrator Service.
 * Coordinates conversation session management, AI JSON cognitive evaluation, and database persistence.
 */
class CounselingService
{
    protected GroqService $groqService;
    protected CognitiveAnalysisService $analysisService;
    protected CognitiveRotationService $rotationService;

    public function __construct(
        GroqService $groqService,
        CognitiveAnalysisService $analysisService,
        CognitiveRotationService $rotationService
    ) {
        $this->groqService = $groqService;
        $this->analysisService = $analysisService;
        $this->rotationService = $rotationService;
    }

    /**
     * Retrieve existing Chat model by session token or create a new session instance.
     *
     * @param string|null $sessionToken Unique session token string
     * @return Chat
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
     * Process student message, request AI JSON evaluation for 8 functions, and store analytics.
     *
     * @param Chat $chat Active chat model instance
     * @param string $userContent User message text
     * @return array Response payload with updated conversation and cognitive metadata
     */
    public function processUserMessage(Chat $chat, string $userContent): array
    {
        // 1. Save user message into chat_messages table
        $userMsg = ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => $userContent,
        ]);

        // 2. Initial analysis for fallback & prompt preparation
        $history = $chat->messages()->pluck('content')->toArray();
        $fallbackAnalysis = $this->analysisService->analyze($userContent, $history);

        // 3. Request LLM completion with strict JSON system prompt
        $systemPrompt = $this->rotationService->getSystemPrompt($fallbackAnalysis);
        $messagesPayload = $chat->messages()->select('role', 'content')->get()->toArray();

        $rawResponse = $this->groqService->generateCompletion($messagesPayload, $systemPrompt);

        // Parse JSON output from AI
        $analysis = $fallbackAnalysis;
        $replyText = $rawResponse;

        // Clean json fences if present
        $cleanJson = preg_replace('/^```json\s*|\s*```$/i', '', trim($rawResponse));
        $parsed = json_decode($cleanJson, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
            $replyText = $parsed['message'] ?? $rawResponse;
            if (isset($parsed['scores']) && is_array($parsed['scores'])) {
                $analysis['scores'] = array_merge([
                    'Ti' => 0, 'Te' => 0, 'Fi' => 0, 'Fe' => 0,
                    'Ni' => 0, 'Ne' => 0, 'Si' => 0, 'Se' => 0
                ], $parsed['scores']);
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
            if (isset($parsed['in_loop'])) {
                $analysis['in_loop'] = (bool)$parsed['in_loop'];
            }
            if (isset($parsed['emotional_clarity_score'])) {
                $analysis['emotional_clarity_score'] = (float)$parsed['emotional_clarity_score'];
            }
        }

        // 4. Persist Cognitive State record into database for historical statistics & dashboard tracking
        CognitiveState::create([
            'chat_id' => $chat->id,
            'primary_function' => $analysis['primary_function'],
            'secondary_function' => $analysis['secondary_function'],
            'loop_detected' => $analysis['loop_detected'],
            'rotation_applied' => $analysis['in_loop'] ? 'Cognitive Loop Disruption' : 'Empathetic Alignment',
            'emotional_clarity_score' => $analysis['emotional_clarity_score'],
        ]);

        // 5. Update Chat model summary flags
        $chat->update([
            'dominant_function' => $analysis['primary_function'],
            'in_loop' => $analysis['in_loop'],
        ]);

        // 6. Save assistant message with full cognitive metadata and scores
        $assistantMsg = ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'assistant',
            'content' => $replyText,
            'cognitive_tags' => [$analysis['primary_function'], $analysis['secondary_function'] ?? 'Te'],
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
