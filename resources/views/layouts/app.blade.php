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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
