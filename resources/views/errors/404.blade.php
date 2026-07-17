<x-layouts.portfolio title="Page Not Found — Naufal Febriansyah">

    <section class="min-h-[70vh] flex flex-col items-center justify-center text-center px-6 py-24">
        <p class="text-sm font-medium text-accent-500 dark:text-accent-400 mb-4">404</p>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Page <span class="bg-gradient-accent bg-clip-text text-transparent">Not Found</span>
        </h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-10 leading-relaxed">
            The page you're looking for doesn't exist or may have been moved.
        </p>

        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 px-6 py-3 font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <path d="M9 22V12h6v10" />
                </svg>
                Back to Home
            </a>
            <a href="{{ route('projects.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 text-gray-900 dark:text-white transition border border-gray-300 dark:border-gray-700 rounded-lg btn-scale hover:border-gray-500">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                View Projects
            </a>
        </div>
    </section>

</x-layouts.portfolio>
