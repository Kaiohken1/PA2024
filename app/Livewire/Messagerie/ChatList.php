<?php

namespace App\Livewire\Messagerie;

use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;

    

    public function render()
    {
        $user = auth()->user();
        return view('livewire.messagerie.chat-list', [
            'conversations'=>$user->conversations()->latest('updated_at')->get()]);
    }
}
