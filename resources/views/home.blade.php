<x-layouts.portfolio title="Naufal Febriansyah — Backend Developer & Information Systems Student"
    description="Portfolio of Naufal Febriansyah, a Backend Developer focused on building SaaS systems and business applications using Laravel & PostgreSQL. Check out projects like Kelolain, Employee Attendance App, and more.">

    {{-- Hero Section --}}
    <section
        class="relative flex flex-col items-center justify-center max-w-4xl px-4 pt-20 pb-16 mx-auto text-center sm:px-6 lg:px-8 hero-fade">

        @if ($profile && $profile->photo)
            <div
                class="relative w-32 h-32 mb-5 overflow-hidden border-2 rounded-full md:w-40 md:h-40 border-accent-500/40">
                <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $profile->name }}"
                    class="object-cover w-full h-full">
            </div>
        @endif

        @if ($profile && $profile->location)
            <div class="flex items-center gap-1.5 text-sm text-gray-400 mb-6">
                <svg class="text-accent-400" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
                <span>{{ $profile->location }}</span>
            </div>
        @endif

        <h1 class="mb-6 text-4xl font-bold leading-tight text-white sm:text-6xl">
            Backend <span class="bg-gradient-accent bg-clip-text text-transparent">Developer</span>
        </h1>

        <p class="max-w-2xl mx-auto mb-10 leading-relaxed text-gray-400">
            Hi, I'm <span class="font-semibold text-white">{{ $profile->name ?? 'Naufal Febriansyah' }}</span>,
            {{ $profile->bio ?? 'a Backend Developer focused on building SaaS systems and business applications using Laravel & PostgreSQL.' }}
        </p>

        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('projects.index') }}"
                class="px-6 py-3 font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                View Projects
            </a>
            <a href="{{ route('contact') }}"
                class="px-6 py-3 text-white transition border border-gray-700 rounded-lg btn-scale hover:border-gray-500">
                Contact Me
            </a>
        </div>

        @if ($profile && ($profile->github_url || $profile->linkedin_url))
            <div class="fixed z-20 flex-col hidden gap-4 -translate-y-1/2 lg:flex right-8 top-1/2">
                @if ($profile->github_url)
                    <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" aria-label="GitHub"
                        class="text-gray-500 transition-colors hover:text-accent-400">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.57.1.78-.25.78-.55 0-.27-.01-1.17-.02-2.12-3.2.7-3.88-1.36-3.88-1.36-.52-1.33-1.28-1.68-1.28-1.68-1.05-.72.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.73-1.55-2.55-.29-5.24-1.28-5.24-5.68 0-1.25.45-2.28 1.19-3.08-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.18 1.18a11.05 11.05 0 0 1 5.79 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.24 2.76.12 3.05.74.8 1.19 1.83 1.19 3.08 0 4.41-2.69 5.38-5.25 5.67.41.36.78 1.06.78 2.14 0 1.55-.01 2.79-.01 3.17 0 .3.2.66.79.55A10.52 10.52 0 0 0 23.5 12c0-6.35-5.15-11.5-11.5-11.5Z" />
                        </svg>
                    </a>
                @endif
                @if ($profile->linkedin_url)
                    <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                        aria-label="LinkedIn" class="text-gray-500 transition-colors hover:text-accent-400">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.15 1.45-2.15 2.94v5.67H9.35V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45Z" />
                        </svg>
                    </a>
                @endif
            </div>
        @endif

    </section>


    {{-- Experience Section --}}
    <section id="tentang" class="max-w-4xl px-4 py-16 mx-auto sm:px-6 lg:px-8 fade-in-up">
        <div class="mb-16 text-center">
            <p class="mb-2 text-sm font-medium text-accent-400">Behind The Scenes</p>
            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                Experience <span class="bg-gradient-accent bg-clip-text text-transparent">Built</span>
            </h2>
        </div>

        @php
            $timeline = [
                [
                    'period' => '2025 — Present',
                    'role' => 'Backend Developer',
                    'org' => 'Kelolain · Project Team',
                    'description' =>
                        'Built 100+ REST API endpoints for a Laravel 12 + Sanctum + PostgreSQL SaaS platform targeting Indonesian SMEs. Developed the Auth, Product, Invoice, and Owner/User Dashboard modules, a Help Center system, soft deletes across 10 tables, an SEO/Marketing module, user management for Owners, and an automated daily backup system with a custom Artisan command and Laravel Scheduler.',
                    'tags' => ['Laravel 12', 'Sanctum', 'PostgreSQL', 'REST API'],
                ],
                [
                    'period' => '2024 — 2025',
                    'role' => 'Front-End Developer',
                    'org' => 'Employee Attendance App · Project Team',
                    'description' =>
                        'Developed a mobile employee attendance app using Flutter with a Clean Architecture approach and BLoC state management, ensuring a structured and scalable data flow.',
                    'tags' => ['Flutter', 'Clean Architecture', 'BLoC'],
                ],
                [
                    'period' => '2023 — 2024',
                    'role' => 'Web Developer (Solo)',
                    'org' => "HvnCake's & KopiKita",
                    'description' =>
                        'Designed and built F&B e-commerce landing pages from scratch using pure HTML, CSS, and JavaScript, complete with a WhatsApp checkout flow to streamline customer transactions.',
                    'tags' => ['HTML/CSS', 'JavaScript', 'WhatsApp Checkout'],
                ],
            ];
        @endphp

        <div class="relative pl-10">
            <div class="absolute left-[7px] top-0 bottom-0 w-px bg-gray-800"></div>

            @foreach ($timeline as $item)
                <div class="relative mb-14 last:mb-0">
                    <span
                        class="absolute -left-10 top-1.5 w-3.5 h-3.5 rounded-full bg-accent-500 shadow-[0_0_12px_2px_rgba(99,102,241,0.5)]"></span>

                    <p class="mb-1 text-sm text-gray-500">{{ $item['period'] }}</p>
                    <h3 class="mb-0.5 text-xl font-semibold text-white">{{ $item['role'] }}</h3>
                    <p class="mb-3 text-sm text-accent-400">{{ $item['org'] }}</p>
                    <p class="max-w-2xl mb-4 leading-relaxed text-gray-400">{{ $item['description'] }}</p>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($item['tags'] as $tag)
                            <span
                                class="px-2.5 py-1 text-xs text-gray-400 border border-gray-800 rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Stacks Section --}}
    <section id="stacks" class="max-w-5xl px-4 py-16 mx-auto sm:px-6 lg:px-8 fade-in-up">
        <div class="mb-10 text-center">
            <p class="mb-2 text-sm font-medium text-accent-400">Built With Precision</p>
            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                Core <span class="bg-gradient-accent bg-clip-text text-transparent">Stack</span>
            </h2>
        </div>

        @php
            $categoryOrder = ['Backend', 'Frontend', 'Mobile & Tools'];
            $iconMap = [
                'Laravel 12' => ['slug' => 'laravel', 'color' => 'FF2D20'],
                'Laravel' => ['slug' => 'laravel', 'color' => 'FF2D20'],
                'Laravel Sanctum' => ['slug' => 'laravel', 'color' => 'FF2D20'],
                'PHP 8.3' => ['slug' => 'php', 'color' => '777BB4'],
                'PHP' => ['slug' => 'php', 'color' => '777BB4'],
                'PostgreSQL' => ['slug' => 'postgresql', 'color' => '4169E1'],
                'MySQL' => ['slug' => 'mysql', 'color' => '4479A1'],
                'Livewire' => ['slug' => 'livewire', 'color' => 'FB70A9'],
                'HTML5' => ['slug' => 'html5', 'color' => 'E34F26'],
                'CSS3' => ['slug' => 'css', 'color' => '1572B6'],
                'JavaScript' => ['slug' => 'javascript', 'color' => 'F7DF1E'],
                'TypeScript' => ['slug' => 'typescript', 'color' => '3178C6'],
                'Tailwind CSS' => ['slug' => 'tailwindcss', 'color' => '06B6D4'],
                'Next.js' => ['slug' => 'nextdotjs', 'color' => 'FFFFFF'],
                'React' => ['slug' => 'react', 'color' => '61DAFB'],
                'Flutter' => ['slug' => 'flutter', 'color' => '02569B'],
                'Git' => ['slug' => 'git', 'color' => 'F05032'],
                'GitHub' => ['slug' => 'github', 'color' => 'FFFFFF'],
                'VS Code' => ['slug' => 'visualstudiocode', 'color' => '007ACC'],
            ];
        @endphp

        <div class="space-y-10">
            @foreach ($categoryOrder as $categoryName)
                @if (isset($stacks[$categoryName]) && $stacks[$categoryName]->isNotEmpty())
                    <div>
                        <p class="mb-4 text-sm text-center text-gray-500 md:text-left">{{ $categoryName }}</p>
                        <div class="flex flex-wrap justify-center gap-3 md:justify-start">
                            @foreach ($stacks[$categoryName] as $tech)
                                @php $iconData = $iconMap[$tech->name] ?? null; @endphp
                                <div
                                    class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-gray-800 bg-gray-900/40 hover:border-accent-500/60 transition-colors">
                                    @if ($iconData)
                                        <img src="https://cdn.simpleicons.org/{{ $iconData['slug'] }}/{{ $iconData['color'] }}"
                                            alt="{{ $tech->name }}" class="w-[18px] h-[18px]" loading="lazy">
                                    @endif
                                    <span class="text-sm text-gray-300">{{ $tech->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    {{-- Featured Projects --}}
    <section id="project" class="max-w-5xl px-4 py-16 mx-auto sm:px-6 lg:px-8 fade-in-up">
        <div class="mb-10 text-center">
            <p class="mb-2 text-sm font-medium text-accent-400">From Idea to System</p>
            <h2 class="text-3xl font-bold text-white sm:text-4xl">
                Featured <span class="bg-gradient-accent bg-clip-text text-transparent">Projects</span>
            </h2>
        </div>

        @if ($featuredProjects->isEmpty())
            <p class="text-center text-gray-500">No featured projects yet. Add one from the admin panel.</p>
        @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <a href="{{ route('projects.show', $project->slug) }}"
                        class="block overflow-hidden transition bg-gray-900 border border-gray-800 hover-lift rounded-xl hover:border-accent-500/60">
                        @if ($project->thumbnail)
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                                class="object-cover object-top w-full h-40">
                        @else
                            <div class="flex items-center justify-center w-full h-40 text-gray-600 bg-gray-800">No Image
                            </div>
                        @endif
                        <div class="p-5">
                            <h3 class="mb-2 font-semibold text-white">{{ $project->title }}</h3>
                            <p class="mb-3 text-sm text-gray-400 line-clamp-2">{{ $project->description }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($project->technologies->take(3) as $tech)
                                    <span
                                        class="px-2 py-1 text-xs text-gray-300 bg-gray-800 rounded">{{ $tech->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Contact CTA Section --}}
    <section class="max-w-4xl px-4 py-16 mx-auto text-center sm:px-6 lg:px-8 fade-in-up">
        <div class="p-10 border rounded-2xl border-gray-800 bg-gradient-to-br from-accent-500/10 to-cyan-400/10">
            <p class="mb-2 text-sm font-medium text-accent-400">Have a Backend Project?</p>
            <h2 class="mb-4 text-2xl font-bold text-white sm:text-3xl">
                Let's Discuss Your System Requirements
            </h2>
            <p class="max-w-lg mx-auto mb-8 leading-relaxed text-gray-400">
                Open to internship opportunities, project collaborations, or just chatting about backend & system
                architecture.
            </p>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 px-6 py-3 font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                Contact Me
            </a>
        </div>
    </section>

</x-layouts.portfolio>
