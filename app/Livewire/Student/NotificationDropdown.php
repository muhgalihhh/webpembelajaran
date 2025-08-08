<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class NotificationDropdown extends Component
{
    public $notificationCount = 0;

    public function mount()
    {
        $this->notificationCount = $this->unreadCount;
    }
    protected function getListeners()
    {
        if (!Auth::check() || !Auth::user()->class_id) {
            return [];
        }

        $classId = Auth::user()->class_id;

        return [
            "echo-private:class.{$classId},.new-content-notification" => 'handleNewNotification',
            'notification-read' => 'refreshNotificationData',
        ];
    }

    /**
     * Method ini dipanggil saat notifikasi baru diterima via broadcast.
     */
    public function handleNewNotification($event)
    {
        // Log untuk debugging
        Log::info('Broadcast notification received', [
            'event' => $event,
            'user_id' => Auth::id(),
            'class_id' => Auth::user()->class_id ?? null
        ]);

        // Refresh notifikasi count
        $this->notificationCount = Auth::user()->unreadNotifications()->count();


        // Dispatch browser event untuk update UI
        $this->dispatch('notification-updated', [
            'count' => $this->notificationCount,
            'type' => $event['type'] ?? 'Notifikasi Baru',
            'title' => $event['title'] ?? 'Ada konten baru'
        ]);

        // Show toast notification dengan informasi spesifik
        $this->dispatch('flash-message', [
            'message' => ($event['type'] ?? 'Notifikasi') . ': ' . ($event['title'] ?? 'Ada konten baru'),
            'type' => 'info'
        ]);
    }

    /**
     * Me-refresh data setelah notifikasi dibaca.
     */
    #[On('notification-read')]
    public function refreshNotificationData()
    {
        // Update notification count langsung dari database
        $this->notificationCount = Auth::user()->unreadNotifications()->count();


        // Dispatch event untuk update UI
        $this->dispatch('notification-updated', [
            'count' => $this->notificationCount
        ]);
    }

    public function markAsReadAndRedirect(string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
            $this->refreshNotificationData();

            $link = $notification->data['link'] ?? '#';
            if ($link !== '#') {
                return $this->redirect($link, navigate: true);
            }
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->refreshNotificationData();

    }

    /**
     * Computed property untuk notifications - refresh setiap kali dipanggil
     */
    #[Computed]
    public function notifications()
    {
        if (!Auth::check()) {
            return collect();
        }

        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();

        Log::info('Loading notifications', [
            'count' => $notifications->count(),
            'user_id' => Auth::id()
        ]);

        return $notifications;
    }

    /**
     * Computed property untuk unread count - refresh setiap kali dipanggil
     */
    #[Computed]
    public function unreadCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        $count = Auth::user()->unreadNotifications()->count();

        Log::info('Unread count', [
            'count' => $count,
            'user_id' => Auth::id()
        ]);

        return $count;
    }

    public function render()
    {
        return view('livewire.student.notification-dropdown');
    }
}