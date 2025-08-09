<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;


class ScoringNotification extends Notification implements ShouldBroadcast
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

        $type = 'Tugas Anda telah dinilai!';
        $title = '' . $this->model->task->title . ' telah dinilai oleh ' . $this->model->task->creator->name;
        $link = route('tasks');

        return [
            'type' => $type,
            'title' => $title,
            'link' => $link,
            'subject_name' => $this->model->task->subject->name ?? 'Umum',
        ];
    }

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