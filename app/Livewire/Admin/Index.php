<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout("layouts.landing")]
#[Title('Selamat Datang Admin')]

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.index');
    }
}
