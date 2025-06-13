<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FreshGrocer') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    <!-- Centered Wrapper -->
    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <!-- Logo or Header -->
        <div class="mb-6">
            <a href="/" class="text-2xl font-bold text-green-600">Online Grocery Store</a>
        </div>

        <!-- Main Content Box -->
        <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-6">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} FreshGrocer. All rights reserved.
        </div>
    </div>

</body>
</html>
