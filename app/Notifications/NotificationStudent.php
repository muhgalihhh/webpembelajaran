<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Task;

class NotificationStudent extends Notification implements ShouldBroadcast
{
    protected $model;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param object $model
     * @param string|null $type
     */
    public function __construct(object $model, string $type = null)
    {
        $this->model = $model;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array
     */
    public function toDatabase(object $notifiable): array
    {
        $type = 'Konten Baru';
        $title = 'Konten Baru Ditambahkan!';
        $link = '#';

        if ($this->model instanceof Material) {
            $type = 'Materi Baru';
            $link = 'student.materials';
            $title = $this->model->title;
        } elseif ($this->model instanceof Task) {
            $type = 'Tugas Baru';
            $link = 'student.tasks';
            $title = $this->model->title;
        } elseif ($this->model instanceof Quiz) {
            if ($this->type === 'quiz_reminder') {
                $type = 'Pengingat Kuis';
                $title = 'Kuis "' . $this->model->title . '" akan segera berakhir!';
                $link = 'student.quizzes';
            } else {
                $type = 'Kuis Baru';
                $link = 'student.quizzes';
                $title = $this->model->title;
            }
        }

        return [
            'type' => $type,
            'title' => $title,
            'link' => $link,
            'subject_name' => $this->model->subject->name ?? 'Umum',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('class.' . $this->model->class_id),
        ];
    }

    /**
     * The type of the notification being broadcast.
     *
     * @return string
     */
    public function broadcastType(): string
    {
        return 'new-content-notification';
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param object $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
