<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} — Naufal Febriansyah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased text-gray-100 bg-gray-950" x-data="{ sidebarOpen: false, collapsed: localStorage.getItem('adminSidebarCollapsed') === 'true' }" x-init="$watch('collapsed', value => localStorage.setItem('adminSidebarCollapsed', value))">

    @php
        $navItems = [
            [
                'route' => 'admin.dashboard',
                'active' => 'admin.dashboard',
                'label' => 'Dashboard',
                'icon' =>
                    '<rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>',
            ],
            [
                'route' => 'admin.projects.index',
                'active' => 'admin.projects.*',
                'label' => 'Projects',
                'icon' =>
                    '<path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/>',
            ],
            [
                'route' => 'admin.technologies.index',
                'active' => 'admin.technologies.*',
                'label' => 'Technologies',
                'icon' =>
                    '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M9 2v2M15 20v2M9 20v2M20 15h2M20 9h2M2 15h2M2 9h2"/>',
            ],
            [
                'route' => 'admin.messages.index',
                'active' => 'admin.messages.*',
                'label' => 'Inbox',
                'icon' =>
                    '<path d="M22 7 13.03 12.7a2 2 0 0 1-2.06 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>',
            ],
            [
                'route' => 'admin.profile.edit',
                'active' => 'admin.profile.*',
                'label' => 'Profile',
                'icon' =>
                    '<path d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="10"/>',
            ],
        ];

        $breadcrumbLabel =
            collect($navItems)->first(fn($item) => request()->routeIs($item['active']))['label'] ?? 'Admin';
    @endphp

    <div class="flex min-h-screen">

        {{-- Sidebar (desktop) --}}
        <aside :class="collapsed ? 'lg:w-[68px]' : 'lg:w-64'"
            class="flex-shrink-0 hidden transition-all duration-200 bg-gray-900 border-r border-gray-800 lg:flex lg:flex-col">

            <div class="flex items-center h-16 px-4 border-b border-gray-800"
                :class="collapsed ? 'justify-center' : 'justify-between'">
                <span x-show="!collapsed" class="text-sm font-bold text-white">Admin Panel</span>
                <button @click="collapsed = !collapsed"
                    class="p-1.5 rounded-lg text-gray-500 hover:text-white hover:bg-gray-800 transition">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        :class="collapsed && 'rotate-180'" class="transition-transform">
                        <path d="M11 17l-5-5 5-5M18 17l-5-5 5-5" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-6 space-y-1">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" :class="collapsed && 'justify-center px-0'"
                        title="{{ $item['label'] }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition {{ request()->routeIs($item['active']) ? 'bg-accent-500/10 text-accent-400' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                            {!! $item['icon'] !!}
                        </svg>
                        <span x-show="!collapsed" x-transition.opacity>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="p-3 space-y-1 border-t border-gray-800">
                <a href="{{ route('home') }}" target="_blank" :class="collapsed && 'justify-center px-0'"
                    title="View public site"
                    class="flex items-center gap-3 px-3 py-2 text-xs text-gray-500 transition rounded-lg hover:text-gray-300 hover:bg-gray-800">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                        <path d="M15 3h6v6" />
                        <path d="M10 14 21 3" />
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                    </svg>
                    <span x-show="!collapsed" x-transition.opacity>View public site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" :class="collapsed && 'justify-center px-0'" title="Logout"
                        class="flex items-center w-full gap-3 px-3 py-2 text-sm text-red-400 transition rounded-lg hover:bg-red-500/10">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="M16 17l5-5-5-5" />
                            <path d="M21 12H9" />
                        </svg>
                        <span x-show="!collapsed" x-transition.opacity>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile top bar --}}
        <div
            class="fixed top-0 left-0 right-0 z-40 flex items-center justify-between px-4 bg-gray-900 border-b border-gray-800 lg:hidden h-14">
            <span class="text-sm font-bold text-white">Admin Panel</span>
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-300">
                <svg x-show="!sidebarOpen" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 12h16M4 6h16M4 18h16" />
                </svg>
                <svg x-show="sidebarOpen" x-cloak width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile sidebar --}}
        <aside x-show="sidebarOpen" x-cloak
            class="fixed bottom-0 left-0 right-0 z-30 p-4 space-y-1 overflow-y-auto bg-gray-900 lg:hidden top-14">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs($item['active']) ? 'bg-accent-500/10 text-accent-400' : 'text-gray-300' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-800">
                @csrf
                <button type="submit" class="w-full px-3 py-2 text-sm text-left text-red-400">Logout</button>
            </form>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 lg:ml-0 pt-14 lg:pt-0">
            {{-- Topbar (desktop) --}}
            <div
                class="sticky top-0 z-20 items-center hidden h-16 px-8 border-b border-gray-800 lg:flex bg-gray-950/80 backdrop-blur">
                <nav class="flex items-center gap-2 text-sm">
                    <span class="text-gray-500">Admin</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" class="text-gray-700">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                    <span class="font-medium text-white">{{ $breadcrumbLabel }}</span>
                </nav>
            </div>

            <div class="p-6 lg:p-10">
                {{ $slot }}
            </div>
        </main>

    </div>

    @livewireScripts
</body>

</html>
