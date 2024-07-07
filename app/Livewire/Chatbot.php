<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChatbotMessage;
use App\Jobs\ProcessChatbotMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Process;

class Chatbot extends Component
{
    public $message = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        $user = Auth::user();
        $messages = ChatbotMessage::where('user_id', $user->id)->get();
        $lastMessage = $messages->last();
        if(!isset($lastMessage)){
            $lastMessageOfChatbot = 1;
        } else{
            $lastMessageOfChatbot = $lastMessage->is_chatbot_message;
        }

        // $this->dispatch('refreshComponent');
        return view('livewire.chatbot', [
            'messages' => $messages,
            'lastMessageOfChatbot' => $lastMessageOfChatbot
        ]);
    }

    public function sendMessage()
{
    $this->validate([
        'message' => 'required|string|max:255',
    ], [
        'message.required' => 'Le message ne peut pas être vide.',
        'message.max' => 'Le message ne peut pas dépasser 255 caractères.',
    ]);

    ChatbotMessage::create([
        'user_id' => auth()->id(),
        'message' => $this->message,
        'is_chatbot_message' => false,
    ]);

    Log::info('Message sent by user: ' . $this->message);

    $userMessage = $this->message;
    $this->reset('message');

    $this->dispatch('processMessage', $userMessage);
    $this->dispatch('refreshComponent');

 
    }

    #[On('processMessage')]
    public function processMessage($userMessage)
    {

        Log::info('test message dans processMessage: ' . $userMessage);
        ProcessChatbotMessage::dispatch($userMessage, auth()->id());

        $this->dispatch('refreshComponent');
    }
}
