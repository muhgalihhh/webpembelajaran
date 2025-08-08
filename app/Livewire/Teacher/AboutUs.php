<?php

namespace App\Livewire\Teacher;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.teacher')]
#[Title('Tentang Aplikasi')]
class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.teacher.about-us');
    }
}