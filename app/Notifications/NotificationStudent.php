<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;
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
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        $data = $this->toDatabase($notifiable);
        return new BroadcastMessage([
            'notification' => $data,
            'user_id' => $notifiable->id,
        ]);
    }

    public function broadcastOn(): array
    {
        // Broadcast ke channel class yang sesuai dengan class_id dari model
        return [
            new PrivateChannel('class.' . $this->model->class_id),
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
