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
            session()->flash('flash-message', [
                'message' => 'Username atau password tidak sesuai.',
                'type' => 'error'
            ]);
            $this->dispatch('flash-message', [
                'message' => 'Username atau password tidak sesuai.',
                'type' => 'error'
            ]);
            $this->addError('username', 'Username atau password tidak sesuai.');
            return;
        }

        $user = Auth::user();

        if (!$user->hasRole('admin')) {
            Auth::logout();
            session()->flash('flash-message', [
                'message' => 'Anda tidak memiliki akses sebagai Admin.',
                'type' => 'error'
            ]);
            $this->dispatch('flash-message', [
                'message' => 'Anda tidak memiliki akses sebagai Admin.',
                'type' => 'error'
            ]);
            $this->addError('username', 'Anda tidak memiliki akses sebagai Admin.');
            return;
        }


        session()->regenerate();
        session()->flash('flash-message', [
            'message' => 'Selamat datang, ' . $user->name . '!',
            'type' => 'success'
        ]);

        return $this->redirect(route('admin.index'), navigate: true);
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
