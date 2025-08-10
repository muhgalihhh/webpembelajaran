<?php

namespace App\Livewire\Student;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title("Selamat Datang di Sistem Pembelajaran")]
#[Layout("layouts.landing")]
class Materi extends Component
{
    public bool $showLogoutModal = false;
    public string $modalType = '';

    // Deklarasi property sebagai public dan berikan nilai default
    public array $subjects = [];

    // Method mount untuk inisialisasi data
    public function mount()
    {
        $this->subjects = [
            ['name' => 'IPA'],
            ['name' => 'Matematika', ],
            ['name' => 'Bahasa Indonesia', ],
            ['name' => 'IPS', ],
            ['name' => 'Pendidikan Agama', ]
        ];
    }

    // Method untuk menampilkan modal logout
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
        return view('livewire.student.materi', [
            'subjects' => $this->subjects
        ]);
    }
}
