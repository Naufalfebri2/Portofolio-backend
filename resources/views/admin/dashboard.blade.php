<x-layouts.admin title="Dashboard">

    <h1 class="mb-6 text-2xl font-bold text-white">Dashboard</h1>

    <div x-data="{ tab: 'overview' }">
        {{-- Tab switcher --}}
        <div class="inline-flex gap-1 p-1 mb-8 border border-gray-800 rounded-lg bg-gray-900/50">
            <button @click="tab = 'overview'"
                :class="tab === 'overview' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white'"
                class="px-4 py-1.5 text-sm rounded-md transition">
                Overview
            </button>
            <button @click="tab = 'analytics'"
                :class="tab === 'analytics' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white'"
                class="px-4 py-1.5 text-sm rounded-md transition">
                Analytics
            </button>
        </div>

        {{-- Tab: Overview --}}
        <div x-show="tab === 'overview'">
            <div class="grid grid-cols-1 gap-4 mb-10 sm:grid-cols-2 lg:grid-cols-4">

                <div class="p-5 border border-gray-800 rounded-xl bg-gray-900/50">
                    <div class="flex items-start justify-between mb-3">
                        <p class="text-sm text-gray-400">Total Projects</p>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                            <path
                                d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z" />
                        </svg>
                    </div>
                    <p class="mb-1 text-3xl font-bold text-white">{{ $totalProjects }}</p>
                    <p class="text-xs text-gray-500">{{ $featuredProjects }} marked as featured</p>
                </div>

                <div class="p-5 border border-gray-800 rounded-xl bg-gray-900/50">
                    <div class="flex items-start justify-between mb-3">
                        <p class="text-sm text-gray-400">Unread Messages</p>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                            <path d="M22 7 13.03 12.7a2 2 0 0 1-2.06 0L2 7" />
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                        </svg>
                    </div>
                    <p class="mb-1 text-3xl font-bold text-accent-400">{{ $unreadMessages }}</p>
                    <p class="text-xs text-gray-500">out of {{ $totalMessages }} total messages</p>
                </div>

                <div class="p-5 border border-gray-800 rounded-xl bg-gray-900/50">
                    <div class="flex items-start justify-between mb-3">
                        <p class="text-sm text-gray-400">Total Technologies</p>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                            <rect x="4" y="4" width="16" height="16" rx="2" />
                            <rect x="9" y="9" width="6" height="6" />
                            <path d="M15 2v2M9 2v2M15 20v2M9 20v2M20 15h2M20 9h2M2 15h2M2 9h2" />
                        </svg>
                    </div>
                    <p class="mb-1 text-3xl font-bold text-white">{{ $totalTechnologies }}</p>
                    <p class="text-xs text-gray-500">registered in the system</p>
                </div>

                <div class="p-5 border border-gray-800 rounded-xl bg-gray-900/50">
                    <div class="flex items-start justify-between mb-3">
                        <p class="text-sm text-gray-400">CV Downloads</p>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <path d="M7 10l5 5 5-5" />
                            <path d="M12 15V3" />
                        </svg>
                    </div>
                    <p class="mb-1 text-3xl font-bold text-white">{{ $resumeDownloads }}</p>
                    <p class="text-xs text-gray-500">since first recorded</p>
                </div>

            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.projects.index') }}"
                    class="px-5 py-2.5 font-medium text-sm text-gray-950 transition rounded-lg bg-gradient-accent hover:opacity-90">
                    Manage Projects
                </a>
                <a href="{{ route('admin.messages.index') }}"
                    class="px-5 py-2.5 text-sm text-white transition bg-gray-800 rounded-lg hover:bg-gray-700">
                    View Inbox
                </a>
            </div>
        </div>

        {{-- Tab: Analytics --}}
        <div x-show="tab === 'analytics'" x-cloak>
            <livewire:admin.analytics-overview />
        </div>
    </div>

</x-layouts.admin>
