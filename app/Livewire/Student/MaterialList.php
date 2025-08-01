<?php

namespace App\Livewire\Student;

use App\Models\Subject;
use App\Models\Material;
use App\Models\MaterialAccessLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Daftar Materi')]
#[Layout('layouts.landing')]

class MaterialList extends Component
{
    public Subject $subject;
    public $activeTab = 'text';

    public function mount(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function recordAccess($materialId)
    {
        // Mencatat akses tanpa redirect agar komponen bisa me-refresh.
        MaterialAccessLog::updateOrCreate(
            ['user_id' => Auth::id(), 'material_id' => $materialId],
            ['accessed_at' => now()]
        );
    }

    public function render()
    {
        $user = Auth::user();

        $allMaterials = Material::where('subject_id', $this->subject->id)
            ->where('class_id', $user->class_id)
            ->where('is_published', true)
            ->get();

        $textMaterials = $allMaterials->filter(fn($m) => !empty($m->content) || !empty($m->file_path));
        $videoMaterials = $allMaterials->filter(fn($m) => !empty($m->youtube_url));


        $lastAccessed = $user->lastAccessedMaterials()
            ->where('subject_id', $this->subject->id)
            ->distinct()
            ->latest('material_access_logs.accessed_at')
            ->take(5)
            ->get();


        return view('livewire.student.material-list', [
            'textMaterials' => $textMaterials,
            'videoMaterials' => $videoMaterials,
            'lastAccessed' => $lastAccessed,
        ]);
    }
}
