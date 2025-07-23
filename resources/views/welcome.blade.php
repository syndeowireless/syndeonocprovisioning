<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Syndeo</title>
        <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm.ico') }}">
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />

        <!-- Vite -->
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <h1 class="text-6xl font-bold text-gray-800 dark:text-white">Syndeo Noc</h1>
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="ml-4 text-lg leading-7 font-semibold">
                                    <a href="{{ url('/login') }}" class="underline text-gray-900 dark:text-white">Login</a>
                                </div>
                            </div>

                            <div class="ml-12">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    Good to have you back! login to continue where you was.
                                </div>
                            </div>
                        </div>

                        <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                            <div class="flex items-center">
                                <div class="ml-4 text-lg leading-7 font-semibold">
                                    <a href="{{ url('/register') }}" class="underline text-gray-900 dark:text-white">Register</a>
                                </div>
                            </div>

                            <div class="ml-12">
                                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                                    Register and start using our service.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 sm:text-left">
                        Syndeo NOC v0.01  (PHP v{{ PHP_VERSION }})

                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
