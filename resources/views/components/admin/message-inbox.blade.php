<?php

use Livewire\Component;
use App\Models\Message;

new class extends Component {
    public $messages;
    public ?int $selectedId = null;

    public function mount(): void
    {
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        $this->messages = Message::orderByDesc('created_at')->get();
    }

    public function openMessage(int $id): void
    {
        $this->selectedId = $id;

        $message = Message::findOrFail($id);
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
            $this->loadMessages();
        }
    }

    public function closeDetail(): void
    {
        $this->selectedId = null;
    }

    public function delete(int $id): void
    {
        Message::findOrFail($id)->delete();

        if ($this->selectedId === $id) {
            $this->selectedId = null;
        }

        $this->loadMessages();
        session()->flash('success', 'Message deleted successfully.');
    }

    public function getSelectedMessageProperty()
    {
        return $this->selectedId ? Message::find($this->selectedId) : null;
    }
};
?>

<div>
    @if (session('success'))
        <div class="p-3 mb-6 text-sm border rounded-lg bg-accent-900/30 border-accent-500/40 text-accent-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

        {{-- Message list --}}
        <div class="overflow-hidden border border-gray-800 bg-gray-900/50 lg:col-span-2 rounded-xl">
            <div class="divide-y divide-gray-800 max-h-[70vh] overflow-y-auto">
                @forelse($messages as $msg)
                    @php
                        $initials = collect(explode(' ', $msg->name))
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp
                    <button wire:click="openMessage({{ $msg->id }})" wire:key="msg-{{ $msg->id }}"
                        class="w-full text-left px-4 py-3 hover:bg-gray-800/40 transition {{ $selectedId === $msg->id ? 'bg-gray-800/60' : '' }}">
                        <div class="flex items-start gap-3">
                            <span
                                class="flex items-center justify-center text-xs font-medium rounded-full w-9 h-9 shrink-0 bg-accent-500/10 text-accent-400">
                                {{ $initials }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5 gap-2">
                                    <span class="text-sm font-medium text-white truncate">{{ $msg->name }}</span>
                                    @if (!$msg->is_read)
                                        <span
                                            class="text-xs border border-accent-500/40 text-accent-400 px-2 py-0.5 rounded-full font-medium shrink-0">New</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 truncate">{{ $msg->subject ?: '(No subject)' }}</p>

                                {{-- NEW: event_date badge --}}
                                @if ($msg->event_date)
                                    <p class="flex items-center gap-1 mt-1 text-xs text-accent-400">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <path d="M16 2v4M8 2v4M3 10h18" />
                                        </svg>
                                        {{ $msg->event_date->format('d M Y') }}
                                    </p>
                                @endif

                                <p class="mt-1 text-xs text-gray-600">{{ $msg->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="px-4 py-10 text-sm text-center text-gray-500">No messages yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Message detail --}}
        <div class="p-6 border border-gray-800 bg-gray-900/50 lg:col-span-3 rounded-xl">
            @if ($this->selectedMessage)
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-white">
                            {{ $this->selectedMessage->subject ?: '(No subject)' }}</h3>
                        <p class="mt-1 text-sm text-gray-400">
                            {{ $this->selectedMessage->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <button wire:click="delete({{ $this->selectedMessage->id }})"
                        wire:confirm="Are you sure you want to delete this message?"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-gray-800 transition">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18" />
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 mb-4 space-y-1 text-sm border border-gray-800 rounded-lg bg-gray-800/30">
                    <p><span class="text-gray-500">Name:</span> <span
                            class="text-white">{{ $this->selectedMessage->name }}</span></p>
                    <p><span class="text-gray-500">Email:</span> <a href="mailto:{{ $this->selectedMessage->email }}"
                            class="text-accent-400 hover:underline">{{ $this->selectedMessage->email }}</a></p>
                    @if ($this->selectedMessage->company)
                        <p><span class="text-gray-500">Company:</span> <span
                                class="text-white">{{ $this->selectedMessage->company }}</span></p>
                    @endif
                    {{-- NEW: event_date in detail --}}
                    @if ($this->selectedMessage->event_date)
                        <p><span class="text-gray-500">Interview/Meeting Date:</span> <span
                                class="text-white">{{ $this->selectedMessage->event_date->format('d F Y') }}</span>
                        </p>
                    @endif
                </div>

                <p class="leading-relaxed text-gray-300 whitespace-pre-line">{{ $this->selectedMessage->message }}</p>
            @else
                <div class="flex items-center justify-center h-full py-20 text-sm text-gray-500">
                    Select a message on the left to view its details
                </div>
            @endif
        </div>

    </div>
</div>
