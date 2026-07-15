<x-layouts.portfolio :title="$project->title . ' — Naufal Febriansyah'" :description="\Illuminate\Support\Str::limit(strip_tags($project->description), 155)" :og-image="$project->thumbnail ? asset('storage/' . $project->thumbnail) : null">

    <section class="max-w-4xl px-4 py-16 mx-auto sm:px-6 lg:px-8 fade-in-up">

        <a href="{{ route('projects.index') }}"
            class="inline-block mb-6 text-sm text-gray-500 dark:text-gray-400 hover:text-accent-500 dark:hover:text-accent-400">
            &larr; Back to all projects
        </a>

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $project->title }}</h1>
            <span
                class="text-xs px-3 py-1 rounded {{ $project->type === 'team' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400' }}">
                {{ $project->type === 'team' ? 'Team Project' : 'Solo Project' }}
            </span>
        </div>

        @if ($project->role)
            <p class="mb-6 text-sm text-accent-500 dark:text-accent-400">Role: {{ $project->role }}</p>
        @endif

        @if ($project->thumbnail)
            <div class="max-w-full p-4 mx-auto mb-8 bg-gray-100 dark:bg-gray-900 w-fit rounded-xl">
                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"
                    class="block object-contain max-w-full rounded-lg max-h-96">
            </div>
        @endif

        <div class="mb-8 prose dark:prose-invert max-w-none">
            <p class="leading-relaxed text-gray-700 dark:text-gray-300">{{ $project->description }}</p>
        </div>

        <div class="mb-8">
            <h3 class="mb-3 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400">Tech Stack</h3>
            <div class="flex flex-wrap gap-2">
                @foreach ($project->technologies as $tech)
                    <span
                        class="text-sm bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg">{{ $tech->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="flex gap-4 mb-12">
            @if ($project->repo_url)
                <a href="{{ $project->repo_url }}" target="_blank"
                    class="btn-scale bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-5 py-2.5 rounded-lg transition text-sm font-medium">
                    View Source Code
                </a>
            @endif
            @if ($project->demo_url)
                <a href="{{ $project->demo_url }}" target="_blank"
                    class="btn-scale bg-gradient-accent hover:opacity-90 text-gray-950 px-5 py-2.5 rounded-lg transition text-sm font-medium">
                    View Demo
                </a>
            @endif
        </div>

        @if ($project->images->isNotEmpty())
            <div>
                <h3 class="mb-4 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400">Gallery</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($project->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->caption }}"
                            class="w-full border border-gray-300 rounded-lg dark:border-gray-800 hover-lift">
                    @endforeach
                </div>
            </div>
        @endif

    </section>

</x-layouts.portfolio>
