<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


#[Title("Selamat Datang di Sistem Pembelajaran")]
class Index extends Component
{
    // Properti untuk modal logout
    public bool $showLogoutModal = false;
    public string $modalType = '';

    // Method untuk menampilkan modal
    public function confirmLogout()
    {
        $this->showLogoutModal = true;
        $this->modalType = 'logout';
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
            $this->modalType = '';


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
        $this->modalType = '';
    }

    public function render()
    {
        return view('livewire.student.index');
    }
}
