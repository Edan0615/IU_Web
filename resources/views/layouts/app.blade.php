<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'NOW')) - Cognitive Counseling Center</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & SASS via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-warm-white text-dark min-vh-100 d-flex flex-column font-sans">
    <div id="app" class="flex-grow-1 d-flex flex-column">
        <!-- Unified Master Navigation Bar -->
        <nav class="navbar navbar-expand-lg bg-white border-bottom border-stone-200 sticky-top shadow-sm py-3">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <div class="rounded-3 bg-orange-subtle text-orange p-2 d-flex align-items-center justify-content-center border border-orange-subtle">
                        <svg class="bi bi-cpu" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h.5A1.5 1.5 0 0 1 14 3.5V4h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v.5A1.5 1.5 0 0 1 12.5 14H12v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-.5A1.5 1.5 0 0 1 2 12.5V12H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2V4H.5a.5.5 0 0 1 0-1H2v-.5A1.5 1.5 0 0 1 3.5 0zm.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zM4 4h8v8H4z"/>
                        </svg>
                    </div>
                    <span class="fs-4 fw-bold text-dark tracking-tight">NOW</span>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item">
                            <a class="nav-link text-dark fw-medium" href="{{ route('counseling') }}">Counseling Center</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center gap-3 mt-3 mt-lg-0">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-dark fw-medium" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-orange btn-sm px-4 rounded-pill fw-semibold shadow-sm" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-stone-200 rounded-3" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item fs-7" href="{{ route('home') }}">Dashboard History</a>
                                    <a class="dropdown-item fs-7" href="{{ route('counseling') }}">New Counseling Session</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item fs-7 text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Dynamic Content Body -->
        <main class="flex-grow-1 py-4">
            @yield('content')
        </main>

        <!-- Unified Footer -->
        <footer class="py-4 text-center text-secondary border-top border-stone-200 bg-white mt-auto">
            <div class="container">
                <p class="mb-1 fs-7">&copy; {{ date('Y') }} NOW Cognitive Counseling Center. All rights reserved.</p>
                <p class="mb-0 fs-8 text-muted">Powered by Laravel 10, Bootstrap 5, Vue 3 & Jungian 8-Cognitive Function Engine.</p>
            </div>
        </footer>
    </div>
</body>
</html>
