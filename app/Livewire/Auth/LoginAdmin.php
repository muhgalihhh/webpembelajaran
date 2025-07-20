<?php

namespace App\Livewire\Auth;


use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
#[Title('Login Admin')]
class LoginAdmin extends Component
{


    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $showPassword = false;

    public function authenticate()
    {
        $this->validate();

        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            // 3. Panggil method dari Trait untuk menampilkan error
            $this->showErrorAlert('Login Gagal', 'Username atau password tidak sesuai.');
            return;
        }

        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            Auth::logout();
            $this->showErrorAlert('Akses Ditolak', 'Anda tidak memiliki akses sebagai Admin.');
            return;
        }
        session()->regenerate();
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.login-admin');
    }
}