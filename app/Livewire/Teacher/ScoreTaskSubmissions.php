<?php

namespace App\Livewire\Teacher;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.teacher')]
#[Title('Penilaian Pengumpulan Tugas')]
class ScoreTaskSubmissions extends Component
{
    use WithPagination;

    public Task $task;

    // Properti untuk Modal Penilaian
    public bool $isScoring = false;
    public ?TaskSubmission $scoringSubmission = null;
    #[Rule('required|numeric|min:0|max:100', as: 'Nilai')]
    public $score = 0;
    #[Rule('nullable|string', as: 'Umpan Balik')]
    public $feedback = '';

    // Properti untuk Modal File Viewer
    public ?string $fileViewerUrl = null;
    public ?string $fileViewerType = null; // 'pdf', 'image'
    public ?TaskSubmission $viewingFileFor = null;

    // Properti untuk konfirmasi hapus
    public ?int $itemToDeleteId = null;


    public function mount(Task $task)
    {
        $this->task = $task;
    }

    #[Computed]
    public function submissions()
    {
        return TaskSubmission::with('student')
            ->where('task_id', $this->task->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    private function resetScoreForm()
    {
        $this->reset(['isScoring', 'scoringSubmission', 'score', 'feedback']);
        $this->resetValidation();
    }

    public function scoreTask(TaskSubmission $submission)
    {
        $this->isScoring = true;
        $this->scoringSubmission = $submission;
        $this->score = $submission->score ?? 0;
        $this->feedback = $submission->feedback ?? '';
        $this->dispatch('open-modal', id: 'score-form-modal');
    }

    public function saveScore()
    {
        $this->validateOnly('score');
        $this->validateOnly('feedback');

        if ($this->scoringSubmission) {
            $this->scoringSubmission->update([
                'score' => $this->score,
                'feedback' => $this->feedback,
                'status' => 'graded',
            ]);

            $this->dispatch('flash-message', message: 'Nilai berhasil disimpan.', type: 'success');
            $this->dispatch('close-modal');
        }
    }

    public function closeModal()
    {
        $this->resetScoreForm();
        $this->dispatch('close-modal');
    }

    public function viewFile(int $submissionId)
    {
        $submission = TaskSubmission::with('student')->find($submissionId);

        if (!$submission || !$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            $this->dispatch('flash-message', message: 'File tidak ditemukan.', type: 'error');
            return;
        }

        $this->viewingFileFor = $submission;
        $mimeType = Storage::disk('public')->mimeType($submission->file_path);

        if ($mimeType === 'application/pdf') {
            $this->fileViewerType = 'pdf';
            $this->fileViewerUrl = Storage::url($submission->file_path);
            $this->dispatch('open-modal', id: 'file-viewer-modal');
        } elseif (str_starts_with($mimeType, 'image/')) {
            $this->fileViewerType = 'image';
            $this->fileViewerUrl = Storage::url($submission->file_path);
            $this->dispatch('open-modal', id: 'file-viewer-modal');
        } else {
            return Storage::disk('public')->download($submission->file_path);
        }
    }

    public function closeFileViewer()
    {
        $this->reset(['fileViewerUrl', 'fileViewerType', 'viewingFileFor']);
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    // Fungsi untuk menghapus data
    public function deleteSubmission()
    {
        if ($this->itemToDeleteId) {
            $submission = TaskSubmission::find($this->itemToDeleteId);
            if ($submission) {
                if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
                    Storage::disk('public')->delete($submission->file_path);
                }
                $submission->delete();
                $this->dispatch('flash-message', message: 'Pengumpulan tugas berhasil dihapus.', type: 'success');
            } else {
                $this->dispatch('flash-message', message: 'Pengumpulan tugas tidak ditemukan.', type: 'error');
            }
        }

        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.teacher.score-task-submissions');
    }
}
