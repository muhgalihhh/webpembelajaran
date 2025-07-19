<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


#[Title("Selamat Datang di Sistem Pembelajaran")]
class Index extends Component
{


    // Method untuk logout
    public function logoutUser()
    {
        try {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            // Redirect dengan JavaScript untuk memastikan
            $this->dispatch('redirect-to-welcome');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat logout.');
        }
    }

    // Method untuk membatalkan logout


    public function render()
    {
        return view('livewire.student.index');
    }
}
