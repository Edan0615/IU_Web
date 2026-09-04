<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Services\CounselingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    protected CounselingService $counselingService;

    public function __construct(CounselingService $counselingService)
    {
        $this->counselingService = $counselingService;
    }

    /**
     * Display the main counseling interface.
     */
    public function index(): View
    {
        return view('counseling');
    }

    /**
     * Process user message via 3-Stage Counseling Pipeline.
     */
    public function sendMessage(SendMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $sessionToken = $validated['session_token'] ?? null;
        $userMessage = $validated['message'];

        $chat = $this->counselingService->getOrCreateChat($sessionToken);
        $result = $this->counselingService->processUserMessage($chat, $userMessage);

        return response()->json([
            'status' => 'success',
            'session_token' => $chat->session_token,
            'data' => $result,
        ]);
    }

    /**
     * Retrieve current active session messages & cognitive history.
     */
    public function getHistory(Request $request): JsonResponse
    {
        $sessionToken = $request->query('session_token');
        if (!$sessionToken) {
            return response()->json([
                'status' => 'success',
                'session_token' => null,
                'messages' => [],
                'cognitive_states' => [],
            ]);
        }

        $chat = $this->counselingService->getOrCreateChat($sessionToken);
        $chat->load(['messages', 'cognitiveStates']);

        return response()->json([
            'status' => 'success',
            'session_token' => $chat->session_token,
            'dominant_function' => $chat->dominant_function,
            'messages' => $chat->messages,
            'cognitive_states' => $chat->cognitiveStates,
        ]);
    }
}
