<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\CognitiveState;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Render the member statistics dashboard with cognitive analytics.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        
        // Fetch user's chats or session chats
        $chats = Chat::where('user_id', $user->id)
            ->with(['cognitiveStates', 'messages'])
            ->get();

        $chatIds = $chats->pluck('id')->toArray();
        $cognitiveStates = CognitiveState::whereIn('chat_id', $chatIds)->get();

        // Extract historical function scores arrays from analysis_metadata in assistant messages
        $assistantMessages = ChatMessage::whereIn('chat_id', $chatIds)
            ->where('role', 'assistant')
            ->whereNotNull('analysis_metadata')
            ->get();

        $historyScores = [];
        foreach ($assistantMessages as $msg) {
            $meta = $msg->analysis_metadata;
            if (isset($meta['scores']) && is_array($meta['scores'])) {
                $historyScores[] = $meta['scores'];
            }
        }

        // Calculate Average Emotional Clarity
        $avgClarity = $cognitiveStates->count() > 0 
            ? round($cognitiveStates->avg('emotional_clarity_score'), 2)
            : 0.85;

        // Loop count
        $loopCount = $cognitiveStates->where('loop_detected', '!=', null)->count();

        // Clarity timeline history
        $clarityTimeline = $cognitiveStates->take(10)->map(function ($state) {
            return [
                'date' => $state->created_at->format('M d, H:i'),
                'clarity' => (float)$state->emotional_clarity_score,
                'primary' => $state->primary_function,
            ];
        })->values();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_sessions' => $chats->count(),
                'total_turns' => $assistantMessages->count(),
                'average_emotional_clarity' => $avgClarity,
                'cognitive_loop_interventions' => $loopCount,
                'top_mbti_match' => 'Balanced Spectrum',
                'clarity_timeline' => $clarityTimeline,
            ]
        ]);
    }
}
