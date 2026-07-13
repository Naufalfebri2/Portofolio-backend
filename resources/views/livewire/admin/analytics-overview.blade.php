<?php

use Livewire\Component;
use App\Models\PageView;
use Illuminate\Support\Carbon;

new class extends Component {
    public int $range = 7;

    public array $rangeOptions = [7, 30, 90];

    public function getStatsProperty(): array
    {
        $start = Carbon::now()
            ->subDays($this->range - 1)
            ->startOfDay();
        $views = PageView::where('created_at', '>=', $start)->get();

        // Total visits & unique visitors
        $totalVisits = $views->count();
        $uniqueVisitors = $views->pluck('session_id')->unique()->count();

        // Average duration (only entries with a recorded duration)
        $withDuration = $views->whereNotNull('duration_seconds');
        $avgDuration = $withDuration->isNotEmpty() ? round($withDuration->avg('duration_seconds')) : 0;

        // Visits per day (for the bar chart)
        $dailyCounts = [];
        for ($i = $this->range - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $dailyCounts[$date] = 0;
        }
        foreach ($views as $view) {
            $date = $view->created_at->toDateString();
            if (isset($dailyCounts[$date])) {
                $dailyCounts[$date]++;
            }
        }
        $maxDaily = !empty($dailyCounts) ? max($dailyCounts) : 0;

        // Most visited pages
        $topPages = $views->groupBy('path')->map(fn($group) => $group->count())->sortDesc()->take(5);

        // Pages with the longest average duration
        $longestPages = $views->whereNotNull('duration_seconds')->groupBy('path')->map(fn($group) => round($group->avg('duration_seconds')))->sortDesc()->take(5);

        return [
            'totalVisits' => $totalVisits,
            'uniqueVisitors' => $uniqueVisitors,
            'avgDuration' => $avgDuration,
            'dailyCounts' => $dailyCounts,
            'maxDaily' => $maxDaily,
            'topPages' => $topPages,
            'longestPages' => $longestPages,
        ];
    }

    public function setRange(int $range): void
    {
        $this->range = $range;
    }
};
?>

<div>
    {{-- Date range filter --}}
    <div class="flex items-center gap-2 mb-6">
        @foreach ($rangeOptions as $option)
            <button wire:click="setRange({{ $option }})"
                class="px-3 py-1.5 text-sm rounded-lg transition {{ $range === $option ? 'bg-accent-500/10 text-accent-400 border border-accent-500/40' : 'text-gray-400 hover:text-white hover:bg-gray-800 border border-transparent' }}">
                {{ $option }} Days
            </button>
        @endforeach
    </div>

    {{-- Summary stats --}}
    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-3">
        <div class="p-5 border rounded-xl border-gray-800 bg-gray-900/50">
            <p class="mb-1 text-sm text-gray-400">Total Visits</p>
            <p class="text-2xl font-bold text-white">{{ $this->stats['totalVisits'] }}</p>
        </div>
        <div class="p-5 border rounded-xl border-gray-800 bg-gray-900/50">
            <p class="mb-1 text-sm text-gray-400">Unique Visitors</p>
            <p class="text-2xl font-bold text-white">{{ $this->stats['uniqueVisitors'] }}</p>
        </div>
        <div class="p-5 border rounded-xl border-gray-800 bg-gray-900/50">
            <p class="mb-1 text-sm text-gray-400">Average Duration</p>
            <p class="text-2xl font-bold text-white">{{ $this->stats['avgDuration'] }}<span
                    class="text-base font-normal text-gray-500">s</span></p>
        </div>
    </div>

    {{-- Traffic chart --}}
    <div class="p-6 mb-8 border rounded-xl border-gray-800 bg-gray-900/50">
        <h3 class="mb-6 text-sm font-semibold text-white">Traffic for the Last {{ $range }} Days</h3>

        @if ($this->stats['maxDaily'] === 0)
            <p class="py-10 text-sm text-center text-gray-500">No visit data for this range yet.</p>
        @else
            <div class="flex items-end gap-1 h-40">
                @foreach ($this->stats['dailyCounts'] as $date => $count)
                    @php
                        $heightPercent =
                            $this->stats['maxDaily'] > 0
                                ? max(($count / $this->stats['maxDaily']) * 100, $count > 0 ? 6 : 0)
                                : 0;
                    @endphp
                    <div class="relative flex-1 h-full group">
                        <div class="absolute bottom-0 left-0 right-0 mx-auto transition-all rounded-t bg-accent-500/70 group-hover:bg-accent-400"
                            style="height: {{ $heightPercent }}%; width: 70%; margin: 0 auto;">
                        </div>
                        <div
                            class="absolute hidden px-2 py-1 text-xs text-white -translate-x-1/2 bg-gray-800 border border-gray-700 rounded-lg bottom-full left-1/2 mb-1 whitespace-nowrap group-hover:block">
                            {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('d M') }}: {{ $count }}
                            visits
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-600">
                <span>{{ \Illuminate\Support\Carbon::parse(array_key_first($this->stats['dailyCounts']))->translatedFormat('d M') }}</span>
                <span>{{ \Illuminate\Support\Carbon::parse(array_key_last($this->stats['dailyCounts']))->translatedFormat('d M') }}</span>
            </div>
        @endif
    </div>

    {{-- Top pages --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="p-6 border rounded-xl border-gray-800 bg-gray-900/50">
            <h3 class="mb-4 text-sm font-semibold text-white">Most Visited Pages</h3>
            @if ($this->stats['topPages']->isEmpty())
                <p class="text-sm text-gray-500">No data yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($this->stats['topPages'] as $path => $count)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-300 truncate">{{ $path === '/' ? 'Home' : $path }}</span>
                            <span class="ml-3 font-medium text-white shrink-0">{{ $count }}x</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="p-6 border rounded-xl border-gray-800 bg-gray-900/50">
            <h3 class="mb-4 text-sm font-semibold text-white">Pages with the Longest Duration</h3>
            @if ($this->stats['longestPages']->isEmpty())
                <p class="text-sm text-gray-500">No data yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($this->stats['longestPages'] as $path => $avgDuration)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-300 truncate">{{ $path === '/' ? 'Home' : $path }}</span>
                            <span class="ml-3 font-medium text-white shrink-0">{{ $avgDuration }}s</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
