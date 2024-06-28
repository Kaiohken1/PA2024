<?php

namespace App\Livewire\Messagerie;

use App\Models\Chat;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;

    public $paginate_var = 10;

    protected $listeners = [
        'loadMore'
    ];

    public function loadMore() {
        $this->paginate_var +=10;

        $this->loadMessages();

        $this->dispatch('update-chat-height');
    }

    public function loadMessages()
    {
        $count = Chat::where('conversation_id', $this->selectedConversation->id)->count();

        $this->loadedMessages = Chat::where('conversation_id', $this->selectedConversation->id)
            ->skip($count-$this->paginate_var)
            ->take($this->paginate_var)
            ->get();
    }


    public function sendMessage()
    {
        $this->validate(['body' => ['required', 'string']]);

        $createdMessage = Chat::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body
        ]);

        $this->reset('body');

        $this->loadedMessages->push($createdMessage);
        $this->dispatch('scroll-bottom');

        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();

        // $this->dispatch('messagerie.chat-list')->to('refresh');
    }


    public function mount() {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.messagerie.chat-box');
    }
}
