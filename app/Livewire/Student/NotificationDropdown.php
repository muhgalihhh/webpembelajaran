<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    /**
     * Load notifications dari database - RETURN SEBAGAI COLLECTION, BUKAN ARRAY
     */
    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            // JANGAN gunakan toArray() - biarkan sebagai Collection
            $this->notifications = $user->notifications()->latest()->take(10)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    /**
     * Event listener untuk notifikasi baru dari Pusher
     */
    #[On('notification-received')]
    public function handleNewNotification($notificationData = null)
    {
        $this->loadNotifications();
        $this->dispatch('show-notification-toast', $notificationData);
    }

    /**
     * Event listener untuk notifikasi yang dibaca
     */
    #[On('notification-read')]
    public function handleNotificationRead()
    {
        $this->loadNotifications();
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsReadAndRedirect(string $notificationId)
    {
        $user = Auth::user();
        if (!$user)
            return;

        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
            $this->dispatch('notification-read');

            $link = $notification->data['link'] ?? '#';
            return $this->redirect($link, navigate: true);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
            $this->dispatch('notification-read');
        }
    }

    /**
     * Computed property untuk notifications
     */
    public function getNotificationsProperty()
    {
        $user = Auth::user();
        if ($user) {
            return $user->notifications()->latest()->take(10)->get();
        }
        return collect([]);
    }

    /**
     * Computed property untuk unread count
     */
    public function getUnreadCountProperty()
    {
        $user = Auth::user();
        if ($user) {
            return $user->unreadNotifications()->count();
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.student.notification-dropdown');
    }
}
