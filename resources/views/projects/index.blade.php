<x-layouts.portfolio title="Projects — Naufal Febriansyah"
    description="A collection of projects by Naufal Febriansyah: KLolain (SaaS invoice & POS), Employee Attendance App (Flutter), and other solo web projects.">

    <section class="max-w-6xl px-4 py-16 mx-auto sm:px-6 lg:px-8 fade-in-up">
        <h1 class="mb-2 text-3xl font-bold text-white">All Projects</h1>
        <p class="mb-10 text-gray-400">A collection of projects I've worked on, both solo and as part of a team.</p>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @foreach ($projects as $project)
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
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-white">{{ $project->title }}</h3>
                            <span
                                class="text-xs px-2 py-1 rounded {{ $project->type === 'team' ? 'bg-blue-900 text-blue-300' : 'bg-gray-800 text-gray-400' }}">
                                {{ $project->type === 'team' ? 'Team' : 'Solo' }}
                            </span>
                        </div>
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

        <div class="mt-10">
            {{ $projects->links() }}
        </div>
    </section>

</x-layouts.portfolio>
