<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

        // Attempt login
        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $this->addError('username', 'Username atau password tidak sesuai.');
            return;
        }

        $user = Auth::user();

        // Check admin role
        if (!$user->hasRole('admin')) {
            Auth::logout();
            $this->addError('username', 'Anda tidak memiliki akses sebagai Admin.');
            return;
        }

        session()->regenerate();

        $this->redirect(route('admin.index'), navigate: true);
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
