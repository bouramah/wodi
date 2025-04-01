<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .login-page {
                background-color: #f3f4f6;
            }
            .login-card {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .btn-login {
                background-color: #4f46e5;
            }
            .btn-login:hover {
                background-color: #4338ca;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased login-page">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white login-card shadow-md overflow-hidden sm:rounded-lg">
                <div class="flex justify-center mb-6">
                    <a href="/" class="flex flex-col items-center">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-600" />
                        <h1 class="mt-2 text-2xl font-bold text-gray-800">{{ config('app.name', 'Wodi') }}</h1>
                    </a>
                </div>

                {{ $slot }}
            </div>
            <br>
            <div class="mt-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Wodi') }}. {{ __('Tous droits réservés') }}</p>
            </div>
        </div>
    </body>
</html>
