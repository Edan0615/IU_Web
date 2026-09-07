<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\CognitiveState;
use Illuminate\Support\Str;

/**
 * Main Counseling Orchestrator Service.
 * Coordinates conversation session management, 3-stage service pipeline execution, and database persistence.
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

        // Reuse existing empty chat (0 messages) for active user/guest to prevent session clutter
        $emptyQuery = Chat::whereDoesntHave('messages');
        if ($userId) {
            $emptyQuery->where('user_id', $userId);
        } else {
            $emptyQuery->whereNull('user_id');
        }
        $emptyChat = $emptyQuery->latest()->first();

        if ($emptyChat) {
            return $emptyChat;
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
     * Process student message through clear 3-Stage Service Pipeline:
     * 1. Analyze student's current message statement to detect 8-cognitive function state.
     * 2. Pass detected state into CognitiveRotationService for target prompt generation (Bridge -> Target).
     * 3. Request LLM completion via GroqService & persist database records.
     *
     * @param Chat $chat Active chat model instance
     * @param string $userContent User message text
     * @return array Response payload with updated conversation and cognitive metadata
     */
    public function processUserMessage(Chat $chat, string $userContent): array
    {
        // 1. STAGE 1: Analyze student message FIRST using CognitiveAnalysisService
        $initialAnalysis = $this->analysisService->analyzeUserMessage($userContent);
        $primary = $initialAnalysis['primary_function'] ?? 'Fi';
        $rotationDetails = $this->rotationService->getRotationDetails($primary);
        $initialAnalysis['rotation_details'] = $rotationDetails;

        // 2. Save user message into chat_messages table with cognitive analysis metadata
        $userMsg = ChatMessage::create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => $userContent,
            'cognitive_tags' => [$primary],
            'analysis_metadata' => $initialAnalysis,
        ]);

        // 3. STAGE 2: Pass detected cognitive state into CognitiveRotationService for prompt building
        $systemPrompt = $this->rotationService->getSystemPrompt($initialAnalysis);

        // 4. STAGE 3: Single Direct LLM Call to Groq via GroqService
        $messagesPayload = $chat->messages()->select('role', 'content')->get()->toArray();
        $rawResponse = $this->groqService->generateCompletion($messagesPayload, $systemPrompt);

        // 5. Parse response & normalize payload via CognitiveAnalysisService
        $analysis = $this->analysisService->parseResponse($rawResponse, $initialAnalysis);
        $analysis['rotation_details'] = $rotationDetails;
        $replyText = $analysis['reply_text'];

        // 6. Persist Cognitive State record into DB
        CognitiveState::create([
            'chat_id' => $chat->id,
            'primary_function' => $analysis['primary_function'],
            'secondary_function' => $analysis['secondary_function'],
            'loop_detected' => null,
            'rotation_applied' => $rotationDetails['vector'],
            'emotional_clarity_score' => $analysis['emotional_clarity_score'],
        ]);

        // 7. Update Chat model summary flags
        $chat->update([
            'dominant_function' => $analysis['primary_function'],
            'in_loop' => false,
        ]);

        // 8. Save assistant message with full cognitive metadata and rotation details
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
