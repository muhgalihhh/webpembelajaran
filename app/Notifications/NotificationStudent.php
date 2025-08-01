<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Material; // Import model Material

class NotificationStudent extends Notification
{
    use Queueable;

    protected $material;

    /**
     * Create a new notification instance.
     */
    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Kita hanya akan menyimpannya di database untuk sekarang
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data ini akan disimpan dalam format JSON di kolom 'data' pada tabel notifications
        return [
            'title' => 'Materi Baru Ditambahkan!',
            'message' => "Materi baru '{$this->material->title}' telah ditambahkan di kelas Anda.",
            'link' => route('student.subjects.show', $this->material->id), // Contoh link ke detail materi
            'material_id' => $this->material->id,
        ];
    }
}
