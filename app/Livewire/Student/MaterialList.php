<?php

namespace App\Livewire\Student;

use App\Models\MaterialAccessLog;
use App\Models\Subject;
use App\Models\Material;
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
    public $selectedMaterial = null;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Menangani klik pada materi video.
     * Fungsi ini sekarang menjadi pusat untuk interaksi video.
     */
    public function selectMaterial($materialId)
    {
        $this->selectedMaterial = Material::find($materialId);

        if ($this->selectedMaterial) {
            // Logika dari recordAccess() dipindahkan ke sini.
            // Mencatat akses langsung saat video dipilih.
            $this->selectedMaterial->accessLogs()->updateOrCreate(
                ['user_id' => Auth::id(), 'material_id' => $materialId],
                ['accessed_at' => now()]
            );
        }
    }

    public function extractYoutubeId($url)
    {
        preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches);
        return $matches[2] ?? null;
    }

    public function recordAccess($materialId)
    {
        $user = Auth::user();
        $material = Material::find($materialId);

        if ($material) {
            MaterialAccessLog::updateOrCreate(
                ['user_id' => $user->id, 'material_id' => $materialId],
                ['accessed_at' => now()]
            );
        }
    }
    public function render()
    {
        $user = Auth::user();

        $allMaterials = Material::where('subject_id', $this->subject->id)
            ->where('class_id', $user->class_id)
            ->where('is_published', true)
            ->get();

        $lastAccessed = $user->lastAccessedMaterials()
            ->where('materials.subject_id', $this->subject->id)
            ->distinct()
            ->take(5)
            ->get();

        return view('livewire.student.material-list', [
            'textMaterials' => $allMaterials->filter(fn($m) => !empty($m->content) || !empty($m->file_path)),
            'videoMaterials' => $allMaterials->filter(fn($m) => !empty($m->youtube_url)),
            'lastAccessed' => $lastAccessed,
        ]);
    }
}
