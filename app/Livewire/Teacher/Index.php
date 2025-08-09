<?php

namespace App\Livewire\Teacher;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout("layouts.landing")]
#[Title('Selamat Datang Guru')]

class Index extends Component
{
    public function render()
    {

        return view('livewire.teacher.index');
    }
}
