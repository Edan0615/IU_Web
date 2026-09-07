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

        // If user has zero chats, also check if there's any recent unattached chat session in DB from last 2 hours and claim it
        if (auth()->user()->chats()->count() === 0) {
            \App\Models\Chat::whereNull('user_id')
                ->where('created_at', '>=', now()->subHours(2))
                ->latest()
                ->first()?->update(['user_id' => $userId]);
        }

        $chats = auth()->user()
            ->chats()
            ->withCount('messages')
            ->with(['cognitiveStates' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->latest()
            ->get();

        return view('home', compact('chats'));
    }
}
