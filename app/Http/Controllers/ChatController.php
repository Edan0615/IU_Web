<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\SendMessageRequest;
use App\Services\CounselingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use Illuminate\View\View;

/**
 * Controller handling counseling chat web routes and RESTful API endpoints.
 * Follows SOLID & Backend Standards by delegating all business logic to CounselingService.
 */
class ChatController extends Controller
{
    protected CounselingService $counselingService;

    public function __construct(CounselingService $counselingService)
    {
        $this->counselingService = $counselingService;
    }

    /**
     * Render the main counseling page using traditional Blade view.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $sessionToken = $request->cookie('counseling_session_token') ?? $request->header('X-Session-Token');
        $chat = $this->counselingService->getOrCreateChat($sessionToken);

        return view('counseling', [
            'chat' => $chat->load(['messages', 'cognitiveStates']),
            'sessionToken' => $chat->session_token,
        ]);
    }

    /**
     * RESTful API endpoint to handle user message dispatch and response generation.
     *
     * @param SendMessageRequest $request
     * @return JsonResponse
     */
    public function sendMessage(SendMessageRequest $request): JsonResponse
    {
        $sessionToken = $request->validated('session_token') ?? $request->cookie('counseling_session_token');
        $chat = $this->counselingService->getOrCreateChat($sessionToken);

        $result = $this->counselingService->processUserMessage($chat, $request->validated('message'));

        return response()->json($result)->cookie('counseling_session_token', $chat->session_token, 60 * 24 * 30);
    }
}
