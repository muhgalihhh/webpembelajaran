<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Material;
use App\Models\Task;
use App\Models\Quiz;

class NotificationStudent extends Notification
{
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
        $type = 'Konten Baru';
        $title = 'Konten Baru Ditambahkan!';
        $link = '#';

        if ($this->model instanceof Material) {
            $type = 'Materi Baru';
            $title = $this->model->title;
            // $link = route('student.materials.show', $this->model->id);
        } elseif ($this->model instanceof Task) {
            $type = 'Tugas Baru';
            $title = $this->model->title;
            // $link = route('student.tasks.show', $this->model->id);
        } elseif ($this->model instanceof Quiz) {
            $type = 'Kuis Baru';
            $title = $this->model->title;
            //$link = route('student.quizzes.show', $this->model->id);
        }

        return [
            'type' => $type,
            'title' => $title,
            'link' => $link,
            'subject_name' => $this->model->subject->name ?? 'Umum',
            'class_id' => $this->model->class_id ?? null,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = $this->toDatabase($notifiable);

        // Tambahkan info tambahan untuk real-time
        $data['notification_id'] = $this->id;
        $data['user_id'] = $notifiable->id;

        return new BroadcastMessage($data);
    }

    public function broadcastOn(): array
    {
        return [
            'notifications', // Channel global
            // Bisa juga tambahkan channel per kelas:
            // 'class.' . $this->model->class_id,
        ];
    }

    public function broadcastType(): string
    {
        if ($this->model instanceof Material) {
            return 'material.created';
        } elseif ($this->model instanceof Task) {
            return 'task.created';
        } elseif ($this->model instanceof Quiz) {
            return 'quiz.created';
        }

        return 'content.created';
    }
}
