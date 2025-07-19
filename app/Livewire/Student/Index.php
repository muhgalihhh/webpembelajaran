<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


#[Title("Selamat Datang di Sistem Pembelajaran")]
#[Layout("layouts.landing")]
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
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
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
