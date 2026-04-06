<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LinkedIn Post Automation') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='48' fill='%230A66C2'/><g fill='white'><rect x='30' y='25' width='40' height='50' rx='2'/><line x1='37' y1='35' x2='63' y2='35' stroke='white' stroke-width='2' stroke-linecap='round'/><line x1='37' y1='45' x2='63' y2='45' stroke='white' stroke-width='2' stroke-linecap='round'/><line x1='37' y1='55' x2='55' y2='55' stroke='white' stroke-width='2' stroke-linecap='round'/><path d='M 70 45 L 75 40 L 75 50' stroke='white' stroke-width='2' fill='none' stroke-linecap='round' stroke-linejoin='round'/></g></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-2">
                   <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect width="48" height="48" rx="12" fill="#0A66C2"/>
  <rect x="11" y="13" width="19" height="24" rx="2" fill="white" fill-opacity="0.95"/>
  <line x1="15" y1="19" x2="26" y2="19" stroke="#0A66C2" stroke-width="1.8" stroke-linecap="round"/>
  <line x1="15" y1="23" x2="26" y2="23" stroke="#0A66C2" stroke-width="1.8" stroke-linecap="round"/>
  <line x1="15" y1="27" x2="22" y2="27" stroke="#0A66C2" stroke-width="1.8" stroke-linecap="round"/>
  <circle cx="34" cy="30" r="9" fill="#0A66C2" stroke="white" stroke-width="2"/>
  <path d="M30.5 30 L37.5 30 M34.5 26.5 L38 30 L34.5 33.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
  <circle cx="34" cy="30" r="9" fill="none" stroke="#5CB8FF" stroke-width="1" stroke-dasharray="4 3" opacity="0.7">
    <animateTransform attributeName="transform" type="rotate" from="0 34 30" to="360 34 30" dur="6s" repeatCount="indefinite"/>
  </circle>
</svg>
                    <!-- Title -->
                    <div class="text-left">
                        <div class="text-2xl font-bold text-blue-600">{{ config('app.name', 'LinkedIn Post Automation') }}</div>
                        <div class="text-xs text-gray-500">Automate Your LinkedIn Posts</div>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
