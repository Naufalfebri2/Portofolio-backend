<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public ?string $bio = '';
    public ?string $phone = '';
    public ?string $email = '';
    public ?string $location = '';
    public ?string $github_url = '';
    public ?string $linkedin_url = '';
    public ?string $instagram_url = '';

    public $photo = null;
    public ?string $currentPhoto = null;

    public $resume = null;
    public ?string $currentResume = null;

    public function mount(): void
    {
        $profile = Profile::firstOrCreate([], ['name' => 'Naufal Febriansyah']);

        $this->name = $profile->name;
        $this->bio = $profile->bio;
        $this->phone = $profile->phone;
        $this->email = $profile->email;
        $this->location = $profile->location;
        $this->github_url = $profile->github_url;
        $this->linkedin_url = $profile->linkedin_url;
        $this->instagram_url = $profile->instagram_url;
        $this->currentPhoto = $profile->photo;
        $this->currentResume = $profile->resume_path;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'resume' => ['nullable', 'mimes:pdf', 'max:5120'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $profile = Profile::firstOrCreate([]);

        $data = [
            'name' => $this->name,
            'bio' => $this->bio,
            'phone' => $this->phone,
            'email' => $this->email,
            'location' => $this->location,
            'github_url' => $this->github_url,
            'linkedin_url' => $this->linkedin_url,
            'instagram_url' => $this->instagram_url,
        ];

        if ($this->photo) {
            if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $this->photo->store('profile', 'public');
        }

        if ($this->resume) {
            if ($profile->resume_path && Storage::disk('public')->exists($profile->resume_path)) {
                Storage::disk('public')->delete($profile->resume_path);
            }
            $data['resume_path'] = $this->resume->store('resume', 'public');
        }

        $profile->update($data);

        $this->currentPhoto = $profile->fresh()->photo;
        $this->currentResume = $profile->fresh()->resume_path;
        $this->photo = null;
        $this->resume = null;

        session()->flash('success', 'Profile updated successfully.');
    }
};
?>

<div>
    @if (session('success'))
        <div class="p-3 mb-6 text-sm border rounded-lg bg-accent-900/30 border-accent-500/40 text-accent-300">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="max-w-2xl space-y-6">

        {{-- Basic Info --}}
        <div class="p-6 border border-gray-800 rounded-xl bg-gray-900/50">
            <h2 class="mb-4 text-sm font-semibold text-white">Basic Info</h2>
            <div class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-400">Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-400">Bio</label>
                    <textarea wire:model="bio" rows="5"
                        class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none"></textarea>
                    @error('bio')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Phone</label>
                        <input type="text" wire:model="phone"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-400">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                        @error('email')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-400">Location</label>
                    <input type="text" wire:model="location" placeholder="e.g. South Tangerang, Indonesia"
                        class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    @error('location')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="p-6 border border-gray-800 rounded-xl bg-gray-900/50">
            <h2 class="mb-4 text-sm font-semibold text-white">Social Links</h2>
            <div class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-400">GitHub URL</label>
                    <input type="text" wire:model="github_url"
                        class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    @error('github_url')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-400">LinkedIn URL</label>
                    <input type="text" wire:model="linkedin_url"
                        class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    @error('linkedin_url')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-400">Instagram URL</label>
                    <input type="text" wire:model="instagram_url"
                        class="w-full px-3 py-2 text-white bg-gray-800 border border-gray-700 rounded-lg focus:border-accent-500 focus:outline-none">
                    @error('instagram_url')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Files --}}
        <div class="p-6 border border-gray-800 rounded-xl bg-gray-900/50">
            <h2 class="mb-4 text-sm font-semibold text-white">Photo & Documents</h2>
            <div class="space-y-5">
                <div>
                    <label class="block mb-1 text-sm text-gray-400">Profile Photo</label>
                    @if ($currentPhoto && !$photo)
                        <img src="{{ asset('storage/' . $currentPhoto) }}"
                            class="object-cover w-24 h-24 mb-2 rounded-full">
                    @endif
                    @if ($photo && str_starts_with($photo->getMimeType(), 'image/'))
                        <img src="{{ $photo->temporaryUrl() }}" class="object-cover w-24 h-24 mb-2 rounded-full">
                    @endif
                    <input type="file" wire:model="photo" accept="image/*"
                        class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-800 file:text-gray-300">
                    <div wire:loading wire:target="photo" class="mt-1 text-xs text-accent-400">Uploading...</div>
                    @error('photo')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-400">Resume (PDF)</label>
                    @if ($currentResume)
                        <p class="mb-2 text-sm text-gray-400">Current file: <span
                                class="text-accent-400">{{ basename($currentResume) }}</span></p>
                    @endif
                    <input type="file" wire:model="resume" accept=".pdf"
                        class="w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-800 file:text-gray-300">
                    <div wire:loading wire:target="resume" class="mt-1 text-xs text-accent-400">Uploading...</div>
                    @error('resume')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <button type="submit" wire:loading.attr="disabled"
                class="bg-gradient-accent hover:opacity-90 disabled:opacity-50 text-gray-950 font-medium px-6 py-2.5 rounded-lg transition text-sm">
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>

    </form>
</div>
