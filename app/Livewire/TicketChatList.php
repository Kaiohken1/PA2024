<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class TicketChatList extends Component
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
        $this->conversations = TicketMessage::with(['fromUser', 'toUser', 'ticket'])
            ->where(function ($query) use ($userId) {
                $query->where('from_user_id', $userId)
                      ->orWhere('to_user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('ticket_id')
            ->map(function ($message) use ($userId) {
                $user = $message->from_user_id == $userId ? $message->toUser : $message->fromUser;
                $userName = $user->name;

                $notifs = DatabaseNotification::where('notifiable_id', $userId)
                    ->where('data->title', 'like', '%Ticket #'.$message->ticket->id.'%')
                    ->whereNull('read_at')
                    ->count();

                return [
                    'user' => $user,
                    'user_name' => $userName,
                    'ticket' => $message->ticket,
                    'last_message' => $message,
                    'notifs' => $notifs,
                ];
            }); 
    }

    public function markAsRead($ticketId)
    {
        $userId = Auth::id();

        DatabaseNotification::where('notifiable_id', $userId)
            ->where('data->title', 'like', '%Ticket #'.$ticketId.'%')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.ticket-chat-list');
    }
}

