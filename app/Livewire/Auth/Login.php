<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Login extends Component
{
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $showPassword = false;

    public function login($role)
    {
        $this->validate();

        // Attempt login
        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $this->addError('username', 'Username atau password tidak sesuai.');
            return;
        }

        $user = Auth::user();

        // Check role
        if (!$user->hasRole($role)) {
            Auth::logout();
            $this->addError('username', 'Anda tidak memiliki akses sebagai ' . ucfirst($role) . '.');
            return;
        }

        session()->regenerate();

        // Redirect based on role
        $redirectRoute = match($role) {
            'siswa' => 'student.index',
            'guru' => 'teacher.index',
            'admin' => 'admin.index',
            default => 'dashboard'
        };

        $this->redirect(route($redirectRoute), navigate: true);
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
