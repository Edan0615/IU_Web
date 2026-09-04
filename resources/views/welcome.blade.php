<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>IU Cognitive Counseling Center - {{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & SASS via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-dark text-light min-vh-100 d-flex flex-column font-sans">
    <div id="app" class="flex-grow-1 d-flex flex-column">
        <!-- Bootstrap Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-body-tertiary border-bottom border-secondary border-opacity-25 sticky-top">
            <div class="container">
                <a class="navbar-brand font-weight-bold d-flex items-center gap-2" href="{{ url('/') }}">
                    <span class="badge bg-primary rounded-pill p-2">🧠</span>
                    <span class="fs-5 fw-bold text-white">IU Cognitive</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center gap-3">
                        <li class="nav-item">
                            <a class="nav-link text-light" href="#counseling-section">Counseling</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="#features-section">Features</a>
                        </li>

                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item">
                                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-3 rounded-pill">Dashboard</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link text-light">Log in</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="btn btn-outline-info btn-sm px-3 rounded-pill">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="py-5 text-center container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2 mb-3">
                        Jungian 8 Cognitive Functions & Dynamic Rotation Engine
                    </span>

                    <h1 class="display-5 fw-bold text-white mb-3">
                        Unlock Your Cognitive Potential <br/>
                        <span class="text-info">Reshape Mindset & Emotional Clarity</span>
                    </h1>

                    <p class="lead text-secondary max-w-2xl mx-auto fs-6">
                        Powered by Carl Jung's Cognitive Function Analysis Model and Groq LLM inference, detecting your cognitive tilt and loop state in real time.
                    </p>
                </div>
            </div>
        </header>

        <!-- Main Vue Application Mount -->
        <main id="counseling-section" class="container flex-grow-1 pb-5">
            <chat-interface></chat-interface>
        </main>

        <!-- Features Section -->
        <section id="features-section" class="py-5 border-top border-secondary border-opacity-25 bg-body-tertiary">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card bg-dark text-light border-secondary border-opacity-25 h-100 p-3 rounded-4">
                            <div class="card-body">
                                <div class="fs-3 mb-3 text-info">📊</div>
                                <h5 class="card-title fw-bold text-white">8-Cognitive Function Radar</h5>
                                <p class="card-text text-secondary fs-7">Real-time tracking of Ni, Ne, Si, Se, Ti, Te, Fi, Fe intensities with dynamic visual spectrum graphics.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-dark text-light border-secondary border-opacity-25 h-100 p-3 rounded-4">
                            <div class="card-body">
                                <div class="fs-3 mb-3 text-primary">🔄</div>
                                <h5 class="card-title fw-bold text-white">3-Stage Rotation Pipeline</h5>
                                <p class="card-text text-secondary fs-7">State Analysis -> Bridge Prompting -> Rotation Target strategy to break cognitive loop traps.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-dark text-light border-secondary border-opacity-25 h-100 p-3 rounded-4">
                            <div class="card-body">
                                <div class="fs-3 mb-3 text-success">🔒</div>
                                <h5 class="card-title fw-bold text-white">Private Session Isolation</h5>
                                <p class="card-text text-secondary fs-7">Isolated session token architecture ensuring confidential counseling and data protection.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-4 text-center text-secondary border-top border-secondary border-opacity-25 bg-dark">
            <div class="container">
                <p class="mb-1 fs-7">&copy; {{ date('Y') }} IU Cognitive Counseling Center. All rights reserved.</p>
                <p class="mb-0 fs-8 text-muted">Built with Laravel 10, Bootstrap 5, Vue 3 & Chart.js.</p>
            </div>
        </footer>
    </div>
</body>
</html>
