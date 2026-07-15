<!DOCTYPE html>
<html lang="en" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' ||
        (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" x-init="$watch('darkMode', value => {
    localStorage.setItem('darkMode', value);
    document.documentElement.classList.toggle('dark', value);
})" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Set the dark class BEFORE Alpine loads and BEFORE first paint, so the
         page never flashes the wrong theme on load. Alpine's x-init above
         only runs after the DOM is parsed, which would otherwise cause a
         visible flash from light -> dark on every page load for users who
         have dark mode saved. --}}
    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

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

<body class="antialiased text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-950 transition-colors" x-data>

    <nav
        class="sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-lg font-bold text-gray-900 dark:text-white">
                    Naufal Febriansyah<span class="bg-gradient-accent bg-clip-text text-transparent">.</span>
                </a>

                <div class="items-center hidden space-x-8 sm:flex">
                    <a href="{{ route('home') }}"
                        class="nav-underline text-sm {{ request()->routeIs('home') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">Home</a>
                    <a href="{{ route('about') }}"
                        class="nav-underline text-sm {{ request()->routeIs('about') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">About</a>
                    <a href="{{ route('projects.index') }}"
                        class="nav-underline text-sm {{ request()->routeIs('projects.*') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">Projects</a>
                    <a href="{{ route('contact') }}"
                        class="nav-underline text-sm {{ request()->routeIs('contact') ? 'bg-gradient-accent bg-clip-text text-transparent font-medium active' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Theme toggle --}}
                    <button type="button" @click="darkMode = !darkMode"
                        :aria-label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        {{-- Sun icon (shown in dark mode, click to go light) --}}
                        <svg x-show="darkMode" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4" />
                            <path
                                d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                        </svg>
                        {{-- Moon icon (shown in light mode, click to go dark) --}}
                        <svg x-show="!darkMode" x-cloak width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                        </svg>
                    </button>

                    <a href="{{ route('resume.download') }}"
                        class="px-4 py-2 text-sm font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                        Download CV
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-gray-200 dark:border-gray-800">
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
