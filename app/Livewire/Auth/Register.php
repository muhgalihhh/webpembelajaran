<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On; // Import untuk #[On]
use Livewire\Attributes\Rule as LivewireRule; // Import untuk #[Rule]



#[Title("Registrasi Siswa atau Guru")]
#[Layout("layouts.app")]


class Register extends Component
{
    

    #[LivewireRule('required|string|max:255')]
    public string $name = '';

    #[LivewireRule('required|string|max:255|unique:users')]
    public string $username = '';

    #[LivewireRule('required|string|email|max:255|unique:users')]
    public string $email = '';

    #[LivewireRule('required|string|min:8|confirmed')]
    public string $password = '';
    public string $password_confirmation = '';

    // Properti untuk loading state
    public bool $isRegisteringStudent = false;
    public bool $isRegisteringTeacher = false;



    public function registerStudent()
    {
        $this->isRegisteringStudent = true;
        try {
            $this->validate(); // Lakukan validasi penuh saat submit
            $this->registerUser('siswa');
        } finally {
            $this->isRegisteringStudent = false;
        }
    }

    public function registerTeacher()
    {
        $this->isRegisteringTeacher = true;
        try {
            $this->validate(); // Lakukan validasi penuh saat submit
            $this->registerUser('guru');
        } finally {
            $this->isRegisteringTeacher = false;
        }
    }

    private function registerUser(string $assignedRole)
    {
        $role = Role::where('name', $assignedRole)->first();
        if (!$role) {
            session()->flash('error', "Role '{$assignedRole}' tidak ditemukan. Mohon hubungi administrator.");
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email, // <-- Gunakan properti email
            'password' => Hash::make($this->password),
            'status' => 'active',
        ]);

        $user->assignRole($role->name);

        Auth::login($user);

        if ($assignedRole === 'siswa') {
            return redirect()->intended(route('siswa.index'))->with('success', 'Akun siswa Anda berhasil dibuat!');
        } elseif ($assignedRole === 'guru') {
            return redirect()->intended(route('guru.index'))->with('success', 'Akun guru Anda berhasil dibuat!');
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
