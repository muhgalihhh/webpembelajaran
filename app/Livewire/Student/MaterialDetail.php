<?php

namespace App\Livewire\Student;

use App\Models\Material;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.landing')]
#[Title('Detail Materi')]
class MaterialDetail extends Component
{
    public Material $material;
    public Collection $subjectMaterials; // <-- Tambahkan properti ini

    public function mount(Material $material)
    {
        if (!$material->is_published || $material->class_id !== Auth::user()->class_id) {
            abort(404);
        }
        $this->material = $material;
        $this->recordAccess($material->id);

        // <-- Tambahkan logika ini untuk mengambil semua materi dari mapel yang sama
        $this->subjectMaterials = Material::where('subject_id', $this->material->subject_id)
            ->where('is_published', true)
            ->orderBy('chapter')
            ->orderBy('title')
            ->get();
    }


    public function recordAccess($materialId)
    {
        $material = Material::find($materialId);

        if ($material) {
            $material->accessLogs()->updateOrCreate(
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

    public function render()
    {
        return view('livewire.student.material-detail');
    }
}
