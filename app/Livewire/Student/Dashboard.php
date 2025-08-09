<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout("layouts.landing")]
#[Title("Selamat Datang di Dashboard")]
class Dashboard extends Component
{

    #[Computed]
    public function studentClass()
    {

        return Auth::user()->class;
    }

    public function render()
    {
        return view('livewire.student.dashboard');
    }
}
