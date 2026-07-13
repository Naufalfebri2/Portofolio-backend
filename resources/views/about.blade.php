<x-layouts.portfolio title="About Me — Naufal Febriansyah"
    description="6th-semester Information Systems student at Universitas Pamulang with experience building multi-role SaaS systems and Flutter mobile apps. Learn more about my background and skills.">

    <section class="max-w-4xl px-4 py-16 mx-auto sm:px-6 lg:px-8 fade-in-up">

        <h1 class="mb-8 text-3xl font-bold text-white">About Me</h1>

        @if ($profile)
            <div class="flex flex-col gap-8 mb-10 sm:flex-row">
                <div class="shrink-0">
                    @if ($profile->photo)
                        <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $profile->name }}"
                            class="object-cover w-32 h-32 border-2 rounded-full hover-lift border-accent-500/60">
                    @else
                        <div
                            class="flex items-center justify-center w-32 h-32 text-3xl font-bold text-gray-500 bg-gray-800 rounded-full hover-lift">
                            {{ substr($profile->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div>
                    <h2 class="mb-3 text-xl font-semibold text-white">{{ $profile->name }}</h2>
                    <p class="mb-4 leading-relaxed text-gray-300">{{ $profile->bio }}</p>

                    <div class="flex flex-wrap gap-3 text-sm">
                        @if ($profile->email)
                            <a href="mailto:{{ $profile->email }}"
                                class="btn-scale bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg transition">
                                {{ $profile->email }}
                            </a>
                        @endif
                        @if ($profile->phone)
                            <span class="bg-gray-800 text-gray-300 px-3 py-1.5 rounded-lg">
                                {{ $profile->phone }}
                            </span>
                        @endif
                        @if ($profile->github_url)
                            <a href="{{ $profile->github_url }}" target="_blank"
                                class="btn-scale bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg transition">
                                GitHub
                            </a>
                        @endif
                        @if ($profile->linkedin_url)
                            <a href="{{ $profile->linkedin_url }}" target="_blank"
                                class="btn-scale bg-gray-800 hover:bg-gray-700 text-gray-300 px-3 py-1.5 rounded-lg transition">
                                LinkedIn
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-800">
                <a href="{{ route('resume.download') }}"
                    class="inline-block px-6 py-3 font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                    Download CV
                </a>
            </div>
        @else
            <p class="text-gray-500">Profile not filled in yet. Please complete it from the admin panel.</p>
        @endif

    </section>

</x-layouts.portfolio>
