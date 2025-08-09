<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.landing')]
#[Title('Profil Saya')]
class ProfileSiswa extends Component
{
    use WithFileUploads;

    // Properti untuk form info profil
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public $photo;

    // Properti untuk form ubah password
    public string $password = '';
    public string $password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
    }

    #[Computed]
    public function classInfo()
    {
        // Mengambil info kelas dari user yang sedang login
        return Auth::user()->class;
    }

    public function updateUser()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'photo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        if ($this->photo) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $this->photo->store('profile-photos', 'public');
        } else {
            // Jika tidak ada foto baru yang diunggah, hapus 'photo' dari data yang divalidasi
            unset($validated['photo']);
        }

        $user->update($validated);

        $this->dispatch('flash-message', message: 'Profil berhasil diperbarui.', type: 'success');
    }

    public function changePassword()
    {
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['password', 'password_confirmation']);
        $this->dispatch('flash-message', message: 'Password berhasil diubah.', type: 'success');
    }

    public function render()
    {
        return view('livewire.student.profile-siswa');
    }
}