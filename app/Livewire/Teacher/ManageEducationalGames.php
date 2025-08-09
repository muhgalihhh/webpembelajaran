<?php

namespace App\Livewire\Teacher;

use App\Models\Classes; // 1. Impor model Classes
use App\Models\EducationalGame;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Manajemen Game Edukasi')]
class ManageEducationalGames extends Component
{
    use WithPagination, WithFileUploads;

    // Properti Filter
    #[Url(as: 'q')]
    public string $search = '';
    #[Url(as: 'mapel')]
    public string $subjectFilter = '';
    #[Url(as: 'kelas')] // 2. Tambahkan filter kelas
    public string $classFilter = '';

    // Properti State
    public bool $isEditing = false;
    public ?EducationalGame $editingGame = null;
    public $itemToDeleteId = null;

    // Properti Form
    #[Rule('required|string|max:255')]
    public string $title = '';
    #[Rule('required|string')]
    public string $description = '';
    #[Rule('required|url')]
    public string $game_url = '';
    #[Rule('required|exists:subjects,id')]
    public $subject_id = '';
    #[Rule('required|exists:classes,id')] // 3. Tambahkan properti & aturan validasi
    public $class_id = '';
    #[Rule('nullable|image|max:2048')]
    public $uploadedImage;
    public ?string $currentImagePath = null;

    // Lifecycle Hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSubjectFilter()
    {
        $this->resetPage();
    }
    public function updatingClassFilter()
    {
        $this->resetPage();
    } // 4. Tambahkan hook untuk filter kelas

    #[Computed]
    public function games()
    {
        return EducationalGame::with(['subject', 'class']) // Muat relasi class
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->subjectFilter, fn($q) => $q->where('subject_id', $this->subjectFilter))
            ->when($this->classFilter, fn($q) => $q->where('class_id', $this->classFilter)) // Terapkan filter kelas
            ->orderBy('title', 'asc')
            ->paginate(9);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::orderBy('kurikulum', 'asc')->orderBy('name')->get()
            ->mapWithKeys(function ($subject) {
                $displayText = "{$subject->name} - ({$subject->kurikulum})";
                return [$subject->id => $displayText];
            });
    }

    // 5. Tambahkan computed property untuk mengambil data kelas
    #[Computed]
    public function classes()
    {
        return Classes::orderBy('class')->get();
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingGame', 'title', 'description', 'game_url', 'subject_id', 'class_id', 'uploadedImage', 'currentImagePath']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->dispatch('open-modal', id: 'game-form-modal');
    }

    public function edit(EducationalGame $game)
    {
        $this->isEditing = true;
        $this->editingGame = $game;
        $this->title = $game->title;
        $this->description = $game->description;
        $this->game_url = $game->game_url;
        $this->subject_id = $game->subject_id;
        $this->class_id = $game->class_id; // Isi data class_id saat edit
        $this->currentImagePath = $game->image_path;
        $this->dispatch('open-modal', id: 'game-form-modal');
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'game_url' => 'required|url',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id', // Validasi class_id
            'uploadedImage' => $this->isEditing ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $dataToSave = [
            'title' => $this->title,
            'description' => $this->description,
            'game_url' => $this->game_url,
            'subject_id' => $this->subject_id,
            'class_id' => $this->class_id, // Simpan class_id
        ];

        if ($this->uploadedImage) {
            if ($this->isEditing && $this->editingGame->image_path) {
                Storage::disk('public')->delete($this->editingGame->image_path);
            }
            $dataToSave['image_path'] = $this->uploadedImage->store('game-images', 'public');
        }

        if ($this->isEditing) {
            $this->editingGame->update($dataToSave);
            $message = 'Game berhasil diperbarui.';
        } else {
            EducationalGame::create($dataToSave);
            $message = 'Game berhasil ditambahkan.';
        }
        $this->dispatch('flash-message', message: $message, type: 'success');
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        if ($this->itemToDeleteId) {
            $game = EducationalGame::find($this->itemToDeleteId);
            if ($game) {
                if ($game->image_path) {
                    Storage::disk('public')->delete($game->image_path);
                }
                $game->delete();
                $this->dispatch('flash-message', message: 'Game berhasil dihapus.', type: 'success');
            }
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function render()
    {
        return view('livewire.teacher.manage-educational-games');
    }
}