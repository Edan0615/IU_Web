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
    public function index()
    {
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
