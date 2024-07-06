<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications;
    public $refreshComponent;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $this->notifications = Auth::user()->unreadNotifications;
    }

    public function markAsRead($notificationId)
    {
        Auth::user()->unreadNotifications->where('id', $notificationId)->markAsRead();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}

