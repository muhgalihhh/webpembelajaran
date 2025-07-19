<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;


#[Title("Selamat Datang di Sistem Pembelajaran")]
class Index extends Component
{
    // Properti untuk modal logout
    public bool $showLogoutModal = false;

    // Method untuk menampilkan modal
    public function confirmLogout()
    {
        $this->showLogoutModal = true;
    }

    // Method untuk logout
    public function logoutUser()
    {
        try {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            // Reset modal state
            $this->showLogoutModal = false;

            // Redirect dengan JavaScript untuk memastikan
            $this->dispatch('redirect-to-welcome');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat logout.');
        }
    }

    // Method untuk membatalkan logout
    public function cancelLogout()
    {
        $this->showLogoutModal = false;
    }

    public function render()
    {
        return view('livewire.student.index');
    }
}
