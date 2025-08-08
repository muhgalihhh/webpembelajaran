<?php

namespace App\Livewire\Student;

use App\Models\EducationalGame;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Game Edukatif')]
class GameList extends Component
{
    #[Computed]
    public function games()
    {
        $studentClassId = Auth::user()->class_id;

        return EducationalGame::with(['subject', 'class'])
            ->where('class_id', $studentClassId)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.student.game-list');
    }
}