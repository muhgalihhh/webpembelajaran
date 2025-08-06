<?php

namespace App\Livewire\Student;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Daftar Materi')]
#[Layout('layouts.landing')]
class MaterialList extends Component
{
    use WithPagination;

    /**
     * Mengatur tema pagination agar sesuai dengan Tailwind CSS.
     * Ini akan memperbaiki tampilan pagination yang rusak.
     */
    protected $paginationTheme = 'tailwind';

    public Subject $subject;
    public $activeTab = 'text';
    public $selectedMaterial = null;

    public function mount(Subject $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Mendefinisikan listener untuk broadcast events
     */
    protected function getListeners()
    {
        if (!Auth::check() || !Auth::user()->class_id) {
            return [];
        }

        $classId = Auth::user()->class_id;

        return [
            "echo-private:class.{$classId},.material.created" => 'handleNewMaterial',
            "echo-private:class.{$classId},.task.created" => 'handleNewContent',
            "echo-private:class.{$classId},.quiz.created" => 'handleNewContent',
        ];
    }

    /**
     * Handle ketika ada materi baru
     */
    public function handleNewMaterial($event)
    {
        Log::info('New material broadcast received', [
            'event' => $event,
            'subject_id' => $this->subject->id,
            'user_id' => Auth::id()
        ]);


        $this->resetPage();
        $this->render();

        $this->dispatch('flash-message', [
            'message' => 'Materi baru telah ditambahkan!',
            'type' => 'info'
        ]);
    }

    /**
     * Handle ketika ada konten baru (task/quiz)
     */
    public function handleNewContent($event)
    {
        Log::info('New content broadcast received', [
            'event' => $event,
            'subject_id' => $this->subject->id
        ]);
    }

    /**
     * Event listener untuk refresh manual
     */
    #[On('refresh-materials')]
    public function refreshMaterials()
    {
        $this->resetPage();
        $this->render();
    }

    /**
     * Mengganti tab aktif dan mereset paginasi ke halaman pertama.
     */
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    public function selectMaterial($materialId)
    {
        $this->selectedMaterial = Material::find($materialId);

        if ($this->selectedMaterial) {
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

    public function render()
    {
        $user = Auth::user();

        // Query dasar untuk materi dengan fresh data
        $baseQuery = Material::where('subject_id', $this->subject->id)
            ->where('class_id', $user->class_id)
            ->where('is_published', true);

        $textMaterials = collect();
        $videoMaterials = collect();

        // Memuat data hanya untuk tab yang aktif dengan paginasi
        if ($this->activeTab === 'text') {
            $textMaterials = (clone $baseQuery)
                ->where(function (Builder $query) {
                    $query->whereNotNull('content')->orWhereNotNull('file_path');
                })
                ->latest('updated_at') // Ubah ke updated_at untuk memastikan data terbaru muncul
                ->paginate(6, ['*'], 'textPage');

            Log::info('Text materials loaded', [
                'count' => $textMaterials->count(),
                'subject_id' => $this->subject->id,
                'class_id' => $user->class_id
            ]);
        } elseif ($this->activeTab === 'video') {
            $videoMaterials = (clone $baseQuery)
                ->whereNotNull('youtube_url')
                ->latest('updated_at') // Ubah ke updated_at untuk memastikan data terbaru muncul
                ->paginate(6, ['*'], 'videoPage');

            Log::info('Video materials loaded', [
                'count' => $videoMaterials->count(),
                'subject_id' => $this->subject->id,
                'class_id' => $user->class_id
            ]);
        }

        // Mengambil materi yang terakhir diakses
        $lastAccessed = $user->lastAccessedMaterials()
            ->where('materials.subject_id', $this->subject->id)
            ->distinct()
            ->get();

        return view('livewire.student.material-list', [
            'textMaterials' => $textMaterials,
            'videoMaterials' => $videoMaterials,
            'lastAccessed' => $lastAccessed,
        ]);
    }
}
