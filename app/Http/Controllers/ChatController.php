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
     * Display the main counseling interface with optional initial session token.
     */
    public function index(Request $request): View
    {
        $sessionToken = $request->query('session_token');
        return view('counseling', compact('sessionToken'));
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

        // Guest limit check (Max 3 user messages for unauthenticated guests)
        if (!auth()->check()) {
            $userMsgCount = $chat->messages()->where('role', 'user')->count();
            if ($userMsgCount >= 3) {
                return response()->json([
                    'status' => 'guest_limit_reached',
                    'message' => 'Guest trial limit reached (3/3 messages). Please log in or register for unlimited cognitive counseling access.',
                    'is_guest' => true,
                    'guest_user_msg_count' => $userMsgCount,
                ], 403);
            }
        }

        $result = $this->counselingService->processUserMessage($chat, $userMessage);

        $userMsgCount = !auth()->check() ? $chat->messages()->where('role', 'user')->count() : 0;

        return response()->json([
            'status' => 'success',
            'session_token' => $chat->session_token,
            'is_guest' => !auth()->check(),
            'guest_user_msg_count' => $userMsgCount,
            'data' => $result,
        ]);
    }

    /**
     * Retrieve current active session messages & cognitive history.
     */
    public function getHistory(Request $request): JsonResponse
    {
        $sessionToken = $request->query('session_token');

        if (!$sessionToken && auth()->check()) {
            $userChat = auth()->user()->chats()->latest()->first();
            if ($userChat) {
                $sessionToken = $userChat->session_token;
            }
        }

        if (!$sessionToken) {
            return response()->json([
                'status' => 'success',
                'session_token' => null,
                'messages' => [],
                'cognitive_states' => [],
                'is_guest' => !auth()->check(),
                'guest_user_msg_count' => 0,
            ]);
        }

        $chat = $this->counselingService->getOrCreateChat($sessionToken);
        $chat->load(['messages', 'cognitiveStates']);

        $userMsgCount = !auth()->check() ? $chat->messages()->where('role', 'user')->count() : 0;

        return response()->json([
            'status' => 'success',
            'session_token' => $chat->session_token,
            'dominant_function' => $chat->dominant_function,
            'messages' => $chat->messages,
            'cognitive_states' => $chat->cognitiveStates,
            'is_guest' => !auth()->check(),
            'guest_user_msg_count' => $userMsgCount,
        ]);
    }
}
