<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Now') }} - AI Counseling & Jungian Cognitive Rotation</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Noto+Sans+TC:wght@300;400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-slate-950 text-slate-100 selection:bg-cyan-500 selection:text-white overflow-hidden">
    <div id="app" class="h-full flex flex-col">
        <!-- Main Navigation Header -->
        <header class="h-16 border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-xl flex items-center justify-between px-6 z-20">
            <div class="flex items-center space-x-3">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 via-indigo-500 to-fuchsia-500 p-0.5 shadow-lg shadow-cyan-500/20 group-hover:scale-105 transition-transform duration-300">
                        <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                            <span class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-400">現在</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight text-white flex items-center space-x-2">
                            <span>Now</span>
                            <span class="px-2 py-0.5 text-[10px] font-semibold tracking-wider text-cyan-300 bg-cyan-950/80 border border-cyan-800/60 rounded-full uppercase">Cognitive Rotation</span>
                        </h1>
                        <p class="text-xs text-slate-400">AI Pedagogical Counseling for Exam Stress</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center space-x-2 px-3 py-1 text-xs font-medium text-slate-300 bg-slate-800/60 border border-slate-700/60 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Session: {{ substr($chat->session_token, 0, 8) }}...</span>
                </span>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-300 hover:text-white px-3 py-1.5 rounded-lg bg-slate-800/50 hover:bg-slate-800 border border-slate-700/50 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300 px-3 py-1.5 rounded-lg bg-cyan-950/40 border border-cyan-800/50 transition">Log in</a>
                @endauth
            </div>
        </header>

        <!-- Main Content Mounting Area for Vue components -->
        <main class="flex-1 overflow-hidden relative" data-session-token="{{ $sessionToken }}" data-chat-id="{{ $chat->id }}">
            <div class="h-full flex items-center justify-center p-6">
                <div class="w-full max-w-5xl h-full bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 backdrop-blur-md flex flex-col justify-between shadow-2xl">
                    <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                        @foreach($chat->messages as $msg)
                            <div class="flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xl p-4 rounded-2xl {{ $msg->role === 'user' ? 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-br-none' : 'bg-slate-800/80 border border-slate-700/80 text-slate-200 rounded-bl-none shadow-lg' }}">
                                    <p class="text-sm leading-relaxed">{{ $msg->content }}</p>
                                    @if($msg->role === 'assistant' && !empty($msg->cognitive_tags))
                                        <div class="mt-3 pt-2 border-t border-slate-700/60 flex items-center space-x-2 text-[10px] text-cyan-300">
                                            <span>Detected Vector: {{ implode(' -> ', $msg->cognitive_tags) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Interactive Form / Input Bar -->
                    <div class="mt-4 pt-4 border-t border-slate-800/80">
                        <form action="/api/chat/send" method="POST" class="flex space-x-3">
                            @csrf
                            <input type="hidden" name="session_token" value="{{ $sessionToken }}">
                            <input type="text" name="message" placeholder="Type what's on your mind... (e.g. I feel stressed about math exams)" class="flex-1 bg-slate-950 border border-slate-800 focus:border-cyan-500 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-cyan-500 transition">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-cyan-500/20 hover:scale-[1.02] transition duration-200">
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
