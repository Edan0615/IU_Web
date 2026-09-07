<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $guestToken = $request->input('guest_session_token') ?: session('guest_session_token');

        if ($guestToken) {
            \App\Models\Chat::where('session_token', $guestToken)
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }

        // Clean up 0-message empty chats to prevent dashboard clutter
        auth()->user()->chats()->whereDoesntHave('messages')->delete();

        // If user has zero chats, check if there's a recent unattached chat with messages from last 2 hours and claim it
        if (auth()->user()->chats()->count() === 0) {
            \App\Models\Chat::whereNull('user_id')
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
