<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Naufal Febriansyah — Portfolio' }}</title>
    <meta name="description"
        content="{{ $description ?? 'Portfolio of Naufal Febriansyah, Backend Developer & Information Systems Student. Building SaaS systems and business applications with Laravel, PostgreSQL, and Flutter.' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Social Share --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Naufal Febriansyah — Portfolio' }}">
    <meta property="og:description"
        content="{{ $description ?? 'Portfolio of Naufal Febriansyah, Backend Developer & Information Systems Student.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-default.png') }}">
    <meta property="og:site_name" content="Naufal Febriansyah Portfolio">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Naufal Febriansyah — Portfolio' }}">
    <meta name="twitter:description"
        content="{{ $description ?? 'Portfolio of Naufal Febriansyah, Backend Developer & Information Systems Student.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-default.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased text-gray-100 bg-gray-950" x-data>

    <nav class="sticky top-0 z-50 border-b border-gray-800 bg-gray-950/80 backdrop-blur">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-lg font-bold text-white">
                    Naufal Febriansyah<span class="bg-gradient-accent bg-clip-text text-transparent">.</span>
                </a>

                <div class="hidden space-x-8 sm:flex">
                    <a href="{{ route('home') }}"
                        class="nav-underline text-sm {{ request()->routeIs('home') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-300 hover:text-white' }}">Home</a>
                    <a href="{{ route('about') }}"
                        class="nav-underline text-sm {{ request()->routeIs('about') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-300 hover:text-white' }}">About</a>
                    <a href="{{ route('projects.index') }}"
                        class="nav-underline text-sm {{ request()->routeIs('projects.*') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-300 hover:text-white' }}">Projects</a>
                    <a href="{{ route('contact') }}"
                        class="nav-underline text-sm {{ request()->routeIs('contact') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-300 hover:text-white' }}">Contact</a>
                </div>

                <a href="{{ route('resume.download') }}"
                    class="px-4 py-2 text-sm font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                    Download CV
                </a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-gray-800">
        <div class="max-w-6xl px-4 py-8 mx-auto text-sm text-center text-gray-500 sm:px-6 lg:px-8">
            &copy; {{ date('Y') }} Naufal Febriansyah. Built with Laravel & Livewire.
        </div>
    </footer>

    <script>
        function initFadeInObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });

            document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
        }

        document.addEventListener('DOMContentLoaded', initFadeInObserver);
        document.addEventListener('livewire:navigated', initFadeInObserver);
    </script>

    @livewireScripts
</body>

</html>
