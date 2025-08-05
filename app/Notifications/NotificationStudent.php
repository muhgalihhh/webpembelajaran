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
        } elseif ($this->model instanceof Task) {
            $type = 'Tugas Baru';
            $title = $this->model->title;
        } elseif ($this->model instanceof Quiz) {
            $type = 'Kuis Baru';
            $title = $this->model->title;
        }

        return [
            'type' => $type,
            'title' => $title,
            'link' => $link,
            'subject_name' => $this->model->subject->name ?? 'Umum',
        ];
    }

    /**
     * Menentukan channel siaran notifikasi.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('class.' . $this->model->class_id),
        ];
    }

    /**
     * Menentukan nama event siaran.
     */
    public function broadcastType(): string
    {
        return 'new-content-notification';
    }

    /**
     * Mengambil representasi siaran dari notifikasi.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
