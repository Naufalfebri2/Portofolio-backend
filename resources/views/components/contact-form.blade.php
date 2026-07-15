<?php

use Livewire\Component;
use App\Models\Message;
use App\Mail\NewContactMessage;
use Illuminate\Support\Facades\Mail;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $company = '';
    public string $subject = '';
    public string $event_date = '';
    public string $message = '';
    public bool $submitted = false;
    public string $whatsappUrl = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    protected function messages(): array
    {
        return [
            'event_date.required' => 'Please select a date.',
            'event_date.date' => 'Invalid date format.',
            'event_date.after_or_equal' => 'Date cannot be in the past.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $contactMessage = Message::create([
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'subject' => $this->subject,
            'event_date' => $this->event_date,
            'message' => $this->message,
            'is_read' => false,
        ]);

        Mail::to(config('mail.from.address'))->send(new NewContactMessage($contactMessage));

        $waNumber = '6281385230785';
        $waText = urlencode("Hi, I'm {$this->name}. I just sent you a message through your portfolio:\n\n\"{$this->message}\"");
        $this->whatsappUrl = "https://api.whatsapp.com/send/?phone={$waNumber}&text={$waText}";

        $this->reset(['name', 'email', 'company', 'subject', 'event_date', 'message']);
        $this->submitted = true;
    }
};
?>

<div>
    @if ($submitted)
        <div class="p-4 mb-6 border rounded-lg bg-accent-100 dark:bg-accent-900/30 border-accent-500/40 text-accent-700 dark:text-accent-300">
            <p class="mb-3">Thank you! Your message has been sent, I'll get back to you soon.</p>
            <a href="{{ $whatsappUrl }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 text-gray-950">
                Continue via WhatsApp
            </a>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label class="block mb-1 text-sm text-gray-600 dark:text-gray-400">Name</label>
            <input type="text" wire:model="name"
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-accent-500 focus:outline-none">
            @error('name')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-600 dark:text-gray-400">Email</label>
            <input type="email" wire:model="email"
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-accent-500 focus:outline-none">
            @error('email')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-600 dark:text-gray-400">Company (optional)</label>
            <input type="text" wire:model="company" placeholder="Your company / organization name"
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:border-accent-500 focus:outline-none">
            @error('company')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-600 dark:text-gray-400">Subject (optional)</label>
            <input type="text" wire:model="subject"
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-accent-500 focus:outline-none">
        </div>

        <div wire:ignore x-data="{
                date: @entangle('event_date'),
            }" x-init="
                flatpickr($refs.eventDateInput, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd F Y',
                    minDate: 'today',
                    defaultDate: date || null,
                    onChange: (selectedDates, dateStr) => { date = dateStr; },
                });
            ">
            <label class="block mb-1 text-sm text-gray-600 dark:text-gray-400">Interview / Meeting Date</label>
            <input type="text" x-ref="eventDateInput" placeholder="Select a date" readonly
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-accent-500 focus:outline-none cursor-pointer">
            @error('event_date')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-600 dark:text-gray-400">Message</label>
            <textarea wire:model="message" rows="5"
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-accent-500 focus:outline-none"></textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled"
            class="px-6 py-3 font-medium transition rounded-lg btn-scale bg-gradient-accent hover:opacity-90 disabled:opacity-50 text-gray-950">
            <span wire:loading.remove>Send Message</span>
            <span wire:loading>Sending...</span>
        </button>
    </form>
</div>