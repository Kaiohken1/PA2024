<?php

namespace App\Livewire\Messagerie;

use Livewire\Component;
use App\Models\Conversation;
use Livewire\Attributes\Layout;
use App\Models\Chat as ModelsChat;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $query;
    public $selectedConversation;
    public $layout;

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
        if (Auth::user()->provider) {
            $this->layout = 'layouts.provider';
        } else {
            $this->layout = 'layouts.admin'; 
        }
        return view('livewire.messagerie.chat')
        ->layout($this->layout);
    }
}
