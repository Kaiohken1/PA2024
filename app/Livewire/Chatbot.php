<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatbotMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Livewire\Attributes\On;

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
            $lastMessageOfChatbot = 0;
        } else{
            $lastMessageOfChatbot = $lastMessage->is_chatbot_message;
        }


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

        $chatbotRequest = $this->message;
        $this->reset('message');

        // Emit an event to refresh the component to display the user's message
        $this->dispatch('refreshComponent');

        // Dispatch the processMessage method to handle the chatbot response
        $this->dispatch('processMessage', $chatbotRequest);
    }

    #[On('processMessage')]
    public function processMessage($chatbotRequest)
    {
        $result = Process::run('python3 /var/www/html/chatbot.py ' . escapeshellarg($chatbotRequest));
        $chatBotMessage = $result->output();
        $errorOutput = $result->errorOutput();

        Log::info('Chatbot response: ' . $chatBotMessage);
        Log::error('Chatbot error output: ' . $errorOutput);

        ChatbotMessage::create([
            'user_id' => auth()->id(),
            'message' => $chatBotMessage,
            'is_chatbot_message' => true,
        ]);

        Log::info('Chatbot message saved: ' . $chatBotMessage);

        $this->dispatch('refreshComponent');
    }
}
