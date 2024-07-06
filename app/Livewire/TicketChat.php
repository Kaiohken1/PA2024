<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use MBarlow\Megaphone\Types\General;

class TicketChat extends Component
{
    public User $user;
    public Ticket $ticket;

    public $message = '';
    
    public function render()
    {
        $this->dispatch('messageReceived');
        
        return view('livewire.ticket-chat', [
            'messages' => TicketMessage::where(function ($query) {
                        $query->where('from_user_id', auth()->id())
                              ->orWhere('from_user_id', $this->user->id)
                              ->orWhere('to_user_id', auth()->id())
                              ->orWhere('to_user_id', $this->user->id);
                    })
                    ->where('ticket_id', $this->ticket->id)
                    ->get(),
                ])
                ->layout('layouts.app');


    }

    public function sendMessage() {

        $this->validate([
            'message' => 'required|string|max:255',
        ], [
            'message.required' => 'Le message ne peut pas être vide.',
            'message.max' => 'Le message ne peut pas dépasser 255 caractères.',
        ]);

        TicketMessage::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $this->user->id,
            'message' => $this->message,
            'ticket_id' => $this->ticket->id,
        ]);

        $notification = new General(
            'Nouveau message - Ticket #' . $this->ticket->id,
            'Un nouveau message a été envoyé',
            url('/tickets/' . $this->ticket->id . '/chat/' . $this->user->id),
            'Voir la conversation'
        );

        $this->user->notify($notification);

        $this->dispatch('messageReceived');

        $this->reset('message');
    }
}
