<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\Chat;
use App\Services\CounselingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller managing HTTP endpoints for AI cognitive counseling sessions.
 * Handlers request dispatching, guest session limits, and session history claiming.
 */
class ChatController extends Controller
{
    protected CounselingService $counselingService;

    /**
     * Inject CounselingService orchestrator instance.
     */
    public function __construct(CounselingService $counselingService)
    {
        $this->counselingService = $counselingService;
    }

    /**
     * Display the main counseling interface with optional initial session token.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $sessionToken = $request->query('session_token');
        return view('counseling', compact('sessionToken'));
    }

    /**
     * Process user message via 3-Stage Counseling Pipeline.
     *
     * @param SendMessageRequest $request Validated HTTP request
     * @return JsonResponse
     */
    public function sendMessage(SendMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $sessionToken = $validated['session_token'] ?? session('guest_session_token');
        $userMessage = $validated['message'];

        $chat = $this->counselingService->getOrCreateChat($sessionToken);

        // Store active session_token in Laravel session memory for guest continuity across login/register
        session(['guest_session_token' => $chat->session_token]);

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
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getHistory(Request $request): JsonResponse
    {
        $sessionToken = $request->query('session_token') ?: session('guest_session_token');

        if (auth()->check()) {
            $userId = auth()->id();
            // Automatically claim any unattached guest session in session memory or by token
            if ($sessionToken) {
                Chat::where('session_token', $sessionToken)->whereNull('user_id')->update(['user_id' => $userId]);
            }
            if (session()->has('guest_session_token')) {
                Chat::where('session_token', session('guest_session_token'))->whereNull('user_id')->update(['user_id' => $userId]);
            }

            if (!$sessionToken) {
                $userChat = auth()->user()->chats()->has('messages')->latest()->first();
                if ($userChat) {
                    $sessionToken = $userChat->session_token;
                } else {
                    $recentUnattached = Chat::whereNull('user_id')->has('messages')->where('created_at', '>=', now()->subHours(2))->latest()->first();
                    if ($recentUnattached) {
                        $recentUnattached->update(['user_id' => $userId]);
                        $sessionToken = $recentUnattached->session_token;
                    }
                }
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

        $chat = Chat::where('session_token', $sessionToken)->first();

        if (!$chat) {
            return response()->json([
                'status' => 'success',
                'session_token' => null,
                'messages' => [],
                'cognitive_states' => [],
                'is_guest' => !auth()->check(),
                'guest_user_msg_count' => 0,
            ]);
        }

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
