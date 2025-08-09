<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class NotificationTeacher extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $student;
    protected $model;
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param object $student
     * @param object $model
     * @param string $type
     */
    public function __construct(object $student, object $model, string $type)
    {
        $this->student = $student;
        $this->model = $model;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $message = '';
        $link = '#';

        if ($this->type === 'task_submission') {
            $message = $this->student->name . ' telah mengumpulkan tugas: ' . $this->model->title;
            $link = 'teacher.tasks';
        } elseif ($this->type === 'quiz_completion') {
            $message = $this->student->name . ' telah menyelesaikan kuis: ' . $this->model->title;
            $link = 'teacher.quizzes';
        }

        return [
            'message' => $message,
            'link' => $link,
            'student_name' => $this->student->name,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'data' => $this->toDatabase($notifiable)
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Notifikasi dikirim ke channel privat milik guru
        return [
            new PrivateChannel('teachers.' . $this->model->teacher_id),
        ];
    }
}
