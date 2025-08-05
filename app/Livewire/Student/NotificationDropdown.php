<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;

class NotificationDropdown extends Component
{
    /**
     * Mendefinisikan listener secara dinamis untuk menangani event broadcast.
     */
    protected function getListeners()
    {
        if (!Auth::check() || !Auth::user()->class_id) {
            return [];
        }

        $classId = Auth::user()->class_id;

        return [
            "echo-private:class.{$classId},.material.created" => 'handleNewNotification',
            "echo-private:class.{$classId},.task.created" => 'handleNewNotification',
            "echo-private:class.{$classId},.quiz.created" => 'handleNewNotification',
            'notification-read' => 'refreshNotificationData',
        ];
    }

    /**
     * Method ini dipanggil saat notifikasi baru diterima.
     */
    public function handleNewNotification($notificationData = null)
    {
        // Mengosongkan properti computed agar dihitung ulang saat render berikutnya.
        unset($this->notifications);
        unset($this->unreadCount);

        // Memberi notifikasi toast di frontend.
        // Dispatch ini juga akan memicu re-render pada komponen.
        $this->dispatch('flash-message', message: 'Ada notifikasi baru!', type: 'info');
    }

    /**
     * Me-refresh data setelah notifikasi dibaca.
     */
    public function refreshNotificationData()
    {
        unset($this->notifications);
        unset($this->unreadCount);
    }

    public function markAsReadAndRedirect(string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notification-read');

            $link = $notification->data['link'] ?? '#';
            if ($link !== '#') {
                return $this->redirect($link, navigate: true);
            }
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notification-read');
    }

    /**
     * 👇 PERUBAHAN DI SINI: Hapus 'persist: true'
     * Ini memastikan data selalu fresh setiap kali komponen render.
     */
    #[Computed]
    public function notifications()
    {
        // Mengambil notifikasi terbaru untuk pengguna yang sedang login.
        return Auth::check() ? Auth::user()->notifications()->latest()->take(10)->get() : collect();
    }

    #[Computed]
    public function unreadCount()
    {
        return Auth::check() ? Auth::user()->unreadNotifications()->count() : 0;
    }

    public function render()
    {
        return view('livewire.student.notification-dropdown');
    }
}
