<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-4">Управляйте своими Telegram-ботами</h1>
                    <p class="text-lg mb-4">
                        Наш сервис позволяет легко управлять несколькими Telegram-ботами,
                        отслеживать подписчиков и делать рассылки.
                    </p>
                    @guest
                        <div class="mt-6">
                            <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Начать бесплатно
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
