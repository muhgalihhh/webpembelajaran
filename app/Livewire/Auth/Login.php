<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Livewire\Attributes\Rule as LivewireRule;

#[Layout('layouts.app')]

class Login extends Component
{
    #[LivewireRule('required|string')]
    public string $username = '';

    #[LivewireRule('required|string')]
    public string $password = '';

    public bool $showPassword = false;

    public function authenticateStudent()
    {
        $this->authenticate('siswa', 'siswa.dashboard');
    }

    public function authenticateTeacher()
    {
        $this->authenticate('guru', 'guru.dashboard');
    }

    private function authenticate(string $expectedRole, string $redirectTo)
    {
        $this->validate();

        $user = User::where('username', $this->username)->first();

        if (!$user || !Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            throw ValidationException::withMessages([
                'username' => __('Username atau password tidak sesuai.'),
            ]);
        }

        if (!$user->hasRole($expectedRole)) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => __('Anda tidak memiliki akses sebagai ' . ucfirst($expectedRole) . '.'),
            ]);
        }

        session()->regenerate();
        return redirect()->intended(route($redirectTo));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
