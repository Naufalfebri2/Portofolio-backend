<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Naufal Febriansyah') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#0a0e14] text-gray-200">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">

        <a href="{{ url('/') }}" class="mb-6 text-xl font-bold text-white hover:text-emerald-400 transition">
            Naufal Febriansyah<span class="text-emerald-400">.</span>
        </a>

        <div class="w-full sm:max-w-md bg-[#0d1117] border border-gray-800 rounded-xl shadow-xl px-6 py-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-xs text-gray-500">
            &copy; {{ date('Y') }} Naufal Febriansyah. Built with Laravel &amp; Livewire.
        </p>
    </div>
</body>

</html>
