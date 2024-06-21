<?php

namespace App\Livewire;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

class ChatList extends Component
{
    public $conversations;
    public $totalNotifs;

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $userId = Auth::id();

        $this->conversations = Message::with(['fromUser', 'toUser', 'intervention'])
            ->where(function ($query) use ($userId) {
                $query->where('from_user_id', $userId)
                      ->orWhere('to_user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('intervention_id')
            ->map(function ($message) use ($userId) {
                $user = $message->from_user_id == $userId ? $message->toUser : $message->fromUser;
                $userName = $user->provider ? $user->provider->name : '';

                $notifs = DatabaseNotification::where('notifiable_id', $userId)
                    ->where('data->title', 'like', '%Intervention #'.$message->intervention->id.'%')
                    ->whereNull('read_at')
                    ->count();

                return [
                    'user' => $user,
                    'user_name' => $userName,
                    'intervention' => $message->intervention,
                    'last_message' => $message,
                    'notifs' => $notifs,
                ];
            }); 
    }

    public function markAsRead($interventionId)
    {
        $userId = Auth::id();

        DatabaseNotification::where('notifiable_id', $userId)
            ->where('data->title', 'like', '%Intervention #'.$interventionId.'%')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}

