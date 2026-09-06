@extends('layouts.app')

@section('title', 'NOW - Master the Present Moment with Jungian Cognitive Rotation')

@section('content')
<!-- Hero Section: Why "NOW" Matters (100% RWD) -->
<header class="py-4 py-md-5 text-center container">
    <div class="row justify-content-center py-2 py-md-4">
        <div class="col-12 col-lg-9">
            <span class="badge bg-orange-subtle text-orange border border-orange-subtle rounded-pill px-3 py-2 mb-3 fw-semibold fs-7 shadow-sm">
                Carl Jung 8-Cognitive Functions & Dynamic Rotation Engine
            </span>

            <h1 class="display-4 fw-extrabold text-dark mb-3 mb-md-4 tracking-tight lh-sm">
                Master the Present Moment.<br/>
                <span class="text-orange">Why "NOW" Is Your Transformation Key.</span>
            </h1>

            <p class="lead text-secondary max-w-3xl mx-auto fs-6 fs-md-5 lh-relaxed mb-4 px-2">
                Most emotional stress and academic anxiety stem from being trapped in past failure memories or catastrophic future projections. 
                <strong class="text-dark">NOW</strong> uses Jungian Cognitive Function Rotation to anchor your mind back to clarity in the present moment.
            </p>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 pt-2">
                <a href="{{ route('counseling') }}" class="btn btn-orange btn-lg px-4 px-md-5 rounded-pill fw-bold shadow">
                    Start Counseling NOW ➔
                </a>
                <a href="#why-now-section" class="btn btn-outline-secondary btn-lg px-4 rounded-pill fw-semibold border-stone-200 text-dark">
                    Why "NOW" Matters
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Section 1: Why "NOW" (現在) Is So Important (100% RWD Grid) -->
<section id="why-now-section" class="py-5 bg-white border-top border-bottom border-stone-200">
    <div class="container py-3">
        <div class="text-center max-w-2xl mx-auto mb-4 mb-md-5">
            <h2 class="fw-bold text-dark mb-3">Why "NOW" Is Your Breakthrough</h2>
            <p class="text-secondary fs-6">Understanding how your cognitive functions get trapped, and how returning to the present moment dissolves stress.</p>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card bg-warm-white border border-stone-200 shadow-sm h-100 p-3 p-md-4 rounded-4">
                    <div class="card-body">
                        <div class="w-12 h-12 rounded-3 bg-orange-subtle text-orange d-inline-flex align-items-center justify-content-center mb-3 p-2">
                            <svg class="bi bi-clock-history" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.6zm1.75 1.139a7 7 0 0 0-.697-.565l.59-.81a8 8 0 0 1 1.08.87zM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8"/>
                                <path d="M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h4a.5.5 0 0 0 .5-.5z"/>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Escape Past Memory Loops</h5>
                        <p class="text-secondary fs-7 lh-relaxed mb-0">
                            Introverted Feeling (Fi) and Introverted Sensing (Si) loops trap students in past test failures and self-blame. NOW redirects your cognitive focus away from lingering regret.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card bg-warm-white border border-stone-200 shadow-sm h-100 p-3 p-md-4 rounded-4">
                    <div class="card-body">
                        <div class="w-12 h-12 rounded-3 bg-orange-subtle text-orange d-inline-flex align-items-center justify-content-center mb-3 p-2">
                            <svg class="bi bi-shield-exclamation" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.5 1.5 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 1.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.5 1.5 0 0 1 2.185 1.43C2.844 1.215 3.962.86 5.072.56"/>
                                <path d="M7.001 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.553.553 0 0 1-1.1 0z"/>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Silence Future Catastrophe</h5>
                        <p class="text-secondary fs-7 lh-relaxed mb-0">
                            Introverted Intuition (Ni) and Thinking (Ti) paralysis project catastrophic future outcomes. NOW grounds your perception into actionable, present-moment steps.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card bg-warm-white border border-stone-200 shadow-sm h-100 p-3 p-md-4 rounded-4">
                    <div class="card-body">
                        <div class="w-12 h-12 rounded-3 bg-orange-subtle text-orange d-inline-flex align-items-center justify-content-center mb-3 p-2">
                            <svg class="bi bi-compass" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.5-14.752A6.97 6.97 0 0 1 14.752 8 6.97 6.97 0 0 1 8.5 14.752 6.97 6.97 0 0 1 2.248 8 6.97 6.97 0 0 1 8.5 1.248"/>
                                <path d="m6.94 7.44 4.95-2.83-2.83 4.95-4.95 2.83z"/>
                            </svg>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Actionable Present Grounding</h5>
                        <p class="text-secondary fs-7 lh-relaxed mb-0">
                            By shifting through Bridge functions (active empathy, social warmth, sensory grounding), NOW moves you straight into Extraverted Execution (Te/Se).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Carl Jung 8 Cognitive Functions Framework (100% RWD Grid) -->
<section class="py-5 bg-warm-white">
    <div class="container py-3">
        <div class="text-center max-w-2xl mx-auto mb-4 mb-md-5">
            <span class="badge bg-orange-subtle text-orange border border-orange-subtle rounded-pill px-3 py-1 mb-2 fw-semibold fs-8">
                Theoretical Core
            </span>
            <h2 class="fw-bold text-dark mb-3">Carl Jung's 8 Cognitive Functions</h2>
            <p class="text-secondary fs-6">NOW continuously measures and balances your 8 cognitive orientation dimensions during every statement.</p>
        </div>

        <div class="row g-3">
            @php
                $functions = [
                    ['code' => 'Fi', 'name' => 'Introverted Feeling', 'desc' => 'Authentic core personal values & emotional alignment.'],
                    ['code' => 'Fe', 'name' => 'Extraverted Feeling', 'desc' => 'Active social empathy & interpersonal harmony.'],
                    ['code' => 'Ti', 'name' => 'Introverted Thinking', 'desc' => 'Deep internal logical analysis & conceptual framework.'],
                    ['code' => 'Te', 'name' => 'Extraverted Thinking', 'desc' => 'Clear structured execution & task organization.'],
                    ['code' => 'Ni', 'name' => 'Introverted Intuition', 'desc' => 'Singular long-term vision & deep pattern synthesis.'],
                    ['code' => 'Ne', 'name' => 'Extraverted Intuition', 'desc' => 'Exploring fresh creative possibilities & paths.'],
                    ['code' => 'Si', 'name' => 'Introverted Sensing', 'desc' => 'Anchoring steady routines & past experiential memory.'],
                    ['code' => 'Se', 'name' => 'Extraverted Sensing', 'desc' => 'Present sensory awareness & physical reality.'],
                ];
            @endphp

            @foreach ($functions as $fn)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card bg-white border border-stone-200 shadow-sm h-100 p-3 rounded-4 transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-orange-subtle text-orange border border-orange-subtle font-monospace fw-bold fs-7">
                                    {{ $fn['code'] }}
                                </span>
                                <small class="text-secondary fs-8">Function</small>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $fn['name'] }}</h6>
                            <p class="text-secondary fs-8 mb-0 lh-normal">{{ $fn['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Section 3: Two-Stage Cognitive Rotation Engine (Elevated Description & Live Vectors) -->
<section class="py-5 bg-white border-top border-stone-200">
    <div class="container py-3">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-6">
                <span class="badge bg-orange-subtle text-orange border border-orange-subtle rounded-pill px-3 py-1 mb-2 fw-semibold fs-8">
                    Psychological Intervention Pipeline
                </span>
                <h2 class="fw-bold text-dark mb-3">Two-Stage Cognitive Rotation Engine</h2>
                <p class="text-secondary fs-6 lh-relaxed mb-4">
                    When emotional distress or cognitive loop traps occur, NOW detects your dominant state and guides your mind through a precise <strong>2-stage psychological rotation path</strong> to restore present-moment clarity:
                </p>

                <div class="d-flex flex-column gap-3 fs-7">
                    <!-- Step 0: State Diagnosis -->
                    <div class="p-3 bg-warm-white border border-stone-200 rounded-4 d-flex gap-3 align-items-start shadow-sm card-hover-lift">
                        <span class="badge bg-secondary text-white rounded-circle p-2 fs-7 fw-bold flex-shrink-0" style="width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center;">0</span>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong class="text-dark">Phase 0: State Diagnosis</strong>
                                <span class="badge bg-light text-secondary border border-stone-200 fs-8">Detection</span>
                            </div>
                            <span class="text-secondary fs-7">Real-time LLM semantic evaluation identifies your current cognitive trap—self-blame loops (Fi), analysis paralysis (Ti), catastrophic foresight (Ni), or failure memory traps (Si).</span>
                        </div>
                    </div>

                    <!-- Step 1: Stage 1 Bridge Function (Pivot) -->
                    <div class="p-3 bg-orange-subtle border border-orange-subtle rounded-4 d-flex gap-3 align-items-start shadow-sm card-hover-lift">
                        <span class="badge btn-orange text-white rounded-circle p-2 fs-7 fw-bold flex-shrink-0" style="width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center;">1</span>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong class="text-orange">Stage 1 Rotation: Bridge Function Pivot</strong>
                                <span class="badge bg-white text-orange border border-orange-subtle fs-8 fw-semibold">Soothe & Support</span>
                            </div>
                            <span class="text-dark fs-7">Deploys supportive auxiliary functions—active empathy (Fe), creative options (Ne), or courage affirmation (Fi)—to unlock rigid emotional paralysis and soothe anxiety.</span>
                        </div>
                    </div>

                    <!-- Step 2: Stage 2 Target Function (Grounding) -->
                    <div class="p-3 bg-warm-white border border-stone-200 rounded-4 d-flex gap-3 align-items-start shadow-sm card-hover-lift">
                        <span class="badge bg-success text-white rounded-circle p-2 fs-7 fw-bold flex-shrink-0" style="width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong class="text-dark">Stage 2 Rotation: Target Function Grounding</strong>
                                <span class="badge bg-success-subtle text-success border border-success-subtle fs-8 fw-semibold">Action in NOW</span>
                            </div>
                            <span class="text-secondary fs-7">Anchors your cognitive attention straight into present execution (Te), physical sensory reality (Se), or clear goal alignment (Ni) in the present moment ("NOW").</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Rotation Vector Example Cards (100% Responsive) -->
            <div class="col-12 col-lg-6">
                <div class="p-4 bg-warm-white border border-stone-200 rounded-4 shadow-sm">
                    <h5 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                        <span class="text-orange">
                            <svg class="bi bi-arrow-repeat spin-slow" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.534 7h3.932a.25.25 0 0 0 .192-.41l-1.966-2.36a.25.25 0 0 0-.384 0l-1.966 2.36a.25.25 0 0 0 .192.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9"/>
                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5 5 0 0 0 8.9 4.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z"/>
                            </svg>
                        </span>
                        <span>Dynamic Rotation Vector Examples</span>
                    </h5>
                    <p class="fs-7 text-secondary mb-4">How NOW transforms trapped mental states step-by-step:</p>

                    <div class="d-flex flex-column gap-3 text-start fs-8">
                        <div class="p-3 bg-white border border-stone-200 rounded-3 shadow-sm card-hover-lift">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <strong class="text-dark fs-7">Self-Blame Trap:</strong>
                                <span class="badge bg-orange-subtle text-orange border border-orange-subtle font-monospace fw-bold fs-8">Fi → Fe (Bridge) → Te (Target)</span>
                            </div>
                            <span class="text-secondary fs-8">First express Fe active empathy to soothe Fi self-blame, then guide toward Te structured execution steps.</span>
                        </div>

                        <div class="p-3 bg-white border border-stone-200 rounded-3 shadow-sm card-hover-lift">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <strong class="text-dark fs-7">Logic Over-Analysis Loop:</strong>
                                <span class="badge bg-orange-subtle text-orange border border-orange-subtle font-monospace fw-bold fs-8">Ti → Ne (Bridge) → Se (Target)</span>
                            </div>
                            <span class="text-secondary fs-8">First use Ne to open up fresh creative possibilities breaking Ti paralysis, then direct to Se sensory grounding.</span>
                        </div>

                        <div class="p-3 bg-white border border-stone-200 rounded-3 shadow-sm card-hover-lift">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <strong class="text-dark fs-7">Catastrophic Future Anxiety:</strong>
                                <span class="badge bg-orange-subtle text-orange border border-orange-subtle font-monospace fw-bold fs-8">Ni → Fi (Bridge) → Se (Target)</span>
                            </div>
                            <span class="text-secondary fs-8">First affirm inner courage with Fi, then ground catastrophic Ni future anxiety into immediate Se reality.</span>
                        </div>

                        <div class="p-3 bg-white border border-stone-200 rounded-3 shadow-sm card-hover-lift">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <strong class="text-dark fs-7">Past Failure Memory Trap:</strong>
                                <span class="badge bg-orange-subtle text-orange border border-orange-subtle font-monospace fw-bold fs-8">Si → Fe (Bridge) → Ne (Target)</span>
                            </div>
                            <span class="text-secondary fs-8">First offer Fe social reassurance to soothe Si past failure memories, then inspire Ne fresh positive options.</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <a href="{{ route('counseling') }}" class="btn btn-orange rounded-pill px-4 py-2 fw-bold shadow-sm w-100">
                            Experience Interactive Rotation NOW ➔
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- High Impact Call-to-Action Banner (100% RWD) -->
<section class="py-5 bg-orange-subtle border-top border-orange-subtle text-center">
    <div class="container py-4">
        <h2 class="display-6 fw-extrabold text-dark mb-3">Ready to Transform Your Mindset in the Present Moment?</h2>
        <p class="lead text-secondary max-w-xl mx-auto fs-6 mb-4 px-2">
            Try 3 free trial statements right now, or create an account for unlimited cognitive tracking history.
        </p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="{{ route('counseling') }}" class="btn btn-orange btn-lg px-5 rounded-pill fw-bold shadow">
                Start Counseling NOW
            </a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-white btn-lg px-4 rounded-pill fw-semibold border border-orange-subtle text-orange bg-white">
                    Create Free Account
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection
