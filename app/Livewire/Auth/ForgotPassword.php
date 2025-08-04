<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Lupa Sandi - Website Pembelajaran Digital')]
class ForgotPassword extends Component
{
    public string $email = '';
    public ?string $emailSentMessage = null;

    // app/Livewire/Auth/ForgotPassword.php

    public function sendResetLink()
    {
        $this->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSentMessage = __($status);
            return;
        }

        // Jika email tidak ditemukan atau ada error lain
        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
