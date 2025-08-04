<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
#[Title('Reset Password - Website Pembelajaran Digital')]
class ResetPassword extends Component
{
    public string $token;
    public string $email;
    public string $password;
    public string $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function resetPassword()
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // [PERBAIKAN] Menggunakan metode Password::reset() yang baru
        $status = Password::reset(
            $this->all(),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            session()->flash('flash-message', [
                'message' => 'Password berhasil direset. Silakan masuk dengan password baru Anda.',
                'type' => 'success'
            ]);
            return $this->redirect(route('login'), navigate: true);
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
