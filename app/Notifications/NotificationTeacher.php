<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\QuizAttempt;
use App\Models\TaskSubmission;
use Illuminate\Support\Str;

class NotificationTeacher extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $model;

    public function __construct(object $model)
    {
        $this->model = $model;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->generateNotificationData();
    }

    protected function generateNotificationData(): array
    {
        $modelName = class_basename($this->model);

        switch ($modelName) {
            case 'TaskSubmission':
                $studentName = $this->model->student->name;
                $taskTitle = Str::limit($this->model->task->title, 40);
                return [
                    'type' => 'Tugas Dikumpulkan',
                    'title' => "Tugas Baru dari {$studentName}",
                    'message' => "Telah mengumpulkan tugas '{$taskTitle}'.",
                    'link' => route('teacher.scores.submissions', $this->model->task_id),
                ];

            case 'QuizAttempt':
                $studentName = $this->model->user->name;
                $quizTitle = Str::limit($this->model->quiz->title, 40);
                return [
                    'type' => 'Kuis Selesai',
                    'title' => "Hasil Kuis dari {$studentName}",
                    'message' => "Telah menyelesaikan kuis '{$quizTitle}'.",
                    'link' => route('teacher.quizzes')
                ];

            default:
                return [
                    'type' => 'Pemberitahuan Umum',
                    'title' => 'Aktivitas Baru',
                    'message' => 'Ada aktivitas baru yang memerlukan perhatian Anda.',
                    'link' => route('teacher.dashboard'), // Tautan aman sebagai fallback
                ];

        }
    }

    public function broadcastOn(): array
    {
        $teacherId = null;
        if ($this->model instanceof TaskSubmission) {
            $teacherId = $this->model->task->user_id;
        } elseif ($this->model instanceof QuizAttempt) {
            $teacherId = $this->model->quiz->user_id;
        }

        // Hindari error jika teacherId null
        if (!$teacherId)
            return [];

        return [new PrivateChannel('teacher.' . $teacherId)];
    }

    public function broadcastType(): string
    {
        return 'teacher-notification';
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
