<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>認知轉化諮商中心 - {{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
          },
        },
      }
    </script>

    <!-- Scripts & Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex flex-col">
    <div id="app" class="flex-1 flex flex-col">
        <!-- 導覽列 Navbar -->
        <nav class="bg-slate-900/70 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 text-white font-bold text-lg">
                    <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm shadow-md">🧠</span>
                    <span class="tracking-wide">Cognitive Counseling</span>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-white transition">首頁</a>
                    @auth
                        <a href="{{ route('home') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold">主控台</a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs text-slate-300 hover:text-white">登入</a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-sm transition">註冊</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Vue 主掛載區域 -->
        <main class="flex-1 py-8 px-4">
            <chat-interface></chat-interface>
        </main>

        <!-- 頁尾 Footer -->
        <footer class="bg-slate-900/40 border-t border-slate-800/80 py-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} IU Cognitive Counseling Center. Powered by Laravel 10 & Vue 3.
        </footer>
    </div>
</body>
</html>
