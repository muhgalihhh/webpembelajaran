<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Task;
use Illuminate\Support\Str;

class NotificationStudent extends Notification implements ShouldBroadcast
{
    protected $model;
    protected $actionType; // 'new', 'updated', 'reminder'

    /**
     * Create a new notification instance.
     *
     * @param object $model
     * @param string $actionType
     */
    public function __construct(object $model, string $actionType = 'new')
    {
        $this->model = $model;
        $this->actionType = $actionType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $data = $this->generateNotificationData();

        return [
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'link' => $data['link'],
            'subject_name' => $this->model->subject->name ?? 'Umum',
        ];
    }

    /**
     * Helper to generate notification data based on model and action type.
     *
     * @return array
     */
    protected function generateNotificationData(): array
    {
        $modelName = class_basename($this->model);
        $subjectName = $this->model->subject->name ?? 'Umum';
        $title = Str::limit($this->model->title, 50);

        switch ($modelName) {
            case 'Material':
                return [
                    'type' => $this->actionType === 'updated' ? 'Materi Diperbarui' : 'Materi Baru',
                    'title' => $this->actionType === 'updated' ? "Materi '{$title}' Diperbarui!" : "Materi Baru: '{$title}'",
                    'message' => "Ada pembaruan pada materi '{$subjectName}'. Silakan cek kembali.",
                    'link' => route('student.materials.show', $this->model->id),
                ];

            case 'Task':
                return [
                    'type' => $this->actionType === 'updated' ? 'Tugas Diperbarui' : 'Tugas Baru',
                    'title' => $this->actionType === 'updated' ? "Tugas '{$title}' Diperbarui!" : "Tugas Baru: '{$title}'",
                    'message' => "Ada pembaruan pada tugas '{$subjectName}'. Cek detailnya sekarang.",
                    'link' => route('student.tasks'),
                ];


            case 'Quiz':
                if ($this->actionType === 'quiz_reminder') {
                    return [
                        'type' => 'Pengingat Kuis',
                        'title' => "Jangan Lupa! Kuis '{$title}'",
                        'message' => "Kuis untuk '{$subjectName}' akan segera berakhir. Segera kerjakan!",
                        'link' => route('student.quizzes.attempt', $this->model->id),
                    ];
                }

                return [
                    'type' => $this->actionType === 'updated' ? 'Kuis Diperbarui' : 'Kuis Baru',
                    'title' => $this->actionType === 'updated' ? "Kuis '{$title}' Diperbarui!" : "Kuis Baru: '{$title}'",
                    'message' => "Ada pembaruan pada kuis '{$subjectName}'. Cek detailnya sekarang.",
                    'link' => route('student.quizzes.attempt', $this->model->id),
                ];

            default:
                return [
                    'type' => 'Pemberitahuan',
                    'title' => 'Ada Konten Baru',
                    'message' => 'Konten baru telah ditambahkan.',
                    'link' => '#',
                ];
        }
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('class.' . $this->model->class_id),
        ];
    }

    /**
     * The type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return 'student-notification';
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}