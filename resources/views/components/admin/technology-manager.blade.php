<?php

use Livewire\Component;
use App\Models\Technology;

new class extends Component {
    public $technologies;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public ?string $icon = '';
    public string $category = 'Backend';

    public array $categoryOptions = ['Backend', 'Frontend', 'Mobile & Tools'];

    public function mount(): void
    {
        $this->loadTechnologies();
    }

    public function loadTechnologies(): void
    {
        $this->technologies = Technology::withCount('projects')->orderBy('name')->get();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $tech = Technology::findOrFail($id);
        $this->editingId = $tech->id;
        $this->name = $tech->name;
        $this->icon = $tech->icon;
        $this->category = $tech->category ?? 'Backend';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->icon = '';
        $this->category = 'Backend';
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->editingId ? 'unique:technologies,name,' . $this->editingId : 'unique:technologies,name'],
            'icon' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:Backend,Frontend,Mobile & Tools'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            Technology::findOrFail($this->editingId)->update([
                'name' => $this->name,
                'icon' => $this->icon,
                'category' => $this->category,
            ]);
        } else {
            Technology::create([
                'name' => $this->name,
                'icon' => $this->icon,
                'category' => $this->category,
            ]);
        }

        $this->loadTechnologies();
        $this->closeModal();
        session()->flash('success', $this->editingId ? 'Technology updated successfully.' : 'Technology added successfully.');
    }

    public function delete(int $id): void
    {
        $tech = Technology::withCount('projects')->findOrFail($id);

        if ($tech->projects_count > 0) {
            session()->flash('error', 'Cannot delete, this technology is still used in ' . $tech->projects_count . ' project(s).');
            return;
        }

        $tech->delete();
        $this->loadTechnologies();
        session()->flash('success', 'Technology deleted successfully.');
    }
};
?>

<div>
    @if (session('success'))
        <div class="p-3 mb-6 text-sm border rounded-lg bg-accent-900/30 border-accent-500/40 text-accent-300">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-3 mb-6 text-sm text-red-300 border border-red-700 rounded-lg bg-red-900/40">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div></div>
        <button wire:click="openCreateModal"
            class="bg-gradient-accent hover:opacity-90 text-gray-950 font-medium px-5 py-2.5 rounded-lg transition text-sm">
            + Add Technology
        </button>
    </div>

    <div class="overflow-hidden border border-gray-800 bg-gray-900/50 rounded-xl">
        <table class="w-full text-sm">
            <thead class="text-left border-b border-gray-800">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Category</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Used In</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($technologies as $tech)
                    <tr wire:key="tech-{{ $tech->id }}" class="transition-colors hover:bg-gray-800/40">
                        <td class="px-4 py-3 font-medium text-white">{{ $tech->name }}</td>
                        <td class="px-4 py-3 text-gray-400">
                            @if ($tech->category)
                                <span
                                    class="text-xs border border-gray-800 text-gray-300 px-2 py-0.5 rounded-full">{{ $tech->category }}</span>
                            @else
                                <span class="text-xs text-gray-600">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-400">{{ $tech->projects_count }} project(s)</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="openEditModal({{ $tech->id }})" title="Edit"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-accent-400 hover:bg-gray-800 transition">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $tech->id }})"
                                    wire:confirm="Are you sure you want to delete this technology?" title="Delete"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-gray-800 transition">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No technologies yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" wire:click.self="closeModal">
            <div class="w-full max-w-md p-6 bg-gray-900 border border-gray-800 rounded-xl">
                <h2 class="mb-5 text-lg font-semibold text-white">
                    {{ $editingId ? 'Edit Technology' : 'Add Technology' }}</h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                        @error('name')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Category</label>
                        <select wire:model="category"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                            @foreach ($categoryOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Icon (optional, class/icon name)</label>
                        <input type="text" wire:model="icon" placeholder="e.g. devicon-laravel-plain"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm text-gray-400 hover:text-white">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="bg-gradient-accent hover:opacity-90 disabled:opacity-50 text-gray-950 font-medium px-5 py-2.5 rounded-lg transition text-sm">
                            <span wire:loading.remove wire:target="save">Save</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
