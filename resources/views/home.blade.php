@extends('layouts.app')

@section('title', 'User Counseling Dashboard')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- User Profile Card -->
        <div class="col-lg-4">
            <div class="card bg-white border border-stone-200 shadow-sm rounded-4 p-4 text-dark mb-4">
                <div class="card-body text-center">
                    <div class="w-16 h-16 rounded-circle bg-orange-subtle text-orange d-inline-flex align-items-center justify-content-center mb-3 fs-3 fw-bold border border-orange-subtle" style="width: 64px; height: 64px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-secondary fs-7 mb-3">{{ Auth::user()->email }}</p>
                    <span class="badge bg-orange-subtle text-orange border border-orange-subtle rounded-pill px-3 py-2 fs-8 fw-semibold">
                        Registered Member
                    </span>
                </div>
            </div>

            <div class="card bg-white border border-stone-200 shadow-sm rounded-4 p-3">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-3">Quick Navigation</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('counseling') }}" class="btn btn-orange rounded-pill fw-semibold shadow-sm">
                            + Start New Counseling Session
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Saved Counseling Sessions & 8-Cognitive Records -->
        <div class="col-lg-8">
            <div class="card bg-white border border-stone-200 shadow-sm rounded-4 p-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-dark mb-3 d-flex align-items-center justify-content-between">
                        <span>Your Counseling History & 8-Cognitive Records</span>
                        <span class="badge bg-light text-secondary border border-stone-200 fs-8">{{ count($chats) }} Sessions</span>
                    </h5>

                    @if ($chats->isEmpty())
                        <div class="text-center py-5 text-secondary">
                            <div class="mb-3 text-orange d-inline-flex p-3 rounded-circle bg-orange-subtle border border-orange-subtle">
                                <svg class="bi bi-chat-heart" width="36" height="36" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.965 12.695A1 1 0 0 0 4 13h8a1 1 0 0 0 .8-1.4L11 9.586V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v8.695z"/>
                                </svg>
                            </div>
                            <h6 class="fw-bold text-dark">No Counseling History Yet</h6>
                            <p class="fs-7 text-secondary">Start a conversation in the Counseling Center to track your Jungian 8-Cognitive Functions.</p>
                            <a href="{{ route('counseling') }}" class="btn btn-orange rounded-pill btn-sm px-4 fw-semibold shadow-sm">Start Session</a>
                        </div>
                    @else
                        <div class="list-group list-group-flush gap-3">
                            @foreach ($chats as $chat)
                                <div class="list-group-item bg-light border border-stone-200 rounded-3 p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-orange-subtle text-orange border border-orange-subtle font-monospace fw-bold">
                                                Dominant: {{ $chat->dominant_function ?? 'Fi' }}
                                            </span>
                                            <strong class="text-dark fs-7">{{ $chat->title }}</strong>
                                        </div>
                                        <small class="text-secondary fs-8">{{ $chat->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between fs-8 text-secondary">
                                        <span>Total Statements: {{ $chat->messages_count }}</span>
                                        <span>Session Token: <code class="text-muted">{{ substr($chat->session_token, 0, 10) }}...</code></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
