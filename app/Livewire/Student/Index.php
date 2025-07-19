<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

#[Layout("layouts.landing")]
#[Title("Selamat Datang di Sistem Pembelajaran")]

class Index extends Component
{

    public $showLogoutModal = false;

    public function confirmLogout()
    {
        $this->showLogoutModal = true;
    }

    public function logoutUser()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->showLogoutModal = false;

        return redirect()->route('welcome');
    }

    public function cancelLogout()
    {
        $this->showLogoutModal = false;
    }
    public function render()
    {
        return view('livewire.student.index');
    }
}
