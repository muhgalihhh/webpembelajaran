<?php

namespace App\Livewire\Teacher;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule as LivewireRule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.teacher')] // Menggunakan layout teacher
#[Title('Profil Saya')]
class ProfileGuru extends Component
{
    use WithFileUploads;

    // Properti untuk form informasi profil
    #[LivewireRule('required|string|max:255')]
    public string $name = '';

    #[LivewireRule('required|string|max:255')]
    public string $username = '';

    #[LivewireRule('required|email|max:255')]
    public string $email = '';

    #[LivewireRule('nullable|string|max:20')]
    public string $phone_number = '';

    // Properti untuk upload foto
    #[LivewireRule('nullable|image|max:1024')] // Maks 1MB
    public $photo;

    // Properti untuk form ganti password
    #[LivewireRule('required|string')]
    public string $current_password = '';

    #[LivewireRule(['required', 'string', 'confirmed'])]
    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number ?? '';
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($this->photo) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $this->photo->store('profile-photos', 'public');
        }

        $user->update($validated);
        $this->reset('photo');
        $this->dispatch('flash-message', message: 'Profil berhasil diperbarui.', type: 'success');
    }

    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update(['password' => Hash::make($validated['password'])]);
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('flash-message', message: 'Kata sandi berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        return view('livewire.teacher.profile-guru');
    }
}
