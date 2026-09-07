<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

/**
 * Controller managing authenticated user dashboard and counseling history listing.
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance with auth middleware guard.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user counseling dashboard with claimed history sessions.
     *
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $userId = auth()->id();
        $guestToken = $request->input('guest_session_token') ?: session('guest_session_token');

        // Claim unattached guest session matching token
        if ($guestToken) {
            Chat::where('session_token', $guestToken)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }

        // Clean up 0-message empty chats to prevent dashboard clutter
        auth()->user()->chats()->whereDoesntHave('messages')->delete();

        // If user has zero chats, check if there's a recent unattached chat with messages from last 2 hours and claim it
        if (auth()->user()->chats()->count() === 0) {
            Chat::whereNull('user_id')
                ->has('messages')
                ->where('created_at', '>=', now()->subHours(2))
                ->latest()
                ->first()?->update(['user_id' => $userId]);
        }

        $chats = auth()->user()
            ->chats()
            ->has('messages')
            ->withCount('messages')
            ->with(['cognitiveStates' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->latest()
            ->get();

        return view('home', compact('chats'));
    }
}
