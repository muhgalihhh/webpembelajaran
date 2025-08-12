<?php

namespace App\Livewire\Student;

use App\Models\EducationalGame;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.landing')]
#[Title('Game Edukatif')]
class GameList extends Component
{
    use WithPagination;

    public ?EducationalGame $selectedGame = null;
    #[Computed]
    public function games()
    {
        $studentClassId = Auth::user()->class_id;

        return EducationalGame::with(['subject', 'class'])
            ->where('class_id', $studentClassId)
            ->paginate(10);
    }

    public function showGameDetail($gameId)
    {
        $this->selectedGame = EducationalGame::with(['subject', 'class'])
            ->find($gameId);
        $this->dispatch('open-modal', id: 'game-detail-modal');
    }

    public function closeModal()
    {
        $this->selectedGame = null;
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.student.game-list');
    }
}