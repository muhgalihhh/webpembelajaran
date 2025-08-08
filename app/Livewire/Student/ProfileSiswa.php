<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Profil Saya')]
class ProfileSiswa extends Component
{
    // Properti untuk form info profil
    public string $name = '';
    public string $username = '';
    public string $email = '';

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
        ]);

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