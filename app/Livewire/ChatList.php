<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class ChatList extends Component
{
    public $conversations;

    protected $listeners = ['messageSent' => 'refreshConversations'];

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = Message::with(['fromUser', 'toUser', 'intervention'])
            ->where('from_user_id', Auth::id())
            ->orWhere('to_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('intervention_id')
            ->map(function ($message) {
                $user = $message->from_user_id == Auth::id() ? $message->toUser : $message->fromUser;
                $userName = $user->provider ? $user->provider->name : '';

                $notifs = DatabaseNotification::where('notifiable_id', Auth::id())
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

    public function refreshConversations()
    {
        $this->loadConversations();
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}
