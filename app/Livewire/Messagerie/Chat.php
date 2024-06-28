<?php

namespace App\Livewire\Messagerie;

use App\Models\Chat as ModelsChat;
use Livewire\Component;
use App\Models\Conversation;

class Chat extends Component
{
    public $query;
    public $selectedConversation;

    public function mount()
    {

        $this->selectedConversation= Conversation::findOrFail($this->query);


       ModelsChat::where('conversation_id',$this->selectedConversation->id)
                ->where('receiver_id',auth()->id())
                ->whereNull('read_at')
                ->update(['read_at'=>now()]);
    }

    public function render()
    {
        return view('livewire.messagerie.chat');
    }
}
