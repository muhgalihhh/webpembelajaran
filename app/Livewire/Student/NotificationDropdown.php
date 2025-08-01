<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public $unreadCount = 0;

    // Listener untuk memuat ulang notifikasi dari komponen lain jika perlu
    protected $listeners = ['notificationReceived' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    /**
     * Mengambil notifikasi hanya ketika dropdown dibuka untuk efisiensi.
     */
    public function getNotificationsProperty()
    {
        return Auth::user()->notifications()->take(5)->get();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        if ($user) {
            $notification = $user->unreadNotifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->markAsRead();
                $this->loadNotifications(); // Update jumlah notifikasi yang belum dibaca
            }
        }
    }

    public function render()
    {
        return view('livewire.student.notification-dropdown');
    }
}
