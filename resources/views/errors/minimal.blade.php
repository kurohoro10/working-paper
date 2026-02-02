<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full">
                <!-- Error Code -->
                <div class="text-center mb-8">
                    <h1 class="text-6xl font-bold text-gray-800 mb-2">
                        @yield('code')
                    </h1>
                    <p class="text-xl font-semibold text-gray-700">
                        @yield('message')
                    </p>
                </div>

                <!-- Error Description (Optional) -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <p class="text-gray-600 text-center">
                        @hasSection('description')
                            @yield('description')
                        @else
                            The page you are looking for could not be found.
                        @endif
                    </p>
                </div>

                <!-- Back Button -->
                <div class="text-center">
                    <a href="{{ url('dashboard') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Go to Home
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
