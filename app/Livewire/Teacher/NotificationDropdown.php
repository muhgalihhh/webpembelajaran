<?php

namespace App\Livewire\Teacher;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

class NotificationDropdown extends Component
{
    public $notificationCount = 0;
    public string $filter = 'all'; // Opsi: 'all', 'task', 'quiz'

    protected function getListeners()
    {
        if (!Auth::check())
            return [];
        return [
            // Channel dan event disesuaikan untuk guru
            'echo-private:teacher.' . Auth::id() . ',.teacher-notification' => 'handleNewNotification',
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
        $this->refreshNotificationData();
        $this->dispatch('flash-message', [
            'message' => ($event['type'] ?? 'Notifikasi') . ': ' . ($event['title'] ?? 'Ada aktivitas baru'),
            'type' => 'info'
        ]);
    }

    public function refreshNotificationData()
    {
        unset($this->unreadCount, $this->notifications, $this->unreadTaskCount, $this->unreadQuizCount);
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
            if ($link && Str::startsWith($link, 'http')) {
                return $this->redirect($link, navigate: true);
            }
        }
        return $this->redirect(route('teacher.dashboard'), navigate: true);
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
        if ($this->filter === 'task') {
            $query->where('data->type', 'like', '%Tugas%');
        } elseif ($this->filter === 'quiz') {
            $query->where('data->type', 'like', '%Kuis%');
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

    #[Computed]
    public function unreadTaskCount()
    {
        if (!Auth::check())
            return 0;
        return Auth::user()->unreadNotifications()->where('data->type', 'like', '%Tugas%')->count();
    }

    #[Computed]
    public function unreadQuizCount()
    {
        if (!Auth::check())
            return 0;
        return Auth::user()->unreadNotifications()->where('data->type', 'like', '%Kuis%')->count();
    }

    public function render()
    {
        return view('livewire.teacher.notification-dropdown');
    }
}
