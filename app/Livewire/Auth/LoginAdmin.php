<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // Import ValidationException
use App\Models\User;

#[Layout('layouts.app')] // Mengatur layout menggunakan atribut
#[Title('Login Admin')] // Mengatur judul menggunakan atribut
class LoginAdmin extends Component
{
    #[Rule('required|string')] // Aturan validasi langsung pada properti
    public string $username = '';

    #[Rule('required|string')] // Aturan validasi langsung pada properti
    public string $password = '';

    public bool $showPassword = false;

    public function authenticate()
    {
        // Validasi akan dipicu secara otomatis oleh atribut #[Rule]
        $this->validate();

        $user = User::where('username', $this->username)->first(); // Mencari user berdasarkan username

        // Jika user tidak ditemukan ATAU kredensial tidak cocok
        if (!$user || !Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            throw ValidationException::withMessages([
                'username' => __('Username atau password tidak sesuai.'), // Melemparkan exception untuk error kredensial
            ]);
        }

        // Pastikan pengguna yang login adalah admin
        if (!$user->hasRole('admin')) { // Memeriksa peran
            Auth::logout(); // Logout jika tidak sesuai peran
            throw ValidationException::withMessages([
                'username' => __('Anda tidak memiliki akses sebagai Admin.'), // Melemparkan exception untuk error peran
            ]);
        }

        session()->regenerate(); // Regenerate session for security
        session()->regenerateToken(); // Regenerate token for security

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    public function render()
    {
        return view('livewire.auth.login-admin');
    }
}
