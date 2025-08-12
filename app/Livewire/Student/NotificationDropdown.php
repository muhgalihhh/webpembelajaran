<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

class NotificationDropdown extends Component
{
    public $notificationCount = 0;
    public string $filter = 'all';

    protected function getListeners()
    {
        if (!Auth::check() || !Auth::user()->class_id) {
            return [];
        }

        return [
            'echo-private:class.' . Auth::user()->class_id . ',.student-notification' => 'handleNewNotification',
            'notification-read-static' => 'refreshNotificationData',
        ];
    }

    public function mount()
    {
        if (Auth::check()) {
            $this->notificationCount = $this->unreadCount;
        }
    }

    public function handleNewNotification($event)
    {
        Log::info('Broadcast notification received', ['event' => $event, 'user_id' => Auth::id()]);
        $this->refreshNotificationData();
        $this->dispatch('flash-message', [
            'message' => ($event['type'] ?? 'Notifikasi') . ': ' . ($event['title'] ?? 'Ada konten baru'),
            'type' => 'info'
        ]);
    }

    public function refreshNotificationData()
    {
        // Unset semua computed property agar dihitung ulang
        unset($this->unreadCount);
        unset($this->notifications);
        unset($this->unreadNewCount);
        unset($this->unreadUpdatedCount);
    }

    public function setFilter(string $filter)
    {
        $this->filter = $filter;
    }

    public function markAsReadAndRedirect(string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            $link = $notification->data['link'] ?? null;
            if (is_null($notification->read_at)) {
                $notification->markAsRead();
                $this->dispatch('notification-read-static');
            }
            if (filter_var($link, FILTER_VALIDATE_URL)) {
                return $this->redirect($link, navigate: true);
            }
        }
        return $this->redirect(route('student.dashboard'), navigate: true);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notification-read-static');
    }

    #[Computed]
    public function notifications()
    {
        if (!Auth::check())
            return collect();

        $query = Auth::user()->notifications();

        if ($this->filter === 'new') {
            $query->where('data->type', 'like', '%Baru%');
        } elseif ($this->filter === 'updated') {
            $query->where('data->type', 'like', '%Diperbarui%');
        }

        return $query->latest()->take(15)->get();
    }

    #[Computed(persist: true)]
    public function unreadCount()
    {
        if (!Auth::check())
            return 0;
        return Auth::user()->unreadNotifications()->count();
    }

    // --- PERUBAHAN DI SINI: Computed Property untuk Hitung Notifikasi "Baru" ---
    #[Computed]
    public function unreadNewCount()
    {
        if (!Auth::check())
            return 0;
        return Auth::user()->unreadNotifications()
            ->where('data->type', 'like', '%Baru%')
            ->count();
    }

    #[Computed]
    public function unreadUpdatedCount()
    {
        if (!Auth::check())
            return 0;
        return Auth::user()->unreadNotifications()
            ->where('data->type', 'like', '%Diperbarui%')
            ->count();
    }

    public function render()
    {
        return view('livewire.student.notification-dropdown', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
            'unreadNewCount' => $this->unreadNewCount,
            'unreadUpdatedCount' => $this->unreadUpdatedCount,
        ]);
    }
}