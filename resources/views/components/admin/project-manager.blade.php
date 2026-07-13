<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Technology;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $projects;
    public $technologies;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $description = '';
    public string $type = 'solo';
    public ?string $role = '';
    public ?string $repo_url = '';
    public ?string $demo_url = '';
    public bool $is_featured = false;
    public int $order = 0;
    public array $selectedTechnologies = [];
    public $thumbnail = null;
    public ?string $currentThumbnail = null;

    public $galleryImages = [];
    public $newGalleryFiles = [];

    public function mount(): void
    {
        $this->technologies = Technology::orderBy('name')->get();
        $this->loadProjects();
    }

    public function loadProjects(): void
    {
        $this->projects = Project::with('technologies')->orderBy('order')->get();
    }

    public function loadGallery(): void
    {
        if ($this->editingId) {
            $this->galleryImages = ProjectImage::where('project_id', $this->editingId)->orderBy('order')->get();
        } else {
            $this->galleryImages = [];
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $project = Project::with('technologies')->findOrFail($id);

        $this->editingId = $project->id;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->type = $project->type;
        $this->role = $project->role;
        $this->repo_url = $project->repo_url;
        $this->demo_url = $project->demo_url;
        $this->is_featured = $project->is_featured;
        $this->order = $project->order;
        $this->selectedTechnologies = $project->technologies->pluck('id')->toArray();
        $this->currentThumbnail = $project->thumbnail;
        $this->thumbnail = null;
        $this->newGalleryFiles = [];

        $this->loadGallery();

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
        $this->title = '';
        $this->description = '';
        $this->type = 'solo';
        $this->role = '';
        $this->repo_url = '';
        $this->demo_url = '';
        $this->is_featured = false;
        $this->order = 0;
        $this->selectedTechnologies = [];
        $this->thumbnail = null;
        $this->currentThumbnail = null;
        $this->galleryImages = [];
        $this->newGalleryFiles = [];
        $this->resetErrorBag();
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:solo,team'],
            'role' => ['nullable', 'string', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['boolean'],
            'order' => ['integer', 'min:0'],
            'selectedTechnologies' => ['array'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'newGalleryFiles.*' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'role' => $this->role,
            'repo_url' => $this->repo_url,
            'demo_url' => $this->demo_url,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
        ];

        if ($this->thumbnail) {
            $path = $this->thumbnail->store('projects', 'public');
            $data['thumbnail'] = $path;
        }

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);

            if ($this->thumbnail && $project->thumbnail && Storage::disk('public')->exists($project->thumbnail)) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $project->update($data);
        } else {
            $project = Project::create($data);
        }

        $project->technologies()->sync($this->selectedTechnologies);

        if (!empty($this->newGalleryFiles)) {
            $maxOrder = ProjectImage::where('project_id', $project->id)->max('order') ?? 0;

            foreach ($this->newGalleryFiles as $file) {
                $maxOrder++;
                $path = $file->store('projects', 'public');
                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'order' => $maxOrder,
                ]);
            }
        }

        $this->loadProjects();
        $this->closeModal();
        session()->flash('success', $this->editingId ? 'Project updated successfully.' : 'Project added successfully.');
    }

    public function deleteGalleryImage(int $imageId): void
    {
        $image = ProjectImage::findOrFail($imageId);

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();
        $this->loadGallery();
        session()->flash('success', 'Gallery image deleted successfully.');
    }

    public function updateCaption(int $imageId, string $caption): void
    {
        ProjectImage::where('id', $imageId)->update(['caption' => $caption]);
    }

    public function delete(int $id): void
    {
        $project = Project::findOrFail($id);

        if ($project->thumbnail && Storage::disk('public')->exists($project->thumbnail)) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach (ProjectImage::where('project_id', $id)->get() as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $project->delete();
        $this->loadProjects();
        session()->flash('success', 'Project deleted successfully.');
    }
};
?>

<div>
    @if (session('success'))
        <div class="p-3 mb-6 text-sm border rounded-lg bg-accent-900/30 border-accent-500/40 text-accent-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div></div>
        <button wire:click="openCreateModal"
            class="bg-gradient-accent hover:opacity-90 text-gray-950 font-medium px-5 py-2.5 rounded-lg transition text-sm">
            + Add Project
        </button>
    </div>

    <div class="overflow-hidden border border-gray-800 bg-gray-900/50 rounded-xl">
        <table class="w-full text-sm">
            <thead class="text-left border-b border-gray-800">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Project</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Featured</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-gray-500 uppercase">Order</th>
                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($projects as $project)
                    <tr wire:key="project-{{ $project->id }}" class="transition-colors hover:bg-gray-800/40">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($project->thumbnail)
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}"
                                        class="object-cover w-10 h-10 border border-gray-800 rounded-lg">
                                @else
                                    <div
                                        class="flex items-center justify-center w-10 h-10 text-gray-600 bg-gray-800 border border-gray-800 rounded-lg">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="9" cy="9" r="2" />
                                            <path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21" />
                                        </svg>
                                    </div>
                                @endif
                                <span class="font-medium text-white">{{ $project->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-400">{{ $project->type === 'team' ? 'Team' : 'Solo' }}</td>
                        <td class="px-4 py-3">
                            @if ($project->is_featured)
                                <span
                                    class="px-2 py-0.5 text-xs rounded-full border border-accent-500/40 text-accent-400">Yes</span>
                            @else
                                <span
                                    class="px-2 py-0.5 text-xs rounded-full border border-gray-800 text-gray-500">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-400">{{ $project->order }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="openEditModal({{ $project->id }})" title="Edit"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-accent-400 hover:bg-gray-800 transition">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $project->id }})"
                                    wire:confirm="Are you sure you want to delete this project?" title="Delete"
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
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">No projects yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" wire:click.self="closeModal">
            <div
                class="bg-gray-900 border border-gray-800 rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6">
                <h2 class="mb-5 text-lg font-semibold text-white">{{ $editingId ? 'Edit Project' : 'Add Project' }}
                </h2>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Title</label>
                        <input type="text" wire:model="title"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                        @error('title')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Description</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none"></textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-400">Type</label>
                            <select wire:model="type"
                                class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                                <option value="solo">Solo</option>
                                <option value="team">Team</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-400">Role (optional)</label>
                            <select wire:model="role"
                                class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                                <option value="">— Select Role —</option>
                                <option value="Backend Developer">Backend Developer</option>
                                <option value="Front-End Developer">Front-End Developer</option>
                                <option value="Full-Stack Developer">Full-Stack Developer</option>
                                <option value="Mobile Developer">Mobile Developer</option>
                                <option value="UI/UX Designer">UI/UX Designer</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="QA / Tester">QA / Tester</option>
                                <option value="DevOps Engineer">DevOps Engineer</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-400">Repo URL (optional)</label>
                            <input type="text" wire:model="repo_url"
                                class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                            @error('repo_url')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-1 text-sm text-gray-400">Demo URL (optional)</label>
                            <input type="text" wire:model="demo_url"
                                class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                            @error('demo_url')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid items-end grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm text-gray-400">Order</label>
                            <input type="number" wire:model="order"
                                class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                        </div>
                        <div class="flex items-center gap-2 pb-2">
                            <input type="checkbox" wire:model="is_featured" id="is_featured"
                                class="bg-gray-800 border-gray-700 rounded text-accent-500 focus:ring-accent-500">
                            <label for="is_featured" class="text-sm text-gray-300">Show on Home
                                (Featured)</label>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Technologies</label>
                        <div
                            class="grid grid-cols-2 gap-2 p-3 overflow-y-auto rounded-lg sm:grid-cols-3 max-h-40 bg-gray-800/50">
                            @foreach ($technologies as $tech)
                                <label class="flex items-center gap-2 text-sm text-gray-300">
                                    <input type="checkbox" wire:model="selectedTechnologies"
                                        value="{{ $tech->id }}"
                                        class="bg-gray-800 border-gray-700 rounded text-accent-500 focus:ring-accent-500">
                                    {{ $tech->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Thumbnail</label>
                        @if ($currentThumbnail && !$thumbnail)
                            <img src="{{ asset('storage/' . $currentThumbnail) }}"
                                class="object-cover w-32 h-20 mb-2 rounded-lg">
                        @endif
                        @if ($thumbnail && str_starts_with($thumbnail->getMimeType(), 'image/'))
                            <img src="{{ $thumbnail->temporaryUrl() }}"
                                class="object-cover w-32 h-20 mb-2 rounded-lg">
                        @endif
                        <input type="file" wire:model="thumbnail" accept="image/*"
                            class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-800 file:text-gray-300">
                        <div wire:loading wire:target="thumbnail" class="mt-1 text-xs text-accent-400">Uploading...
                        </div>
                        @error('thumbnail')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gallery — only shown while editing --}}
                    @if ($editingId)
                        <div class="pt-4 border-t border-gray-800">
                            <label class="block mb-2 text-sm text-gray-400">Image Gallery</label>

                            @if (count($galleryImages) > 0)
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    @foreach ($galleryImages as $img)
                                        <div class="relative group" wire:key="gallery-{{ $img->id }}">
                                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                                class="object-cover w-full h-24 border border-gray-700 rounded-lg">
                                            <button type="button"
                                                wire:click="deleteGalleryImage({{ $img->id }})"
                                                wire:confirm="Remove this image from the gallery?"
                                                class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition bg-red-600 rounded-full opacity-0 top-1 right-1 hover:bg-red-500 group-hover:opacity-100">
                                                ✕
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="mb-3 text-xs text-gray-500">No gallery images yet.</p>
                            @endif

                            <label class="block mb-1 text-xs text-gray-500">Add new images (you can select several
                                at once)</label>
                            <input type="file" wire:model="newGalleryFiles" multiple accept="image/*"
                                class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-800 file:text-gray-300">
                            <div wire:loading wire:target="newGalleryFiles" class="mt-1 text-xs text-accent-400">
                                Uploading...</div>
                            @error('newGalleryFiles.*')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror

                            @if (!empty($newGalleryFiles))
                                <div class="grid grid-cols-3 gap-3 mt-3">
                                    @foreach ($newGalleryFiles as $file)
                                        @if (is_object($file) && str_starts_with($file->getMimeType(), 'image/'))
                                            <img src="{{ $file->temporaryUrl() }}"
                                                class="object-cover w-full h-24 border rounded-lg border-accent-600">
                                        @endif
                                    @endforeach
                                </div>
                                <p class="mt-1 text-xs text-gray-500">{{ count($newGalleryFiles) }} image(s) will be
                                    added when saved.</p>
                            @endif
                        </div>
                    @else
                        <div class="pt-4 border-t border-gray-800">
                            <p class="text-xs text-gray-500">The image gallery can be added after the project is saved.
                            </p>
                        </div>
                    @endif

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
